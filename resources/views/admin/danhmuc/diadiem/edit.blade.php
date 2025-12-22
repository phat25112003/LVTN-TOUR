{{-- resources/views/admin/danhmuc/diadiem/edit.blade.php --}}
@extends('admin.layouts.dashboard')

@section('title', 'Sửa Địa Điểm')

@section('content')
<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary mb-0">
            Sửa Địa Điểm: <span class="text-dark">{{ $diadiem->tenDiaDiem }}</span>
        </h3>
        <a href="{{ route('admin.diadiem.index') }}" class="btn btn-outline-secondary">
            Quay lại danh sách
        </a>
    </div>

    {{-- Thông báo lỗi nếu có --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-5">
            <form action="{{ route('admin.diadiem.update', $diadiem->maDiaDiem) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="tenDiaDiem" class="form-label fw-bold text-dark">
                        Tên Địa Điểm <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="tenDiaDiem" 
                           id="tenDiaDiem"
                           class="form-control form-control-lg @error('tenDiaDiem') is-invalid @enderror"
                           value="{{ old('tenDiaDiem', $diadiem->tenDiaDiem) }}"
                           placeholder="Ví dụ: Phú Quốc, Đà Lạt..."
                           required
                           autofocus>
                    @error('tenDiaDiem')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="maDanhMuc" class="form-label fw-bold text-dark">
                        Thuộc Danh Mục <span class="text-danger">*</span>
                    </label>
                    <select name="maDanhMuc" 
                            id="maDanhMuc" 
                            class="form-select form-select-lg @error('maDanhMuc') is-invalid @enderror"
                            required>
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($danhmuc as $dm)
                            <option value="{{ $dm->maDanhMuc }}"
                                {{ old('maDanhMuc', $diadiem->maDanhMuc) == $dm->maDanhMuc ? 'selected' : '' }}>
                                {{ $dm->tenDanhMuc }}
                            </option>
                        @endforeach
                    </select>
                    @error('maDanhMuc')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid d-md-flex justify-content-end gap-3">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        Cập nhật
                    </button>
                    <a href="{{ route('admin.diadiem.index') }}" class="btn btn-secondary btn-lg px-5">
                        Hủy bỏ
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection