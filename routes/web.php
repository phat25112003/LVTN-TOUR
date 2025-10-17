<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\TourController;
use App\Http\Controllers\TourPublicController;
use App\Http\Controllers\Admin\NguoiDungController;

Route::get('/', fn () => view('admin.login'));

// Route công khai
Route::controller(TourPublicController::class)->prefix('tours')->name('tours.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('{maTour}', 'show')->name('show');
});

// Route admin
Route::prefix('admin')->name('admin.')->group(function () {
    // Route cho AuthController
    Route::controller(AuthController::class)->group(function () {
        Route::get('login', 'showLoginForm')->name('login');
        Route::post('login', 'login');
        Route::post('logout', 'logout')->middleware('auth:admin')->name('logout');
        Route::get('dashboard', 'dashboard')->middleware(['auth:admin'])->name('dashboard');
        Route::get('profile', 'profile')->middleware('auth:admin')->name('profile');
        Route::post('profile/update', 'updateProfile')->middleware('auth:admin')->name('profile.update');
    });

    // Route cho NguoiDungController
    Route::controller(NguoiDungController::class)->middleware('auth:admin')->prefix('nguoidung')->name('nguoidung.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::put('{maNguoiDung}/status', 'updateStatus')->name('updateStatus');
        Route::delete('{maNguoiDung}', 'destroy')->name('destroy');
    });

    // Route cho TourController
    Route::controller(TourController::class)->middleware('auth:admin')->prefix('tours')->name('tours.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('{tour}', 'show')->name('show');
        Route::get('{tour}/edit', 'edit')->name('edit');
        Route::put('{tour}', 'update')->name('update');
        Route::delete('{tour}', 'destroy')->name('destroy');
        Route::get('{tour}/create-schedule', 'createSchedule')->name('createSchedule');
        Route::post('{tour}/store-schedule', 'storeSchedule')->name('storeSchedule');
        Route::get('{tour}/edit-schedule', 'editSchedule')->name('editSchedule');
        Route::post('{tour}/update-schedule', 'updateSchedule')->name('updateSchedule');
    });
});