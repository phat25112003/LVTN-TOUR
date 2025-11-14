{{-- resources/views/admin/huongdanvien/edit.blade.php --}}
@extends('admin.layouts.dashboard')

@section('content')
<div class="admin-main-container">
    <h3 class="text-center mb-4 fw-bold text-primary">Chỉnh Sửa Hướng Dẫn Viên</h3>

    @if ($errors->any())
        <div class="notify notify-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.huongdanvien.update', $hdv->maHDV) }}" method="POST" enctype="multipart/form-data" class="admin-form">
        @csrf @method('PUT')

        <div class="form-row">
            <div class="form-group">
                <label>Họ tên <span class="required">*</span></label>
                <input type="text" name="hoTen" class="form-control" value="{{ old('hoTen', $hdv->hoTen) }}" required>
            </div>
            <div class="form-group">
                <label>Số điện thoại <span class="required">*</span></label>
                <input type="text" name="soDienThoai" class="form-control" value="{{ old('soDienThoai', $hdv->soDienThoai) }}" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $hdv->email) }}">
            </div>
            <div class="form-group">
                <label>Địa chỉ</label>
                <input type="text" name="diaChi" class="form-control" value="{{ old('diaChi', $hdv->diaChi) }}">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Số CCCD</label>
                <input type="text" name="soCCCD" class="form-control" value="{{ old('soCCCD', $hdv->soCCCD) }}" maxlength="20">
            </div>
            <div class="form-group">
                <label>Ngày sinh</label>
                <input type="date" 
                name="ngaySinh" 
                class="form-control" 
                value="{{ old('ngaySinh', $hdv->ngaySinh ? \Carbon\Carbon::parse($hdv->ngaySinh)->format('Y-m-d') : '') }}">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Giới tính <span class="required">*</span></label>
                <select name="gioiTinh" class="form-control" required>
                    <option value="Nam" {{ old('gioiTinh', $hdv->gioiTinh) == 'Nam' ? 'selected' : '' }}>Nam</option>
                    <option value="Nu" {{ old('gioiTinh', $hdv->gioiTinh) == 'Nu' ? 'selected' : '' }}>Nữ</option>
                </select>
            </div>

            <div class="form-group">
                <label>Trạng thái <span class="required">*</span></label>
                <select name="trangThai" class="form-control" required>
                    <option value="HoatDong" {{ old('trangThai', $hdv->trangThai) == 'HoatDong' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="NgungHoatDong" {{ old('trangThai', $hdv->trangThai) == 'NgungHoatDong' ? 'selected' : '' }}>Ngừng hoạt động</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Ghi chú <span class="required">*</span></label>
            <textarea name="ghiChu" 
                class="form-control" 
                rows="4" 
                required 
                placeholder="Nhập ghi chú...">{{ old('ghiChu', $hdv->ghiChu) }}</textarea>
        </div>

        <div class="form-group">
            <div>
                <label>Ảnh đại diện hiện tại</label>
            </div>
            <div>
                @if($hdv->avatar)
                    <img src="{{ $hdv->avatar_url }}" width="100" class="rounded-circle" alt="Avatar">
                @else
                    <em class="text-muted">Chưa có ảnh</em>
                @endif
            </div>
            <input type="file" name="avatar" class="form-control mt-2" accept="image/*">
            <small class="text-muted">Để trống nếu không muốn thay đổi</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-confirm">Cập nhật</button>
            <a href="{{ route('admin.huongdanvien.index') }}" class="btn-cancel">Hủy</a>
        </div>
    </form>
</div>
@endsection

{{-- DÙNG LẠI CSS TỪ create.blade.php --}}
@push('styles')
<style>
    .admin-form { max-width: 800px; margin: 0 auto; background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 2px 15px rgba(0,0,0,0.1); }
    .form-row { display: flex; gap: 15px; margin-bottom: 15px; }
    .form-row .form-group { flex: 1; }
    .form-group label { display: block; margin-bottom: 6px; font-weight: 600; color: #333; }
    .form-group .required { color: #e74c3c; }
    .form-control { width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }
    .form-control:focus { border-color: #007bff; outline: none; box-shadow: 0 0 0 3px rgba(0,123,255,0.1); }
    .form-actions { margin-top: 25px; text-align: center; }
    .btn-confirm, .btn-cancel { padding: 10px 25px; margin: 0 8px; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; text-decoration: none; display: inline-block; }
    .btn-confirm { background: #28a745; color: #fff; }
    .btn-confirm:hover { background: #218838; }
    .btn-cancel { background: #6c757d; color: #fff; }
    .btn-cancel:hover { background: #5a6268; }
    .notify { padding: 12px 15px; border-radius: 6px; margin-bottom: 20px; }
    .notify-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
</style>
@endpush