<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DatCho;
use App\Models\HoaDon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;
use Illuminate\Support\Facades\Log;

class DatChoController extends Controller
{
    public function index()
    {
        $admin = auth()->guard('admin')->user();
        $datChos = DatCho::with(['tour', 'chuyentour', 'thanhtoan'])
                        ->orderBy('ngayDat', 'desc')
                        ->get();
        return view('admin.datcho.index', compact('datChos', 'admin'));
    }

    public function show($maDatCho)
    {
        $admin = auth()->guard('admin')->user();
        $datCho = DatCho::with([
                'tour' => fn($q) => $q->select('maTour', 'tieuDe', 'thoiGian'), 
                'chuyentour' => fn($q) => $q->select(
                    'maChuyen', 'maTour', 'diemKhoiHanh', 'maHDV', 'phuongTien',
                    'ngayBatDau', 'ngayKetThuc', 'soLuongToiDa', 'soLuongDaDat', 'tinhTrangChuyen'
                ),
                'chuyentour.giatour' => fn($q) => $q->select('maChuyen', 'nguoiLon', 'treEm', 'emBe'),
                'chuyentour.huongdanvien' => fn($q) => $q->select('maHDV', 'hoTen'),
                'thanhtoan',
                'hoadon'
            ])->findOrFail($maDatCho);

        // Tính giá
        $gia = $datCho->chuyentour?->giatour;
        $giaNguoiLon = $gia?->nguoiLon ?? 0;
        $giaTreEm    = $gia?->treEm ?? 0;
        $giaEmBe     = $gia?->emBe ?? 0;

        $tongTienTinhToan = 
            ($datCho->soNguoiLon * $giaNguoiLon) +
            ($datCho->soTreEm * $giaTreEm) +
            ($datCho->soEmBe * $giaEmBe);

        return view('admin.datcho.show', compact(
            'datCho', 'giaNguoiLon', 'giaTreEm', 'giaEmBe', 'tongTienTinhToan', 'admin'
        ));
    }

    /**
     * Xuất & Gửi Hóa Đơn qua Email
     */
    public function sendInvoice($maDatCho)
    {
        $datCho = DatCho::with([
                'tour',
                'chuyentour',
                'chuyentour.giatour',
                'chuyentour.huongdanvien',  // Lấy tên HDV
                'thanhtoan',
                'hoadon'
            ])->findOrFail($maDatCho);

        // Kiểm tra thanh toán
        if (!$datCho->thanhtoan || $datCho->thanhtoan->tinhTrangThanhToan !== 'Đã thanh toán') {
            return back()->with('error', 'Không thể gửi hóa đơn: Chưa thanh toán.');
        }

        // Tính giá (đảm bảo)
        $gia = $datCho->chuyentour?->giatour;
        $giaNguoiLon = $gia?->nguoiLon ?? 0;
        $giaTreEm    = $gia?->treEm ?? 0;
        $giaEmBe     = $gia?->emBe ?? 0;

        $tongTienTinhToan = 
            ($datCho->soNguoiLon * $giaNguoiLon) +
            ($datCho->soTreEm * $giaTreEm) +
            ($datCho->soEmBe * $giaEmBe);

        // Tạo/cập nhật hóa đơn
        $hoaDon = HoaDon::updateOrCreate(
            ['maDatCho' => $datCho->maDatCho],
            [
                'soTien' => $tongTienTinhToan,
                'ngayTao' => now(),
                'chiTiet' => "Hóa đơn tour: {$datCho->tour->tieuDe} - Mã chuyến: {$datCho->maChuyen}",
                'trangThai' => 'Đã gửi'
            ]
        );

        try {
            Mail::to($datCho->email)->send(new InvoiceMail($datCho, $hoaDon, $tongTienTinhToan));
            return back()->with('success', "Hóa đơn đã được gửi đến: {$datCho->email}");
        } catch (\Exception $e) {
            Log::error("Lỗi gửi email hóa đơn #{$maDatCho}: " . $e->getMessage());
            return back()->with('error', 'Gửi thất bại. Vui lòng kiểm tra email hoặc cấu hình mail.');
        }
    }
}