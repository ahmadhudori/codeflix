<?php

use App\Http\Controllers\SubscribeController;
use App\Services\DeviceLimitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->middleware(['auth', 'checkDeviceLimit'])->name('home');

Route::get('/subscribe', [SubscribeController::class, 'showPlans'])->name('subscribe.plans');
Route::get('/subscribe/plan/{plan}', [SubscribeController::class, 'checkout'])->name('subscribe.checkout');
Route::post('/subscribe/plan/checkout', [SubscribeController::class, 'checkoutProcess'])->name('subscribe.checkout.process');
Route::get('/subscribe/success', [SubscribeController::class, 'successCheckout'])->name('subscribe.success');

Route::post('/logout', function(Request $request, DeviceLimitService $deviceService) {
	 $deviceId = session('device_id');
    if ($deviceId) {
        $deviceService->logoutDevice($deviceId); // ← ini dijamin jalan sebelum destroy()
    }
	return app(\Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::class)->destroy($request);
})->name('logout')->middleware(['auth']);