<!DOCTYPE html>
<html lang="vi">

@include('layout.head')
<body class="starter-page-page">

  @include('layout.header')

  <main class="main">
    <!-- Page Title -->
    <div class="page-title dark-background" style="background-image: url(assets/img/travel/showcase-11.webp);">
      <div class="container position-relative">
        <h1>Đăng ký tài khoản</h1>
        <p>Tạo tài khoản để đặt tour và nhận nhiều ưu đãi hấp dẫn.</p>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="current">Đăng ký</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Register Section -->
    <section id="register-section" class="section light-background">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-6 col-md-8">
            <div class="login-card">

              <h2 class="text-center mb-4">Tạo tài khoản mới</h2>
              <p class="text-center mb-4 text-muted">Điền thông tin bên dưới để đăng ký</p>

              <form action="{{ route('user.dangky.post') }}" method="POST">
                @csrf

                <!-- Tên đăng nhập -->
                <div class="form-group mb-3">
                  <label for="tenDangNhap" class="form-label">Tên đăng nhập</label>
                  <input type="text" name="tenDangNhap" id="tenDangNhap" class="form-control" placeholder="Nhập tên đăng nhập" required>
                </div>

                <!-- Họ và tên -->
                <div class="form-group mb-3">
                  <label for="hoTen" class="form-label">Họ và tên</label>
                  <input type="text" name="hoTen" id="hoTen" class="form-control" placeholder="Nhập họ và tên đầy đủ" required>
                </div>

                <!-- Mật khẩu -->
                <div class="form-group mb-3">
                  <label for="matKhau" class="form-label">Mật khẩu</label>
                  <input type="password" name="matKhau" id="matKhau" class="form-control" placeholder="••••••" required>
                </div>
                
                <!-- Xác nhận mật khẩu -->
                <div class="form-group mb-3">
                <label for="matKhau_confirmation" class="form-label">Xác nhận mật khẩu</label>
                <input type="password" name="matKhau_confirmation" id="matKhau_confirmation" class="form-control" placeholder="Nhập lại mật khẩu" required>
                </div>

                <!-- Email -->
                <div class="form-group mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" name="email" id="email" class="form-control" placeholder="Nhập email của bạn" required>
                </div>

                <!-- Số điện thoại -->
                <div class="form-group mb-3">
                  <label for="soDienThoai" class="form-label">Số điện thoại</label>
                  <input type="tel" name="soDienThoai" id="soDienThoai" class="form-control" placeholder="Nhập số điện thoại" required>
                </div>

                <!-- Địa chỉ -->
                <div class="form-group mb-3">
                  <label for="diaChi" class="form-label">Địa chỉ</label>
                  <input type="text" name="diaChi" id="diaChi" class="form-control" placeholder="Nhập địa chỉ" required>
                </div>

                <!-- Giới tính -->
                <div class="form-group mb-3">
                  <label class="form-label d-block">Giới tính</label>
                  <div class="form-check form-check-inline">
                    <input type="radio" name="gioiTinh" id="nam" value="Nam" class="form-check-input" checked>
                    <label for="nam" class="form-check-label">Nam</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input type="radio" name="gioiTinh" id="nu" value="Nữ" class="form-check-input">
                    <label for="nu" class="form-check-label">Nữ</label>
                  </div>
                </div>
                <!-- Submit -->
                <button type="submit" class="btn btn-primary w-100">Đăng ký</button>

                <div class="text-center mt-4">
                  <p>Đã có tài khoản? <a href="{{ route('user.login') }}">Đăng nhập ngay</a></p>
                </div>
              </form>
@if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
          </div>
        </div>
      </div>
    </section>

  </main>

  @include('layout.footer')
  @include('layout.preloader')

</body>
</html>
