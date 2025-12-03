<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController, // ✅ Ajout du controller Auth
    UserController, SiteController, DeviceController, AttendanceRecordController,
    ScheduleController, AbsenceController, CorrectionRequestController,
    NotificationController, NotificationPreferenceController, ReportController,
    SystemSettingController
};

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::prefix('v1/auth')->group(function () {
    // Public
    Route::post('register', [AuthController::class, 'register'])
        ->name('auth.register')
        ->middleware('throttle:10,1'); // 10 req/min

    Route::post('login', [AuthController::class, 'login'])
        ->name('auth.login')
        ->middleware('throttle:20,1'); // 20 req/min

    // Protected
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [AuthController::class, 'me'])->name('auth.me');
        Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::post('logout-all', [AuthController::class, 'logoutAll'])->name('auth.logoutAll');
        Route::post('rotate-token', [AuthController::class, 'rotateToken'])->name('auth.rotate');
    });
});

/*
|--------------------------------------------------------------------------
| PROTECTED API ROUTES (BUSINESS)
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('sites', SiteController::class);

    Route::apiResource('devices', DeviceController::class)
        ->only(['index','show','store','update','destroy']);

    Route::apiResource('attendance', AttendanceRecordController::class);
    Route::get('attendance/{id}/validate', [AttendanceRecordController::class, 'validateRecord']);
    Route::post('attendance/{id}/validate', [AttendanceRecordController::class, 'validateRecord']);
    Route::get('attendance/stats/daily', [AttendanceRecordController::class, 'dailyStats']);

    Route::apiResource('schedules', ScheduleController::class)
        ->only(['index','show','store','update','destroy']);

    Route::apiResource('absences', AbsenceController::class);
    Route::apiResource('corrections', CorrectionRequestController::class)
        ->only(['index','show','store','update']);

    Route::get('notifications', [NotificationController::class, 'index']);
    Route::post('notifications/{id}/read', [NotificationController::class, 'markRead']);

    Route::get('preferences/me', [NotificationPreferenceController::class, 'showMine']);
    Route::put('preferences/me', [NotificationPreferenceController::class, 'updateMine']);

    Route::get('reports', [ReportController::class, 'index']);
    Route::post('reports', [ReportController::class, 'queue']);
    Route::get('reports/{id}', [ReportController::class, 'show']);

    Route::get('settings', [SystemSettingController::class, 'index']);
    Route::get('settings/{key}', [SystemSettingController::class, 'showByKey']);
    Route::put('settings/{key}', [SystemSettingController::class, 'updateByKey']);

    
});

Route::apiResource('departments', \App\Http\Controllers\Api\DepartmentController::class)
    ->middleware('auth:sanctum');

// Additional route for department dropdown
Route::get('departments/dropdown', [\App\Http\Controllers\Api\DepartmentController::class, 'dropdown'])
    ->middleware('auth:sanctum');