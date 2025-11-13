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
            @if (session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
              <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if (!empty($canhBao))
              <div class="alert alert-warning">
                {{ $canhBao }}
              </div>
            @endif
            <h3 class="section-title">Các tour bạn đã đặt</h3>

            @forelse($datCho as $index => $dat)
              <div class="tour-card mb-3 shadow-sm rounded">
                <div class="card-header bg-light">
                  <a class="fw-bold text-danger text-decoration-none d-block mb-2" 
                    data-bs-toggle="collapse" 
                    href="#tourCollapse{{ $index }}" 
                    role="button" 
                    aria-expanded="false" 
                    aria-controls="tourCollapse{{ $index }}">
                    {{ $dat->tour->tieuDe ?? 'Tour không xác định' }}
                    <i class="bi bi-chevron-down float-end"></i>
                  </a>
                </div>

                <div class="collapse" id="tourCollapse{{ $index }}">
                  <div class="card-body">
                    <p><i class="bi bi-calendar-check"></i>
                      <strong>Ngày đặt:</strong> {{ \Carbon\Carbon::parse($dat->ngayDat)->format('d/m/Y H:i') }}
                    </p>

                    <p><i class="bi bi-calendar-event"></i>
                      <strong>Ngày khởi hành:</strong> {{ \Carbon\Carbon::parse($dat->chuyenTour->ngayBatDau)->format('d/m/Y') }}
                      - <strong>Ngày kết thúc:</strong> {{ \Carbon\Carbon::parse($dat->chuyenTour->ngayKetThuc)->format('d/m/Y') }}
                    </p>

                    <p><i class="bi bi-people-fill"></i>
                      <strong>Người lớn:</strong> {{ $dat->soNguoiLon }} |
                      <strong>Trẻ em:</strong> {{ $dat->soTreEm }} |
                      <strong>Em Bé:</strong> {{ $dat->soEmBe }} |
                    </p>

                    <p><i class="bi bi-cash-stack"></i>
                      <strong>Tổng giá tiền:</strong>
                      <span class="text-success fw-bold">{{ number_format($dat->tongGia, 0, ',', '.') }} ₫</span>
                    </p>

                    <p><i class="bi bi-credit-card"></i>
                      <strong>Phương thức thanh toán:</strong> {{ ucfirst($dat->phuongThucThanhToan ?? 'Chưa xác định') }}
                    </p>

                    <p><i class="bi bi-check-circle"></i>
                      <strong>Trạng thái xác nhận:</strong>
                      @if($dat->xacNhan == 1)
                        <span class="text-success fw-bold">Đã xác nhận</span>
                      @else
                        <span class="text-warning fw-bold">Chưa xác nhận</span>
                      @endif
                    </p>
                    <a href="{{ route('user.suatourdetail.index', ['maDatCho' => $dat->maDatCho]) }}" 
                      class="btn btn-lg ">
                      <i class="bi bi-gear"></i>
                    </a>
                    <form action="{{ route('user.thongtinuser.destroy', ['maDatCho' => $dat->maDatCho]) }}" method="POST" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-lg " onclick="return confirm('Bạn có chắc chắn muốn xóa tour này không?');">
                        <i class="bi bi-trash3-fill"></i>
                      </button>
                    </form>
                  </div>
                </div>
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