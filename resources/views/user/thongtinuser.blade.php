<!DOCTYPE html>
<html lang="en">
@include('layout.head')
<body class="contact-page">

    @include('layout.header')

  <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background" style="background-image: url(assets/img/travel/showcase-11.webp);">
      <div class="container position-relative">
        <h1>Contact</h1>
        <p>Esse dolorum voluptatum ullam est sint nemo et est ipsa porro placeat quibusdam quia assumenda numquam molestias.</p>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.html">Home</a></li>
            <li class="current">Contact</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Contact Section -->
    <section id="contact" class="contact section">

      <div class="container">
        <div class="contact-wrapper">
          <div class="contact-info-panel">
            <div class="contact-info-header">
              <h3>Thông tin người dùng</h3>
            </div>
            <div class="contact-info-cards">
              <div class="info-card">
                <div class="icon-container">
                  <i class="bi bi-person-circle"></i>
                </div>
                <div class="card-content">
                  <p>Họ và tên:</p>
                  <h4>{{ $user->tenDangNhap }}</h4>
                </div>
              </div>

              <div class="info-card">
                <div class="icon-container">
                  <i class="bi bi-envelope-open"></i>
                </div>
                <div class="card-content">
                  <p>Email</p>
                  <h4>{{ $user->email }}</h4>
                </div>
              </div>

              <div class="info-card">
                <div class="icon-container">
                  <i class="bi bi-telephone-fill"></i>
                </div>
                <div class="card-content">
                  <p>Số Điện Thoại</p>
                  <h4>{{ $user->soDienThoai }}</h4>
                </div>
              </div>

              <div class="info-card">
                <div class="icon-container">
                  <i class="bi bi-pin-map-fill"></i>
                </div>
                <div class="card-content">
                  <p>Địa Chỉ</p>
                  <h4>{{ $user->diaChi }}</h4>
                </div>
              </div>
            </div>

            <div class="social-links-panel">
              <h5>Follow Us</h5>
              <div class="social-icons">
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-twitter-x"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
                <a href="#"><i class="bi bi-linkedin"></i></a>
                <a href="#"><i class="bi bi-youtube"></i></a>
              </div>
            </div>
          </div>
          <div class="booked-tours-section mt-4">
            <h3 class="section-title">Các tour bạn đã đặt</h3>

            @forelse($datCho as $dat)
              <div class="tour-card mb-4 p-3 shadow-sm rounded">
                <h5 class="fw-bold text-danger mb-2">{{ $dat->tour->tieuDe ?? 'Tour không xác định' }}</h5>

                <p><i class="bi bi-calendar-check"></i>
                  <strong>Ngày đặt:</strong> {{ \Carbon\Carbon::parse($dat->ngayDat)->format('d/m/Y') }}
                </p>

                <p><i class="bi bi-calendar-event"></i>
                  <strong>Ngày khởi hành:</strong> {{ \Carbon\Carbon::parse($dat->ngayKhoiHanh)->format('d/m/Y') }}
                  - <strong>Ngày kết thúc:</strong> {{ \Carbon\Carbon::parse($dat->ngayKetThuc)->format('d/m/Y') }}
                </p>

                <p><i class="bi bi-people-fill"></i>
                  <strong>Người lớn:</strong> {{ $dat->soNguoiLon }}|
                  <strong>Trẻ em:</strong> {{ $dat->soTreEm }}|
                  <strong>Thiếu niên:</strong> {{ $dat->soThieuNien }}|
                  <strong>Người già:</strong> {{ $dat->soNguoiGia }}
                </p>

                <p><i class="bi bi-cash-stack"></i>
                  <strong>Tổng giá tiền:</strong>
                  <span class="text-success fw-bold">{{ number_format($dat->tongGia, 0, ',', '.') }} ₫</span>
                </p>

                <p><i class="bi bi-credit-card"></i>
                  <strong>Phương thức thanh toán:</strong> {{ ucfirst($dat->phuongThucThanhToan ?? 'Chưa xác định') }}
                </p>

                <p>
                  <i class="bi bi-check-circle"></i>
                  <strong>Trạng thái xác nhận:</strong>
                  @if($dat->xacNhan == 1)
                    <span class="text-success fw-bold">Đã xác nhận</span>
                  @else
                    <span class="text-warning fw-bold">Chưa xác nhận</span>
                  @endif
                </p>
              </div>
            @empty
              <p class="text-muted mt-3">Bạn chưa đặt tour nào.</p>
            @endforelse
          </div>
        </div>
      </div>
    </section><!-- /Contact Section -->

  </main>

@include('layout.footer')

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
    @include('layout.preloader')
</body>

</html>