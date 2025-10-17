@extends('admin.layouts.dashboard')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Quản lý Người Dùng</h2>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
        </div>
    </div>

    <div class="row">
        @foreach ($nguoiDungs as $nguoiDung)
            <div class="col-md-4 col-sm-6">
                <div class="card user-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3 text-center">
                                <img src="{{ $nguoiDung->avatar ?: '/images/avatars/default.jpg' }}" alt="Avatar" class="avatar-user">
                            </div>
                            <div class="col-9">
                                <h5 class="card-title">{{ $nguoiDung->tenDangNhap }}</h5>
                                <p class="card-text"><small class="text-muted">Email: {{ $nguoiDung->email }}</small></p>
                                <p class="card-text"><small class="text-muted">SĐT: {{ $nguoiDung->soDienThoai }}</small></p>
                                <p class="card-text"><small class="text-muted">Địa chỉ: {{ $nguoiDung->diaChi }}</small></p>
                                <p class="card-text"><small class="text-muted">Giới tính: {{ $nguoiDung->gioiTinh ?: 'Chưa cập nhật' }}</small></p>
                                <span class="status-badge {{ isset($nguoiDung->tinhTrang) && $nguoiDung->tinhTrang ? 'status-active' : 'status-blocked' }}">
                                    {{ isset($nguoiDung->tinhTrang) && $nguoiDung->tinhTrang ? 'Kích hoạt' : 'Chặn' }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-sm btn-warning" onclick="toggleStatus('{{ json_encode($nguoiDung->maNguoiDung) }}', {{ isset($nguoiDung->tinhTrang) ? $nguoiDung->tinhTrang : 0 }})">
                                {{ isset($nguoiDung->tinhTrang) && $nguoiDung->tinhTrang ? 'Chặn' : 'Kích hoạt' }}
                            </button>
                            <form action="{{ route('admin.nguoidung.destroy', $nguoiDung->maNguoiDung) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa?')">
                                    Xóa
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    function toggleStatus(maNguoiDung, tinhTrangHienTai) {
        const newStatus = tinhTrangHienTai === 1 ? 0 : 1;
        fetch('/admin/nguoidung/' + maNguoiDung + '/status', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ tinhTrang: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Cập nhật thất bại!');
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
@endsection