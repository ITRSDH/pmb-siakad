<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Internal\MahasiswaController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/webhook/payment-status', [\App\Http\Controllers\Webhook\PaymentStatusController::class, 'updateStatus'])->middleware(\App\Http\Middleware\VerifyWebhookSignature::class);

Route::prefix('internal')
    ->middleware([\App\Http\Middleware\VerifyWebhookSignature::class])
    ->group(function () {
        Route::get('/payments', [\App\Http\Controllers\Api\Internal\PaymentController::class, 'index']);
        Route::get('/payments/{id}', [\App\Http\Controllers\Api\Internal\PaymentController::class, 'show']);
    });

Route::prefix('pmb')->group(function () {
    Route::get('/mahasiswa', [MahasiswaController::class, 'index']);
    Route::get('/mahasiswa/{id}', [MahasiswaController::class, 'show']);
    Route::get('/periode-pendaftaran', [MahasiswaController::class, 'getPeriodePendaftaran']);
});
