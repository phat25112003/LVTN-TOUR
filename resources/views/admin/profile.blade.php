@extends('admin.layouts.dashboard')

@section('content')
<div class="container mt-4">
    <h3 class="text-center mb-4 fw-bold text-primary">Thông tin cá nhân</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-4 text-center">
            <img src="{{ $admin->avatar ? asset('storage/avatars/'.$admin->avatar) : asset('images/avatars/default.jpg') }}"
                 class="rounded-circle mb-3 border" width="150" height="150" style="object-fit: cover;">
        </div>

        <div class="col-md-8">
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Tên đăng nhập</label>
                    <input type="text" name="tenDangNhap" class="form-control"
                        value="{{ old('tenDangNhap', $admin->tenDangNhap) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                        value="{{ old('email', $admin->email) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="soDienThoai" class="form-control"
                        value="{{ old('soDienThoai', $admin->soDienThoai) }}">
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label text-danger fw-bold">Mật khẩu hiện tại <span class="text-muted">(bắt buộc nếu đổi mật khẩu)</span></label>
                        <input type="password" name="matKhauHienTai" class="form-control" placeholder="Nhập mật khẩu hiện tại">
                        @error('matKhauHienTai')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mật khẩu mới</label>
                        <input type="password" name="matKhau" class="form-control" placeholder="Nhập mật khẩu mới">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Xác nhận mật khẩu</label>
                        <input type="password" name="matKhau_confirmation" class="form-control" placeholder="Nhập lại mật khẩu mới">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ảnh đại diện</label>
                    <input type="file" name="avatar" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary px-4">Lưu thay đổi</button>
            </form>
        </div>
    </div>
</div>
@endsection
