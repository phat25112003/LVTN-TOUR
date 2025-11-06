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
            <textarea name="moTa" rows="4" class="form-control" required>{{ old('moTa') }}</textarea>
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

        <div class="mb-3">
            <label class="form-label">Hình ảnh Tour (nhiều ảnh)</label>
            <input type="file" name="hinhAnh[]" multiple class="form-control" accept="image/*">
            <small class="text-muted">Tối đa 5MB/ảnh, định dạng: jpg, png, webp</small>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-success btn-lg">
                Lưu & Tiếp tục
            </button>
            <a href="{{ route('admin.tours.index') }}" class="btn btn-secondary btn-lg">Hủy</a>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .form-control, .form-select {
        border-radius: 6px;
        padding: 10px;
    }
    .btn-success {
        background: #28a745;
        border: none;
        padding: 12px 30px;
        font-weight: 600;
    }
    .btn-success:hover { background: #218838; }
    .btn-secondary { padding: 12px 30px; }
</style>
@endpush