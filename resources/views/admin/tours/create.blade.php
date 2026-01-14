{{-- resources/views/admin/tours/create.blade.php --}}
@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid mt-4">
    <h3 class="text-center mb-4 fw-bold text-primary">Thêm Tour Mới</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Lỗi!</strong> Vui lòng kiểm tra lại.
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
                <label class="form-label">Tiêu đề Tour <span class="text-danger">*</span></label>
                <input type="text" name="tieuDe" class="form-control" value="{{ old('tieuDe') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Thời gian (ví dụ: 3 ngày 2 đêm) <span class="text-danger">*</span></label>
                <input type="text" name="thoiGian" class="form-control" value="{{ old('thoiGian') }}" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả <span class="text-danger">*</span></label>
            <textarea name="moTa" rows="6" class="form-control" required>{{ old('moTa') }}</textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Điểm đến <span class="text-danger">*</span></label>
                <input type="text" name="diemDen" class="form-control" value="{{ old('diemDen') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Danh mục</label>
                <select name="maDanhMuc" class="form-select">
                    <option value="">Chưa chọn</option>
                    @foreach ($danhmucs ?? [] as $danhmuc)
                        <option value="{{ $danhmuc->maDanhMuc }}" {{ old('maDanhMuc') == $danhmuc->maDanhMuc ? 'selected' : '' }}>
                            {{ $danhmuc->tenDanhMuc }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- THÊM TRƯỜNG GIÁ PHÒNG ĐƠN -->
        <div class="mb-3">
            <label class="form-label">Giá phụ thu phòng đơn (VNĐ) <span class="text-danger">*</span></label>
            <input type="number" name="giaPhongDon" class="form-control" value="{{ old('giaPhongDon', 0) }}" min="0" step="10000" required>
            <small class="text-muted">Phụ phí khi khách chọn phòng đơn (ví dụ: 2.000.000)</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Hình ảnh Tour (nhiều ảnh)</label>
            <input type="file" name="hinhAnh[]" multiple class="form-control" accept="image/*">
            <small class="text-muted">Tối đa 5MB/ảnh, định dạng: jpg, png, webp, gif</small>
        </div>

        <div class="text-center mt-5">
            <button type="submit" class="btn btn-success btn-lg px-5">
                Lưu & Tiếp tục
            </button>
            <a href="{{ route('admin.tours.index') }}" class="btn btn-secondary btn-lg px-5 ms-3">
                Hủy
            </a>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .container-fluid {
        max-width: 1000px;
        margin: 0 auto;
    }
    .form-control, .form-select {
        border-radius: 6px;
        padding: 10px 12px;
        border: 1px solid #ced4da;
    }
    .form-control:focus, .form-select:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    .btn-success {
        background: #28a745;
        border: none;
        font-weight: 600;
    }
    .btn-success:hover {
        background: #218838;
    }
    .btn-secondary {
        background: #6c757d;
        border: none;
    }
    .btn-secondary:hover {
        background: #5a6268;
    }
    .alert-danger {
        border-radius: 8px;
    }
</style>
@endpush
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush