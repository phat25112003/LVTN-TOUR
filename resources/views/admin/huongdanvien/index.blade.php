{{-- resources/views/admin/huongdanvien/index.blade.php --}}
@extends('admin.layouts.dashboard')

@section('content')
    <div class="admin-main-container">
        <h3 class="text-center mb-4 fw-bold text-primary">Quản Lý Hướng Dẫn Viên</h3>

        @if (session('success'))
            <div class="notify notify-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('admin.huongdanvien.create') }}" class="add-btn">+ Thêm HDV</a>
        <!-- TÌM KIẾM THEO TÊN -->
        <form action="{{ route('admin.huongdanvien.index') }}" method="GET" class="d-flex">
            <div class="input-group" style="max-width: 350px;">
                <input type="text" name="search" class="form-control" placeholder="Tìm theo họ tên..."
                       value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit">
                    <i class="fa-solid fa-search"></i>
                </button>
            </div>
            @if(request('search'))
                <a href="{{ route('admin.huongdanvien.index') }}" class="btn btn-secondary ms-2">
                    <i class="fa-solid fa-times"></i>
                </a>
            @endif
        </form>

        <!-- BẢNG GIỐNG HỆT KHUYẾN MÃI -->
        <div class="admin-card">
            <table class="promo-admin-table">
                <thead>
                    <tr>
                        <th>Ảnh</th>
                        <th>Họ tên</th>
                        <th>SĐT</th>
                        <th>Email</th>
                        <th>Trạng thái</th>
                        <th>Số chuyến</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hdvs as $hdv)
                        <tr>              
                            <td class="text-center">
                                <img src="{{ $hdv->avatar_url }}" 
                                    class="rounded-circle border shadow-sm" 
                                    width="120" height="120" 
                                    style="object-fit: cover;"
                                    alt="{{ $hdv->hoTen }}">
                            </td>
                            <td><strong>{{ $hdv->hoTen }}</strong></td>
                            <td>{{ $hdv->soDienThoai }}</td>
                            <td>{{ $hdv->email ?? 'Chưa có' }}</td>
                            <td>
                                <span class="status-badge {{ $hdv->trangThai == 'HoatDong' ? 'status-active' : 'status-inactive' }}">
                                    {{ $hdv->trangThai == 'HoatDong' ? 'Hoạt động' : 'Nghỉ' }}
                                </span>
                            </td>
                            <td>
                                <span class="">{{ $hdv->chuyenTours->count() }}</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.huongdanvien.show', $hdv->maHDV) }}" 
                                    class="btn-action btn-view">Xem</a>
                                    <a href="{{ route('admin.huongdanvien.edit', $hdv->maHDV) }}" 
                                    class="btn-action btn-edit">Sửa</a>

                                    <form action="{{ route('admin.huongdanvien.destroy', $hdv->maHDV) }}" 
                                        method="POST" 
                                        class="inline-form" 
                                        onsubmit="return confirm('Bạn có chắc muốn xóa HDV này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                Chưa có hướng dẫn viên nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <!-- PHÂN TRANG ĐẸP, CĂN GIỮA (GIỐNG TRANG ĐỊA ĐIỂM) -->
            <div class="d-flex justify-content-center mt-4">
                <nav>
                    <ul class="pagination pagination-sm">
                        <!-- Nút Previous -->
                        <li class="page-item {{ $hdvs->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $hdvs->previousPageUrl() }}" tabindex="-1">‹</a>
                        </li>

                        <!-- Các số trang -->
                        @foreach($hdvs->getUrlRange(1, $hdvs->lastPage()) as $page => $url)
                            <li class="page-item {{ $page == $hdvs->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach

                        <!-- Nút Next -->
                        <li class="page-item {{ $hdvs->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link" href="{{ $hdvs->nextPageUrl() }}">›</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush