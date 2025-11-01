@extends('admin.layouts.dashboard')

@section('content')
    <div class="admin-main-container">
        <h2 class="promo-page-title">Quản lý Khuyến Mãi</h2>

        @if (session('success'))
            {{-- Dùng class notify-success theo mẫu --}}
            <div class="notify notify-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('admin.khuyenmai.create') }}" class="add-btn">+ Thêm Khuyến Mãi</a>
        
        {{-- Đổi tên class bao ngoài bảng --}}
        <div class="admin-card">
            <table class="promo-admin-table">
                <thead>
                    <tr>
                        <th>Mã Khuyến Mãi</th>
                        <th>Tên Khuyến Mãi</th>
                        <th>Mức Giảm</th>
                        <th>Thời Gian</th>
                        <th>Tên Tour Áp Dụng</th>
                        <th>Trạng Thái</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($khuyenMais as $km)
                        <tr>
                            <td>{{ $km->maKhuyenMai }}</td>
                            <td>{{ $km->tenKhuyenMai }}</td>
                            <td>{{ $km->mucGiam }} {{ $km->loaiGiam === 'Phan tram' ? '%' : 'VND' }}</td>
                            <td>{{ $km->ngayBatDau }} - {{ $km->ngayKetThuc }}</td>
                            <td>
                                @if ($km->apDungTatCaTour)
                                    Áp dụng cho tất cả tour
                                @else
                                    {{ $km->tour ? $km->tour->tieuDe : 'Không áp dụng' }}
                                @endif
                            </td>
                            <td>
                                {{-- Dùng class status-success/status-pending theo mẫu --}}
                                <span class="status-badge {{ $km->tinhTrang ? 'status-active' : 'status-inactive' }}">
                                    {{ $km->tinhTrang ? 'Kích hoạt' : 'Ngưng' }}
                                </span>
                            </td>
                            <td class="action-buttons">
                                {{-- Sử dụng class btn-confirm cho nút hành động --}}
                                <button class="toggle-status action-button btn-confirm {{ $km->tinhTrang ? '' : 'btn-activate' }}" 
                                        data-id="{{ $km->id }}" 
                                        data-status="{{ $km->tinhTrang }}">
                                    {{ $km->tinhTrang ? 'Ngưng' : 'Kích hoạt' }}
                                </button>

                                <a href="{{ route('admin.khuyenmai.edit', $km->id) }}" class="btn-action btn-edit">Sửa</a>

                                
                                <form action="{{ route('admin.khuyenmai.destroy', $km->id) }}" method="POST" class="inline-form" onsubmit="return confirm('Bạn có chắc muốn xóa?');">
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
    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.toggle-status').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const id = this.getAttribute('data-id');
                    const currentStatus = this.getAttribute('data-status') === '1';
                    const tinhTrang = currentStatus ? '0' : '1'; 
                    const buttonText = tinhTrang === '1' ? 'Ngưng' : 'Kích hoạt';

                    fetch(`{{ route('admin.khuyenmai.toggle-status', ['id' => ':id']) }}`.replace(':id', id), {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ tinhTrang })
                    })
                    .then(response => {
                        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            this.setAttribute('data-status', tinhTrang);
                            this.textContent = buttonText;
                            
                            // 🚦 CẬP NHẬT MÀU NÚT 🚦
                            if (tinhTrang === '1') {
                                // Trạng thái MỚI là KÍCH HOẠT -> nút hiển thị 'Ngưng' (Màu mặc định btn-confirm)
                                this.classList.remove('btn-activate');
                            } else {
                                // Trạng thái MỚI là Ngưng -> nút hiển thị 'Kích hoạt' (Thêm màu kích hoạt)
                                this.classList.add('btn-activate');
                            }
                            
                            // Cập nhật thẻ trạng thái (badge)
                            const badge = this.closest('tr').querySelector('.status-badge');
                            badge.className = 'status-badge status ' + (tinhTrang === '1' ? 'status-active' : 'status-inactive');
                            badge.textContent = tinhTrang === '1' ? 'Kích hoạt' : 'Ngưng';
                            
                            alert(data.message);
                        } else {
                            alert('Cập nhật thất bại!');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Lỗi kết nối!');
                    });
                });
            });
        });
    </script>
@endpush
