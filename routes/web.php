<?php

use App\Http\Controllers\HealthMonitorController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'throttle:5,1'])->group(function () {
    Route::get('/', [HealthMonitorController::class, 'index'])->name('home');
    Route::post('/check', [HealthMonitorController::class, 'check'])->name('check');
    Route::get('/download/{urlHash}', [HealthMonitorController::class, 'download'])->name('download.report');
});
