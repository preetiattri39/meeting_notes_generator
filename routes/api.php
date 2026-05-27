<?php

use App\Http\Controllers\MeetingController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/meetings', [MeetingController::class, 'index'])->name('api.meetings.index');
    Route::get('/meetings/{meeting}', [MeetingController::class, 'show'])->name('api.meetings.show');
});
