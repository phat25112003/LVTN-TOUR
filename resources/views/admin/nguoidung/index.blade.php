@extends('admin.layouts.dashboard')

@section('content')
    <div class="container">
        <h2 class="text-center mb-4 fw-bold text-primary">Quản lý Người Dùng</h2>

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="user-grid">
            @foreach ($nguoiDungs as $nguoiDung)
                <div class="user-card">
                    <div class="user-info">
                        <div class="avatar-container">

                        <img src="{{ $nguoiDung->avatar 
                                ? asset('storage/avatar-users/' . $nguoiDung->avatar) 
                                : asset('images/avatars/default.jpg') }}" 
                            alt="Avatar" 
                            class="avatar-user">
                        </div>
                        <div class="user-details">
                            <h5>{{ $nguoiDung->hoTen }}</h5>
                            <p>Email: {{ $nguoiDung->email }}</p>
                            <p>SĐT: {{ $nguoiDung->soDienThoai }}</p>
                            <p>Địa chỉ: {{ $nguoiDung->diaChi }}</p>
                            <p>Giới tính: {{ $nguoiDung->gioiTinh ?: 'Chưa cập nhật' }}</p>
                            <span class="status-badge {{ $nguoiDung->tinhTrang ? 'status-active' : 'status-blocked' }}">
                                {{ $nguoiDung->tinhTrang ? 'Kích hoạt' : 'Chặn' }}
                            </span>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button class="toggle-status btn-sm 
                            {{ $nguoiDung->tinhTrang ? '' : 'status-inactive' }}" 
                            data-id="{{ $nguoiDung->maNguoiDung }}" 
                            data-status="{{ $nguoiDung->tinhTrang }}">
                            {{ $nguoiDung->tinhTrang ? 'Chặn' : 'Kích hoạt' }}
                        </button>
                        <form action="{{ route('admin.nguoidung.destroy', $nguoiDung->maNguoiDung) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete btn-sm">Xóa</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.toggle-status').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const maNguoiDung = this.getAttribute('data-id');
                    // Lấy trạng thái MỚI (0 nếu hiện tại là 1, 1 nếu hiện tại là 0)
                    const tinhTrang = this.getAttribute('data-status') == 1 ? 0 : 1; 
                    const buttonText = tinhTrang == 1 ? 'Chặn' : 'Kích hoạt';

                    const url = `/admin/nguoidung/${maNguoiDung}/update-status`;

                    fetch(url, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            // Đảm bảo thẻ meta CSRF có tồn tại
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({ tinhTrang })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // 1. Cập nhật thuộc tính data-status
                            this.setAttribute('data-status', tinhTrang);
                            // 2. Cập nhật nội dung nút
                            this.textContent = buttonText;

                            // 3. 🟡 THAO TÁC CLASS ĐỂ ĐỔI MÀU NÚT 🟡
                            if (tinhTrang == 0) {
                                // Người dùng bị CHẶN -> nút hiển thị "Kích hoạt" (Màu vàng)
                                this.classList.add('status-inactive');
                            } else {
                                // Người dùng được KÍCH HOẠT -> nút hiển thị "Chặn" (Màu mặc định/Đỏ)
                                this.classList.remove('status-inactive');
                            }
                            // ----------------------------------------------------

                            // 4. Cập nhật status badge
                            const badge = this.closest('.user-card').querySelector('.status-badge');
                            badge.className = 'status-badge ' + (tinhTrang ? 'status-active' : 'status-blocked');
                            badge.textContent = tinhTrang ? 'Kích hoạt' : 'Chặn';
                            
                            alert(data.message);
                        } else {
                            alert('Cập nhật thất bại!');
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        alert('Lỗi kết nối, vui lòng thử lại!');
                    });
                });
            });
        });
    </script>
@endpush
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush