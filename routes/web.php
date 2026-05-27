<?php

use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\MeetingExportController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('welcome');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::resource('meetings', MeetingController::class);
    Route::post('/meetings/{meeting}/retry', [MeetingController::class, 'retry'])->name('meetings.retry');
    Route::get('/meetings/{meeting}/export/pdf', [MeetingExportController::class, 'pdf'])->name('meetings.export.pdf');
    Route::get('/meetings/{meeting}/export/docx', [MeetingExportController::class, 'docx'])->name('meetings.export.docx');
    Route::get('/admin/analytics', AnalyticsController::class)->name('admin.analytics');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
