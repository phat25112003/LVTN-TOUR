@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid mt-4">

{{-- Thay toàn bộ đoạn này --}}
<h3 class="text-center mb-4 fw-bold text-primary">Quản lý Danh mục</h3>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div>
    <a href="{{ route('admin.danhmuc.create') }}" class="add-btn">
        + Thêm Khu Vực
    </a>

    <a href="{{ route('admin.diadiem.index') }}" class="view-btn">
        <i class="fa-solid fa-location-dot me-2"></i>Xem địa điểm
    </a>
    <a href="{{ route('admin.loaidulich.index') }}" class="view-btn">
        <i class="fa-solid fa-tags me-2"></i> Quản Lý Loại Du Lịch
    </a>
</div>

    {{-- Thay class table-striped bằng class mới --}}
    <table class="category-admin-table">
        <thead>
            <tr>
                <th>Tên Khu Vực</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($danhmucs as $danhmuc)
                <tr>
                    <td>{{ $danhmuc->tenDanhMuc }}</td>
                    <td>
                        <a href="{{ route('admin.danhmuc.edit', $danhmuc->maDanhMuc) }}" class="btn-action btn-edit">Sửa</a>
                        {{-- Thêm class inline-form --}}
                        <form action="{{ route('admin.danhmuc.destroy', $danhmuc->maDanhMuc) }}" method="POST" class="inline-form" onsubmit="return confirm('Bạn có chắc muốn xóa?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush