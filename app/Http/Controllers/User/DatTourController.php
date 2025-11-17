<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\DatCho;
use App\Models\GiaTour;
use App\Models\ChuyenTour;
use Illuminate\Support\Facades\DB;
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
        return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập.'], 401);
    }

    $validated = $request->validate([
        'maTour' => 'required|exists:tour,maTour',
        'maChuyen' => 'required|exists:chuyentour,maChuyen',
        'hoTen' => 'required|string|max:255',
        'email' => 'required|email',
        'nguoiLon' => 'required|integer|min:1',
        'treEm' => 'required|integer|min:0',
        'emBe' => 'required|integer|min:0',
        'phuongThucThanhToan' => 'required|in:momo,paypal,tại văn phòng',
    ]);

    $user = Auth::guard('web')->user();
    $maChuyenMoi = $validated['maChuyen'];

    // LẤY NGÀY CỦA CHUYẾN ĐANG ĐẶT
    $chuyenMoi = DB::table('chuyentour')
        ->where('maChuyen', $maChuyenMoi)
        ->select('ngayBatDau', 'ngayKetThuc')
        ->first();

    if (!$chuyenMoi) {
        return redirect()->back()->with('error', 'Chuyến tour không tồn tại.');
    }

    $startMoi = Carbon::parse($chuyenMoi->ngayBatDau);
    $endMoi   = Carbon::parse($chuyenMoi->ngayKetThuc);

    // LẤY TẤT CẢ CHUYẾN ĐÃ ĐẶT (XÁC NHẬN) CỦA USER + NGÀY TỪ CHUYENTOUR
    $datCho = DB::table('datcho')
        ->join('chuyentour', 'datcho.maChuyen', '=', 'chuyentour.maChuyen')
        ->join('tour', 'datcho.maTour', '=', 'tour.maTour')
        ->where('datcho.maNguoiDung', $user->maNguoiDung)
        ->where('datcho.maChuyen', '!=', $maChuyenMoi) // loại trừ chính nó
        ->select(
            'tour.tieuDe',
            'chuyentour.ngayBatDau',
            'chuyentour.ngayKetThuc'
        )
        ->get();

    // KIỂM TRA TRÙNG (gọn như đoạn bạn gửi)
    $trungVoi = [];

    foreach ($datCho as $d) {
        $start = Carbon::parse($d->ngayBatDau);
        $end   = Carbon::parse($d->ngayKetThuc);

        if ($startMoi->lte($end) && $endMoi->gte($start)) {
            $trungVoi[] = $d->tieuDe;
        }
    }

    if (!empty($trungVoi)) {
        $danhSach = implode(', ', $trungVoi);
        return redirect()->back()
            ->with('error', "Bạn đã đặt tour trùng thời gian với: $danhSach. Vui lòng chọn chuyến khác!");
    }

    // === TÍNH GIÁ ===
    $gia = DB::table('giatour')->where('maChuyen', $maChuyenMoi)->first();
    if (!$gia) {
        return response()->json(['success' => false, 'message' => 'Chưa có bảng giá.'], 400);
    }

    $tongGia = $validated['nguoiLon'] * $gia->nguoiLon +
               $validated['treEm']   * $gia->treEm +
               $validated['emBe']    * $gia->emBe;

    // === LƯU ĐẶT CHỖ ===
    DatCho::create([
        'maNguoiDung' => $user->maNguoiDung,
        'hoTen' => $validated['hoTen'],
        'maChuyen' => $maChuyenMoi,
        'maTour' => $validated['maTour'],
        'ngayDat' => now(),
        'tongGia' => $tongGia,
        'phuongThucThanhToan' => $validated['phuongThucThanhToan'],
        'xacNhan' => 0,
        'diaChi' => $request->address ?? $user->diaChi ?? null,
        'soDienThoai' => $request->phone ?? $user->soDienThoai ?? null,
        'email' => $request->email ?? $user->email ?? null,
        'soNguoiLon' => $validated['nguoiLon'],
        'soTreEm' => $validated['treEm'],
        'soEmBe' => $validated['emBe'],
    ]);

    return redirect()
            ->route('dattour.create', $validated['maTour'])
            ->with('success', 'Đặt tour thành công!');
    }
}
