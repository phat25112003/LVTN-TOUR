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
use App\Models\KhachThamGia;
use Illuminate\Support\Facades\Log;
use App\Services\ChuyenTourStatusService;

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
        $now = Carbon::now();
        $chuyen = ChuyenTour::with('giatour')
            ->where('maTour', $maTour)
            ->whereIn('tinhTrangChuyen', ['ChuaDuKhach', 'DuKhach'])
            ->get()
            ->filter(function ($c) use ($now) {
                $thoiDiemDongBan = ChuyenTourStatusService::thoiDiemDongBan($c);

                // ✅ CHỈ GIỮ LẠI CHUYẾN CHƯA ĐẾN HẠN ĐÓNG BÁN
                return $now->lt($thoiDiemDongBan);
            });

        $events = $chuyen->map(function ($c) {
            $slot = max(0, $c->soLuongToiDa - $c->soLuongDaDat);
            $gia = $c->giatour;               // quan hệ 1-1
            $hetHanDatVe = ChuyenTourStatusService::thoiDiemDongBan($c);
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
                    'soChoConLai'=> $slot,
                    'hetHanDatVe' => $hetHanDatVe->toIso8601String(),
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
            'phuongThucThanhToan' => 'required|in:momo,vnpay,tại văn phòng',
            'soDienThoai' => 'nullable|string|max:20',
            'diaChi' => 'nullable|string|max:255',
            // === phần mới cho mã khuyến mãi ===
            'maKM' => 'nullable|exists:khuyenmai,maKM',
            'giaGiam' => 'nullable|numeric|min:0',
            // === phần mới cho danh sách khách ===
            'hoTenKhach' => 'required|array',
            'hoTenKhach.*' => 'required|string|max:255',

            'gioiTinh' => 'required|array',
            'gioiTinh.*' => 'required|string|in:Nam,Nu',

            'tuoi' => 'required|array',
            'tuoi.*' => 'required|integer|min:0',

            'phongDon' => 'nullable|array',

            'loaiKhach' => 'required|array',
            'loaiKhach.*' => 'required|string|in:adult,child,baby',

            'ghiChu' => 'nullable|string|max:500',


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
        // --- KIỂM TRA ĐẶT TRÙNG TOUR + TRÙNG CHUYẾN ---
        $daDatChuyenNay = DatCho::where('maNguoiDung', $user->maNguoiDung)
        ->where('maTour', $validated['maTour'])
        ->where('maChuyen', $validated['maChuyen'])
        ->first();

        /**
         * TRÙNG TOUR + TRÙNG CHUYẾN
         * → CHƯA CONFIRM → HIỂN THỊ CẢNH BÁO
         * → ĐÃ CONFIRM → CHO ĐI TIẾP
         */
        if ($daDatChuyenNay && !$request->has('confirm_trung_tour_chuyen')) {
            return redirect()->back()
                ->withInput()
                ->with([
                    'warning_trung_tour_chuyen' => true,
                    'message_trung_tour_chuyen' =>
                        'Bạn đã đặt tour này cho đúng chuyến này rồi. Bạn có chắc chắn vẫn muốn tiếp tục đặt không?'
                ]);
        }


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

        if (!empty($trungVoi) && !$request->has('confirm_trung_chuyen')) {
            $danhSach = implode(', ', $trungVoi);

            return redirect()->back()
                ->withInput()
                ->with([
                    'warning_trung_chuyen' => true,
                    'message_trung_chuyen' =>
                        "Bạn đã đặt tour trùng thời gian với: $danhSach. Bạn có muốn tiếp tục đặt không?"
                ]);
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
        
        $now = now();
        $phuongThuc = $validated['phuongThucThanhToan'];

        if ($phuongThuc === 'momo' || $phuongThuc === 'vnpay') {
            $expireAt = $now->copy()->addMinutes(30);  // 30 phút
        } else {
            $expireAt = $now->copy()->addDays(7);    // 7 ngày tại văn phòng
        }

        // === LƯU ĐẶT CHỖ ===
        $datCho = DatCho::create([
            'maNguoiDung' => $user->maNguoiDung,
            'hoTen' => $validated['hoTen'],
            'maChuyen' => $maChuyenMoi,
            'maTour' => $validated['maTour'],
            'ngayDat' => now(),
            'ngayhethan' => $expireAt,
            'tongGia' => $tongGiaSauGiam,
            'phuongThucThanhToan' => $validated['phuongThucThanhToan'],
            'xacNhan' => 0,
            'diaChi' => $request->address ?? $user->diaChi ?? null,
            'soDienThoai' => $request->phone ?? $user->soDienThoai ?? null,
            'email' => $request->email ?? $user->email ?? null,
            'soNguoiLon' => $validated['nguoiLon'],
            'soTreEm' => $validated['treEm'],
            'soEmBe' => $validated['emBe'],
            'ghiChu' => $request->ghiChu ?? null,
        ]);
        // === LƯU DANH SÁCH KHÁCH + TÍNH PHỤ THU PHÒNG ĐƠN ===
        $phuThuPhongDon = 0;
        $tour = Tour::find($validated['maTour']);

        // Giá phòng đơn (lấy từ tour hoặc để giá mặc định)
        $giaPhongDon = $tour->giaPhongDon ?? 200000;

        for ($i = 0; $i < count($request->hoTenKhach); $i++) {

            $suDungPhongDon = !empty($request->phongDon[$i])
                ? 'PhongDon'
                : 'Ghep';

            KhachThamGia::create([
                'hoTenKhach'     => $request->hoTenKhach[$i],
                'gioiTinh'       => $request->gioiTinh[$i],
                'tuoi'           => $request->tuoi[$i],
                'maDatCho'       => $datCho->maDatCho,
                'luaChonPhong'   => $suDungPhongDon,
            ]);

            if ($suDungPhongDon === 'PhongDon') {
                $phuThuPhongDon += $giaPhongDon;
            }
        }

        // === CẬP NHẬT TỔNG GIÁ SAU KHI CÓ PHỤ THU PHÒNG ĐƠN ===
        $tongGiaSauGiam += $phuThuPhongDon;

        $datCho->update([
            'tongGia' => $tongGiaSauGiam,
        ]);


        // === NẾU CÓ MÃ KHUYẾN MÃI → LƯU LỊCH SỬ SỬ DỤNG ===
        if ($maKM && $giaGiam > 0) {
            KhuyenMaiSuDung::create([
                'maKM' => $maKM,
                'maDatCho' => $datCho->maDatCho,
                'maNguoiDung' => $user->maNguoiDung,
                'giaGiam' => $giaGiam,
            ]);

            // tăng lượt sử dụng
            KhuyenMai::where('maKM', $maKM)
                ->increment('soLuotDaDung');
        }
        // --- TĂNG số lượng đã đặt cho chuyến tour ---
        $totalNguoi = $validated['nguoiLon'] + $validated['treEm'] + $validated['emBe'];

        ChuyenTour::where('maChuyen', $maChuyenMoi)
            ->increment('soLuongDaDat', $totalNguoi);

        // CẬP NHẬT TÌNH TRẠNG CHUYẾN TOUR
        $chuyen = ChuyenTour::find($maChuyenMoi);
        ChuyenTourStatusService::capNhat($chuyen);

        return redirect()
                ->route('user.thongtinuser')
                ->with('success', 'Đặt tour thành công!');
    }
}


