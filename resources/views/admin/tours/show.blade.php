{{-- resources/views/admin/tours/show.blade.php --}}
@extends('admin.layouts.dashboard')

@section('content')
<style>
    body {
        background-color: #f4f6f8;
        font-family: "Segoe UI", sans-serif;
    }

    .tour-detail {
        max-width: 1150px;
        margin: 30px auto;
        background: #fff;
        padding: 30px 40px;
        border-radius: 14px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    .carousel-item img {
        width: 100%;
        height: 480px;
        object-fit: cover;
        border-radius: 12px;
    }

    .tour-title {
        font-size: 28px;
        font-weight: 700;
        color: #2b9084;
        margin: 20px 0 12px;
        text-align: center;
    }

    .tour-desc {
        color: #444;
        font-size: 15.5px;
        line-height: 1.7;
        text-align: justify;
        margin-bottom: 25px;
        padding: 0 10px;
    }

    .tour-info {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        padding: 22px;
        border-radius: 12px;
        margin-bottom: 30px;
        border: 1px solid #dee2e6;
    }

    .tour-info strong {
        color: #2b9084;
        font-weight: 600;
    }

    .section-title {
        font-size: 21px;
        font-weight: 600;
        color: #2b9084;
        margin: 35px 0 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #2b9084;
        display: inline-block;
    }

    /* Timeline lịch trình */
    .timeline {
        position: relative;
        padding-left: 35px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        top: 0;
        left: 16px;
        height: 100%;
        width: 3px;
        background: linear-gradient(to bottom, #2b9084, #1a5f57);
    }

    .timeline-item {
        margin-bottom: 28px;
        position: relative;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -35px;
        top: 12px;
        width: 14px;
        height: 14px;
        background: #2b9084;
        border: 3px solid #fff;
        border-radius: 50%;
        box-shadow: 0 0 0 3px #2b9084;
    }

    .timeline-card {
        background: #fff;
        border: 1px solid #e1e1e1;
        border-radius: 10px;
        padding: 18px;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
    }

    .timeline-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .timeline-card h5 {
        color: #2b9084;
        margin-bottom: 12px;
        font-size: 18px;
        font-weight: 600;
    }

    .meal-label {
        font-weight: 600;
        color: #2b9084;
    }

    /* Chuyến tour */
    .trip-card {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .trip-card:hover {
        background: #fff;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transform: translateY(-3px);
    }

    .trip-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        font-weight: 600;
        color: #2b9084;
        font-size: 18px;
    }

    .trip-status {
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-chuadukhach { background: #fff3cd; color: #856404; }
    .status-dukhach     { background: #d1edff; color: #0c5460; }
    .status-dakhoihanh  { background: #d4edda; color: #155724; }
    .status-huy         { background: #f8d7da; color: #721c24; }

    .min-guest-info {
        background: #fff8e1;
        padding: 10px;
        border-radius: 8px;
        margin-top: 10px;
        font-size: 14px;
        border-left: 4px solid #ffc107;
    }

    .price-info {
        background: #e9f7ef;
        padding: 12px;
        border-radius: 8px;
        margin-top: 12px;
        font-size: 14px;
    }

    .price-info strong {
        color: #155724;
    }

    .btn-back {
        background: linear-gradient(135deg, #6c757d, #495057);
        color: white;
        font-weight: 600;
        padding: 12px 32px;
        border-radius: 50px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        margin-top: 40px;
    }

    .btn-back:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        background: linear-gradient(135deg, #5a6268, #343a40);
    }

    @media (max-width: 768px) {
        .tour-detail { margin: 15px; padding: 20px; }
        .tour-title { font-size: 24px; }
        .carousel-item img { height: 300px; }
        .trip-header { flex-direction: column; align-items: flex-start; gap: 10px; }
    }
</style>

<div class="tour-detail">
    <!-- Ảnh tour -->
    @if ($hinhAnh->count() > 0)
        <div id="tourCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach ($hinhAnh as $index => $hinh)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . $hinh->duongDanHinh) }}" 
                             class="d-block w-100" 
                             alt="Hình tour {{ $tour->tieuDe }}"
                             onerror="this.src='{{ asset('images/no-image.png') }}'">
                    </div>
                @endforeach
            </div>
            @if($hinhAnh->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#tourCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#tourCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            @endif
        </div>
    @else
        <div class="text-center mb-4">
            <img src="{{ asset('images/no-image.png') }}" width="400" class="rounded" alt="Không có hình">
            <p class="text-muted mt-3">Chưa có hình ảnh cho tour này</p>
        </div>
    @endif

    <!-- Tiêu đề & mô tả -->
    <h1 class="tour-title">{{ $tour->tieuDe }}</h1>
    <p class="tour-desc">{{ $tour->moTa }}</p>

    <!-- Thông tin cơ bản -->
    <div class="tour-info row">
        <div class="col-md-6">
            <p><strong>Thời gian:</strong> {{ $tour->thoiGian }}</p>
            <p><strong>Điểm đến:</strong> {{ $tour->diemDen }}</p>
            @if($tour->danhmuc)
                <p><strong>Danh mục:</strong> {{ $tour->danhmuc->tenDanhMuc }}</p>
            @endif
            <p><strong>Phụ thu phòng đơn:</strong> {{ number_format($tour->giaPhongDon) }} VNĐ</p>
        </div>
        <div class="col-md-6">
            <p><strong>Tổng số chuyến:</strong> <span class="badge bg-primary fs-5">{{ $chuyenTours->count() }}</span></p>
            <p><strong>Số hình ảnh:</strong> {{ $hinhAnh->count() }}</p>
            <p><strong>Lịch trình:</strong> {{ $lichTrinh->count() }} ngày</p>
        </div>
    </div>

    <!-- CHUYẾN TOUR -->
    @if($chuyenTours->count() > 0)
        <h3 class="section-title">
            <i class="fas fa-bus"></i> Các Chuyến Tour
        </h3>
        <div class="row">
            @foreach($chuyenTours as $chuyen)
                @php
                    $daDat = $chuyen->soLuongDaDat;
                    $toiThieu = $chuyen->so_khach_toi_thieu ?? 10;
                    $conThieu = max(0, $toiThieu - $daDat);
                @endphp
                <div class="col-lg-6 mb-4">
                    <div class="trip-card">
                        <div class="trip-header">
                            <div>
                                Chuyến #{{ $loop->iteration }} 
                                <small class="text-muted">
                                    ({{ $chuyen->ngayBatDau->format('d/m/Y') }} → {{ $chuyen->ngayKetThuc->format('d/m/Y') }})
                                </small>
                            </div>
                            <span class="trip-status 
                                {{ $chuyen->tinhTrangChuyen == 'ChuaDuKhach' ? 'status-chuadukhach' : 
                                   ($chuyen->tinhTrangChuyen == 'DuKhach' ? 'status-dukhach' : 
                                   ($chuyen->tinhTrangChuyen == 'DaKhoiHanh' ? 'status-dakhoihanh' : 'status-huy')) }}">
                                {{ $chuyen->tinhTrangChuyen == 'ChuaDuKhach' ? 'Chưa đủ khách' :
                                   ($chuyen->tinhTrangChuyen == 'DuKhach' ? 'Đủ khách' :
                                   ($chuyen->tinhTrangChuyen == 'DaKhoiHanh' ? 'Đã khởi hành' : 'Đã hủy')) }}
                            </span>
                        </div>

                        <p><strong>Khởi hành từ:</strong> {{ $chuyen->diemKhoiHanh }}</p>
                        <p><strong>Số chỗ:</strong> {{ $daDat }} / {{ $chuyen->soLuongToiDa }}
                            <small class="text-muted">(Còn {{ $chuyen->soLuongToiDa - $daDat }} chỗ)</small>
                        </p>

                        <!-- SỐ KHÁCH TỐI THIỂU -->
                        <div class="min-guest-info">
                            <strong>Số khách tối thiểu để chạy tour:</strong> {{ $toiThieu }} người<br>
                            <span class="{{ $daDat >= $toiThieu ? 'text-success' : 'text-warning' }} fw-bold">
                                Đã đặt: {{ $daDat }} người 
                                @if($conThieu > 0)
                                    → Còn thiếu {{ $conThieu }} người
                                @else
                                    → Đã đủ khách!
                                @endif
                            </span>
                        </div>

                        @if($chuyen->huongDanVien)
                            <p><strong>Hướng dẫn viên:</strong> {{ $chuyen->huongDanVien->hoTen }}
                                @if($chuyen->huongDanVien->soDienThoai)
                                    <small class="text-muted">({{ $chuyen->huongDanVien->soDienThoai }})</small>
                                @endif
                            </p>
                        @else
                            <p><strong>Hướng dẫn viên:</strong> <em class="text-muted">Chưa chỉ định</em></p>
                        @endif

                        @if($chuyen->phuongTien)
                            <p><strong>Phương tiện:</strong> {{ $chuyen->phuongTien }}</p>
                        @endif

                        @if($chuyen->ghiChu)
                            <p><strong>Ghi chú:</strong> <em>{{ $chuyen->ghiChu }}</em></p>
                        @endif

                        <!-- Giá chuyến -->
                        @if($chuyen->giaTour)
                            <div class="price-info">
                                <strong>Giá vé:</strong><br>
                                <span>Người lớn: {{ number_format($chuyen->giaTour->nguoiLon) }} VNĐ</span><br>
                                <span>Trẻ em: {{ number_format($chuyen->giaTour->treEm) }} VNĐ</span><br>
                                <span>Em bé: {{ $chuyen->giaTour->emBe == 0 ? 'Miễn phí' : number_format($chuyen->giaTour->emBe) . ' VNĐ' }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-bus fa-4x mb-3 opacity-50"></i>
            <p class="fs-4">Chưa có chuyến tour nào được tạo cho tour này.</p>
        </div>
    @endif

    <!-- LỊCH TRÌNH -->
    @if($lichTrinh->count() > 0)
        <h3 class="section-title">
            <i class="fas fa-route"></i> Lịch Trình Chi Tiết
        </h3>
        <div class="timeline">
            @php
                $firstTrip = $chuyenTours->sortBy('ngayBatDau')->first();
                $startDate = $firstTrip ? $firstTrip->ngayBatDau : \Carbon\Carbon::today();
            @endphp
            @foreach ($lichTrinh as $item)
                @php
                    $currentDate = $startDate->copy()->addDays($item->ngay - 1);
                @endphp
                <div class="timeline-item">
                    <div class="timeline-card">
                        <h5>Ngày {{ $item->ngay }} • {{ $currentDate->format('d/m/Y') }}</h5>
                        <p><strong>Hướng đi:</strong> {{ $item->huongDi }}</p>
                        <p><span class="meal-label">Buổi sáng:</span> {{ $item->sang ?? 'Tự do' }}</p>
                        <p><span class="meal-label">Buổi trưa:</span> {{ $item->trua ?? 'Ăn trưa tại nhà hàng' }}</p>
                        <p><span class="meal-label">Buổi chiều:</span> {{ $item->chieu ?? 'Tham quan' }}</p>
                        <p><span class="meal-label">Buổi tối:</span> {{ $item->toi ?? 'Nghỉ ngơi tại khách sạn' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-calendar-times fa-4x mb-3 opacity-50"></i>
            <p class="fs-4">Chưa có lịch trình chi tiết cho tour này.</p>
        </div>
    @endif

    <!-- NÚT QUAY LẠI -->
    <div class="text-center">
        <a href="{{ route('admin.tours.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Quay Lại Danh Sách Tour
        </a>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush