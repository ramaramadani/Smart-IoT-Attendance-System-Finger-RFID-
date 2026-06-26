<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\EmergencyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logs', [LogController::class, 'index'])->name('logs');
    Route::get('/logs/export/pdf', [LogController::class, 'exportPdf'])->name('logs.export.pdf');
    Route::get('/logs/export/excel', [LogController::class, 'exportExcel'])->name('logs.export.excel');
    Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring');
    Route::get('/emergency', [EmergencyController::class, 'index'])->name('emergency');
    Route::get('/sp-report', [App\Http\Controllers\SpReportController::class, 'index'])->name('sp_report');

    Route::middleware('role:admin')->group(function () {
        Route::resource('employees', EmployeeController::class)->except(['create', 'show', 'edit']);
        Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);
        Route::resource('departments', DepartmentController::class)->except(['create', 'show', 'edit']);
    });
});