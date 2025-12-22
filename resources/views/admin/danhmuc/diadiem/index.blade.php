{{-- resources/views/admin/danhmuc/diadiem/index.blade.php --}}
@extends('admin.layouts.dashboard')

@section('title', 'Quản lý Địa Điểm')

@section('content')
<div class="container-fluid mt-4">

    <h3 class="text-center mb-4 fw-bold text-primary">Quản Lý Địa Điểm</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- NÚT THÊM + QUAY LẠI – GIỐNG HỆT TRANG DANH MỤC -->
    <div>
        <a href="{{ route('admin.diadiem.create') }}" class="add-btn">
            + Thêm Địa Điểm
        </a>

        <a href="{{ route('admin.danhmuc.index') }}" class="btn btn-outline-primary btn-sm">
            Quay lại Danh mục
        </a>
    </div>

    <!-- TÌM KIẾM NHỎ GỌN -->
    <form action="{{ route('admin.diadiem.index') }}" method="GET" class="mb-4">
        <div class="input-group" style="max-width: 350px;">
            <input type="text" name="search" class="form-control" placeholder="Tìm địa điểm..."
                   value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit">Tìm</button>
        </div>
    </form>

    <!-- BẢNG GIỐNG HỆT TRANG DANH MỤC -->
    <table class="category-admin-table">
        <thead>
            <tr>
                <th width="8%"> STT</th>
                <th>Tên Địa Điểm</th>
                <th>Danh Mục</th>
                <th width="20%">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($diadiem as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration + ($diadiem->currentPage() - 1) * $diadiem->perPage() }}</td>
                    <td><strong>{{ $item->tenDiaDiem }}</strong></td>
                    <td>
                        <span>
                            {{ $item->danhMuc?->tenDanhMuc ?? '—' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.diadiem.edit', $item->maDiaDiem) }}"
                           class="btn-action btn-edit">Sửa</a>

                        <form action="{{ route('admin.diadiem.destroy', $item->maDiaDiem) }}"
                              method="POST" class="inline-form"
                              onsubmit="return confirm('Xóa địa điểm «{{ addslashes($item->tenDiaDiem) }}»?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-5 text-muted">
                        Chưa có địa điểm nào
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- PHÂN TRANG NHỎ GỌN, ĐẸP, CĂN GIỮA -->
    <div class="d-flex justify-content-center mt-4">
        <nav>
            <ul class="pagination pagination-sm">
                {{-- Nút Previous --}}
                <li class="page-item {{ $diadiem->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $diadiem->previousPageUrl() }}" tabindex="-1">‹</a>
                </li>

                {{-- Các số trang --}}
                @foreach($diadiem->getUrlRange(1, $diadiem->lastPage()) as $page => $url)
                    <li class="page-item {{ $page == $diadiem->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach

                {{-- Nút Next --}}
                <li class="page-item {{ $diadiem->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link" href="{{ $diadiem->nextPageUrl() }}">›</a>
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