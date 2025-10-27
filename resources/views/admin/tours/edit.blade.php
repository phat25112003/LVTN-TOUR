@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid mt-4">
    <h3 class="text-center mb-4 fw-bold text-primary">Chỉnh sửa Tour</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.tours.update', $tour->maTour) }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Tiêu đề</label>
                <input type="text" name="tieuDe" class="form-control" value="{{ old('tieuDe', $tour->tieuDe) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Thời gian</label>
                <input type="text" name="thoiGian" class="form-control" value="{{ old('thoiGian', $tour->thoiGian) }}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="moTa" class="form-control" rows="3" required>{{ old('moTa', $tour->moTa) }}</textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Ngày bắt đầu</label>
                <input type="date" name="ngayBatDau" class="form-control" value="{{ old('ngayBatDau', \Carbon\Carbon::parse($tour->ngayBatDau)->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Ngày kết thúc</label>
                <input type="date" name="ngayKetThuc" class="form-control" value="{{ old('ngayKetThuc', \Carbon\Carbon::parse($tour->ngayKetThuc)->format('Y-m-d')) }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Số lượng</label>
                <input type="number" name="soLuong" class="form-control" value="{{ old('soLuong', $tour->soLuong) }}" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Giá người lớn</label>
                <input type="number" name="giaNguoiLon" class="form-control" value="{{ old('giaNguoiLon', $tour->giaNguoiLon) }}" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Giá trẻ em</label>
                <input type="number" name="giaTreEm" class="form-control" value="{{ old('giaTreEm', $tour->giaTreEm) }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Điểm đến</label>
                <input type="text" name="diemDen" class="form-control" value="{{ old('diemDen', $tour->diemDen) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Tình trạng</label>
                <select name="tinhTrang" class="form-select">
                    <option value="1" {{ old('tinhTrang', $tour->tinhTrang) == 1 ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ old('tinhTrang', $tour->tinhTrang) == 0 ? 'selected' : '' }}>Ngưng</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Danh mục</label>
            <select name="maDanhMuc" class="form-select">
                <option value="">Chưa có</option>
                @foreach ($danhmucs ?? [] as $danhmuc)
                    <option value="{{ $danhmuc->maDanhMuc }}" {{ old('maDanhMuc', $tour->maDanhMuc) == $danhmuc->maDanhMuc ? 'selected' : '' }}>
                        {{ $danhmuc->tenDanhMuc }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Hình ảnh hiện tại</label><br>
            <div class="d-flex flex-wrap gap-2">
                @foreach ($hinhAnh as $hinh)
                    <div class="text-center">
                        <img src="{{ asset('storage/' . $hinh->duongDanHinh) }}" width="100" height="70" class="rounded border">
                        <div>
                            <input type="checkbox" name="hinhAnhXoa[]" value="{{ $hinh->maHinhAnh }}"> Xóa
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Thêm hình ảnh mới</label>
            <input type="file" name="hinhAnh[]" multiple class="form-control">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-success">Lưu Thay Đổi</button>
            <button type="submit" name="updateSchedule" value="1" class="btn-schedule">Cập nhật lịch trình</button>
            <a href="{{ route('admin.tours.index') }}" class="btn-cancel">Hủy</a>
        </div>
    </form>
</div>

    <style>
        .container-fluid {
            padding: 20px;
        }
        h3 {
            margin-bottom: 20px;
            color: #333;
        }
        .bg-white {
            background-color: #fff;
        }
        .form-label {
            font-weight: 500;
        }
        .form-control, .form-select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-actions {
            margin-top: 20px;
        }
        .btn-success {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .btn-schedule {
            background-color: #17a2b8;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }
        .btn-schedule:hover {
            background-color: #138496;
        }
        .btn-cancel {
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-left: 10px;
        }
        .btn-cancel:hover {
            background-color: #c82333;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .d-flex {
            gap: 10px;
        }
        .rounded {
            border-radius: 5px;
        }
        .border {
            border: 1px solid #ddd;
        }
    </style>
@endsection
