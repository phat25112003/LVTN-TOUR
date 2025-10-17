<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết Tour: {{ $tour->tieuDe }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .tour-detail { max-width: 1200px; margin: auto; padding: 20px; background: white; box-shadow: 0 0 10px rgba(0,0,0,0.1); border-radius: 10px; }
        .carousel-item img { height: 500px; object-fit: cover; border-radius: 10px; }
        .info-section { margin-top: 20px; }
        .btn-back { background: #6c757d; color: white; }
    </style>
</head>
<body>
    <div class="tour-detail">
        <!-- Carousel ảnh -->
        <div id="tourCarousel" class="carousel slide" data-bs-ride="carousel">
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

        <!-- Thông tin tour -->
        <div class="info-section">
            <h1>{{ $tour->tieuDe }}</h1>
                     <h3>Lịch Trình</h3>
            @foreach ($lichTrinh as $item)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Ngày {{ $item->ngay }}</h5>
                        <p class="card-text"><strong>Hướng đi:</strong> {{ $item->huongDi }}</p>
                        <p class="card-text"><strong>Nội dung:</strong> {{ $item->noiDung }}</p>
                    </div>
                </div>
            @endforeach
            <p class="text-muted">{{ $tour->moTa }}</p>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Giá người lớn:</strong> {{ number_format($tour->giaNguoiLon) }} VNĐ</p>
                    <p><strong>Giá trẻ em:</strong> {{ number_format($tour->giaTreEm) }} VNĐ</p>
                    <p><strong>Thời lượng:</strong> {{ $tour->thoiLuong }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Điểm đến:</strong> {{ $tour->diemDen }}</p>
                    <p><strong>Số lượng chỗ:</strong> {{ $tour->soLuong }}</p>
                    <p><strong>Tình trạng:</strong> {{ $tour->tinhTrang ? 'Hoạt động' : 'Ngưng' }}</p>
                </div>
            </div>
            <div class="text-center mt-3">
                <a href="{{ route('admin.tours.index') }}" class="btn btn-back">Quay lại</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>