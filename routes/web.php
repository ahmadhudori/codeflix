<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\SubscribeController;
use App\Models\Membership;
use App\Services\DeviceLimitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [MovieController::class, 'index'])->name('home');



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


Route::get('/home', [MovieController::class, 'index'])->name('home');
Route::get('/movies', [MovieController::class, 'all'])->name('movies.all');
Route::get('/movies/search', [MovieController::class, 'search'])->name('movies.search');
Route::get('/movies/{movie:slug}', [MovieController::class, 'show'])->name('movies.show');
Route::get('/categories/{category:slug}', [CategoryController::class, 'showByCategoty'])->name('category.show');

Route::get('test-expired-membership', function () {
	$membership = Membership::find(4);
	event(new \App\Events\MembershipHasExpiredEvent($membership));
	return 'event fired';
});