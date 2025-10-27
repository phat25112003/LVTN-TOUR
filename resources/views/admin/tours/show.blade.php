@extends('admin.layouts.dashboard')

@section('content')
<style>
    body {
        background-color: #f4f6f8;
        font-family: "Segoe UI", sans-serif;
    }

    .tour-detail {
        max-width: 1100px;
        margin: 30px auto;
        background: #fff;
        padding: 25px 35px;
        border-radius: 12px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    .carousel-item img {
        width: 100%;
        max-height: 500px;
        object-fit: cover;
        border-radius: 10px;
    }

    .tour-title {
        font-size: 26px;
        font-weight: 700;
        color: #2b9084;
        margin-bottom: 15px;
        text-align: center;
    }

    .tour-desc {
        color: #555;
        font-size: 15px;
        text-align: justify;
        margin-bottom: 20px;
    }

    .tour-info {
        margin-top: 25px;
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
    }

    .tour-info p {
        margin-bottom: 10px;
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
        margin-bottom: 20px;
    }

    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        top: 0;
        left: 15px;
        height: 100%;
        width: 2px;
        background: #2b9084;
    }

    .timeline-item {
        margin-bottom: 25px;
        position: relative;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -30px;
        top: 10px;
        width: 12px;
        height: 12px;
        background: #2b9084;
        border-radius: 50%;
    }

    .timeline-card {
        background: #fff;
        border: 1px solid #e1e1e1;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .timeline-card h5 {
        color: #2b9084;
        margin-bottom: 10px;
        font-size: 18px;
    }

    .timeline-card p {
        margin: 5px 0;
        color: #555;
        font-size: 14px;
    }

    .meal-label {
        font-weight: 600;
        color: #333;
    }

    .btn-back {
        background-color: #6c757d;
        color: white;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        transition: 0.2s;
        display: inline-block;
    }

    .btn-back:hover {
        background-color: #5a6268;
    }

    @media (max-width: 768px) {
        .tour-detail {
            margin: 15px;
            padding: 15px;
        }

        .tour-title {
            font-size: 22px;
        }

        .tour-desc {
            font-size: 14px;
        }

        .tour-info {
            padding: 15px;
        }

        .tour-info p {
            font-size: 14px;
        }

        .schedule-section h3 {
            font-size: 18px;
        }

        .timeline-card h5 {
            font-size: 16px;
        }

        .timeline-card p {
            font-size: 13px;
        }

        .btn-back {
            padding: 8px 15px;
            font-size: 14px;
        }
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
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#tourCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    @endif

    <!-- Tiêu đề và mô tả -->
    <h1 class="tour-title">{{ $tour->tieuDe }}</h1>
    <p class="tour-desc">{{ $tour->moTa }}</p>

    <!-- Thông tin cơ bản -->
    <div class="tour-info row">
        <div class="col-md-6">
            <p><strong>Ngày bắt đầu:</strong> {{ \Carbon\Carbon::parse($tour->ngayBatDau)->format('d/m/Y') }}</p>
            <p><strong>Ngày kết thúc:</strong> {{ \Carbon\Carbon::parse($tour->ngayKetThuc)->format('d/m/Y') }}</p>
            <p><strong>Điểm đến:</strong> {{ $tour->diemDen }}</p>
            <p><strong>Số lượng chỗ:</strong> {{ $tour->soLuong }}</p>
        </div>
        <div class="col-md-6">
            <p><strong>Giá người lớn:</strong> {{ number_format($tour->giaNguoiLon) }} VNĐ</p>
            <p><strong>Giá trẻ em:</strong> {{ number_format($tour->giaTreEm) }} VNĐ</p>
            <p><strong>Thời gian:</strong> {{ $tour->thoiGian }}</p>
            <p><strong>Trạng thái:</strong>
                <span style="font-weight:600;">
                    {{ $tour->tinhTrang ? 'Hoạt động' : 'Ngưng' }}
                </span>
            </p>
        </div>
    </div>

    <!-- Lịch trình -->
    <div class="schedule-section">
        <h3>Lịch Trình</h3>
        <div class="timeline">
            @foreach ($lichTrinh as $item)
                <div class="timeline-item">
                    <div class="timeline-card">
                        <h5>Ngày {{ \Carbon\Carbon::parse($tour->ngayBatDau)->addDays($item->ngay - 1)->format('d/m/Y') }}</h5>
                        <p><strong>Hướng đi:</strong> {{ $item->huongDi }}</p>
                        <p><span class="meal-label">Sáng:</span> {{ $item->sang ?? 'Chưa cập nhật' }}</p>
                        <p><span class="meal-label">Trưa:</span> {{ $item->trua ?? 'Chưa cập nhật' }}</p>
                        <p><span class="meal-label">Chiều:</span> {{ $item->chieu ?? 'Chưa cập nhật' }}</p>
                        <p><span class="meal-label">Tối:</span> {{ $item->toi ?? 'Chưa cập nhật' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Nút quay lại -->
    <div class="text-center mt-5">
        <a href="{{ route('admin.tours.index') }}" class="btn-back">Quay lại</a>
    </div>
</div>
@endsection