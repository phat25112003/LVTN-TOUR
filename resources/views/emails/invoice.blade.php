{{-- resources/views/emails/invoice.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hóa Đơn Điện Tử</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; background: #f4f6f9; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .header { background: #0d6efd; color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .content { padding: 30px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; font-weight: 600; }
        .total { font-size: 1.4em; font-weight: bold; color: #dc3545; }
        .badge { padding: 6px 12px; border-radius: 50px; font-size: 0.9em; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-info { background: #d1ecf1; color: #0c5460; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 0.9em; color: #6c757d; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>HÓA ĐƠN ĐIỆN TỬ</h1>
        <p>Mã đặt chỗ: <strong>#{{ str_pad($datCho->maDatCho, 6, '0', STR_PAD_LEFT) }}</strong></p>
    </div>

    <div class="content">
        <div style="display: flex; justify-content: space-between; margin-bottom: 30px;">
            <div>
                <h3>Thông Tin Khách Hàng</h3>
                <p><strong>Họ tên:</strong> {{ $datCho->hoTen ?? 'Khách vãng lai' }}</p>
                <p><strong>Email:</strong> {{ $datCho->email }}</p>
                <p><strong>Số ĐT:</strong> {{ $datCho->soDienThoai }}</p>
                <p><strong>Địa chỉ:</strong> {{ $datCho->diaChi ?? 'N/A' }}</p>
            </div>
            <div style="text-align: right;">
                <h3>Công ty TNHH Du Lịch TravelTime</h3>
                <p>123 Đường Cao Lỗ, Quận 8, TPHCM</p>
                <p>Hotline: 1900 1234</p>
                <p>Email: info@dulichabc.com</p>
            </div>
        </div>

        <hr>

        <h3>Thông Tin Tour & Chuyến Đi</h3>
        <p><strong>Tên tour:</strong> {{ $datCho->tour->tieuDe }}</p>
        <p><strong>Mã chuyến:</strong> <span class="badge badge-success">#00{{ $datCho->maChuyen }}</span></p>

        @if($datCho->chuyentour)
            <p><strong>Khởi hành:</strong> {{ \Carbon\Carbon::parse($datCho->chuyentour->ngayBatDau)->format('d/m/Y') }}</p>
            <p><strong>Kết thúc:</strong> {{ \Carbon\Carbon::parse($datCho->chuyentour->ngayKetThuc)->format('d/m/Y') }}</p>
            <p><strong>Điểm khởi hành:</strong> {{ $datCho->chuyentour->diemKhoiHanh }}</p>
            <p><strong>Phương tiện:</strong> {{ $datCho->chuyentour->phuongTien }}</p>
            <p><strong>Hướng dẫn viên:</strong> 
                {{ $datCho->chuyentour->huongdanvien->hoTen ?? 'Chưa phân công' }}
            </p>
            <p><strong>Số chỗ:</strong> 
                {{ $datCho->chuyentour->soLuongDaDat }} / {{ $datCho->chuyentour->soLuongToiDa }} 
                <!-- <span class="badge badge-info">
                    {{ $datCho->chuyentour->tinhTrangChuyen }}
                </span> -->
            </p>
        @else
            <p class="text-danger">Không tìm thấy thông tin chuyến tour.</p>
        @endif

        <h3 style="margin-top: 30px;">Chi Tiết Thanh Toán</h3>
        <table>
            <thead>
                <tr>
                    <th>Loại khách</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $gia = $datCho->chuyentour?->giatour;
                    $giaNguoiLon = $gia?->nguoiLon ?? 0;
                    $giaTreEm    = $gia?->treEm ?? 0;
                    $giaEmBe     = $gia?->emBe ?? 0;
                @endphp

                <tr>
                    <td>Người lớn</td>
                    <td style="text-align: center;">{{ $datCho->soNguoiLon }}</td>
                    <td style="text-align: right;">{{ number_format($giaNguoiLon) }}₫</td>
                    <td style="text-align: right;">{{ number_format($datCho->soNguoiLon * $giaNguoiLon) }}₫</td>
                </tr>
                <tr>
                    <td>Trẻ em</td>
                    <td style="text-align: center;">{{ $datCho->soTreEm }}</td>
                    <td style="text-align: right;">{{ number_format($giaTreEm) }}₫</td>
                    <td style="text-align: right;">{{ number_format($datCho->soTreEm * $giaTreEm) }}₫</td>
                </tr>
                <tr>
                    <td>Em bé</td>
                    <td style="text-align: center;">{{ $datCho->soEmBe }}</td>
                    <td style="text-align: right;">{{ number_format($giaEmBe) }}₫</td>
                    <td style="text-align: right;">{{ number_format($datCho->soEmBe * $giaEmBe) }}₫</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right; font-weight: bold; font-size: 1.3em; color: #dc3545;">
                        TỔNG CỘNG:
                    </td>
                    <td style="text-align: right; font-weight: bold; color: #dc3545; font-size: 1.3em;">
                        {{ number_format($tongTienTinhToan) }}₫
                    </td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top: 30px; padding: 15px; background: #d4edda; border-radius: 8px; text-align: center;">
            <p><strong>Thanh toán thành công!</strong></p>
            <p>Mã giao dịch: <strong>{{ $datCho->thanhtoan->maGiaoDich ?? 'N/A' }}</strong></p>
            <p>Ngày thanh toán: 
                {{ $datCho->thanhtoan->ngayThanhToan 
                    ? \Carbon\Carbon::parse($datCho->thanhtoan->ngayThanhToan)->format('d/m/Y H:i')
                    : 'Chưa ghi nhận' 
                }}
            </p>
        </div>
    </div>

    <div class="footer">
        <p>Cảm ơn quý khách đã sử dụng dịch vụ!</p>
        <p>Hóa đơn được tạo tự động vào {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</div>
</body>
</html>