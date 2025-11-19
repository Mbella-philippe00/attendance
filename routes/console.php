<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use App\Http\Controllers\Api\AuthController;

Route::prefix('v1/auth')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->middleware('throttle:10,1');
    Route::post('login',    [AuthController::class, 'login'])->middleware('throttle:20,1');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me',           [AuthController::class, 'me']);
        Route::post('logout',      [AuthController::class, 'logout']);
        Route::post('logout-all',  [AuthController::class, 'logoutAll']);
        Route::post('rotate-token',[AuthController::class, 'rotateToken']);
    });
});
