{{-- resources/views/admin/huongdanvien/index.blade.php --}}
@extends('admin.layouts.dashboard')

@section('content')
    <div class="admin-main-container">
        <h3 class="text-center mb-4 fw-bold text-primary">Quản Lý Hướng Dẫn Viên</h3>

        @if (session('success'))
            <div class="notify notify-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('admin.huongdanvien.create') }}" class="add-btn">+ Thêm HDV</a>

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
                            <td>{{ $hdv->email ?? '<em class="text-muted">—</em>' }}</td>
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
        </div>

        <!-- PHÂN TRANG GIỐNG HỆT -->
        @if($hdvs->hasPages())
            <div class="pagination-container">
                {{ $hdvs->appends(request()->query())->links('pagination::simple-default') }}
            </div>
        @endif
    </div>
@endsection