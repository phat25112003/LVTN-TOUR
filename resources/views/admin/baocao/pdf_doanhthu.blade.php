{{-- resources/views/admin/baocao/pdf_doanhthu.blade.php --}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Báo Cáo Doanh Thu Chi Tiết - TRAVELTIME</title>
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif; 
            margin: 40px 30px; 
            color: #2c3e50; 
            line-height: 1.6; 
            font-size: 14px;
        }
        .logo { text-align: center; margin-bottom: 20px; }
        .logo h1 { 
            font-size: 42px; 
            background: linear-gradient(135deg, #0d6efd, #0dcaf0); 
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent; 
            font-weight: 900;
            margin: 0;
        }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #0d6efd; margin: 10px 0 8px; font-size: 28px; }
        .header h2 { margin: 0; font-size: 20px; color: #555; }
        .info-box { 
            background: #f8f9fa; 
            padding: 20px; 
            border-radius: 10px; 
            margin: 30px 0; 
            border-left: 5px solid #0d6efd; 
        }
        .info-box p { margin: 8px 0; font-size: 15px; }
        .highlight { color: #e74c3c; font-weight: bold; font-size: 18px; }
        table { width: 100%; border-collapse: collapse; margin: 30px 0; font-size: 13.5px; }
        th { 
            background: #0d6efd; 
            color: white; 
            padding: 14px 10px; 
            text-align: center; 
            font-weight: bold; 
            font-size: 14px;
        }
        td { 
            padding: 12px 10px; 
            text-align: center; 
            vertical-align: middle;
            border: 1px solid #ddd;
        }
        tr:nth-child(even) { background-color: #f8f9fa; }
        tr:hover { background-color: #e3f2fd; }
        .paid { color: #27ae60; font-weight: bold; }
        .unpaid { color: #e74c3c; font-weight: bold; }
        .doanhthu { color: #27ae60; font-weight: bold; font-size: 15px; }
        .total-row { background: #d4edda !important; font-weight: bold; font-size: 16px; }
        .total-row td { font-size: 17px; color: #155724; }
        .footer { margin-top: 80px; text-align: center; color: #7f8c8d; font-size: 13px; padding-top: 20px; border-top: 1px solid #eee; }
    </style>
</head>
<body>

    <div class="logo">
        <h1>TRAVELTIME</h1>
    </div>

    <div class="header">
        <h1>BÁO CÁO DOANH THU CHI TIẾT</h1>
        <h2>Từ ngày {{ $tuNgay }} đến {{ $denNgay }}</h2>
    </div>

    <div class="info-box">
        <p><strong>Ngày xuất báo cáo:</strong> {{ now()->format('d/m/Y H:i') }}</p>
        <p><strong>Tổng số đơn đặt:</strong> <span class="highlight">{{ number_format($tongDonDat) }}</span> đơn</p>
        <p><strong>Số đơn đã thanh toán:</strong> <span class="highlight">{{ number_format($tongDonThanhToan) }}</span> đơn 
            ({{ $tongDonDat > 0 ? round(($tongDonThanhToan / $tongDonDat) * 100, 1) : 0 }}% chuyển đổi)</p>
        <p><strong>TỔNG DOANH THU THỰC TẾ:</strong> 
            <span class="highlight">{{ number_format($tongDoanhThu) }} VNĐ</span>
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">STT</th>
                <th width="10%">Ngày đặt</th>
                <th width="14%">Họ tên</th>
                <th width="10%">Điện thoại</th>
                <th width="18%">Tên tour</th>
                <th width="11%">Ngày đi</th>
                <th width="7%">Số khách</th>
                <th width="11%">Tổng tiền đặt</th>
                <th width="14%">Trạng thái thanh toán</th>
                <th width="10%">Doanh thu thực tế</th> <!-- CỘT MỚI -->
            </tr>
        </thead>
        <tbody>
            @forelse($chiTiet as $index => $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item['ngay_dat'] }}</td>
                    <td><strong>{{ $item['ho_ten'] }}</strong></td>
                    <td>{{ $item['dien_thoai'] }}</td>
                    <td style="text-align:left; padding-left:10px;">{{ $item['tour'] }}</td>
                    <td>{{ $item['ngay_di'] }}</td>
                    <td><strong>{{ $item['so_khach'] }} khách</strong></td>
                    <td><strong>{{ $item['tong_tien_dat'] }}</strong></td>
                    <td>{!! $item['thanh_toan'] !!}</td>

                    <!-- CỘT DOANH THU: CHỈ HIỆN NẾU ĐÃ THANH TOÁN -->
                    <td>
                        @if($item['doanh_thu'] > 0)
                            <strong class="doanhthu">{{ number_format($item['doanh_thu']) }} ₫</strong>
                        @else
                            —
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align:center; color:#999; padding:40px; font-style:italic;">
                        Không có đơn đặt tour nào trong khoảng thời gian này.
                    </td>
                </tr>
            @endforelse

            <!-- DÒNG TỔNG CỘNG – ĐÃ SỬA ĐÚNG 100% -->
            <tr class="total-row">
                <td colspan="5"><strong>TỔNG CỘNG</strong></td>
                <td><strong>{{ number_format($tongDonDat) }} đơn</strong></td>
                <td colspan="2"></td> <!-- Cột "Tổng tiền đặt" + "Trạng thái thanh toán" -->
                <td><strong>{{ number_format($tongDonThanhToan) }} đơn đã thanh toán</strong></td>
                <td><strong>{{ number_format($tongDoanhThu) }} ₫</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p><strong>TRAVELTIME</strong> - Hệ thống đặt tour du lịch hàng đầu Việt Nam</p>
        <p>Hotline: 1900 1234 | Email: info@traveltime.vn | Website: www.traveltime.vn</p>
        <p>Báo cáo được tạo tự động bởi hệ thống quản trị • {{ now()->format('d/m/Y H:i') }}</p>
    </div>

</body>
</html>