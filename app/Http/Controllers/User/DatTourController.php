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
use App\Models\ThanhToan;
use App\Models\KhuyenMai;
use App\Models\KhuyenMaiSuDung;

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

        // === phần mới cho mã khuyến mãi ===
        'maKM' => 'nullable|exists:khuyenmai,maKM',
        'giaGiam' => 'nullable|numeric|min:0',
    ]);

    $user = Auth::guard('web')->user();
    $maChuyenMoi = $validated['maChuyen'];

    // === LẤY NGÀY CỦA CHUYẾN ===
    $chuyenMoi = DB::table('chuyentour')
        ->where('maChuyen', $maChuyenMoi)
        ->select('ngayBatDau', 'ngayKetThuc')
        ->first();

    if (!$chuyenMoi) {
        return redirect()->back()->with('error', 'Chuyến tour không tồn tại.');
    }

    $startMoi = Carbon::parse($chuyenMoi->ngayBatDau);
    $endMoi   = Carbon::parse($chuyenMoi->ngayKetThuc);

    // === KIỂM TRA TRÙNG CHUYẾN ===
    $datChoDaDat = DB::table('datcho')
        ->join('chuyentour', 'datcho.maChuyen', '=', 'chuyentour.maChuyen')
        ->join('tour', 'datcho.maTour', '=', 'tour.maTour')
        ->where('datcho.maNguoiDung', $user->maNguoiDung)
        ->where('datcho.maChuyen', '!=', $maChuyenMoi)
        ->select('tour.tieuDe', 'chuyentour.ngayBatDau', 'chuyentour.ngayKetThuc')
        ->get();

    $trungVoi = [];
    foreach ($datChoDaDat as $d) {
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

    $tongGiaGoc = 
        $validated['nguoiLon'] * $gia->nguoiLon +
        $validated['treEm'] * $gia->treEm +
        $validated['emBe'] * $gia->emBe;

    // --- ÁP DỤNG MÃ GIẢM GIÁ ---
    $tongGiaSauGiam = $tongGiaGoc;
    $giaGiam = $request->giaGiam ?? 0;
    $maKM = $request->maKM ?? null;

    if ($maKM && $giaGiam > 0) {
        // đảm bảo không âm
        $tongGiaSauGiam = max(0, $tongGiaGoc - $giaGiam);
    }

    // === LƯU ĐẶT CHỖ ===
    $datCho = DatCho::create([
        'maNguoiDung' => $user->maNguoiDung,
        'hoTen' => $validated['hoTen'],
        'maChuyen' => $maChuyenMoi,
        'maTour' => $validated['maTour'],
        'ngayDat' => now(),
        'tongGia' => $tongGiaSauGiam,
        'phuongThucThanhToan' => $validated['phuongThucThanhToan'],
        'xacNhan' => 0,
        'diaChi' => $request->address ?? $user->diaChi ?? null,
        'soDienThoai' => $request->phone ?? $user->soDienThoai ?? null,
        'email' => $request->email ?? $user->email ?? null,
        'soNguoiLon' => $validated['nguoiLon'],
        'soTreEm' => $validated['treEm'],
        'soEmBe' => $validated['emBe'],
    ]);

    // === NẾU CÓ MÃ KHUYẾN MÃI → LƯU LỊCH SỬ SỬ DỤNG ===
    if ($maKM && $giaGiam > 0) {
        \App\Models\KhuyenMaiSuDung::create([
            'maKM' => $maKM,
            'maDatCho' => $datCho->maDatCho,
            'maNguoiDung' => $user->maNguoiDung,
            'giaGiam' => $giaGiam,
        ]);

        // tăng lượt sử dụng
        \App\Models\KhuyenMai::where('maKM', $maKM)
            ->increment('soLuotDaDung');
    }

    // === TẠO BẢN GHI THANH TOÁN ===
    ThanhToan::create([
        'maDatCho' => $datCho->maDatCho,
        'maNguoiDung' => $user->maNguoiDung,
        'phuongThucThanhToan' => $validated['phuongThucThanhToan'],
        'soTien' => $tongGiaSauGiam,
        'tinhTrangThanhToan' => null,
        'maGiaoDich' => null,
        'ngayThanhToan' => now(),
    ]);

    return redirect()
            ->route('dattour.create', $validated['maTour'])
            ->with('success', 'Đặt tour thành công!');
}

}
