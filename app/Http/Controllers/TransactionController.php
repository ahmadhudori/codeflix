<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\DeviceLimitService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class TransactionController extends Controller implements HasMiddleware
{
	
	protected $deviceService;
	
	public static function middleware()
	{
		return [
			'auth',
		];
	}
	
	
    public function __construct(DeviceLimitService $deviceService)
	{
		Config::$serverKey = config('midtrans.server_key');
		Config::$isProduction = config('midtrans.is_production');
		Config::$isSanitized = config('midtrans.is_sanitized');
		Config::$is3ds = config('midtrans.is_3ds');

		$this->deviceService = $deviceService;
	}

	public function checkout(Request $request)
	{
		$user = Auth::user();

		$request->validate([
			'plan_id' => 'required|exists:plans,id',
		]);

		$transactionNumber = 'ORDER-' . time() . '-' . $user->id;

		$transaction = Transaction::create([
			'user_id' => $user->id,
			'plan_id' => $request->plan_id,
			'transaction_number' => $transactionNumber,
			'total_amount' => $request->amount,
			'payment_status' => 'pending',
		]);

		$payload = [
            'transaction_details' => [
                'order_id' => $transaction->transaction_number,
                'gross_amount' => (int) $transaction->total_amount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => '000000000000',
            ],
            'item_details' => [
                [
                    'id' => $transaction->plan_id,
                    'price' => (int) $transaction->total_amount,
                    'quantity' => 1,
                    'name' => $transaction->plan->title,
                ]
            ],
        ];

		try {
            // Get Snap Payment Page URL
            $snapToken = Snap::getSnapToken($payload);
            $transaction->update(['midtrans_snap_token' => $snapToken]);
            return response()->json([
                'status' => 'success',
                'snap_token' => $snapToken,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
	}

	public function callback(Request $request)
	{
		$serverKey = config('midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            $transaction = Transaction::with(['user', 'plan'])->where('transaction_number', $request->order_id)->first();
            if ($transaction) {
                $paymentStatus = 'pending';
                if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                    $paymentStatus = 'success';

                    // Create membership after successful payment
                    $user = $transaction->user;
                    $plan = $transaction->plan;
                    try {
                        DB::beginTransaction();

						// Create membership
						$user->memberships()->create([
							'plan_id' => $plan->id,
							'start_date' => now(),
							'end_date' => now()->addDays($plan->duration),
							'active' => true,
						]);

						// Register device
						$this->deviceService->registerDevice($user);

						// Update order status
						$transaction->update([
							'payment_status' => 'success',
							'midtrans_transaction_id' => $request->transaction_id,
						]);

						DB::commit();
                    } catch (\Exception $e) {
                        Log::error('Failed to process successful payment: ' . $e->getMessage());
                        return response()->json(['status' => 'error', 'message' => 'Failed to process membership'], 500);
                    }
                } elseif ($request->transaction_status == 'deny' || $request->transaction_status == 'cancel' || $request->transaction_status == 'expire') {
                    $paymentStatus = 'failed';
                    $transaction->update([
                        'payment_status' => $paymentStatus,
                        'midtrans_transaction_id' => $request->transaction_id,
                    ]);
                }
                return response()->json(['status' => 'success']);
            }
        }
        return response()->json(['status' => 'error'], 404);
	}
}
