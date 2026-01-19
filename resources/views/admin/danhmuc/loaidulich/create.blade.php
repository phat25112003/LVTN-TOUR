{{-- resources/views/admin/danhmuc/loaidulich/create.blade.php --}}
@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid mt-4">
    <h3 class="fw-bold text-primary mb-4">
        <i class="fa-solid fa-plus-circle me-2"></i> Thêm Loại Du Lịch Mới
    </h3>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.loaidulich.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-bold">Tên loại du lịch <span class="text-danger">*</span></label>
                    <input type="text" name="tenLoai" class="form-control @error('tenLoai') is-invalid @enderror" 
                           value="{{ old('tenLoai') }}" maxlength="100" required>
                    @error('tenLoai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Mô tả</label>
                    <textarea name="moTa" rows="5" class="form-control">{{ old('moTa') }}</textarea>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-save me-2"></i> Lưu Loại Mới
                    </button>
                    <a href="{{ route('admin.loaidulich.index') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left me-2"></i> Quay Lại
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush