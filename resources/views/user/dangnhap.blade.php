<!DOCTYPE html>
<html lang="en">

@include('layout.head')
<body class="starter-page-page">

  @include('layout.header')
  <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background" style="background-image: url('{{ asset('assets/img/travel/showcase-11.webp') }}');">
      <div class="container position-relative">
        <h1>Đăng nhập</h1>
        <p>Đăng nhập để tiếp tục hành trình khám phá của bạn</p>
      </div>
    </div><!-- End Page Title -->
<!-- Login Section -->
<section id="login-section" class="section light-background">
  <div class="container">
    <div class="row justify-content-center">

      <div class="col-lg-5 col-md-7">
        <div class="login-card">

          <h2 class="text-center mb-4">Chào mừng trở lại!</h2>
          <p class="text-center mb-4 text-muted">Đăng nhập vào tài khoản của bạn</p>

          <form action="{{ route('user.login.post') }}" method="POST">
            @csrf

            <div class="form-group mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" name="email" id="email" class="form-control" placeholder="Nhập email của bạn" required>
            </div>

            <div class="form-group mb-3">
              <label for="password" class="form-label">Mật khẩu</label>
              <input type="password" name="password" id="password" class="form-control" placeholder="••••••" required>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
              <div class="form-check">
                <input type="checkbox" id="remember" name="remember" class="form-check-input">
                <label for="remember" class="form-check-label">Ghi nhớ đăng nhập</label>
              </div>
              <a href="#" class="forgot-password">Quên mật khẩu?</a>
            </div>

            <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>

            <div class="text-center mt-4">
              <p>Chưa có tài khoản? <a href="{{ route('user.dangky') }}">Đăng ký ngay</a></p>
            </div>
            <div class="text-center mt-3">
              <a href="{{ route('google.login') }}" class="btn btn-danger w-100">Đăng nhập với Google</a>
            </div>

          </form>

        </div>
      </div>
    </div>
  </div>
</section>

  </main>

@include('layout.footer')
<!-- Toast Notification -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1100">
    <div id="errorToast" class="toast text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-danger text-white">
            <strong class="me-auto">Thông báo</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            {{ session('error') }}
        </div>
    </div>
</div>

  <!-- Scroll Top -->
@include('layout.preloader')
</body>
<script>
document.addEventListener('DOMContentLoaded', function () {
    @if (session('error'))
        const toastEl = document.getElementById('errorToast');
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    @endif
});
</script>


</html>