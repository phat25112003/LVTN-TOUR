@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid mt-4">
    <h3 class="text-center mb-4 fw-bold text-primary">Thêm Tour Mới</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Lỗi!</strong> Vui lòng kiểm tra lại thông tin nhập.
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.tours.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Tiêu đề Tour</label>
                <input type="text" name="tieuDe" class="form-control" value="{{ old('tieuDe') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Thời gian (ví dụ: 3 ngày 2 đêm)</label>
                <input type="text" name="thoiGian" class="form-control" value="{{ old('thoiGian') }}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="moTa" rows="3" class="form-control" required>{{ old('moTa') }}</textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Ngày bắt đầu</label>
                <input type="date" name="ngayBatDau" class="form-control" value="{{ old('ngayBatDau') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Ngày kết thúc</label>
                <input type="date" name="ngayKetThuc" class="form-control" value="{{ old('ngayKetThuc') }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Số lượng</label>
                <input type="number" name="soLuong" class="form-control" value="{{ old('soLuong') }}" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Giá người lớn (VNĐ)</label>
                <input type="number" name="giaNguoiLon" class="form-control" value="{{ old('giaNguoiLon') }}" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Giá trẻ em (VNĐ)</label>
                <input type="number" name="giaTreEm" class="form-control" value="{{ old('giaTreEm') }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Điểm đến</label>
                <input type="text" name="diemDen" class="form-control" value="{{ old('diemDen') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Tình trạng</label>
                <select name="tinhTrang" class="form-select">
                    <option value="1" {{ old('tinhTrang') == 1 ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ old('tinhTrang') == 0 ? 'selected' : '' }}>Ngưng</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Danh mục</label>
            <select name="maDanhMuc" class="form-select">
                <option value="">Chưa có</option>
                @foreach ($danhmucs ?? [] as $danhmuc)
                    <option value="{{ $danhmuc->maDanhMuc }}" {{ old('maDanhMuc') == $danhmuc->maDanhMuc ? 'selected' : '' }}>
                        {{ $danhmuc->tenDanhMuc }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Hình ảnh Tour</label>
            <input type="file" name="hinhAnh[]" multiple class="form-control">
            
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-success">Lưu và Tạo Lịch Trình</button>
            <a href="{{ route('admin.tours.index') }}" class="btn-cancel">Hủy</a>
        </div>
    </form>
</div>
@endsection

@push('styles')
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
    </style>
@endpush