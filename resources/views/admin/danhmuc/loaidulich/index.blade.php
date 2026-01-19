{{-- resources/views/admin/danhmuc/loaidulich/index.blade.php --}}
@extends('admin.layouts.dashboard')

@section('title', 'Quản Lý Loại Du Lịch')

@section('content')
<div class="container-fluid mt-4">

    <h3 class="text-center mb-4 fw-bold text-primary">Quản Lý Loại Du Lịch</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- NÚT THÊM + QUAY LẠI – GIỐNG HỆT ĐỊA ĐIỂM -->
    <div>
        <a href="{{ route('admin.loaidulich.create') }}" class="add-btn">
            + Thêm Loại Du Lịch
        </a>

        <a href="{{ route('admin.danhmuc.index') }}" class="btn btn-outline-primary btn-sm">
            Quay lại Danh mục
        </a>
    </div>

    <!-- TÌM KIẾM NHỎ GỌN -->
    <form action="{{ route('admin.loaidulich.index') }}" method="GET" class="mb-4">
        <div class="input-group" style="max-width: 350px;">
            <input type="text" name="search" class="form-control" placeholder="Tìm loại du lịch..."
                   value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit">Tìm</button>
        </div>
    </form>

    <!-- BẢNG GIỐNG HỆT TRANG ĐỊA ĐIỂM -->
    <table class="category-admin-table">
        <thead>
            <tr>
                <th width="10%">STT</th>
                <th>Tên Loại</th>
                <th>Mô Tả</th>
                <th width="20%">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loaiDuLichs as $loai)
                <tr>
                    <td class="text-center">{{ $loop->iteration + ($loaiDuLichs->currentPage() - 1) * $loaiDuLichs->perPage() }}</td>
                    <td><strong>{{ $loai->tenLoai }}</strong></td>
                    <td>{{ $loai->moTa ?? '—' }}</td>
                    <td>
                        <a href="{{ route('admin.loaidulich.edit', $loai->maLoai) }}"
                           class="btn-action btn-edit">Sửa</a>

                        <form action="{{ route('admin.loaidulich.destroy', $loai->maLoai) }}"
                              method="POST" class="inline-form"
                              onsubmit="return confirm('Xóa loại «{{ addslashes($loai->tenLoai) }}»?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-5 text-muted">
                        Chưa có loại du lịch nào
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- PHÂN TRANG ĐẸP, CĂN GIỮA -->
    <div class="d-flex justify-content-center mt-4">
        <nav>
            <ul class="pagination pagination-sm">
                <li class="page-item {{ $loaiDuLichs->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $loaiDuLichs->previousPageUrl() }}" tabindex="-1">‹</a>
                </li>

                @foreach($loaiDuLichs->getUrlRange(1, $loaiDuLichs->lastPage()) as $page => $url)
                    <li class="page-item {{ $page == $loaiDuLichs->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach

                <li class="page-item {{ $loaiDuLichs->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link" href="{{ $loaiDuLichs->nextPageUrl() }}">›</a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<style>
    .category-admin-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border-radius: 8px;
        overflow: hidden;
    }
    .category-admin-table th {
        background: #667eea;
        color: white;
        padding: 15px;
        text-align: center;
        font-weight: 600;
    }
    .category-admin-table td {
        padding: 14px;
        text-align: center;
        border-bottom: 1px solid #eee;
    }
    .category-admin-table tr:hover {
        background-color: #f8f9ff;
    }
    .inline-form {
        display: inline;
    }

    /* Phân trang nhỏ gọn đẹp */
    .pagination {
        gap: 8px;
    }
    .pagination .page-link {
        border-radius: 8px !important;
        padding: 8px 14px;
        font-size: 14px;
    }
    .pagination .page-item.active .page-link {
        background: #667eea;
        border-color: #667eea;
    }
</style>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush