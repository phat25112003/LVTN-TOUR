<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\DatCho;
<<<<<<< HEAD
use App\Models\GiaTour;
use App\Models\ChuyenTour;

=======
>>>>>>> 558f8d9a959838049afa7e59c23074b6b7e3cfad
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DatTourController extends Controller
{
    /**
<<<<<<< HEAD
     * Trang đặt tour
=======
     * Display a listing of the resource.
>>>>>>> 558f8d9a959838049afa7e59c23074b6b7e3cfad
     */
    public function index()
    {
        return view('user.dattour');
    }

    /**
<<<<<<< HEAD
     * Form đặt tour cụ thể
     */
    public function create($maTour)
    {
        // Lấy tour kèm thông tin giá từ bảng giatour
        $tour = Tour::with('giatour')->find($maTour);

        if (!$tour) {
            return redirect()->route('home')->with('error', 'Tour không tồn tại.');
        }
        
=======
     * Show the form for creating a new resource.
     */
    public function create($maTour)
    {
        $tour = Tour::find($maTour);
>>>>>>> 558f8d9a959838049afa7e59c23074b6b7e3cfad
        return view('user.dattour', compact('tour'));
    }

    /**
<<<<<<< HEAD
     * Lưu thông tin đặt tour
     */
    // app/Http/Controllers/User/DatTourController.php

    public function getTourDates($maTour)
    {
        $chuyen = ChuyenTour::with('giatour')
            ->where('maTour', $maTour)
            ->where('tinhTrangChuyen', 'HoatDong')
            ->select('maChuyen', 'ngayBatDau', 'ngayKetThuc')
            ->get();

        $events = $chuyen->map(function ($c) {
            $gia = $c->giatour;               // quan hệ 1-1
            return [
                'title' => number_format($gia?->nguoiLon, 0, ',', '.') . ' ₫',   // lấy tiêu đề tour
                'start'        => $c->ngayBatDau,
                'color'        => '#28a745',
                'extendedProps' => [
                    'maChuyen'    => $c->maChuyen,
                    'giaNguoiLon' => $gia->nguoiLon ?? 0,
                    'giaTreEm'    => $gia->treEm   ?? 0,
                    'giaEmBe'     => $gia->emBe    ?? 0,
                    'ngayKetThuc' => $c->ngayKetThuc,
                ],
            ];
        });

        return response()->json($events);
    }
    public function store(Request $request)
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('user.login')->with('error', 'Vui lòng đăng nhập.');
        }

        $validated = $request->validate([
            'maTour' => 'required|exists:tour,maTour',
            'maChuyen' => 'required|exists:chuyentour,maChuyen',
=======
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'maTour' => 'required|exists:tour,maTour',
>>>>>>> 558f8d9a959838049afa7e59c23074b6b7e3cfad
            'hoTen' => 'required|string|max:255',
            'email' => 'required|email',
            'ngayKhoiHanh' => 'required|date',
            'ngayKetThuc' => 'required|date|after_or_equal:ngayKhoiHanh',
            'nguoiLon' => 'required|integer|min:1',
            'treEm' => 'required|integer|min:0',
<<<<<<< HEAD
            'emBe' => 'required|integer|min:0',
            'phuongThucThanhToan' => 'required|in:momo,paypal,tại văn phòng',
        ]);

        $user = Auth::guard('web')->user();

        // LẤY CHUYẾN + GIÁ THEO maChuyen
        $chuyen = ChuyenTour::with('giatour')
            ->where('maChuyen', $validated['maChuyen'])
            ->where('maTour', $validated['maTour'])
            ->firstOrFail();

        $gia = $chuyen->giatour; // Đây là 1 object

        $tongGia = 
            $validated['nguoiLon'] * $gia->nguoiLon +
            $validated['treEm']   * $gia->treEm +
            $validated['emBe']    * $gia->emBe;

        DatCho::create([
            'maNguoiDung' => $user->maNguoiDung,
            'tenNguoiDat' => $validated['hoTen'],
            'maChuyen' => $validated['maChuyen'],
            'maTour' => $validated['maTour'],
            'ngayDat' => now(),
            'ngayKhoiHanh' => $validated['ngayKhoiHanh'],
            'ngayKetThuc' => $validated['ngayKetThuc'],
            'soNguoiLon' => $validated['nguoiLon'],
            'soTreEm' => $validated['treEm'],
            'soEmBe' => $validated['emBe'],
            'tongGia' => $tongGia,
            'phuongThucThanhToan' => $validated['phuongThucThanhToan'],
            'xacNhan' => 0,
            'diaChi' => $request->address ?? $user->diaChi,
            'soDienThoai' => $request->phone ?? $user->soDienThoai,
            'email' => $request->email ?? $user->email,
        ]);

        return redirect()
            ->route('dattour.create', $validated['maTour'])
            ->with('success', 'Đặt tour thành công!');
=======
            'phuongThucThanhToan' => 'required|in:momo,paypal,tại văn phòng',
        ]);

        DatCho::create([
            'maNguoiDung' => Auth::id(),
            'maTour' => $validated['maTour'],
            'tenNguoiDat' => $validated['hoTen'],
            'ngayDat' => Carbon::now(),
            'ngayKhoiHanh' => $validated['ngayKhoiHanh'],
            'ngayKetThuc' => $validated['ngayKetThuc'],
            'nguoiLon' => $validated['nguoiLon'],
            'treEm' => $validated['treEm'],
            'tongGia' => $request->tongGia, // hoặc tính toán từ giá tour
            'phuongThucThanhToan' => $validated['phuongThucThanhToan'],
            'xacNhan' => 0, // mặc định chờ duyệt
        ]);

        return redirect()->route('dattour.create', ['maTour' => $validated['maTour']])
        ->with('success', 'Đặt tour thành công! Vui lòng chờ admin xác nhận.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
>>>>>>> 558f8d9a959838049afa7e59c23074b6b7e3cfad
    }
}
