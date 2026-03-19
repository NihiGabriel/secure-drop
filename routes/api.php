<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SecretController;

Route::prefix('v1')->group(function () {
    Route::post('/secrets', [SecretController::class, 'store']);
    Route::get('/secrets/{uuid}', [SecretController::class, 'show']);
});