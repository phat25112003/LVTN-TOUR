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
use App\Http\Controllers\Admin\DiaDiemController;
use App\Http\Controllers\User\TourDetailController;
use App\Http\Controllers\User\TourUserController;
use App\Http\Controllers\User\DatTourController;
use App\Http\Controllers\User\UserAuthController;
use App\Http\Controllers\User\DangKyController; 
use App\Http\Controllers\User\ThongTinUserController;
use App\Http\Controllers\User\SuaTourDetailController;
use App\Http\Controllers\User\GoogleLoginCOntroller;
use App\Http\Controllers\User\KhuyenMaiUserController;
use App\Http\Controllers\User\ThanhToanController;
use App\Http\Controllers\User\BinhLuanController;
use App\Http\Controllers\User\GioiThieuController;
use App\Http\Controllers\User\LienHeController;

// Route công khai
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
            Route::delete('{maDatCho}', 'destroy')->name('destroy');
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

    // Route cho DiaDiemController
    Route::controller(DiaDiemController::class)
        ->prefix('diadiem')
        ->name('diadiem.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{maDiaDiem}/edit', 'edit')->name('edit');
            Route::put('/{maDiaDiem}', 'update')->name('update');
            Route::delete('/{maDiaDiem}', 'destroy')->name('destroy');
        });
        
    // Doanh Thu
    Route::get('/bao-cao/doanh-thu', [TongQuatController::class, 'baoCaoDoanhThu'])->name('baocao.doanhthu');
    Route::post('/bao-cao/doanh-thu', [TongQuatController::class, 'xuatBaoCao'])->name('baocao.xuat');
    
    
});
Route::get('/tours/{maTour}', [TourDetailController::class, 'show'])->name('tour.detail');


// Trang chủ
Route::get('/', [TourUserController::class, 'index'])->name('home');
Route::get('/tours/upcoming', [TourUserController::class, 'upcomingTours'])->name('tour.upcoming');

// Danh sách tour / tìm kiếm tour
Route::get('/tours', [TourUserController::class, 'search'])->name('tour.list');
//đặt tour
Route::controller(DatTourController::class)->middleware('auth:web')->prefix('user')->name('dattour.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('create/{maTour}', 'create')->name('create');
    Route::post('/', 'store')->name('store');
});
//dang nhap
Route::controller(UserAuthController::class)->prefix('user')->name('user.')->group(function () {
    Route::get('login', 'index')->name('login');
    Route::post('login', 'login')->name('login.post');
    Route::get('logout', 'logout')->name('logout');
});
//dang ky
Route::get('user/dangky', [DangKyController::class, 'showRegistrationForm'])->name('user.dangky');
Route::post('user/dangky', [DangKyController::class, 'register'])->name('user.dangky.post');
//thong tin user
Route::get('user/thongtin', [ThongTinUserController::class, 'index'])->name('user.thongtinuser');
Route::put('/user/updateinfo', [ThongTinUserController::class, 'update'])->name('user.suathongtinuser');
Route::post('/user/update-avatar', [ThongTinUserController::class, 'updateAvatar'])
    ->name('user.updateAvatar');
Route::put('/user/doimatkhau', [ThongTinUserController::class, 'doiMatKhau'])->name('user.doimatkhau');

//lay ngay khoi hanh tour cho dat tour
Route::get('/api/tour-dates/{maTour}', [DatTourController::class, 'getTourDates']);
//sua thong tin dat cho
Route::controller(SuaTourDetailController::class)->middleware('auth:web')->prefix('user/suatourdetail')->name('user.suatourdetail.')->group(function () {
    Route::get('{maDatCho}', 'index')->name('index');
    Route::put('{maDatCho}/update', 'update')->name('update');
});
//xoa dat cho
Route::delete('user/thongtin/{maDatCho}', [ThongTinUserController::class, 'destroy'])->name('user.thongtinuser.destroy');
//google login
Route::get('auth/google', [GoogleLoginCOntroller::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleLoginCOntroller::class, 'handleGoogleCallback'])->name('google.callback');
//khuyen mai user
Route::post('/khuyenmai/apply', [KhuyenMaiUserController::class, 'apply'])->name('khuyenmai.apply');
//thanh toan momo
Route::post('/user/thanhtoan', [ThanhToanController::class, 'thanhtoan'])
     ->name('user.thanhtoan');
Route::get('/user/momo-return', [ThanhToanController::class, 'momoReturn'])
     ->name('user.momo.return');
//binh luận tour
Route::middleware(['auth'])->group(function () {
    Route::post('/tour/{tour}/binh-luan', [BinhLuanController::class, 'store'])->name('tour.binhluan.store');
});
//gioi thieu
Route::get('/gioithieu', [GioiThieuController::class, 'index'])->name('gioithieu');
//lien he
Route::get('/lienhe', [LienHeController::class, 'index'])->name('lienhe');