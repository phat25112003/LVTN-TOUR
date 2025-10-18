@extends('admin.layouts.dashboard')

@section('content')
<style>
    body {
        background-color: #f4f6f8;
    }

    .tour-detail {
        max-width: 1100px;
        margin: 30px auto;
        background: #fff;
        padding: 25px 35px;
        border-radius: 12px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        font-family: "Segoe UI", sans-serif;
    }

    .carousel-item img {
        height: 450px;
        object-fit: cover;
        border-radius: 10px;
    }

    .tour-title {
        font-size: 26px;
        font-weight: 700;
        color: #2b9084;
        margin-bottom: 10px;
        text-align: center;
    }

    .tour-desc {
        color: #555;
        font-size: 15px;
        text-align: justify;
    }

    .tour-info {
        margin-top: 25px;
        background: #f8f9fa;
        padding: 15px 20px;
        border-radius: 8px;
    }

    .tour-info p {
        margin-bottom: 8px;
        font-size: 15px;
    }

    .tour-info strong {
        color: #2b9084;
    }

    .schedule-section {
        margin-top: 30px;
    }

    .schedule-section h3 {
        font-size: 20px;
        font-weight: 600;
        color: #2b9084;
        margin-bottom: 15px;
    }

    .schedule-card {
        border: 1px solid #e1e1e1;
        border-radius: 8px;
        padding: 15px;
        background: #fff;
        margin-bottom: 15px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .btn-back {
        background-color: #6c757d;
        color: white;
        font-weight: 600;
        padding: 8px 18px;
        border-radius: 6px;
        text-decoration: none;
        transition: 0.2s;
    }

    .btn-back:hover {
        background-color: #5a6268;
    }
</style>

<div class="tour-detail">
    <!-- Carousel ảnh -->
    @if ($hinhAnh->count() > 0)
        <div id="tourCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach ($hinhAnh as $index => $hinh)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . $hinh->duongDanHinh) }}" class="d-block w-100" alt="{{ $hinh->moTa }}">
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
    @endif

    <!-- Tiêu đề và mô tả -->
    <h1 class="tour-title">{{ $tour->tieuDe }}</h1>
    <p class="tour-desc">{{ $tour->moTa }}</p>

    <!-- Thông tin cơ bản -->
    <div class="tour-info row">
        <div class="col-md-6">
            <p><strong>Ngày bắt đầu:</strong> {{ $tour->ngayBatDau }}</p>
            <p><strong>Ngày kết thúc:</strong> {{ $tour->ngayKetThuc }}</p>
            <p><strong>Điểm đến:</strong> {{ $tour->diemDen }}</p>
            <p><strong>Số lượng chỗ:</strong> {{ $tour->soLuong }}</p>
        </div>
        <div class="col-md-6">
            <p><strong>Giá người lớn:</strong> {{ number_format($tour->giaNguoiLon) }} VNĐ</p>
            <p><strong>Giá trẻ em:</strong> {{ number_format($tour->giaTreEm) }} VNĐ</p>
            <p><strong>Thời gian:</strong> {{ $tour->thoiGian }}</p>
            <p><strong>Trạng thái:</strong>
                <span style=" font-weight:600;">
                    {{ $tour->tinhTrang ? 'Hoạt động' : 'Ngưng' }}
                </span>
            </p>
        </div>
    </div>

    <!-- Lịch trình -->
    <div class="schedule-section">
        <h3>Lịch Trình</h3>
        @foreach ($lichTrinh as $item)
            <div class="schedule-card">
                <h5>Ngày {{ $item->ngay }}</h5>
                <p><strong>Hướng đi:</strong> {{ $item->huongDi }}</p>
                <p><strong>Nội dung:</strong> {{ $item->noiDung }}</p>
            </div>
        @endforeach
    </div>

    <!-- Nút quay lại -->
    <div class="text-center mt-4">
        <a href="{{ route('admin.tours.index') }}" class="btn-back">⬅ Quay lại</a>
    </div>
</div>

@endsection
