<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;

class SubscribeController extends Controller implements HasMiddleware
{
	public static function middleware() {
		return ['auth'];
	}
    public function showPlans()
	 {
		$plans = Plan::all();
		return view('subscribe.plans', compact('plans'));
	 }

	 public function checkout(Plan $plan)
	 {
		$user = Auth::user();
		return view('subscribe.checkout', compact('plan', 'user'));
	 }

	 public function checkoutProcess(Request $request)
	 {
		$user = Auth::user();
		$plan = Plan::findOrFail($request->plan_id);
		
		$user->memberships()->create([
			'plan_id' => $request->plan_id,
			'user_id' => $user->id,
			'active' => true,
			'start_date' => now(),
			'end_date' => now()->addDays($plan->duration),
		]);

		return redirect()->route('subscribe.success');
	 }

	 public function successCheckout() 
	 {
		return view('subscribe.success');
	 }
}
