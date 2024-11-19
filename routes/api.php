<?php

use App\Http\Controllers\API\AuthApiController;
use App\Http\Controllers\API\DependentSelectApiController;
use App\Http\Controllers\API\UserApiController;
use App\Http\Controllers\API\VoteApiController;
use App\Http\Controllers\TelegramController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

/**
 * Telegram Webhook
 */
// routes/api.php
Route::post('/telegram/webhook', [TelegramController::class, 'handleWebhook']);


/**
 * Route for API
 */
Route::post('login', [AuthApiController::class, 'login']);

Route::prefix('dependent')->group(function () {
    Route::get('/kecamatan', [DependentSelectApiController::class, 'getKecamatan']); // Semua kecamatan
    Route::get('/kelurahan/{kecamatanId}', [DependentSelectApiController::class, 'getKelurahan']); // Kelurahan berdasarkan kecamatan
    Route::get('/tps/{kelurahanId}', [DependentSelectApiController::class, 'getTps']); // TPS berdasarkan kelurahan
});

Route::middleware(['auth:api'])->group(function () {
    Route::post('vote', [VoteApiController::class, 'store']);
});


