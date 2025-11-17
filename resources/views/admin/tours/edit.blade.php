{{-- resources/views/admin/tours/edit.blade.php --}}
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

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                <input type="text" name="tieuDe" class="form-control" value="{{ old('tieuDe', $tour->tieuDe) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Thời gian (ví dụ: 3 ngày 2 đêm) <span class="text-danger">*</span></label>
                <input type="text" name="thoiGian" class="form-control" value="{{ old('thoiGian', $tour->thoiGian) }}" required>
            </div>
        </div>

        <div class="mt-3">
            <label class="form-label">Mô tả <span class="text-danger">*</span></label>
            <textarea name="moTa" class="form-control" rows="4" required>{{ old('moTa', $tour->moTa) }}</textarea>
        </div>

        <div class="row g-3 mt-3">
            <div class="col-md-6">
                <label class="form-label">Điểm đến <span class="text-danger">*</span></label>
                <input type="text" name="diemDen" class="form-control" value="{{ old('diemDen', $tour->diemDen) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Danh mục</label>
                <select name="maDanhMuc" class="form-select">
                    <option value="">Chưa chọn</option>
                    @foreach ($danhmucs ?? [] as $danhmuc)
                        <option value="{{ $danhmuc->maDanhMuc }}" {{ old('maDanhMuc', $tour->maDanhMuc) == $danhmuc->maDanhMuc ? 'selected' : '' }}>
                            {{ $danhmuc->tenDanhMuc }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- HÌNH ẢNH -->
        <div class="mt-3">
            <label class="form-label">Hình ảnh hiện tại</label>
            <div class="d-flex flex-wrap gap-3">
                @foreach ($hinhAnh as $hinh)
                    <div class="text-center">
                        <img src="{{ asset('storage/' . $hinh->duongDanHinh) }}" width="120" class="rounded border">
                        <div class="mt-1">
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

        <div class="mt-3">
            <label class="form-label">Thêm hình ảnh mới</label>
            <input type="file" name="hinhAnh[]" multiple class="form-control" accept="image/*">
            <small class="text-muted">Tối đa 5MB/ảnh</small>
        </div>

        <!-- NÚT HÀNH ĐỘNG -->
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-success">Lưu Thay Đổi</button>

            <a href="{{ route('admin.tours.editSchedule', $tour->maTour) }}" class="btn btn-success">
                Sửa Lịch Trình
            </a>

            <a href="{{ route('admin.tours.editTrips', $tour->maTour) }}" class="btn btn-success">
                Sửa Chuyến
            </a>

            <a href="{{ route('admin.tours.index') }}" class="btn btn-secondary btn-lg px-5">Hủy</a>
        </div>
    </form>
</div>
@endsection
