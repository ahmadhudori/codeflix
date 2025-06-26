<?php

use App\Http\Controllers\SubscribeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/subscribe', [SubscribeController::class, 'showPlans'])->name('subscribe.plans');
Route::get('/subscribe/plan/{plan}', [SubscribeController::class, 'checkout'])->name('subscribe.checkout');
Route::post('/subscribe/plan/checkout', [SubscribeController::class, 'checkoutProcess'])->name('subscribe.checkout.process');
Route::get('/subscribe/success', [SubscribeController::class, 'successCheckout'])->name('subscribe.success');