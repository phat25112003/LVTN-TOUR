<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\TourController;
use App\Http\Controllers\TourPublicController;
use App\Http\Controllers\Admin\NguoiDungController;
use App\Http\Controllers\Admin\DatChoController; 
use App\Http\Controllers\Admin\KhuyenMaiController;
use App\Http\Controllers\Admin\DanhMucController;
use App\Http\Controllers\Admin\TongQuatController;

Route::get('/', fn () => view('admin.login'));

// Route admin
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/tongquat', [TongQuatController::class, 'index'])->name('tongquat.index');
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
        Route::put('{maNguoiDung}/update-status', 'updateStatus')->name('update-status');
        Route::delete('destroy/{maNguoiDung}', 'destroy')->name('destroy');
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


    // Route cho DatChoController
    Route::controller(DatChoController::class)->middleware('auth:admin')->prefix('datcho')->name('datcho.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('{maDatCho}/xacnhan', 'xacNhan')->name('xacnhan');
        Route::post('xacnhan-thanhtoan/{maDatCho}', 'xacNhanThanhToan')->name('xacnhan_thanhtoan'); 
        Route::get('{maDatCho}/chi-tiet', 'show')->name('show');
        Route::post('{maDatCho}/xuat-hoa-don', 'sendInvoice')->name('send_invoice');
    });


    // Route cho KhuyenMaiController
    Route::controller(KhuyenMaiController::class)->middleware('auth:admin')->prefix('khuyenmai')->name('khuyenmai.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::put('/{id}/toggle-status', 'toggleStatus')->name('toggle-status');
    });

    // Route cho DanhMucController
    Route::controller(DanhMucController::class)->middleware('auth:admin')->prefix('danhmuc')->name('danhmuc.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{maDanhMuc}/edit', 'edit')->name('edit');
        Route::put('/{maDanhMuc}', 'update')->name('update');
        Route::delete('/{maDanhMuc}', 'destroy')->name('destroy');
    });
});