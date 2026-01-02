<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Hóa Đơn Điện Tử #{{ str_pad($datCho->maDatCho, 6, '0', STR_PAD_LEFT) }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {margin:0;padding:0;font-family:'Inter',Arial,sans-serif;background:#f7f9fc;color:#333;line-height:1.6}
        .container {max-width:600px;margin:40px auto;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,.05);border:1px solid #e0e0e0}
        .header {background:#1a1a1a;color:#fff;text-align:center;padding:30px 20px}
        .header h1 {font-size:28px;font-weight:700;margin:0 0 5px;letter-spacing:1px;text-transform:uppercase}
        .header .subtitle {font-size:14px;opacity:0.8;margin:0}
        .order-code {font-family:monospace;font-weight:600;font-size:18px;color:#63b3ed;margin-top:15px;display:inline-block;padding:5px 10px;border:1px dashed #444;border-radius:4px}
        .content {padding:30px}
        .greeting {font-size:18px;font-weight:600;margin-bottom:15px;color:#1a1a1a}
        .intro {font-size:14px;color:#555;margin-bottom:25px}
        .section-title {font-size:16px;font-weight:600;margin-bottom:15px;color:#1a1a1a;border-bottom:2px solid #63b3ed;display:inline-block;padding-bottom:5px;text-transform:uppercase;letter-spacing:.5px}
        .info-grid-table {width:100%;border-collapse:collapse;table-layout:fixed}
        .info-grid-table td {vertical-align:top;width:50%;padding:0 10px 10px 0}
        .info-item {padding:15px;border-bottom:1px solid #eee}
        .info-item:last-child {border-bottom:none}
        .info-label {font-size:12px;font-weight:500;text-transform:uppercase;color:#888;margin-bottom:3px}
        .info-value {font-size:14px;font-weight:500;color:#333}
        .price-table {width:100%;border-collapse:collapse;margin:25px 0;background:#f9f9f9;border:1px solid #eee;border-radius:6px;overflow:hidden}
        .price-table td {padding:12px 15px;border-bottom:1px solid #e0e0e0;font-size:14px}
        .price-table tr:last-child td {border-bottom:none}
        .price-label {font-weight:400;color:#555}
        .price-amount {text-align:right;font-weight:500;color:#333}
        .total-original {background:#f0f0f0}
        .discount-row {color:#d9534f;background:#fff5f5}
        .discount-row .price-amount {color:#d9534f;font-weight:600}
        .final-total {background:#63b3ed;color:#fff;font-weight:700;font-size:16px}
        .final-total .price-amount {font-size:18px;color:#fff}
        .payment-box {text-align:center;border:2px solid #63b3ed;border-radius:8px;padding:20px;background:#f0f8ff;margin:25px 0}
        .payment-status {font-size:18px;color:#1a1a1a;font-weight:700;margin-bottom:8px}
        .payment-method {display:inline-block;background:#1a1a1a;color:#fff;padding:8px 20px;border-radius:20px;font-size:13px}
        .footer {background:#1a1a1a;color:#aaa;text-align:center;padding:25px 20px;font-size:12px}
        .footer h3 {color:#fff;margin:0 0 8px;font-size:16px;font-weight:600}
        .footer a {color:#63b3ed;text-decoration:none}
        .text-center {text-align:center}
        @media (max-width:600px){
            .container {margin:0;border-radius:0;box-shadow:none}
            .info-grid-table td {display:block;width:100%;padding-right:0}
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>HÓA ĐƠN ĐIỆN TỬ</h1>
        <p class="subtitle">TravelTime Premium Travel</p>
        <div class="order-code">#{{ str_pad($datCho->maDatCho, 6, '0', STR_PAD_LEFT) }}</div>
    </div>

    <div class="content">
        <div class="greeting">Kính gửi Quý khách {{ $datCho->hoTen ?? 'Quý khách' }},</div>
        <p class="intro">
            Cảm ơn Quý khách đã tin tưởng lựa chọn TravelTime. Dưới đây là thông tin chi tiết hóa đơn đặt tour của Quý khách.
        </p>

        <!-- Thông tin hành trình -->
        <h2 class="section-title">Thông Tin Hành Trình</h2>
        <table class="info-grid-table">
            <tr>
                <td>
                    <div class="info-item">
                        <div class="info-label">Tên Tour</div>
                        <div class="info-value">{{ $datCho->tour->tieuDe ?? 'N/A' }}</div>
                    </div>
                </td>
                <td>
                    <div class="info-item">
                        <div class="info-label">Thời Gian</div>
                        <div class="info-value">
                            {{ $datCho->chuyentour?->ngayBatDau ? \Carbon\Carbon::parse($datCho->chuyentour->ngayBatDau)->format('d/m/Y') : '—' }}
                            → 
                            {{ $datCho->chuyentour?->ngayKetThuc ? \Carbon\Carbon::parse($datCho->chuyentour->ngayKetThuc)->format('d/m/Y') : '—' }}
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="info-item">
                        <div class="info-label">Điểm Khởi Hành</div>
                        <div class="info-value">{{ $datCho->chuyentour?->diemKhoiHanh ?? 'TP. Hồ Chí Minh' }}</div>
                    </div>
                </td>
                <td>
                    <div class="info-item">
                        <div class="info-label">Ngày Đặt</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($datCho->ngayDat)->format('d/m/Y H:i') }}</div>
                    </div>
                </td>
            </tr>
            @if($datCho->chuyentour?->huongdanvien)
            <tr>
                <td colspan="2">
                    <div class="info-item">
                        <div class="info-label">Hướng Dẫn Viên</div>
                        <div class="info-value">
                            <strong>{{ $datCho->chuyentour->huongdanvien->hoTen }}</strong>
                            <small style="color:#666">({{ $datCho->chuyentour->huongdanvien->soDienThoai }})</small>
                        </div>
                    </div>
                </td>
            </tr>
            @endif
        </table>

        <!-- Bảng giá chi tiết -->
        <h2 class="section-title">Chi Tiết Thanh Toán</h2>
        <table class="price-table">
            <tbody>
                <tr>
                    <td class="price-label">Người lớn × {{ $datCho->soNguoiLon }}</td>
                    <td class="price-amount">{{ number_format($datCho->chuyentour?->giatour?->nguoiLon ?? 0) }}₫</td>
                </tr>
                <tr>
                    <td class="price-label">Trẻ em × {{ $datCho->soTreEm }}</td>
                    <td class="price-amount">{{ number_format($datCho->chuyentour?->giatour?->treEm ?? 0) }}₫</td>
                </tr>
                <tr>
                    <td class="price-label">Em bé × {{ $datCho->soEmBe }}</td>
                    <td class="price-amount">{{ number_format($datCho->chuyentour?->giatour?->emBe ?? 0) }}₫</td>
                </tr>

                <!-- Dùng trong bảng giá -->
                <tr class="total-original">
                    <td class="price-label">Tổng tiền gốc</td>
                    <td class="price-amount">{{ number_format($tongGiaGoc) }}₫</td>
                </tr>

                @foreach($datCho->khuyenMaiDaDung as $km)
                <tr class="discount-row">
                    <td class="price-label">
                        Ưu đãi • {{ $km->khuyenmai->code ?? 'KM#' . $km->maKM }}
                        @if($km->khuyenmai)<small style="color:#999"> ({{ $km->khuyenmai->tenKM }})</small>@endif
                    </td>
                    <td class="price-amount">-{{ number_format($km->giaGiam) }}₫</td>
                </tr>
                @endforeach

                <tr class="final-total">
                    <td class="price-label">THÀNH TIỀN CUỐI CÙNG</td>
                    <td class="price-amount">{{ number_format($thanhTien) }}₫</td>
                </tr>
            </tbody>
        </table>

        <h2 class="section-title">Danh Sách Khách Tham Gia</h2>
        
        @if($datCho->khachThamGia->count() > 0)
            <table style="width:100%; border-collapse:collapse; margin:20px 0; background:#f9f9f9; border:1px solid #eee; border-radius:6px; overflow:hidden;">
                <thead>
                    <tr style="background:#63b3ed; color:#fff;">
                        <th style="padding:12px 15px; text-align:left; font-size:14px;">STT</th>
                        <th style="padding:12px 15px; text-align:left; font-size:14px;">Họ và tên</th>
                        <th style="padding:12px 15px; text-align:center; font-size:14px;">Tuổi</th>
                        <th style="padding:12px 15px; text-align:center; font-size:14px;">Giới tính</th>
                        <th style="padding:12px 15px; text-align:center; font-size:14px;">Phòng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($datCho->khachThamGia->sortBy('maKhach') as $index => $khach)
                        <tr style="border-bottom:1px solid #e0e0e0;">
                            <td style="padding:12px 15px; font-size:14px; color:#555;">{{ $index + 1 }}</td>
                            <td style="padding:12px 15px; font-size:14px; font-weight:500; color:#333;">
                                {{ $khach->hoTenKhach }}
                            </td>
                            <td style="padding:12px 15px; text-align:center; font-size:14px; color:#555;">
                                {{ $khach->tuoi }}
                            </td>
                            <td style="padding:12px 15px; text-align:center; font-size:14px; color:#555;">
                                {{ $khach->gioiTinh == 'Nam' ? 'Nam' : 'Nữ' }}
                            </td>
                            <td style="padding:12px 15px; text-align:center; font-size:14px; color:#555;">
                                {{ $khach->luaChonPhong == 'PhongDon' ? 'Phòng đơn' : 'Ghép phòng' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="color:#888; font-style:italic; text-align:center; margin:20px 0;">
                Chưa có thông tin chi tiết khách tham gia.
            </p>
        @endif

        <!-- Trạng thái thanh toán -->
        <div class="payment-box">
            <div class="payment-status">
                {{ $datCho->thanhtoan?->tinhTrangThanhToan === 'Đã thanh toán' ? 'THANH TOÁN ĐÃ ĐƯỢC XÁC NHẬN' : 'ĐANG CHỜ THANH TOÁN' }}
            </div>
            <div class="payment-method">
                {{ $datCho->thanhtoan?->phuongThucThanhToan 
                    ? ucwords(str_replace('_', ' ', $datCho->thanhtoan->phuongThucThanhToan)) 
                    : 'Thanh toán tại văn phòng' }}
            </div>
        </div>

        <div class="text-center" style="color:#555; font-size:13px;">
            <p>Voucher chính thức sẽ được gửi trước ngày khởi hành 3-5 ngày.</p>
            <p style="margin-top:15px; font-size:15px; color:#333;">
                Hotline hỗ trợ 24/7: <strong style="color:#63b3ed; font-size:18px;">1900 1234</strong>
            </p>
        </div>
    </div>

    <div class="footer">
        <h3>TravelTime Premium</h3>
        <p>Tầng 15, Tòa nhà Bitexco Financial Tower, Quận 1, TP.HCM</p>
        <p>
            Website: <a href="https://traveltime.com">traveltime.com</a> • 
            Email: <a href="mailto:support@traveltime.com">support@traveltime.com</a>
        </p>
        <p style="margin-top:10px;color:#777;">
            © {{ date('Y') }} TravelTime – Khởi tạo hành trình.
        </p>
    </div>
</div>
</body>
</html>