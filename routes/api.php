<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/webhook/payment-status', [\App\Http\Controllers\Webhook\PaymentStatusController::class, 'updateStatus'])
    ->middleware(\App\Http\Middleware\VerifyWebhookSignature::class);

Route::prefix('internal')->middleware([\App\Http\Middleware\VerifyWebhookSignature::class])->group(function () {
    Route::get('/payments', [\App\Http\Controllers\Api\Internal\PaymentController::class, 'index']);
    Route::get('/payments/{id}', [\App\Http\Controllers\Api\Internal\PaymentController::class, 'show']);
});
