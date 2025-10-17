<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Tour: {{ $tour->tieuDe }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Sửa Tour</h2>
        <form method="POST" action="{{ route('admin.tours.update', $tour->maTour) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="tieuDe" class="form-label">Tiêu đề</label>
                <input type="text" class="form-control" id="tieuDe" name="tieuDe" value="{{ old('tieuDe', $tour->tieuDe) }}" required>
            </div>
            <div class="mb-3">
                <label for="moTa" class="form-label">Mô tả</label>
                <textarea class="form-control" id="moTa" name="moTa" required>{{ old('moTa', $tour->moTa) }}</textarea>
            </div>
            <div class="mb-3">
                <label for="soLuong" class="form-label">Số lượng</label>
                <input type="number" class="form-control" id="soLuong" name="soLuong" value="{{ old('soLuong', $tour->soLuong) }}" required>
            </div>
            <div class="mb-3">
                <label for="giaNguoiLon" class="form-label">Giá người lớn</label>
                <input type="number" class="form-control" id="giaNguoiLon" name="giaNguoiLon" step="0.01" value="{{ old('giaNguoiLon', $tour->giaNguoiLon) }}" required>
            </div>
            <div class="mb-3">
                <label for="giaTreEm" class="form-label">Giá trẻ em</label>
                <input type="number" class="form-control" id="giaTreEm" name="giaTreEm" step="0.01" value="{{ old('giaTreEm', $tour->giaTreEm) }}" required>
            </div>
            <div class="mb-3">
                <label for="thoiLuong" class="form-label">Thời lượng</label>
                <input type="number" class="form-control" id="thoiLuong" name="thoiLuong" value="{{ old('thoiLuong', $tour->thoiLuong) }}" required>
            </div>
            <div class="mb-3">
                <label for="diemDen" class="form-label">Điểm đến</label>
                <input type="text" class="form-control" id="diemDen" name="diemDen" value="{{ old('diemDen', $tour->diemDen) }}" required>
            </div>
            <div class="mb-3">
                <label for="tinhTrang" class="form-label">Tình trạng</label>
                <select class="form-control" id="tinhTrang" name="tinhTrang" required>
                    <option value="1" {{ old('tinhTrang', $tour->tinhTrang) == 1 ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ old('tinhTrang', $tour->tinhTrang) == 0 ? 'selected' : '' }}>Ngưng</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Hình ảnh hiện tại</label>
                @foreach ($hinhAnh as $hinh)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $hinh->duongDanHinh) }}" alt="{{ $hinh->moTa }}" style="max-width: 100px; max-height: 100px;">
                        <input type="checkbox" name="hinhAnhXoa[]" value="{{ $hinh->maHinhAnh }}"> Xóa hình này
                    </div>
                @endforeach
            </div>
            <div class="mb-3">
                <label for="hinhAnh" class="form-label">Thêm hình ảnh mới (tối đa 7, hiện tại còn {{ 7 - $hinhAnh->count() }} chỗ)</label>
                <input type="file" class="form-control" id="hinhAnh" name="hinhAnh[]" multiple accept="image/*">
            </div>
            <div class="mb-3">
                <label class="form-check-label">
                    <input type="checkbox" name="updateSchedule" value="1"> Cập nhật lịch trình
                </label>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.tours.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</body>
</html>