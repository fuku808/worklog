<?php

use App\Http\Controllers\TimeTrackingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkLogController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (Auth::user()) {
        return redirect()->route('clockin-out.index');
    } else {
        return view('auth.login');
    }
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'can:member'
])->group(function () {
    Route::get('/clockin-out', [TimeTrackingController::class, 'index'])->name('clockin-out.index');
    Route::post('/clockin-out/store', [TimeTrackingController::class, 'store'])->name('clockin-out.store');
    Route::post('/clockin-out/update/{id}', [TimeTrackingController::class, 'update'])->name('clockin-out.update');
    Route::get('/clockin-out/report', [TimeTrackingController::class, 'report'])->name('clockin-out.report');
    Route::resource('work-log', WorkLogController::class)->except(['create', 'show']);
    Route::get('/work-log/report', [WorkLogController::class, 'report'])->name('work-log.report');
    Route::get('/work-log/download', [WorkLogController::class, 'report_download'])->name('work-log.download');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'can:manager'
])->group(function () {
    Route::get('mangement/clockin-out', [TimeTrackingController::class, 'management'])->name('management.clockin-out');
    Route::post('mangement/clockin-out/store', [TimeTrackingController::class, 'management_store'])->name('management.clockin-out.store');
    Route::get('mangement/clockin-out/{id}/edit', [TimeTrackingController::class, 'management_edit'])->name('management.clockin-out.edit');
    Route::put('mangement/clockin-out/{id}/update', [TimeTrackingController::class, 'management_update'])->name('management.clockin-out.update');
    Route::delete('mangement/clockin-out/{id}/destroy', [TimeTrackingController::class, 'management_destroy'])->name('management.clockin-out.destroy');
    Route::get('mangement/clockin-out/report', [TimeTrackingController::class, 'management_report'])->name('management.clockin-out.report');
    Route::get('mangement/clockin-out/download', [TimeTrackingController::class, 'management_report_download'])->name('management.clockin-out.download');
    Route::get('mangement/work-log/report', [WorkLogController::class, 'management_report'])->name('management.work-log.report');
    Route::get('mangement/work-log/download', [WorkLogController::class, 'management_report_download'])->name('management.work-log.download');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'can:system-admin'
])->group(function () {
    Route::resource('master/user', UserController::class)->except(['show']);
    Route::post('master/user/reset_password', [UserController::class, 'reset_password'])->name('user.reset_password');
});

