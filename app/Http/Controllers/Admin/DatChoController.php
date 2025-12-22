<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DatCho;
use App\Models\HoaDon;
use App\Models\KhuyenMai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DatChoController extends Controller
{
// App\Http\Controllers\Admin\DatChoController.php

    public function index()
    {
        $admin = auth('admin')->user();
        $datChos = DatCho::with([
            'tour', 
            'chuyentour', 
            'thanhtoan',
            // Eager load bảng trung gian khuyenmai_sudung và mối quan hệ KhuyenMai bên trong nó
            'khuyenMaiDaDung.khuyenmai' 
        ])->get();
        
        return view('admin.datcho.index', compact('datChos','admin'));
    }
public function show($maDatCho)
{
    $admin = auth('admin')->user();
    $datCho = DatCho::with([
        'tour:maTour,tieuDe,thoiGian',
        'chuyentour:maChuyen,diemKhoiHanh,phuongTien,ngayBatDau,ngayKetThuc,soLuongToiDa,soLuongDaDat,maHDV',
        'chuyentour.huongdanvien:maHDV,hoTen,soDienThoai',
        'chuyentour.giatour',
        'khuyenMaiDaDung',      // ← Đúng tên bạn muốn
        'thanhtoan'
    ])->findOrFail($maDatCho);

    $giaNguoiLon = $datCho->chuyentour?->giatour?->nguoiLon ?? 0;
    $giaTreEm    = $datCho->chuyentour?->giatour?->treEm ?? 0;
    $giaEmBe     = $datCho->chuyentour?->giatour?->emBe ?? 0;

    $slNL = $datCho->soNguoiLon ?? 0;
    $slTE = $datCho->soTreEm ?? 0;
    $slEB = $datCho->soEmBe ?? 0;

    $tongGiaGoc = ($giaNguoiLon * $slNL) + ($giaTreEm * $slTE) + ($giaEmBe * $slEB);

    // Tổng giảm chính xác 100%
    $giaGiam = $datCho->khuyenMaiDaDung->sum('giaGiam');

    $tongGiaThucThu = $tongGiaGoc - $giaGiam;

    return view('admin.datcho.show', compact(
        'datCho',
        'giaNguoiLon', 'giaTreEm', 'giaEmBe',
        'slNL', 'slTE', 'slEB',
        'tongGiaGoc', 'giaGiam', 'tongGiaThucThu','admin'
    ));
}

public function sendInvoice($maDatCho)
{
    // Load đầy đủ dữ liệu cần thiết
    $datCho = DatCho::with([
        'tour:maTour,tieuDe',
        'chuyentour:maChuyen,diemKhoiHanh,ngayBatDau,ngayKetThuc,maHDV',
        'chuyentour.giatour',
        'chuyentour.huongdanvien:maHDV,hoTen,soDienThoai',
        'khuyenMaiDaDung.khuyenmai',   // ← Quan trọng: load nhiều mã KM + tên mã
        'thanhtoan',
        'hoadon'
    ])->findOrFail($maDatCho);

    // Kiểm tra thanh toán
    if (!$datCho->thanhtoan || $datCho->thanhtoan->tinhTrangThanhToan !== 'Đã thanh toán') {
        return back()->with('error', 'Không thể gửi hóa đơn: Chưa thanh toán hoàn tất.');
    }

    // === TÍNH TOÁN GIÁ CHÍNH XÁC 100% ===
    $gia = $datCho->chuyentour?->giatour;

    $giaNguoiLon = $gia?->nguoiLon ?? 0;
    $giaTreEm    = $gia?->treEm ?? 0;
    $giaEmBe     = $gia?->emBe ?? 0;

    $tongGiaGoc = ($datCho->soNguoiLon * $giaNguoiLon) +
                  ($datCho->soTreEm    * $giaTreEm) +
                  ($datCho->soEmBe     * $giaEmBe);

    // Tổng giảm = tổng tất cả giaGiam trong bảng khuyenmai_sudung
    $tongGiamGia = $datCho->khuyenMaiDaDung->sum('giaGiam');

    $thanhTien = $tongGiaGoc - $tongGiamGia;

    // === TẠO HOẶC CẬP NHẬT HÓA ĐƠN ===
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
            $tongGiaGoc,      // truyền thêm để blade dùng
            $tongGiamGia,
            $thanhTien
        ));

        return back()->with('success', "Đã gửi hóa đơn thành công đến email: {$datCho->email}");

        } catch (\Exception $e) {
            Log::error("Lỗi gửi hóa đơn #{$maDatCho}: " . $e->getMessage());
            // \Sentry\captureException($e);  ← XÓA HOẶC COMMENT DÒNG NÀY
            return back()->with('error', 'Gửi email thất bại. Vui lòng thử lại sau.');
        }
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

    // Xóa đặt chỗ (cascade sẽ xóa hoadon, thanhtoan, khuyenmai_sudung nếu có onDelete cascade)
    $datCho->delete();

    return redirect()->route('admin.datcho.index')->with('success', 'Đã xóa đặt chỗ thành công.');
}
}