{{-- resources/views/admin/datcho/show.blade.php --}}
@extends('admin.layouts.dashboard')

@section('content')
<div class="mono-container">
    <div class="main-content-wrapper">
        <div class="content-panel">
            <a href="{{ route('admin.datcho.index') }}" class="btn-return">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>

            @if (session('success'))
                <div class="alert-box success-style">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="close-alert" onclick="this.parentElement.style.display='none';">&times;</button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert-box error-style">
                    <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="close-alert" onclick="this.parentElement.style.display='none';">&times;</button>
                </div>
            @endif

            <div class="card-invoice">
                <div class="card-header-dark">
                    <h3 class="header-main-title">
                        <i class="fas fa-file-invoice-dollar icon-separator"></i>
                        HÓA ĐƠN ĐIỆN TỬ
                    </h3>
                    <p class="header-sub-text">Mã đặt chỗ: <strong>#{{ str_pad($datCho->maDatCho, 6, '0', STR_PAD_LEFT) }}</strong></p>
                </div>

                <div class="card-body-padded">
                    <div class="grid-2-columns">
                        <div class="info-section">
                            <h5 class="section-title text-company">THÔNG TIN CÔNG TY</h5>
                            <table class="data-detail-table">
                                <tr><td>Tên:</td><td>Công ty TNHH Du Lịch TravelTime</td></tr>
                                <tr><td>Địa chỉ:</td><td>123 Đường Cao Lỗ, Quận 8, TPHCM</td></tr>
                                <tr><td>Điện thoại:</td><td>1900 1234</td></tr>
                                <tr><td>Email:</td><td>info@dulichabc.com</td></tr>
                                <tr><td>Website:</td><td>www.dulichabc.com</td></tr>
                            </table>
                        </div>

                        <div class="info-section">
                            <h5 class="section-title text-customer">THÔNG TIN KHÁCH HÀNG</h5>
                            <table class="data-detail-table">
                                <tr><td>Họ tên:</td><td>{{ $datCho->hoTen ?? 'Khách vãng lai' }}</td></tr>
                                <tr><td>Số ĐT:</td><td>{{ $datCho->soDienThoai ?? 'N/A' }}</td></tr>
                                <tr><td>Email:</td><td>{{ $datCho->email ?? 'N/A' }}</td></tr>
                                <tr><td>Địa chỉ:</td><td>{{ $datCho->diaChi ?? 'N/A' }}</td></tr>
                                <tr><td>Ngày đặt:</td><td>{{ \Carbon\Carbon::parse($datCho->ngayDat)->format('d/m/Y H:i') }}</td></tr>
                            </table>
                        </div>
                    </div>

                    <div class="section-divider"></div>

                    {{-- THÔNG TIN TOUR & CHUYẾN ĐI --}}
                    <h5 class="section-title text-tour">THÔNG TIN TOUR & CHUYẾN ĐI</h5>
                    <div class="grid-2-columns details-spacing">
                        <div class="info-section">
                            <p class="info-line"><strong>Tên Tour:</strong> 
                                <span class="text-company bold">{{ $datCho->tour->tieuDe ?? 'N/A' }}</span>
                            </p>
                            <p class="info-line"><strong>Mã Tour:</strong> {{ $datCho->maTour }}</p>
                            <p class="info-line"><strong>Mã Chuyến:</strong> 
                                <span class="chip chip-highlight bold">
                                    #00{{ $datCho->maChuyen ?? '—' }}
                                </span>
                            </p>
                            <p class="info-line"><strong>Điểm Khởi Hành:</strong> 
                                <span class="text-customer">
                                    {{ $datCho->chuyentour?->diemKhoiHanh ?? 'N/A' }}
                                </span>
                            </p>
                            <p class="info-line"><strong>Hướng Dẫn Viên:</strong> 
                                {{ $datCho->chuyentour?->huongdanvien?->hoTen ?? 'Chưa phân công' }}
                            </p>
                            <p class="info-line"><strong>Phương Tiện:</strong> 
                                {{ $datCho->chuyentour?->phuongTien ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="info-section">
                            @if($datCho->chuyentour)
                                @php
                                    $start = \Carbon\Carbon::parse($datCho->chuyentour->ngayBatDau);
                                    $end = \Carbon\Carbon::parse($datCho->chuyentour->ngayKetThuc);
                                    $days = $start->diffInDays($end) + 1;
                                @endphp

                                <p class="info-line"><strong>Ngày Khởi Hành:</strong> 
                                    <span class="text-danger bold">{{ $start->format('d/m/Y') }}</span>
                                </p>
                                <p class="info-line"><strong>Ngày Kết Thúc:</strong> 
                                    {{ $end->format('d/m/Y') }}
                                </p>
                                <p class="info-line"><strong>Thời gian:</strong> 
                                    <span class="bold">
                                        {{ $datCho->tour->thoiGian ?? 'N/A' }}
                                    </span>
                                </p>
                                <p class="info-line"><strong>Số lượng:</strong> 
                                    {{ $datCho->chuyentour->soLuongDaDat ?? 0 }} / 
                                    {{ $datCho->chuyentour->soLuongToiDa ?? 0 }} khách
                                </p>
                                <p class="info-line"><strong>Tình trạng:</strong>
                                    @php
                                        $tinhTrang = $datCho->chuyentour->tinhTrangChuyen ?? 'N/A';
                                        $class = $tinhTrang === 'HoatDong' ? 'chip-success' : 'chip-danger';
                                        $text = $tinhTrang === 'HoatDong' ? 'Đang hoạt động' : 'Ngừng chạy';
                                    @endphp
                                    <span class="chip {{ $class }}">{{ $text }}</span>
                                </p>
                            @else
                                <p class="info-line text-danger">
                                    Không tìm thấy chuyến tour (maChuyen: {{ $datCho->maChuyen }})
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="table-area">
                        <h5 class="section-title text-danger">CHI TIẾT NGƯỜI THAM GIA</h5>
                        <table class="invoice-table">
                            <thead>
                                <tr class="table-header-row text-center-cell">
                                    <th>Loại khách</th>
                                    <th>Số lượng</th>
                                    <th>Đơn giá</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Người lớn</td>
                                    <td class="text-center-cell bold">{{ $datCho->soNguoiLon }}</td>
                                    <td class="text-right-cell text-company">{{ number_format($giaNguoiLon) }}₫</td>
                                    <td class="text-right-cell">{{ number_format($datCho->soNguoiLon * $giaNguoiLon) }}₫</td>
                                </tr>
                                <tr>
                                    <td>Trẻ em</td>
                                    <td class="text-center-cell">{{ $datCho->soTreEm }}</td>
                                    <td class="text-right-cell text-customer">{{ number_format($giaTreEm) }}₫</td>
                                    <td class="text-right-cell">{{ number_format($datCho->soTreEm * $giaTreEm) }}₫</td>
                                </tr>
                                <tr>
                                    <td>Em bé</td>
                                    <td class="text-center-cell">{{ $datCho->soEmBe }}</td>
                                    <td class="text-right-cell text-tour">{{ number_format($giaEmBe) }}₫</td>
                                    <td class="text-right-cell">{{ number_format($datCho->soEmBe * $giaEmBe) }}₫</td>
                                </tr>
                                <tr class="total-row bold">
                                    <td colspan="3" class="text-right-cell">TỔNG CỘNG:</td>
                                    <td class="text-right-cell total-amount">
                                        {{ number_format($tongTienTinhToan) }}₫
                                        @if($tongTienTinhToan != $datCho->tongGia)
                                            <small class="muted-text block-display">(DB: {{ number_format($datCho->tongGia) }}₫)</small>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="section-divider"></div>

                    <div class="grid-2-columns">
                        <div class="info-section">
                            <h5 class="section-title text-customer">THANH TOÁN</h5>
                            <p><strong>Phương thức:</strong> 
                                <span class="chip chip-info">
                                    {{ ucfirst(str_replace('_', ' ', $datCho->phuongThucThanhToan ?? 'N/A')) }}
                                </span>
                            </p>
                            <p><strong>Trạng thái:</strong>
                                @php
                                    $tt = $datCho->thanhtoan->tinhTrangThanhToan ?? 'Chưa thanh toán';
                                    $class = $tt === 'Đã thanh toán' ? 'chip-success' : 'chip-highlight';
                                @endphp
                                <span class="chip {{ $class }}">{{ $tt }}</span>
                            </p>
                            @if($datCho->thanhtoan && $datCho->thanhtoan->tinhTrangThanhToan === 'Đã thanh toán')
                                <p><strong>Mã GD:</strong> {{ $datCho->thanhtoan->maGiaoDich }}</p>
                                <p><strong>Ngày TT:</strong> 
                                    {{ \Carbon\Carbon::parse($datCho->thanhtoan->ngayThanhToan)->format('d/m/Y H:i') }}
                                </p>
                            @endif
                        </div>

                        <div class="info-section text-right-align">
                            <h5 class="section-title text-company">HÓA ĐƠN</h5>
                            @php
                                $hd = $datCho->hoadon?->trangThai ?? 'Chưa tạo';
                            @endphp
                            <p><strong>Trạng thái:</strong>
                                <span class="chip {{ $hd === 'Đã gửi' ? 'chip-success' : 'chip-secondary' }}">
                                    {{ $hd }}
                                </span>
                            </p>

                            @if($datCho->thanhtoan && $datCho->thanhtoan->tinhTrangThanhToan === 'Đã thanh toán')
                                <form action="{{ route('admin.datcho.sendInvoice', $datCho->maDatCho) }}" method="POST" class="form-action-group">
                                    @csrf
                                    <button type="submit" class="btn-action btn-send"
                                            onclick="return confirm('Gửi hóa đơn điện tử đến {{ $datCho->email }}?')">
                                        <i class="fas fa-paper-plane"></i>
                                        {{ $hd === 'Đã gửi' ? 'Gửi lại Hóa Đơn' : 'Gửi Hóa Đơn' }}
                                    </button>
                                </form>
                            @else
                                <button class="btn-action btn-disabled" disabled>
                                    <i class="fas fa-lock"></i> Chưa thanh toán
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-footer-light">
                    <p class="footer-note">Cảm ơn quý khách đã tin tưởng sử dụng dịch vụ!</p>
                    <p class="footer-timestamp">
                        Hóa đơn được tạo tự động bởi hệ thống - Ngày in: {{ now()->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* 1. CSS VARIABLES (MONOCHROME PALETTE) */
    :root {
        --color-main-bg: #f5f5f5;      /* Xám siêu nhẹ cho nền trang */
        --color-card-bg: #ffffff;      /* Trắng tinh cho thẻ hóa đơn */
        --color-header-bg: #333333;    /* Xám đậm cho tiêu đề chính */
        --color-text-dark: #333333;    /* Màu chữ chính */
        --color-text-medium: #666666;  /* Màu chữ phụ, chú thích */
        --color-border-light: #e0e0e0; /* Đường viền nhẹ */
        --color-border-medium: #cccccc;/* Đường phân cách */
        --color-table-header: #eeeeee; /* Nền header bảng */
        --color-total-row: #e8e8e8;    /* Nền dòng tổng cộng */
        
        /* ACCENT COLORS (Chỉ dùng cho các chi tiết nổi bật cần phân biệt) */
        --color-accent-company: #545454; /* Xám đậm (Công ty) */
        --color-accent-customer: #777777; /* Xám vừa (Khách hàng) */
        --color-accent-tour: #9a9a9a;     /* Xám nhẹ (Tour/Info) */
        --color-accent-danger: #c70039; /* Đỏ (Tổng tiền/Nguy hiểm) */
        --color-accent-success: #3cb462;/* Xanh lá (Hoạt động/Gửi HD) */
        --color-accent-highlight: #f8c000;/* Vàng (Chưa TT/Mã chuyến) */

        --spacing-base: 8px;
        --border-radius: 4px;
    }
    
    /* 2. BASE STYLES AND LAYOUT */
    .mono-container {
        padding: calc(var(--spacing-base) * 4);
        background-color: var(--color-main-bg) !important;
        min-height: 100vh;
        font-family: 'Arial', sans-serif;
        color: var(--color-text-dark);
        font-size: 14px;
    }
    .main-content-wrapper {
        display: flex;
        justify-content: center;
    }
    .content-panel {
        width: 100%;
        max-width: 950px;
    }

    /* 3. CARD CONTAINER (HÓA ĐƠN) */
    .card-invoice {
        background-color: var(--color-card-bg);
        border: 1px solid var(--color-border-light);
        border-radius: var(--border-radius);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    /* 4. CARD HEADER */
    .card-header-dark {
        background-color: var(--color-header-bg) !important;
        color: var(--color-card-bg);
        text-align: center;
        padding: calc(var(--spacing-base) * 4);
    }
    .header-main-title {
        font-size: 24px;
        margin: 0 0 var(--spacing-base);
        font-weight: 700;
        color: var(--color-card-bg);
        letter-spacing: 1px;
    }
    .header-sub-text {
        margin: 0;
        font-size: 15px;
    }
    .icon-separator {
        margin-right: var(--spacing-base);
    }

    /* 5. CARD BODY & CONTENT */
    .card-body-padded {
        padding: calc(var(--spacing-base) * 5);
    }
    .grid-2-columns {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: calc(var(--spacing-base) * 5);
    }
    .details-spacing {
        margin-top: calc(var(--spacing-base) * 3);
    }
    .section-title {
        font-size: 16px;
        font-weight: 700;
        padding-bottom: var(--spacing-base);
        margin-bottom: calc(var(--spacing-base) * 2);
        border-bottom: 1px solid var(--color-border-light);
    }

    /* 6. DATA TABLES (CHI TIẾT & INFO) */
    .data-detail-table {
        border-collapse: collapse;
        width: 100%;
    }
    .data-detail-table td {
        padding: calc(var(--spacing-base) / 2) 0;
    }
    .data-detail-table tr td:first-child {
        font-weight: 600;
        width: 120px;
        color: var(--color-text-medium);
    }
    .info-section p {
        margin: calc(var(--spacing-base) / 2) 0;
    }
    .info-section strong {
        display: inline-block;
        width: 120px;
        font-weight: 600;
        color: var(--color-text-medium);
    }

    /* 7. TRANSACTION TABLE */
    .table-area {
        margin-top: calc(var(--spacing-base) * 5);
    }
    .invoice-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: calc(var(--spacing-base) * 2);
        border: 1px solid var(--color-border-medium);
    }
    .invoice-table th, .invoice-table td {
        padding: calc(var(--spacing-base) * 1.5);
        border: 1px solid var(--color-border-medium);
    }
    .table-header-row {
        background-color: var(--color-table-header) !important;
        font-weight: 700;
        color: var(--color-text-dark);
    }
    .invoice-table tbody tr:nth-child(even) {
        background-color: #fcfcfc;
    }
    .total-row {
        background-color: var(--color-total-row) !important;
        font-size: 16px;
        color: var(--color-text-dark);
    }
    .total-amount {
        font-size: 20px;
        color: var(--color-accent-danger) !important;
    }

    /* 8. CHIPS / BADGES */
    .chip {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        color: var(--color-card-bg);
        margin-left: 5px;
    }
    .chip-success { background-color: var(--color-accent-success); }
    .chip-danger { background-color: var(--color-accent-danger); }
    .chip-highlight { background-color: var(--color-accent-highlight); color: var(--color-text-dark); }
    .chip-info { background-color: var(--color-accent-tour); }
    .chip-secondary { background-color: var(--color-accent-customer); }

    /* 9. BUTTONS AND ALERTS */
    .btn-return {
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        color: var(--color-text-medium);
        padding: var(--spacing-base);
        margin-bottom: calc(var(--spacing-base) * 3);
        border: 1px solid var(--color-border-light);
        border-radius: var(--border-radius);
        background-color: var(--color-card-bg);
        transition: background-color 0.2s;
    }
    .btn-return:hover {
        background-color: var(--color-table-header);
        color: var(--color-text-dark);
    }

    .btn-action {
        display: inline-block;
        padding: calc(var(--spacing-base) * 1.5) calc(var(--spacing-base) * 3);
        border: none;
        border-radius: var(--border-radius);
        cursor: pointer;
        font-weight: 600;
        color: var(--color-card-bg);
        text-decoration: none;
        transition: opacity 0.2s;
        font-size: 16px;
    }
    .btn-send {
        background-color: var(--color-accent-success);
    }
    .btn-disabled {
        background-color: var(--color-accent-customer);
        opacity: 0.7;
        cursor: not-allowed;
    }
    .form-action-group {
        margin-top: calc(var(--spacing-base) * 2);
        display: block;
    }

    .alert-box {
        padding: var(--spacing-base) calc(var(--spacing-base) * 2);
        margin-bottom: calc(var(--spacing-base) * 3);
        border-radius: var(--border-radius);
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-weight: 600;
        font-size: 15px;
    }
    .success-style {
        background-color: #e6ffe6;
        border: 1px solid var(--color-accent-success);
        color: #1a7b45;
    }
    .error-style {
        background-color: #fff0f0;
        border: 1px solid var(--color-accent-danger);
        color: #8c0021;
    }
    .close-alert {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: inherit;
        line-height: 1;
        margin-left: var(--spacing-base);
    }

    /* 10. FOOTER */
    .card-footer-light {
        background-color: var(--color-table-header);
        text-align: center;
        padding: calc(var(--spacing-base) * 3);
        border-top: 1px solid var(--color-border-light);
    }
    .footer-note {
        margin-bottom: var(--spacing-base);
        color: var(--color-text-medium);
        font-size: 14px;
    }
    .footer-timestamp {
        margin-bottom: 0;
        color: #999999;
        font-size: 11px;
    }

    /* 11. UTILITY CLASSES (NẾU CẦN THIẾT) */
    .bold { font-weight: 700; }
    .text-center-cell { text-align: center; }
    .text-right-cell { text-align: right; }
    .text-right-align { text-align: right; }
    .section-divider { 
        height: 1px;
        background-color: var(--color-border-medium);
        margin: calc(var(--spacing-base) * 5) 0;
    }
    .block-display { display: block; }
    .muted-text { color: #a3a3a3; font-size: 12px; font-weight: normal; }

    /* ACCENT COLORS FOR TEXT */
    .text-company { color: var(--color-accent-company) !important; }
    .text-customer { color: var(--color-accent-customer) !important; }
    .text-tour { color: var(--color-accent-tour) !important; }
    .text-danger { color: var(--color-accent-danger) !important; }

    /* 12. RESPONSIVE DESIGN */
    @media (max-width: 768px) {
        .grid-2-columns {
            grid-template-columns: 1fr;
        }
        .info-section:nth-child(2) {
            margin-top: calc(var(--spacing-base) * 2);
        }
        .text-right-align {
            text-align: left;
        }
        .btn-action {
            width: 100%;
        }
    }

    .info-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px dashed var(--color-border-light);
        font-size: 15px;
        line-height: 1.5;
    }

    .info-line:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .info-line strong {
        min-width: 140px;
        color: var(--color-text-medium);
        font-weight: 600;
        flex-shrink: 0;
    }

    .info-line .chip {
        margin-left: 8px;
    }

    /* Đảm bảo 2 cột bằng nhau */
    .grid-2-columns > .info-section {
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }

    .grid-2-columns > .info-section > .info-line {
        flex: 1;
    }

    /* Responsive: mobile thì xếp dọc */
    @media (max-width: 768px) {
        .info-line {
            flex-direction: column;
            align-items: flex-start;
            text-align: left;
            padding: 8px 0;
        }
        .info-line strong {
            min-width: auto;
            margin-bottom: 4px;
        }
        .info-line .chip {
            margin-left: 0;
            margin-top: 4px;
        }
    }
    .grid-2-columns {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        align-items: stretch;
    }
</style>
@endsection