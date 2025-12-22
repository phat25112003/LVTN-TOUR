@extends('admin.layouts.dashboard')

@section('content')
<div class="mono-container">
    <div class="main-content-wrapper">
        <div class="content-panel">

            <a href="{{ route('admin.datcho.index') }}" class="btn-return">
                ← Quay lại danh sách đặt chỗ
            </a>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert-box success-style">
                    {{ session('success') }}
                    <button type="button" class="close-alert">×</button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert-box error-style">
                    {{ session('error') }}
                    <button type="button" class="close-alert">×</button>
                </div>
            @endif

            <div class="card-invoice">
                <!-- Header -->
                <div class="card-header-dark">
                    <h1 class="header-main-title">HÓA ĐƠN ĐIỆN TỬ</h1>
                    <p class="header-sub-text">Mã đặt chỗ: <strong>#{{ str($datCho->maDatCho)->padLeft(6, '0') }}</strong></p>
                </div>

                <div class="card-body-padded">

                    <!-- Thông tin công ty & khách hàng -->
                    <div class="grid-2-columns">
                        <div class="info-section">
                            <h3 class="section-title text-company">THÔNG TIN CÔNG TY</h3>
                            <table class="data-detail-table">
                                <tr><td>Tên:</td><td>Công ty TNHH Du Lịch TravelTime</td></tr>
                                <tr><td>Địa chỉ:</td><td>123 Đường Cao Lỗ, Quận 8, TPHCM</td></tr>
                                <tr><td>Điện thoại:</td><td>1900 1234</td></tr>
                                <tr><td>Email:</td><td>info@dulichabc.com</td></tr>
                                <tr><td>Website:</td><td>www.dulichabc.com</td></tr>
                            </table>
                        </div>

                        <div class="info-section">
                            <h3 class="section-title text-customer">THÔNG TIN KHÁCH HÀNG</h3>
                            <table class="data-detail-table">
                                <tr><td>Họ tên:</td><td>{{ $datCho->hoTen ?? 'Khách vãng lai' }}</td></tr>
                                <tr><td>Số ĐT:</td><td>{{ $datCho->soDienThoai ?? 'N/A' }}</td></tr>
                                <tr><td>Email:</td><td>{{ $datCho->email ?? 'N/A' }}</td></tr>
                                <tr><td>Địa chỉ:</td><td>{{ $datCho->diaChi ?? 'N/A' }}</td></tr>
                                <tr>
                                    <td>Ngày đặt:</td>
                                    <td>{{ $datCho->ngayDat ? \Carbon\Carbon::parse($datCho->ngayDat)->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="section-divider"></div>

                    <!-- Thông tin tour & chuyến đi -->
                    <h3 class="section-title text-tour">THÔNG TIN TOUR & CHUYẾN ĐI</h3>
                    <div class="grid-2-columns">
                        <div class="info-section">
                            <div class="info-line"><strong>Tên Tour:</strong> <span class="bold">{{ $datCho->tour->tieuDe ?? 'N/A' }}</span></div>
                            <div class="info-line"><strong>Mã Tour:</strong> #00TVT{{ $datCho->maTour }}</div>
                            <div class="info-line"><strong>Mã Chuyến:</strong> <span class="chip chip-highlight">#00{{ $datCho->maChuyen ?? '—' }}</span></div>
                            <div class="info-line"><strong>Điểm khởi hành:</strong> {{ $datCho->chuyentour?->diemKhoiHanh ?? 'N/A' }}</div>
<div class="info-line">
    <strong>Hướng dẫn viên:</strong>
    @if($datCho->chuyentour?->huongdanvien)
        <span class="bold">{{ $datCho->chuyentour->huongdanvien->hoTen }}</span>
        <small class="muted-text">({{ $datCho->chuyentour->huongdanvien->soDienThoai }})</small>
    @else
        Chưa phân công
    @endif
</div>
                            <div class="info-line"><strong>Phương tiện:</strong> {{ $datCho->chuyentour?->phuongTien ?? 'N/A' }}</div>
                        </div>

                        <div class="info-section">
                            @if($datCho->chuyentour)
                                <div class="info-line"><strong>Khởi hành:</strong> 
                                    <span class="text-danger bold">
                                        {{ $datCho->chuyentour->ngayBatDau ? \Carbon\Carbon::parse($datCho->chuyentour->ngayBatDau)->format('d/m/Y') : 'Chưa xác định' }}
                                    </span>
                                </div>
                                <div class="info-line"><strong>Kết thúc:</strong> 
                                    {{ $datCho->chuyentour->ngayKetThuc ? \Carbon\Carbon::parse($datCho->chuyentour->ngayKetThuc)->format('d/m/Y') : 'Chưa xác định' }}
                                </div>
                                <div class="info-line"><strong>Thời gian:</strong> {{ $datCho->tour->thoiGian ?? 'N/A' }}</div>
                                <div class="info-line"><strong>Số lượng:</strong> {{ $datCho->chuyentour->soLuongDaDat ?? 0 }} / {{ $datCho->chuyentour->soLuongToiDa ?? 0 }} khách</div>
                            @else
                                <p class="text-danger">Không tìm thấy thông tin chuyến đi</p>
                            @endif
                        </div>
                    </div>

                    <div class="section-divider"></div>

                    <!-- Bảng giá chi tiết -->
                    <h3 class="section-title text-danger">CHI TIẾT GIÁ VÉ</h3>
                    <div class="table-area">
                        <table class="invoice-table">
                            <thead>
                                <tr class="table-header-row">
                                    <th>Loại khách</th>
                                    <th class="text-center-cell">Số lượng</th>
                                    <th class="text-right-cell">Đơn giá</th>
                                    <th class="text-right-cell">Thành tiền</th>
                                </tr>
                            </thead>
<tbody>
    <tr>
        <td>Người lớn</td>
        <td class="text-center-cell">{{ $slNL }}</td>
        <td class="text-right-cell">{{ number_format($giaNguoiLon) }}₫</td>
        <td class="text-right-cell">{{ number_format($slNL * $giaNguoiLon) }}₫</td>
    </tr>
    <tr>
        <td>Trẻ em</td>
        <td class="text-center-cell">{{ $slTE }}</td>
        <td class="text-right-cell">{{ number_format($giaTreEm) }}₫</td>
        <td class="text-right-cell">{{ number_format($slTE * $giaTreEm) }}₫</td>
    </tr>
    <tr>
        <td>Em bé</td>
        <td class="text-center-cell">{{ $slEB }}</td>
        <td class="text-right-cell">{{ number_format($giaEmBe) }}₫</td>
        <td class="text-right-cell">{{ number_format($slEB * $giaEmBe) }}₫</td>
    </tr>

    <tr class="total-row">
        <td colspan="3" class="text-right-cell bold">TỔNG TIỀN GỐC:</td>
        <td class="text-right-cell bold">{{ number_format($tongGiaGoc) }}₫</td>
    </tr>

    <!-- Trong bảng giá -->
    @if($datCho->khuyenMaiDaDung->count() > 0)
        @foreach($datCho->khuyenMaiDaDung as $item)
            <tr style="background:#fff5f5;">
                <td colspan="3" class="text-right-cell text-danger">
                    <strong>GIẢM GIÁ - {{ $item->khuyenmai->code ?? 'KM#'.$item->maKM }}</strong>
                    <small class="muted-text">({{ $item->khuyenmai->tenKM ?? '' }})</small>
                </td>
                <td class="text-right-cell text-danger bold">
                    -{{ number_format($item->giaGiam) }}₫
                </td>
            </tr>
        @endforeach
    @endif

    <tr class="total-row" style="background:#e8f5e9; font-size:19px;">
        <td colspan="3" class="text-right-cell bold">THÀNH TIỀN:</td>
        <td class="text-right-cell bold text-success total-amount">
            {{ number_format($tongGiaThucThu) }}₫
        </td>
    </tr>
</tbody>
                        </table>
                    </div>

                    <div class="section-divider"></div>

                    <!-- Thanh toán & Gửi hóa đơn -->
                    <div class="grid-2-columns actions-grid">
                        <div>
                            <h3 class="section-title">THANH TOÁN</h3>
                            <p><strong>Phương thức:</strong> 
                                <span class="chip chip-info">
                                    {{ $datCho->thanhtoan?->phuongThucThanhToan 
                                        ? ucwords(str_replace('_', ' ', $datCho->thanhtoan->phuongThucThanhToan)) 
                                        : 'Chưa chọn' }}
                                </span>
                            </p>
                            <p><strong>Trạng thái:</strong>
                                <span class="chip {{ $datCho->thanhtoan?->tinhTrangThanhToan === 'Đã thanh toán' ? 'chip-success' : 'chip-highlight' }}">
                                    {{ $datCho->thanhtoan?->tinhTrangThanhToan ?? 'Chưa thanh toán' }}
                                </span>
                            </p>
                            @if($datCho->thanhtoan)
                                <p><strong>Mã GD:</strong> {{ $datCho->thanhtoan->maGiaoDich ?? '—' }}</p>
                                <p><strong>Ngày TT:</strong> 
                                    {{ $datCho->thanhtoan->ngayThanhToan ? \Carbon\Carbon::parse($datCho->thanhtoan->ngayThanhToan)->format('d/m/Y H:i') : '—' }}
                                </p>
                            @endif
                        </div>

                        <div class="text-right-align">
                            <h3 class="section-title" style="color:var(--color-accent-success)">GỬI HÓA ĐƠN</h3>
                            @if($datCho->thanhtoan?->tinhTrangThanhToan === 'Đã thanh toán')
                                <form id="sendInvoiceForm" action="{{ route('admin.datcho.sendInvoice', $datCho->maDatCho) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="button" id="openSendModal" class="btn-action btn-send">Gửi Hóa Đơn Qua Email</button>
                                </form>
                                @if($datCho->hoadon?->trangThai === 'Đã gửi')
                                    <small class="block-display muted-text">
                                        Đã gửi lúc {{ \Carbon\Carbon::parse($datCho->hoadon->updated_at)->format('d/m/Y H:i') }}
                                    </small>
                                @endif
                            @else
                                <button class="btn-action btn-disabled" disabled>Chưa thanh toán</button>
                            @endif

                            <div class="form-action-group">
                                <button onclick="window.print()" class="btn-action" style="background:#666;">In Hóa Đơn</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer-light">
                    <p class="footer-note">Cảm ơn Quý khách đã tin tưởng sử dụng dịch vụ của TravelTime!</p>
                    <p class="footer-timestamp">Hóa đơn được tạo tự động lúc {{ now()->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận gửi email -->
@if($datCho->thanhtoan?->tinhTrangThanhToan === 'Đã thanh toán')
<div id="sendInvoiceModal" class="modal" style="display:none;">
    <div class="modal-content">
        <h3>Xác nhận gửi hóa đơn</h3>
        <p>Bạn có chắc muốn gửi hóa đơn đến email:</p>
        <strong class="text-danger">{{ $datCho->email }}</strong>
        <div class="modal-buttons">
            <button class="btn secondary" id="cancelSend">Hủy</button>
            <button class="btn primary" id="confirmSend">
                <span class="text">Gửi Email</span>
                <span class="loading" style="display:none;">Đang gửi...</span>
            </button>
        </div>
    </div>
</div>
@endif

<style>
    :root {
        --color-main-bg: #f5f5f5;
        --color-card-bg: #ffffff;
        --color-header-bg: #333333;
        --color-text-dark: #333333;
        --color-text-medium: #666666;
        --color-border-light: #e0e0e0;
        --color-border-medium: #cccccc;
        --color-table-header: #eeeeee;
        --color-total-row: #e8e8e8;
        --color-accent-company: #545454;
        --color-accent-customer: #777777;
        --color-accent-tour: #9a9a9a;
        --color-accent-danger: #c70039;
        --color-accent-success: #3cb462;
        --color-accent-highlight: #f8c000;
        --spacing-base: 8px;
        --border-radius: 4px;
    }

    /* Toàn bộ CSS bạn cung cấp trước đó – giữ nguyên 100% */
    /* (Đã tối ưu và gom lại, không thay đổi giao diện) */
    .mono-container{padding:calc(var(--spacing-base)*4);background:var(--color-main-bg)!important;min-height:100vh;font-family:Arial,sans-serif;color:var(--color-text-dark);font-size:14px}
    .main-content-wrapper{display:flex;justify-content:center}
    .content-panel{width:100%;max-width:950px}
    .card-invoice{background:var(--color-card-bg);border:1px solid var(--color-border-light);border-radius:var(--border-radius);box-shadow:0 4px 12px rgba(0,0,0,.04);overflow:hidden}
    .card-header-dark{background:var(--color-header-bg)!important;color:#fff;text-align:center;padding:calc(var(--spacing-base)*4)}
    .header-main-title{font-size:24px;margin:0 0 var(--spacing-base);font-weight:700;letter-spacing:1px}
    .header-sub-text{margin:0;font-size:15px}
    .card-body-padded{padding:calc(var(--spacing-base)*5)}
    .grid-2-columns{display:grid;grid-template-columns:1fr 1fr;gap:calc(var(--spacing-base)*5)}
    .section-title{font-size:16px;font-weight:700;padding-bottom:var(--spacing-base);margin-bottom:calc(var(--spacing-base)*2);border-bottom:1px solid var(--color-border-light)}
    .data-detail-table td{padding:calc(var(--spacing-base)/2) 0}
    .data-detail-table tr td:first-child{font-weight:600;width:120px;color:var(--color-text-medium)}
    .info-line{display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px dashed var(--color-border-light);font-size:15px}
    .info-line:last-child{border-bottom:none;padding-bottom:0}
    .info-line strong{min-width:140px;color:var(--color-text-medium);font-weight:600;flex-shrink:0}
    .invoice-table{width:100%;border-collapse:collapse;margin-top:calc(var(--spacing-base)*2);border:1px solid var(--color-border-medium)}
    .invoice-table th,.invoice-table td{padding:calc(var(--spacing-base)*1.5);border:1px solid var(--color-border-medium)}
    .table-header-row{background:var(--color-table-header)!important;font-weight:700}
    .total-row{background:var(--color-total-row)!important;font-size:16px}
    .total-amount{font-size:20px;color:var(--color-accent-danger)!important}
    .chip{display:inline-block;padding:2px 10px;border-radius:12px;font-size:12px;font-weight:600;color:#fff;margin-left:5px}
    .chip-success{background:var(--color-accent-success)}
    .chip-highlight{background:var(--color-accent-highlight);color:#000}
    .chip-info{background:var(--color-accent-tour)}
    .btn-return{display:inline-flex;align-items:center;text-decoration:none;color:var(--color-text-medium);padding:var(--spacing-base);margin-bottom:calc(var(--spacing-base)*3);border:1px solid var(--color-border-light);border-radius:var(--border-radius);background:var(--color-card-bg)}
    .btn-return:hover{background:var(--color-table-header);color:var(--color-text-dark)}
    .btn-action{padding:calc(var(--spacing-base)*1.5) calc(var(--spacing-base)*3);border:none;border-radius:var(--border-radius);cursor:pointer;font-weight:600;color:#fff;font-size:16px;margin:5px 0;display:inline-block}
    .btn-send{background:var(--color-accent-success)}
    .btn-disabled{background:#aaa;cursor:not-allowed;opacity:0.7}
    .alert-box{padding:var(--spacing-base) calc(var(--spacing-base)*2);margin-bottom:calc(var(--spacing-base)*3);border-radius:var(--border-radius);display:flex;align-items:center;justify-content:space-between;font-weight:600;font-size:15px}
    .success-style{background:#e6ffe6;border:1px solid var(--color-accent-success);color:#1a7b45}
    .error-style{background:#fff0f0;border:1px solid var(--color-accent-danger);color:#8c0021}
    .close-alert{background:none;border:none;font-size:20px;cursor:pointer;margin-left:var(--spacing-base)}
    .card-footer-light{background:var(--color-table-header);text-align:center;padding:calc(var(--spacing-base)*3);border-top:1px solid var(--color-border-light)}
    .footer-note{margin-bottom:var(--spacing-base);color:var(--color-text-medium);font-size:14px}
    .footer-timestamp{margin:0;color:#999;font-size:11px}
    .section-divider{height:1px;background:var(--color-border-medium);margin:calc(var(--spacing-base)*5) 0}
    .text-right-align{text-align:right}
    .block-display{display:block}
    .muted-text{color:#a3a3a3;font-size:12px}
    .text-danger{color:var(--color-accent-danger)!important}
    .bold{font-weight:700}
    .text-center-cell{text-align:center}
    .text-right-cell{text-align:right}

    @media (max-width:768px){
        .grid-2-columns{grid-template-columns:1fr}
        .text-right-align{text-align:left}
        .btn-action{width:100%}
    }

    .modal{position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.6);display:flex;justify-content:center;align-items:center;z-index:9999}
    .modal-content{background:#fff;padding:30px;border-radius:10px;width:90%;max-width:480px;text-align:center;box-shadow:0 10px 30px rgba(0,0,0,.3)}
    .modal-buttons{display:flex;gap:15px;margin-top:25px}
    .modal-buttons .btn{flex:1;padding:12px;font-size:16px}
</style>

@if($datCho->thanhtoan?->tinhTrangThanhToan === 'Đã thanh toán')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('sendInvoiceModal');
        const openBtn = document.getElementById('openSendModal');
        const cancelBtn = document.getElementById('cancelSend');
        const confirmBtn = document.getElementById('confirmSend');
        const form = document.getElementById('sendInvoiceForm');
        const confirmText = confirmBtn.querySelector('.text');
        const loadingText = confirmBtn.querySelector('.loading');

        if (!openBtn || !modal) return;

        openBtn.onclick = () => modal.style.display = 'flex';
        cancelBtn.onclick = () => modal.style.display = 'none';
        modal.onclick = (e) => { if (e.target === modal) modal.style.display = 'none'; };

        confirmBtn.onclick = () => {
            confirmText.style.display = 'none';
            loadingText.style.display = 'inline';
            confirmBtn.disabled = true;
            form.submit();
        };
    });
</script>
@endif
@endsection