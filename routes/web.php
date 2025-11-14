<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\TourController;
// use App\Http\Controllers\TourPublicController;
use App\Http\Controllers\Admin\NguoiDungController;
use App\Http\Controllers\Admin\DatChoController; 
use App\Http\Controllers\Admin\KhuyenMaiController;
use App\Http\Controllers\Admin\DanhMucController;
use App\Http\Controllers\Admin\TongQuatController;
use App\Http\Controllers\User\TourDetailController;
use App\Http\Controllers\User\TourUserController;
use App\Http\Controllers\User\DatTourController;
use App\Http\Controllers\User\UserAuthController;
use App\Http\Controllers\User\DangKyController; 
use App\Http\Controllers\Admin\HuongDanVienController;



// // Route công khai
// Route::controller(TourPublicController::class)->prefix('tours')->name('tours.')->group(function () {
//     Route::get('/', 'index')->name('index');
//     Route::get('{maTour}', 'show')->name('show');
// });

Route::get('/', [TourUserController::class, 'index'])->name('home');

Route::get('/admin/dashboard', [TongQuatController::class, 'index'])->name('admin.dashboard.index');

Route::get('/admin/dashboard/charts', [TongQuatController::class, 'getChartData'])->name('admin.dashboard.charts');

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

    // Route cho TourController (admin)
    Route::controller(TourController::class)
    ->middleware('auth:admin')
    ->prefix('tours')
    ->name('tours.')
    ->group(function () {

        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('{tour}', 'show')->name('show');
        Route::get('{tour}/edit', 'edit')->name('edit');
        Route::put('{tour}', 'update')->name('update');
        Route::delete('{tour}', 'destroy')->name('destroy');

        // Lịch trình
        Route::get('{tour}/create-schedule', 'createSchedule')->name('createSchedule');
        Route::post('{tour}/store-schedule', 'storeSchedule')->name('storeSchedule');
        Route::get('{tour}/edit-schedule', 'editSchedule')->name('editSchedule');
        Route::post('{tour}/update-schedule', 'updateSchedule')->name('updateSchedule');

        // Chuyến tour
        Route::get('{tour}/create-trips', 'createTrips')->name('createTrips');
        Route::post('{tour}/store-trips', 'storeTrips')->name('storeTrips');

        Route::get('{tour}/edit-trips', 'editTrips')->name('editTrips');
        Route::put('{tour}/update-trips', 'updateTrips')->name('updateTrips');
    });


    // Route cho DatChoController
    Route::controller(DatChoController::class)
        ->prefix('datcho')
        ->name('datcho.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('{maDatCho}/chi-tiet', 'show')->name('show');
            Route::post('{maDatCho}/xuat-hoa-don', 'sendInvoice')->name('sendInvoice');
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

    // Route cho HuongDanVienController
    Route::controller(HuongDanVienController::class)
           ->prefix('huongdanvien')
           ->name('huongdanvien.')
           ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{maHDV}/edit', 'edit')->name('edit');
        Route::put('/{maHDV}', 'update')->name('update');
        Route::delete('/{maHDV}', 'destroy')->name('destroy');
        Route::get('/{maHDV}', 'show')->name('show');
    });
});

// routes/web.php
Route::post('/admin/datcho/{maDatCho}/send-invoice', [DatChoController::class, 'sendInvoice'])
     ->name('admin.datcho.sendInvoice');

Route::get('/tours/{maTour}', [TourDetailController::class, 'show'])->name('tour.detail');

Route::get('/tours', [TourUserController::class, 'search'])->name('tour.list');

Route::controller(DatTourController::class)->prefix('user')->name('dattour.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('create/{maTour}', 'create')->name('create');
    Route::post('/', 'store')->name('store');
});
Route::controller(UserAuthController::class)->prefix('user')->name('user.')->group(function () {
    Route::get('login', 'index')->name('login');
    Route::post('login', 'login')->name('login.post');
    Route::get('logout', 'logout')->name('logout');
});
Route::get('user/dangky', [DangKyController::class, 'showRegistrationForm'])->name('user.dangky');
Route::post('user/dangky', [DangKyController::class, 'register'])->name('user.dangky.post');


