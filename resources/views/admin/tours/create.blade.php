<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Tour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Thêm Tour</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('admin.tours.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="tieuDe" class="form-label">Tiêu đề</label>
                <input type="text" class="form-control" id="tieuDe" name="tieuDe" required>
            </div>
            <div class="mb-3">
                <label for="moTa" class="form-label">Mô tả</label>
                <textarea class="form-control" id="moTa" name="moTa" required></textarea>
            </div>
            <div class="mb-3">
                <label for="soLuong" class="form-label">Số lượng</label>
                <input type="number" class="form-control" id="soLuong" name="soLuong" required>
            </div>
            <div class="mb-3">
                <label for="giaNguoiLon" class="form-label">Giá người lớn</label>
                <input type="number" class="form-control" id="giaNguoiLon" name="giaNguoiLon" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="giaTreEm" class="form-label">Giá trẻ em</label>
                <input type="number" class="form-control" id="giaTreEm" name="giaTreEm" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="thoiLuong" class="form-label">Thời lượng</label>
                <input type="number" class="form-control" id="thoiLuong" name="thoiLuong" required>
            </div>
            <div class="mb-3">
                <label for="diemDen" class="form-label">Điểm đến</label>
                <input type="text" class="form-control" id="diemDen" name="diemDen" required>
            </div>
            <div class="mb-3">
                <label for="tinhTrang" class="form-label">Tình trạng</label>
                <select class="form-control" id="tinhTrang" name="tinhTrang" required>
                    <option value="1">Hoạt động</option>
                    <option value="0">Ngưng</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="hinhAnh" class="form-label">Hình ảnh tour (tối đa 7 hình)</label>
                <input type="file" class="form-control" id="hinhAnh" name="hinhAnh[]" multiple accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Thêm</button>
            <a href="{{ route('admin.tours.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</body>
</html>