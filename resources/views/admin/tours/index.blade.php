@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid mt-4">
    <h3 class="text-center mb-4 fw-bold text-primary">Danh sách Tour Du Lịch</h3>

    <!-- @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif -->

    <!-- Thanh chọn danh mục -->
    <form method="GET" action="{{ route('admin.tours.index') }}" class="mb-4">
        <div class="form-group d-flex align-items-center">
            <label for="maDanhMuc" class="mr-2">Danh Mục:</label>
            <select name="maDanhMuc" id="maDanhMuc" class="form-control" onchange="this.form.submit()">
                <option value="">Tất cả</option>
                @foreach ($danhmucs as $danhmuc)
                    <option value="{{ $danhmuc->maDanhMuc }}" {{ request('maDanhMuc') == $danhmuc->maDanhMuc ? 'selected' : '' }}>
                        {{ $danhmuc->tenDanhMuc }}
                    </option>
                @endforeach
            </select>
        </div>
        <!-- Giữ lại từ khóa tìm kiếm khi đổi danh mục -->
        <input type="hidden" name="search" value="{{ request('search') }}">
    </form>

    <!-- TÌM KIẾM NHỎ GỌN -->
    <form action="{{ route('admin.tours.index') }}" method="GET" class="mb-4">
        <div class="input-group" style="max-width: 350px;">
            <input type="text" name="search" class="form-control" placeholder="Tìm địa điểm..."
                value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit">Tìm</button>
        </div>

        <!-- Dòng quan trọng này: giữ lại danh mục đã chọn khi tìm kiếm -->
        <input type="hidden" name="maDanhMuc" value="{{ request('maDanhMuc') }}">
    </form>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <a href="{{ route('admin.tours.create') }}" class="add-btn">+ Thêm Tour</a>

    <table class="table-custom">
        <thead>
            <tr>
                <th>Tiêu đề</th>
                <th>Thời gian</th>
                <th>Mô tả</th>
                <th>Điểm đến</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tours as $tour)
                <tr>
                    <td>{{ $tour->tieuDe }}</td>
                    <td>{{ $tour->thoiGian ?? '-' }}</td>
                    <td class="desc">{{ Str::limit($tour->moTa, 150) }}</td>
                    <td>{{ $tour->diemDen }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.tours.show', $tour->maTour) }}" class="btn-action btn-view">Xem</a>
                            <a href="{{ route('admin.tours.edit', $tour->maTour) }}" class="btn-action btn-edit">Sửa</a>
                            <form action="{{ route('admin.tours.destroy', $tour->maTour) }}" method="POST" onsubmit="return confirm('Xóa tour này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete">Xóa</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- PHÂN TRANG ĐẸP, CĂN GIỮA – DÀNH CHO TOUR -->
        @if ($tours->hasPages())
            <div class="d-flex justify-content-center mt-4">
                <nav>
                    <ul class="pagination pagination-sm">

                        <!-- Nút Previous -->
                        <li class="page-item {{ $tours->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $tours->previousPageUrl() }}" tabindex="-1">‹</a>
                        </li>

                        <!-- Các số trang -->
                        @foreach($tours->getUrlRange(1, $tours->lastPage()) as $page => $url)
                            <li class="page-item {{ $page == $tours->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach

                        <!-- Nút Next -->
                        <li class="page-item {{ $tours->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link" href="{{ $tours->nextPageUrl() }}">›</a>
                        </li>

                    </ul>
                </nav>
            </div>
        @endif
</div>
@endsection
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush