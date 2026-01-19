<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DatCho;
use App\Models\HoaDon;
use App\Models\KhachThamGia;
use App\Exports\KhachThamGiaChuyenExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ChuyenTour;
use App\Models\KhuyenMai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DatChoController extends Controller
{

    public function index(Request $request)
    {
        $admin = auth('admin')->user();

        $query = DatCho::with([
            'tour', 
            'chuyentour', 
            'thanhtoan',
            'khuyenMaiDaDung.khuyenmai' 
        ]);

        // Bộ lọc theo ngày đặt
        if ($request->filled('from_date')) {
            $query->whereDate('ngayDat', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('ngayDat', '<=', $request->to_date);
        }

        // Phân trang và giữ query string (để giữ bộ lọc khi chuyển trang)
        $datChos = $query->orderByDesc('ngayDat')->paginate(15)->withQueryString();

        return view('admin.datcho.index', compact('datChos', 'admin'));
    }
public function show($maDatCho)
{
    $admin = auth('admin')->user();

    $datCho = DatCho::with([
        'tour:maTour,tieuDe,thoiGian,giaPhongDon', // <<< THÊM giaPhongDon
        'chuyentour:maChuyen,diemKhoiHanh,phuongTien,ngayBatDau,ngayKetThuc,soLuongToiDa,soLuongDaDat,maHDV',
        'chuyentour.huongdanvien:maHDV,hoTen,soDienThoai',
        'chuyentour.giatour',
        'khuyenMaiDaDung',     
        'thanhtoan'
    ])->findOrFail($maDatCho);

    // Load khách tham gia
    $khachThamGia = KhachThamGia::where('maDatCho', $maDatCho)
                                ->orderBy('maKhach')
                                ->get();

    // Phân loại khách theo độ tuổi (giữ nguyên logic cũ của bạn)
    $slNL = $datCho->soNguoiLon ?? 0;
    $slTE = $datCho->soTreEm ?? 0;
    $slEB = $datCho->soEmBe ?? 0;

    // Giá vé từng loại khách
    $giaNguoiLon = $datCho->chuyentour?->giatour?->nguoiLon ?? 0;
    $giaTreEm    = $datCho->chuyentour?->giatour?->treEm ?? 0;
    $giaEmBe     = $datCho->chuyentour?->giatour?->emBe ?? 0;

    // Tính tổng giá vé cơ bản (chưa có phụ phí phòng đơn)
    $tongGiaGoc = ($giaNguoiLon * $slNL) + ($giaTreEm * $slTE) + ($giaEmBe * $slEB);

    // === PHỤ PHÍ PHÒNG ĐƠN ===
    $soKhachPhongDon = $khachThamGia->where('luaChonPhong', 'PhongDon')->count();
    $giaPhongDon = $datCho->tour->giaPhongDon ?? 0; // Lấy từ bảng tour
    $phuPhiPhongDon = $soKhachPhongDon * $giaPhongDon;

    // Cộng phụ phí vào tổng gốc
    $tongGiaGoc += $phuPhiPhongDon;

    // Tổng giảm giá từ khuyến mãi
    $giaGiam = $datCho->khuyenMaiDaDung->sum('giaGiam');

    // Thành tiền cuối cùng
    $tongGiaThucThu = $tongGiaGoc - $giaGiam;

    return view('admin.datcho.show', compact(
        'datCho',
        'khachThamGia',
        'giaNguoiLon', 'giaTreEm', 'giaEmBe',
        'slNL', 'slTE', 'slEB',
        'tongGiaGoc',
        'giaGiam',
        'tongGiaThucThu',
        'admin',

        // === TRUYỀN THÊM CÁC BIẾN MỚI ĐỂ VIEW HIỂN THỊ ===
        'soKhachPhongDon',
        'giaPhongDon',
        'phuPhiPhongDon'
    ));
}

public function sendInvoice($maDatCho)
{
    // Load đầy đủ dữ liệu, đặc biệt thêm khachThamGia và giaPhongDon
    $datCho = DatCho::with([
        'tour:maTour,tieuDe,thoiGian,giaPhongDon',
        'chuyentour:maChuyen,diemKhoiHanh,ngayBatDau,ngayKetThuc,phuongTien',
        'chuyentour.huongdanvien:maHDV,hoTen,soDienThoai',
        'chuyentour.giatour',
        'khuyenMaiDaDung.khuyenmai',
        'thanhtoan',
        'hoadon',
        'khachThamGia' // <<< QUAN TRỌNG: để tính phụ phí và hiển thị danh sách khách
    ])->findOrFail($maDatCho);

    // Kiểm tra thanh toán
    if (!$datCho->thanhtoan || $datCho->thanhtoan->tinhTrangThanhToan !== 'Đã thanh toán') {
        return back()->with('error', 'Không thể gửi hóa đơn: Đơn hàng chưa được thanh toán hoàn tất.');
    }

    // === TÍNH TOÁN GIÁ ===
    $gia = $datCho->chuyentour?->giatour;

    $giaNguoiLon = $gia?->nguoiLon ?? 0;
    $giaTreEm    = $gia?->treEm ?? 0;
    $giaEmBe     = $gia?->emBe ?? 0;

    $slNL = $datCho->soNguoiLon ?? 0;
    $slTE = $datCho->soTreEm ?? 0;
    $slEB = $datCho->soEmBe ?? 0;

    $tongGiaGoc = ($slNL * $giaNguoiLon) + ($slTE * $giaTreEm) + ($slEB * $giaEmBe);

    // === PHỤ PHÍ PHÒNG ĐƠN ===
    $soKhachPhongDon = $datCho->khachThamGia->where('luaChonPhong', 'PhongDon')->count();
    $giaPhongDon = $datCho->tour->giaPhongDon ?? 0;
    $phuPhiPhongDon = $soKhachPhongDon * $giaPhongDon;

    // Cộng phụ phí vào tổng gốc
    $tongGiaGoc += $phuPhiPhongDon;

    // Tổng giảm giá
    $tongGiamGia = $datCho->khuyenMaiDaDung->sum('giaGiam');

    // Thành tiền cuối cùng
    $thanhTien = $tongGiaGoc - $tongGiamGia;

    // === CẬP NHẬT HÓA ĐƠN ===
    $hoaDon = HoaDon::updateOrCreate(
        ['maDatCho' => $datCho->maDatCho],
        [
            'soTien'    => $thanhTien,
            'ngayTao'   => now(),
            'chiTiet'   => "Hóa đơn tour: {$datCho->tour->tieuDe} (Mã đặt chỗ: #".str_pad($datCho->maDatCho, 6, '0', STR_PAD_LEFT).")",
            'trangThai' => 'Đã gửi'
        ]
    );

    // === GỬI EMAIL ===
    try {
        Mail::to($datCho->email)->send(new InvoiceMail(
            $datCho,
            $hoaDon,
            $tongGiaGoc,
            $tongGiamGia,
            $thanhTien
        ));

        return back()->with('success', "Đã gửi hóa đơn thành công đến email: {$datCho->email}");

    } catch (\Exception $e) {
        Log::error('Lỗi gửi email hóa đơn #' . $maDatCho . ': ' . $e->getMessage());

        return back()->with('error', 'Gửi email thất bại. Vui lòng kiểm tra lại cấu hình mail hoặc thử lại sau.');
    }
}

public function exportKhachChuyen($maChuyen)
{
    $chuyen = ChuyenTour::with('tour')->findOrFail($maChuyen);

    $tenTour = preg_replace('/[^A-Za-z0-9\-]/', '_', $chuyen->tour->tieuDe); // sạch ký tự đặc biệt

    $fileName = $chuyen->tour->tieuDe . ' - Chuyến #00' . $chuyen->maChuyen . ' (' . 
                \Carbon\Carbon::parse($chuyen->ngayBatDau)->format('d-m-Y') . ').xlsx';

    return Excel::download(new KhachThamGiaChuyenExport($maChuyen), $fileName);
}

public function destroy($maDatCho)
{
    $datCho = DatCho::findOrFail($maDatCho);

    // Cập nhật số lượng đã đặt trong chuyentour (giảm đi)
    if ($datCho->chuyentour) {
        $tongNguoi = $datCho->soNguoiLon + $datCho->soTreEm + $datCho->soEmBe;
        $datCho->chuyentour->soLuongDaDat = max(0, $datCho->chuyentour->soLuongDaDat - $tongNguoi);
        $datCho->chuyentour->save();
    }

    $datCho->hoadon()->delete();
    $datCho->khachthamgia()->delete();
    $datCho->thanhtoan()->delete();
    $datCho->delete();

    return redirect()->route('admin.datcho.index')->with('success', 'Đã xóa đặt chỗ thành công.');
}
}