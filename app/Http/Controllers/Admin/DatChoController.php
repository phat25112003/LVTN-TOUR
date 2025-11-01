<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DatCho;
use App\Models\ThanhToan;
use App\Models\HoaDon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; // Cần thiết cho gửi mail
use App\Mail\InvoiceMail; // <-- Đảm bảo dòng này tồn tại và không bị lỗi chính tả

class DatChoController extends Controller
{
    public function index()
    {
        $admin = auth()->guard('admin')->user();
        // Eager load quan hệ thanhtoan VÀ hoadon
        $datChos = DatCho::with(['nguoiDung', 'tour', 'thanhtoan', 'hoadon'])
                             ->orderBy('ngayDat', 'desc')
                             ->get();
        return view('admin.datcho.index', compact('datChos','admin'));
    }

    public function xacNhan($maDatCho)
    {
        // 1. Tìm kiếm đặt chỗ và eager load thông tin thanh toán
        $datCho = DatCho::with('thanhtoan')->findOrFail($maDatCho);
        
        // 2. Kiểm tra nếu đặt chỗ chưa được xác nhận (xacNhan == 0)
        if ($datCho->xacNhan == 0) {
            
            // 3. Thực hiện quy tắc nghiệp vụ: Phải kiểm tra trạng thái thanh toán
            if ($datCho->thanhtoan && $datCho->thanhtoan->tinhTrangThanhToan === 'Đã thanh toán') {
                
                // Nếu Đã thanh toán, tiến hành xác nhận
                $datCho->update(['xacNhan' => 1]);
                
                return redirect()->route('admin.datcho.index')->with('success', 'Xác nhận tour thành công.');
            } else {
                // Nếu Chưa thanh toán hoặc không tìm thấy bản ghi thanh toán
                return redirect()->route('admin.datcho.index')->with('error', 'Không thể xác nhận: Đặt chỗ chưa được thanh toán.');
            }
        }
        
        // Trạng thái đã được xác nhận từ trước
        return redirect()->route('admin.datcho.index')->with('warning', 'Đặt chỗ này đã được xác nhận trước đó.');
    }

    public function xacNhanThanhToan($maDatCho)
    {
        $datCho = DatCho::with('thanhtoan')->findOrFail($maDatCho);

        // 1. Kiểm tra phương thức có phải là 'tại văn phòng'
        if ($datCho->phuongThucThanhToan !== 'tại văn phòng') {
            // Sửa tên route: datcho.index
            return redirect()->route('admmin.datcho.index')->with('error', 'Không thể xác nhận thanh toán thủ công: Phương thức không phải là "tại văn phòng".');
        }

        // 2. Kiểm tra và cập nhật bản ghi thanh toán
        $thanhToan = $datCho->thanhtoan;

        // Nếu bản ghi thanh toán chưa tồn tại, tạo mới.
        if (!$thanhToan) {
            $thanhToan = ThanhToan::create([
                'maDatCho' => $datCho->maDatCho,
                'phuongThucThanhToan' => $datCho->phuongThucThanhToan,
                'soTien' => $datCho->tongGia,
                'tinhTrangThanhToan' => 'Đã thanh toán',
                'maGiaoDich' => 'TT_VP_' . $datCho->maDatCho . '_' . now()->format('Ymd'),
                'ngayThanhToan' => now(),
            ]);
            
            // Sửa tên route: datcho.index
            return redirect()->route('admin.datcho.index')->with('success', 'Đã tạo và Xác nhận thanh toán thành công (Thanh toán tại văn phòng).');

        } 
        
        // Nếu bản ghi thanh toán đã tồn tại nhưng chưa thanh toán
        elseif ($thanhToan->tinhTrangThanhToan !== 'Đã thanh toán') {
            
            $thanhToan->update([
                'tinhTrangThanhToan' => 'Đã thanh toán',
                'ngayThanhToan' => now(),
            ]);

            // Sửa tên route: datcho.index
            return redirect()->route('admin.datcho.index')->with('success', 'Đã cập nhật trạng thái thanh toán thành công.');
        } 
        
        // Đã thanh toán rồi
        else {
            // Sửa tên route: datcho.index
            return redirect()->route('admin.datcho.index')->with('warning', 'Đặt chỗ này đã được thanh toán trước đó.');
        }
    }

    public function show($maDatCho)
    {
        $admin = auth()->guard('admin')->user();
        // Lấy toàn bộ thông tin chi tiết, bao gồm cả hóa đơn
        $datCho = DatCho::with(['nguoiDung', 'tour', 'thanhtoan', 'hoadon'])->findOrFail($maDatCho);
        
        return view('admin.datcho.show', compact('datCho', 'admin'));
    }

    /**
     * Xuất và Gửi Hóa Đơn qua Email
     */
    public function sendInvoice($maDatCho)
    {
        $datCho = DatCho::with(['nguoiDung', 'tour', 'thanhtoan'])->findOrFail($maDatCho);
        
        // 1. Kiểm tra điều kiện xuất hóa đơn
        $isPaid = $datCho->thanhtoan && $datCho->thanhtoan->tinhTrangThanhToan === 'Đã thanh toán';
        
        if ($datCho->xacNhan == 0 || !$isPaid) {
            return redirect()->route('admin.datcho.show', $maDatCho)->with('error', 'Lỗi: Đặt chỗ chưa được xác nhận hoặc chưa thanh toán.');
        }

        // 2. Tạo hoặc cập nhật bản ghi Hóa Đơn (HoaDon)
        // Dùng updateOrCreate nếu maDatCho đã tồn tại
        $hoaDon = HoaDon::updateOrCreate(
            ['maDatCho' => $datCho->maDatCho],
            [
                'soTien' => $datCho->tongGia,
                'ngayTao' => now(),
                'chiTiet' => 'Hóa đơn đặt tour ' . $datCho->tour->tieuDe, // Có thể thêm chi tiết nếu cần
                'trangThai' => 'Chờ gửi'
            ]
        );

        // 3. Gửi email
        try {
            Mail::to($datCho->email)->send(new InvoiceMail($datCho, $hoaDon)); 
            // Nếu gửi mail thật, hãy đảm bảo email tồn tại và cấu hình đúng
            
            $hoaDon->update(['trangThai' => 'Đã gửi']);
            
            return redirect()->route('admin.datcho.show', $maDatCho)->with('success', 'Đã xuất và gửi hóa đơn thành công đến email: ' . $datCho->email);
        } catch (\Exception $e) {
            // Log lỗi $e->getMessage() nếu cần
            return redirect()->route('admin.datcho.show', $maDatCho)->with('error', 'Lỗi khi gửi email hóa đơn. Vui lòng kiểm tra cấu hình mail.');
        }
    }
}