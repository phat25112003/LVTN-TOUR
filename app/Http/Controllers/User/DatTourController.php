<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\DatCho;
use App\Models\GiaTour;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DatTourController extends Controller
{
    /**
     * Trang đặt tour
     */
    public function index()
    {
        return view('user.dattour');
    }

    /**
     * Form đặt tour cụ thể
     */
    public function create($maTour)
    {
        // Lấy tour kèm thông tin giá từ bảng giatour
        $tour = Tour::with('giatour')->find($maTour);

        if (!$tour) {
            return redirect()->route('home')->with('error', 'Tour không tồn tại.');
        }
        
        return view('user.dattour', compact('tour'));
    }

    /**
     * Lưu thông tin đặt tour
     */
    public function store(Request $request)
    {
        // ✅ Kiểm tra đăng nhập đúng guard
        if (!Auth::guard('web')->check()) {
            return redirect()->route('user.login')->with('error', 'Vui lòng đăng nhập trước khi đặt tour.');
        }

        // ✅ Validate dữ liệu đầu vào
        $validated = $request->validate([
            'maTour' => 'required|exists:tour,maTour',
            'hoTen' => 'required|string|max:255',
            'email' => 'required|email',
            'ngayKhoiHanh' => 'required|date',
            'ngayKetThuc' => 'required|date|after_or_equal:ngayKhoiHanh',
            'maChuyen' => 'required|exists:chuyentour,maChuyen',
            // Bổ sung đủ 4 nhóm người
            'nguoiLon' => 'required|integer|min:1',
            'treEm' => 'required|integer|min:0',
            'emBe' => 'required|integer|min:0',
            'phuongThucThanhToan' => 'required|in:momo,paypal,tại văn phòng',
        ]);

        $user = Auth::guard('web')->user();
        $tour = Tour::with('giatour')->find($validated['maTour']);

        if (!$tour || !$tour->giatour) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giá tour.');
        }

        // ✅ Lấy giá từ bảng giatour
        $gia = $tour->giatour;
        $tongGia =
            ($validated['nguoiLon'] * $gia->nguoiLon) +
            ($validated['treEm'] * $gia->treEm) +
            ($validated['emBe'] * $gia->emBe);

        // ✅ Tạo bản ghi đặt chỗ
        DatCho::create([
            'maNguoiDung' => $user->maNguoiDung,
            'tenNguoiDat' => $validated['hoTen'],
            'maChuyen' => $validated['maChuyen'],
            'maTour' => $validated['maTour'],
            'ngayDat' => Carbon::now(),
            'ngayKhoiHanh' => $validated['ngayKhoiHanh'],
            'ngayKetThuc' => $validated['ngayKetThuc'],
            'soNguoiLon' => $validated['nguoiLon'],
            'soTreEm' => $validated['treEm'],
            'soEmBe' => $validated['emBe'],
            'tongGia' => $tongGia,
            'phuongThucThanhToan' => $validated['phuongThucThanhToan'],
            'xacNhan' => 0,
            'diaChi' => $user->diaChi,
            'soDienThoai' => $user->soDienThoai,
            'email' => $user->email,
        ]);

        return redirect()
            ->route('dattour.create', ['maTour' => $validated['maTour']])
            ->with('success', 'Đặt tour thành công! Vui lòng chờ admin xác nhận.');
    }
    public function getTourDates($maTour)
    {
        $tour = Tour::with(['chuyentour.giatour'])->find($maTour);

        if (!$tour || $tour->chuyentour->isEmpty()) {
            return response()->json([]);
        }

        // Tạo mảng các chuyến tour
        $events = $tour->chuyentour->map(function ($chuyen) use ($tour) {
            return [
                'title' => $tour->tieuDe,
                'start' => \Carbon\Carbon::parse($chuyen->ngayBatDau)->format('Y-m-d'), // chỉ ngày bắt đầu
                'color' => '#28a745',
                'extendedProps' => [
                    'maChuyen' => $chuyen->maChuyen,
                    'giaNguoiLon' => optional($chuyen->giatour)->nguoiLon,
                    'giaTreEm' => optional($chuyen->giatour)->treEm,
                    'giaEmBe' => optional($chuyen->giatour)->emBe,
                    'ngayKetThuc' => $chuyen->ngayKetThuc,
                ]
            ];
        });

        return response()->json($events);
    }

}
