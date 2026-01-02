<!DOCTYPE html>
<html lang="en">
@include('layout.head')
<body class="contact-page">

    @include('layout.header')

  <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background" style="background-image: url(assets/img/travel/showcase-11.webp);">
      <div class="container position-relative">
        <h1>Thông tin tài khoản</h1>
      </div>
    </div><!-- End Page Title -->
    
    <!-- Contact Section -->
    <section id="contact" class="contact section">

      <div class="container">
        <div class="contact-wrapper">
          <div class="contact-info-panel">
            <form id="avatarForm" action="{{ route('user.updateAvatar') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="avatar-wrapper text-center mb-3">
                    <div class="avatar-box position-relative mx-auto">
                      @php
                          $avatar = $user->avatar;

                          if ($avatar) {
                              if (filter_var($avatar, FILTER_VALIDATE_URL)) {
                                  // Avatar Google
                                  $avatarUrl = $avatar;
                              } else {
                                  // Avatar upload
                                  $avatarUrl = asset('storage/avatar-users/' . $avatar);
                              }
                          } else {
                              $avatarUrl = asset('assets/img/default-avatar.png');
                          }
                      @endphp

                      <img src="{{ $avatarUrl }}" class="avatar-img rounded-circle" alt="Avatar">

                        <!-- Nút upload -->
                        <label for="avatarInput" class="change-avatar-btn">
                            <i class="bi bi-camera-fill"></i>
                        </label>
                        <input type="file" id="avatarInput" name="avatar" class="d-none" accept="image/*">
                    </div>
                </div>

            </form>
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
                  <h4>{{ $user->hoTen }}</h4>
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
              <h5>Sửa thông tin cá nhân</h5>
              <div class="social-icons">
                <a href="#"><i class="bi bi-gear" data-bs-toggle="modal" data-bs-target="#editUserModal"></i></a>
              </div>
            </div>
            
          </div>
          <div class="booked-tours-section mt-4">
            <h3 class="section-title">Lịch sử đặt tour</h3>

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
                    @if($dat->xacNhan == 0 && $dat->ngayhethan)
                        <div class="countdown-box mt-2 bi bi-hourglass-split">
                            <strong>Thời gian còn lại để thanh toán:</strong>
                            <span id="countdown-{{ $dat->maDatCho }}" class="text-danger fw-bold"></span>
                        </div>
                    @endif
                    <a href="{{ route('user.suatourdetail.index', ['maDatCho' => $dat->maDatCho]) }}" 
                      class="btn btn-lg ">
                      <i class="bi bi-gear"></i>
                    </a>
                    <form action="{{ route('user.thongtinuser.destroy', ['maDatCho' => $dat->maDatCho]) }}" method="POST" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-lg " onclick="return confirm('Bạn có chắc chắn muốn xóa tour này không?');">
                        <i class="bi bi-trash3-fill"></i>
                      </button>
                    </form>
                    <form action="{{ route('user.thanhtoan') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="maDatCho" value="{{ $dat->maDatCho }}">
                        <input type="hidden" name="tongGia" value="{{ $dat->tongGia }}">
                        <input type="hidden" name="phuongThuc" value="{{ $dat->phuongThucThanhToan }}">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-credit-card-fill"></i>
                        </button>
                    </form>
                  </div>
                </div>
              </div>
              @empty
                <p class="text-muted mt-3">Bạn chưa đặt tour nào.</p>
            @endforelse

            <script>
                window.countdowns = [];
            </script>

            @foreach($datCho as $dat)
                @if($dat->xacNhan == 0 && $dat->ngayhethan)
                    <script>
                        window.countdowns.push({
                            id: "{{ $dat->maDatCho }}",
                            expire_at: "{{ $dat->ngayhethan }}"
                        });
                    </script>
                @endif
            @endforeach

          </div>

        </div>
        <!-- Modal chỉnh sửa thông tin người dùng -->
        <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              
              <div class="modal-header">
                <h5 class="modal-title">Cập nhật thông tin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>

              <form action="{{ route('user.suathongtinuser') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">
                  
                  <div class="mb-3">
                    <label class="form-label">Họ tên</label>
                    <input type="text" name="hoTen" class="form-control" value="{{ $user->hoTen }}" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="soDienThoai" class="form-control" value="{{ $user->soDienThoai }}">
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Địa chỉ</label>
                    <input type="text" name="diaChi" class="form-control" value="{{ $user->diaChi }}">
                  </div>

                </div>
                <a href="#"><i class="bi bi-key-fill text-primary ms-3" data-bs-toggle="modal" data-bs-target="#changePasswordModal">Đổi mật khẩu</i></a>
          
                <div class="modal-footer">
                  <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                  <button class="btn btn-primary" type="submit">Lưu thay đổi</button>
                </div>
              </form>

            </div>
          </div>
        </div>
        <!-- Modal đổi mật khẩu -->
        <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

              <div class="modal-header">
                <h5 class="modal-title">Thay đổi mật khẩu</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
              </div>

              <form action="{{ route('user.doimatkhau') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">

                  <div class="mb-3">
                    <label class="form-label">Mật khẩu hiện tại</label>
                    <input type="password" name="password_old" class="form-control" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Mật khẩu mới</label>
                    <input type="password" name="password_new" class="form-control" minlength="6" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Nhập lại mật khẩu mới</label>
                    <input type="password" name="password_new_confirmation" class="form-control" minlength="6" required>
                  </div>

                </div>

                <div class="modal-footer">
                  <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                  <button class="btn btn-primary" type="submit">Lưu thay đổi</button>
                </div>
              </form>

            </div>
          </div>
        </div>

      </div>
    </section><!-- /Contact Section -->

  </main>
