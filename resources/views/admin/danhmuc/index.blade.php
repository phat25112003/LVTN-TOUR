@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid mt-4">

    <h3 class="text-center mb-4 fw-bold text-primary">Quản lý Danh mục</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Sử dụng class mới nếu bạn muốn thay đổi màu nút Add --}}
    <a href="{{ route('admin.danhmuc.create') }}" class="btn btn-success mb-3">
        + Thêm Danh mục
    </a>

    {{-- Thay class table-striped bằng class mới --}}
    <table class="category-admin-table">
        <thead>
            <tr>
                <th>Tên Danh mục</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($danhmucs as $danhmuc)
                <tr>
                    <td>{{ $danhmuc->tenDanhMuc }}</td>
                    <td>
                        <a href="{{ route('admin.danhmuc.edit', $danhmuc->maDanhMuc) }}" class="btn btn-warning btn-sm">Sửa</a>
                        {{-- Thêm class inline-form --}}
                        <form action="{{ route('admin.danhmuc.destroy', $danhmuc->maDanhMuc) }}" method="POST" class="inline-form" onsubmit="return confirm('Bạn có chắc muốn xóa?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection