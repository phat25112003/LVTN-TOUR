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

    .tour-info .row > div {
        padding: 8px 0;
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

    /* Lịch trình */
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

    .timeline-card p {
        margin: 6px 0;
        color: #444;
        font-size: 14.5px;
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
        padding: 18px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .trip-card:hover {
        background: #fff;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .trip-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        font-weight: 600;
        color: #2b9084;
    }

    .trip-status {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .status-hoatdong { background: #d4edda; color: #155724; }
    .status-dung { background: #f8d7da; color: #721c24; }

    .btn-back {
        background: linear-gradient(135deg, #6c757d, #495057);
        color: white;
        font-weight: 600;
        padding: 12px 28px;
        border-radius: 50px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }

    .btn-back:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.25);
        background: linear-gradient(135deg, #5a6268, #343a40);
    }

    @media (max-width: 768px) {
        .tour-detail { margin: 15px; padding: 20px; }
        .tour-title { font-size: 22px; }
        .tour-desc { font-size: 14px; }
        .carousel-item img { height: 300px; }
        .section-title { font-size: 19px; }
        .timeline { padding-left: 25px; }
        .timeline-item::before { left: -25px; }
        .trip-header { flex-direction: column; align-items: flex-start; gap: 8px; }
        .btn-back { padding: 10px 20px; font-size: 14px; }
    }
</style>

<div class="tour-detail">
    <!-- Ảnh tour -->
    @if ($hinhAnh->count() > 0)
        <div id="tourCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach ($hinhAnh as $index => $hinh)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        @php
                            $noImage = asset('images/no-image.png');
                        @endphp

                        <img src="{{ asset('storage/' . $hinh->duongDanHinh) }}" 
                            class="d-block w-100" 
                            alt="Hình tour"
                            onerror="this.src='{{ $noImage }}'">
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#tourCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#tourCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    @else
        <div class="text-center mb-4">
            <img src="{{ asset('images/no-image.png') }}" width="300" class="rounded" alt="Không có hình">
            <p class="text-muted mt-2">Chưa có hình ảnh</p>
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
        </div>
        <div class="col-md-6">
            <p><strong>Tổng số chuyến:</strong> <span class="badge bg-primary">{{ $chuyenTours->count() }}</span></p>
            <p><strong>Số hình ảnh:</strong> {{ $hinhAnh->count() }}</p>
        </div>
    </div>

    <!-- CHUYẾN TOUR -->
    @if($chuyenTours->count() > 0)
        <h3 class="section-title">
            <i class="bi bi-bus-front"></i> Các Chuyến Tour
        </h3>
        <div class="row">
            @foreach($chuyenTours as $chuyen)
                <div class="col-lg-6 mb-3">
                    <div class="trip-card">
                        <div class="trip-header">
                            <div>
                                Chuyến #{{ $loop->iteration }}
                                <small class="text-muted">
                                    ({{ $chuyen->ngayBatDau->format('d/m') }} → {{ $chuyen->ngayKetThuc->format('d/m/Y') }})
                                </small>
                            </div>
                            <span class="trip-status {{ $chuyen->tinhTrangChuyen === 'HoatDong' ? 'status-hoatdong' : 'status-dung' }}">
                                {{ $chuyen->tinhTrangChuyen === 'HoatDong' ? 'Hoạt động' : 'Dừng' }}
                            </span>
                        </div>
                        <p><strong>Khởi hành:</strong> {{ $chuyen->diemKhoiHanh }}</p>
                        <p><strong>Số chỗ:</strong> {{ $chuyen->soLuongDaDat }} / {{ $chuyen->soLuongToiDa }} 
                            <small class="text-muted">(Còn {{ $chuyen->soLuongToiDa - $chuyen->soLuongDaDat }} chỗ)</small>
                        </p>
                        @if($chuyen->huongDanVien)
                            <p><strong>HDV:</strong> {{ $chuyen->huongDanVien }}</p>
                        @endif
                        @if($chuyen->phuongTien)
                            <p><strong>Phương tiện:</strong> {{ $chuyen->phuongTien }}</p>
                        @endif
                        @if($chuyen->ghiChu)
                            <p><strong>Ghi chú:</strong> <em>{{ $chuyen->ghiChu }}</em></p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-4 text-muted">
            <p>Chưa có chuyến tour nào được tạo.</p>
        </div>
    @endif

    <!-- LỊCH TRÌNH -->
    @if($lichTrinh->count() > 0)
        <h3 class="section-title">
            <i class="bi bi-calendar-check"></i> Lịch Trình Chi Tiết
        </h3>
        <div class="timeline">
            @php
                $firstTrip = $chuyenTours->sortBy('ngayBatDau')->first();
                $startDate = $firstTrip ? $firstTrip->ngayBatDau : \Carbon\Carbon::now();
            @endphp
            @foreach ($lichTrinh as $item)
                @php
                    $currentDate = $startDate->copy()->addDays($item->ngay - 1);
                @endphp
                <div class="timeline-item">
                    <div class="timeline-card">
                        <h5>Ngày {{ $item->ngay }} - {{ $currentDate->format('d/m/Y') }}</h5>
                        <p><strong>Hướng đi:</strong> {{ $item->huongDi }}</p>
                        <p><span class="meal-label">Sáng:</span> {{ $item->sang ?? '—' }}</p>
                        <p><span class="meal-label">Trưa:</span> {{ $item->trua ?? '—' }}</p>
                        <p><span class="meal-label">Chiều:</span> {{ $item->chieu ?? '—' }}</p>
                        <p><span class="meal-label">Tối:</span> {{ $item->toi ?? '—' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-4 text-muted">
            <p>Chưa có lịch trình chi tiết.</p>
        </div>
    @endif

    <!-- NÚT QUAY LẠI -->
    <div class="text-center mt-5">
        <a href="{{ route('admin.tours.index') }}" class="btn-back">
            <i class="bi bi-arrow-left-circle"></i> Quay Lại Danh Sách
        </a>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
@endpush