<!-- Toast ERROR -->
<div class="toast-container position-fixed top-0 start-50 p-3 translate-middle-x" style="z-index: 2000;">
  <div id="errorToast" class="toast align-items-center text-white bg-danger border-0">
    <div class="d-flex">
      <div class="toast-body" id="errorMessage"></div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>

<!-- Toast SUCCESS -->
<div class="toast-container position-fixed top-0 start-50 p-3 translate-middle-x" style="z-index: 2000;">
  <div id="successToast" class="toast align-items-center text-white bg-success border-0">
    <div class="d-flex">
      <div class="toast-body" id="successMessage"></div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>

<!-- Toast WARNING -->
<div class="toast-container position-fixed top-0 start-50 p-3 translate-middle-x" style="z-index: 2000;">
  <div id="warningToast" class="toast align-items-center text-dark bg-warning border-0" data-bs-autohide="false">
    <div class="d-flex">
      <div class="toast-body fw-bold" id="warningMessage"></div>
    </div>
  </div>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // SUCCESS TOAST (toast xanh lá)
    @if(session('success'))
        document.getElementById('successMessage').textContent = "{{ session('success') }}";
        new bootstrap.Toast(document.getElementById('successToast')).show();
    @endif

    // ERROR TOAST (toast đỏ)
    @if(session('error'))
        document.getElementById('errorMessage').textContent = "{{ session('error') }}";
        new bootstrap.Toast(document.getElementById('errorToast')).show();
    @endif

    // CẢNH BÁO (toast vàng)
    @if(!empty($canhBao))
        document.getElementById('warningMessage').textContent = "{{ $canhBao }}";
        new bootstrap.Toast(document.getElementById('warningToast')).show();
    @endif

});
</script>

@include('layout.footer')

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
    @include('layout.preloader')

    

</body>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('avatarInput');
    const avatarImg = document.querySelector('.avatar-img');

    input.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                avatarImg.src = e.target.result; // preview ngay lập tức
            }
            reader.readAsDataURL(this.files[0]);

            // AUTO submit form upload
            document.getElementById('avatarForm').submit();
        }
    });
});
</script>

</html>