<!DOCTYPE html>
<html lang="en">
@include('layout.head')
<body class="booking-page">

  @include('layout.header')

  <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background" style="background-image: url(assets/img/travel/showcase-11.webp);">
      <div class="container position-relative">
        <h1>Đặt Tour</h1>
        <p>Bắt đầu hành trình của bạn chỉ với vài bước đơn giản.</p>
      </div>
    </div><!-- End Page Title -->

    <!-- Travel Booking Section -->
    <section id="travel-booking" class="travel-booking section">

      <div class="container">

        <div class="row">
          <div class="col-lg-8">
            <div class="booking-form">
              <form action="{{ route('dattour.store') }}" method="POST">
                  @csrf
                <div class="booking-step" id="step-2">
                  <div class="step-header">
                    <h3>Thông tin liên lạc</h3>
                    <p>Vui lòng cung cấp thông tin liên lạc của du khách chính (chỉ thay đổi khi đặt hộ)</p>
                    <input type="hidden" id="adult-input" name="nguoiLon" value="1">
                    <input type="hidden" id="child-input" name="treEm" value="0">
                    <input type="hidden" id="baby-input" name="emBe" value="0">
                    <input type="hidden" id="grand-total-input" name="tongGia" value="0">
                    <input type="hidden" name="ngayBatDau" value="{{ $tour->chuyentour->first()->ngayBatDau }}">
                    <input type="hidden" name="ngayKetThuc" value="{{ $tour->chuyentour->first()->ngayKetThuc }}">
                    <input type="hidden" name="maChuyen" id="maChuyen-input" value="">
                    <input type="hidden" name="maTour" value="{{ $tour->maTour }}">
                  </div>
                  <div class="step-content">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="HoTen">Họ Tên</label>
                          <input type="text" name="hoTen" id="HoTen" class="form-control" value="{{ Auth::check() ? Auth::user()->hoTen : '' }}" required="">
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="email">Địa Chỉ Email</label>
                          <input type="email" name="email" id="email" class="form-control" value="{{ Auth::check() ? Auth::user()->email : '' }}" required="">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="phone">Số Điện Thoại</label>
                          <input type="tel" name="phone" id="phone" class="form-control" value="{{ Auth::check() ? Auth::user()->soDienThoai : '' }}" required="">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="nationality">Địa chỉ</label>
                          <input type="text" name="address" id="address" class="form-control" value="{{ Auth::check() ? Auth::user()->diaChi : '' }}" required="">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div id="calendar" style="max-width:900px;margin:30px auto;"></div>

                <div class="booking-step d-none" id="step-3">
                  <div class="step-header">
                    <h3>Hành Khách</h3>
                    <p>Vui Lòng Nhập Số Lượng Hành Khách</p>
                  </div>
                  <div class="step-content ">
                    <div class="add-ons-grid">
                      <div class="add-on-item">
                        <div class="add-on-header">
                          <label for="travel-insurance row align-items-center">
                              <strong class="col-lg-6">Người lớn (> 18 tuổi)</strong>
                              <div class="counter col-lg-6 ">
                                <button type="button" class="btn-minus" data-target="adult">-</button>
                                <span id="adult-count">1</span>
                                <button type="button" class="btn-plus" data-target="adult">+</button>
                              </div>
                            </label>
                          </div>
                        </div>    
                        <div class="add-on-item">
                          <div class="add-on-header">
                            <label for="airport-transfer row align-items-center">
                              <strong class="col-lg-6"> Trẻ em ( 6-12 tuổi )</strong>
                              <div class="counter col-lg-6">
                                <button type="button" class="btn-minus" data-target="child">-</button>
                                <span id="child-count">0</span>
                                <button type="button" class="btn-plus" data-target="child">+</button>
                              </div>
                            </label>
                          </div>
                        </div>
                        <div class="add-on-item">
                          <div class="add-on-header">
                            <label for="airport-transfer row align-items-center">
                              <strong class="col-lg-6">Em bé (> 2 tuổi)</strong>
                              <div class="counter col-lg-6">
                                <button type="button" class="btn-minus" data-target="baby">-</button>
                                <span id="baby-count">0</span>
                                <button type="button" class="btn-plus" data-target="baby">+</button>
                              </div>
                            </label>
                          </div>
                        </div>
                      </div>  
                      <ul id="traveler-list" class="list-unstyled"></ul>
                        <template id="tpl-adult">
                          <li class="traveler-item mb-3 p-3 border rounded">
                            <h5 class="traveler-title"></h5>

                            <div class="row g-2">

                              <div class="col-5">
                                <label class="form-label mb-1">Họ tên</label>
                                <input type="text" class="form-control" name="hoTenKhach[]" placeholder="Họ tên" required>
                              </div>

                              <div class="col-2">
                                <label class="form-label mb-1">Giới tính</label>
                                <select class="form-control" name="gioiTinh[]">
                                  <option value="Nam">Nam</option>
                                  <option value="Nu">Nữ</option>
                                </select>
                              </div>

                              <div class="col-2">
                                <label class="form-label mb-1">Tuổi</label>
                                <input type="number" class="form-control" name="tuoi[]" value="18" min="12" required>
                              </div>

                              <div class="col-3">
                                <label class="form-label mb-1">Phòng đơn</label>

                                <div class="d-flex align-items-center gap-2">
                                  <!-- toggle -->
                                  <label class="switch m-0">
                                    <input type="checkbox" class="phong-don-checkbox" name="phongDon[]" value="1">
                                    <span class="slider round"></span>
                                  </label>

                                  <!-- hiển thị giá -->
                                  <span class="phong-don-price text-danger fw-bold small">
                                    {{ number_format($tour->giaPhongDon, 0, ',', '.') }} ₫
                                  </span>
                                </div>

                              </div>

                            </div>

                            <input type="hidden" name="loaiKhach[]" value="adult">
                          </li>
                        </template>
                        <template id="tpl-child">
                          <li class="traveler-item mb-3 p-3 border rounded">
                            <h5 class="traveler-title"></h5>

                            <div class="row g-2">

                              <div class="col-6">
                                <label class="form-label mb-1">Họ tên</label>
                                <input type="text" class="form-control" name="hoTenKhach[]" required>
                              </div>

                              <div class="col-3">
                                <label class="form-label mb-1">Giới tính</label>
                                <select class="form-control" name="gioiTinh[]">
                                  <option value="Nam">Nam</option>
                                  <option value="Nu">Nữ</option>
                                </select>
                              </div>

                              <div class="col-3">
                                <label class="form-label mb-1">Tuổi</label>
                                <input type="number" class="form-control" name="tuoi[]" value="7" min="2" max="12" required>
                              </div>

                            </div>

                            <input type="hidden" name="loaiKhach[]" value="child">
                            <input type="hidden" name="phongDon[]" value="0">
                          </li>
                        </template>
                        <template id="tpl-baby">
                          <li class="traveler-item mb-3 p-3 border rounded">
                            <h5 class="traveler-title"></h5>

                            <div class="row g-2">

                              <div class="col-6">
                                <label class="form-label mb-1">Họ tên</label>
                                <input type="text" class="form-control" name="hoTenKhach[]" required>
                              </div>

                              <div class="col-3">
                                <label class="form-label mb-1">Giới tính</label>
                                <select class="form-control" name="gioiTinh[]">
                                  <option value="Nam">Nam</option>
                                  <option value="Nu">Nữ</option>
                                </select>
                              </div>

                              <div class="col-3">
                                <label class="form-label mb-1">Tuổi</label>
                                <input type="number" class="form-control" name="tuoi[]" value="1" min="0" max="2" required>
                              </div>

                            </div>

                            <input type="hidden" name="loaiKhach[]" value="baby">
                            <input type="hidden" name="phongDon[]" value="0">
                          </li>
                        </template>
                  </div>
                </div>




                <div class="booking-step d-none" id="step-4">
                  <div class="step-header">
                    <h3>Phương thức thanh toán</h3>
                    <p>Vui lòng lựa chọn phương thúc thanh toán của bạn</p>
                  </div>

                  <div class="step-content">
                    <div class="payment-methods">
                      <div class="payment-method">
                        <input type="radio" name="phuongThucThanhToan" id="credit-card" value="momo" >
                        <label for="credit-card">
                          <i class="bi bi-credit-card"></i>
                          Momo
                        </label>
                      </div>
                      <div class="payment-method">
                        <input type="radio" name="phuongThucThanhToan" id="paypal" value="paypal">
                        <label for="paypal">
                          <i class="bi bi-paypal"></i>
                          PayPal
                        </label>
                      </div>
                      <div class="payment-method">
                        <input type="radio" name="phuongThucThanhToan" id="bank-transfer" value="tại văn phòng">
                        <label for="bank-transfer">
                          <i class="bi bi-bank"></i>
                          Thanh toán tại văn phòng
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="booking-step d-none" id="step-5">
                  <div class="step-header">
                    <h3>Kiểm tra &amp; Xác nhận đơn đặt</h3>
                    <p>Vui lòng xem lại thông tin đặt tour của bạn trước khi xác nhận</p>
                  </div>

                  <div class="step-content">
                  <div class="mt-4">
                      <div class="input-group input-group-lg">
                          <span class="input-group-text bg-white border-end-0">
                              <i class="bi bi-ticket-perforated-fill text-primary"></i>
                          </span>
                          <input 
                              type="text" 
                              id="promo-code-input" 
                              class="form-control border-start-0" 
                              placeholder="Nhập mã giảm giá" 
                              style="text-transform: uppercase; font-weight: 600;"
                              autocomplete="off"
                          >
                          <button 
                              type="button" 
                              id="apply-promo-btn" 
                              class="btn btn-outline-primary"
                          >
                              <span class="apply-text">Áp dụng</span>
                              <span class="applied-text d-none">
                                  <i class="bi bi-check-lg"></i> Đã áp dụng
                              </span>
                          </button>
                      </div>

                      <!-- Thông báo + Nút GỠ MÃ -->
                      <div class="form-text mt-2">
                          <small id="promo-success" class="text-success d-none">
                              <i class="bi bi-check-circle-fill"></i> 
                              Đã áp dụng mã <strong id="applied-code"></strong>
                              <button 
                                  type="button" 
                                  id="remove-promo-btn" 
                                  class="btn btn-sm btn-outline-danger ms-2 border-0"
                                  title="Gỡ mã giảm giá"
                              >
                                  <i class="bi bi-x-circle-fill"></i> Gỡ mã
                              </button>
                          </small>
                          <small id="promo-error" class="text-danger d-none"></small>
                      </div>

                      <!-- Hidden inputs gửi form -->
                      <input type="hidden" name="maKM" id="maKM-input" value="">
                      <input type="hidden" name="giaGiam" id="giaGiam-input" value="0">
                  </div>
                    <div class="terms-conditions">
                      <div class="form-check">
                        <input type="checkbox" name="terms_agreement" id="terms-agreement" class="form-check-input" required="">
                        <label for="terms-agreement" class="form-check-label">
                          Tôi đồng ý với <a href="#" target="_blank">Điều khoản và Điều kiện</a> and <a href="#" target="_blank">Chính sách bảo mật</a>
                        </label>
                      </div>
                    </div>

                    <div class="form-actions">
                      <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-circle"></i>
                        Hoàn tất Đặt Tour
                      </button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="booking-summary">
              <div class="summary-header">
                <h4>Tóm Tắt Đơn Đặt</h4>
              </div>
              <div class="summary-content">
                <div class="selected-tour">
                  <img src="{{ asset('storage/' . optional($tour->hinhanh->first())->duongDanHinh ?? 'default.jpg') }}"alt="Tour" class="img-fluid">
                  <div class="tour-info">
                    <h5>{{ $tour->tieuDe }}</h5>
                    <p>{{ $tour->thoiGian }}</p>
                  </div>
                </div>
                <div class="booking-details">
                  <div class="detail-row">
                    <span>Ngày Bắt Đầu:</span>
                    <span class="ngayBatDauDisplay">--/--/----</span>
                  </div>
                  <div class="detail-row">
                    <span>Ngày Kết Thúc:</span>
                    <span class="ngayKetThucDisplay">--/--/----</span>
                  </div>
                  <div class="detail-row">
                    <span>Mã chuyến:</span>
                    <span id="ma-chuyen-display">-</span>
                  </div>
                  <div class="detail-row">
                    <span>Số chỗ còn lại:</span>
                    <span id="so-slot-display">-</span>
                  </div>
                </div>
                <div class="price-breakdown">
                  <input type="hidden" id="adult-price" value="{{ $tour->giaTour->first()->nguoiLon }}">
                  <input type="hidden" id="child-price" value="{{ $tour->giaTour->first()->treEm }}">
                  <input type="hidden" id="baby-price" value="{{ $tour->giaTour->first()->emBe }}">
                  <input type="hidden" id="slot" value="{{ $tour->chuyentour->first()->soLuongToiDa }}">
                <h6>Chi Tiết Giá</h6>

                  <!-- Người lớn -->
                  <div class="price-row" id="adult-price-row">
                      <span>Người lớn <small class="text-muted" id="adult-count-display">× 1</small></span>
                      <span>
                          <span id="adult-unit-price"></span> × 
                          <strong id="adult-count-strong">1</strong> = 
                          <strong id="adult-total" class="text-primary"></strong>
                      </span>
                  </div>

                  <!-- Trẻ em -->
                  <div class="price-row" id="child-price-row" style="display: none;">
                      <span>Trẻ em (6-11 tuổi) <small class="text-muted" id="child-count-display"></small></span>
                      <span>
                          <span id="child-unit-price"></span> × 
                          <strong id="child-count-strong">0</strong> = 
                          <strong id="child-total" class="text-primary">0 ₫</strong>
                      </span>
                  </div>

                  <!-- Em bé -->
                  <div class="price-row" id="baby-price-row" style="display: none;">
                      <span>Em bé (2-5 tuổi) <small class="text-muted" id="baby-count-display"></small></span>
                      <span>
                          <span id="baby-unit-price"></span> × 
                          <strong id="baby-count-strong">0</strong> = 
                          <strong id="baby-total" class="text-primary">0 ₫</strong>
                      </span>
                  </div>

                  <!-- Tổng cộng -->
                  <div class="price-row fw-bold border-top pt-2 mt-2">
                      <span>Tổng tiền</span>
                      <span id="grand-total" class="fs-5 text-danger">0 ₫</span>
                  </div>

                  <!-- Giảm giá -->
                  <div class="price-row text-success fw-bold d-none" id="discount-row">
                      <span>Giảm giá:</span>
                      <span id="discount-amount">-0 ₫</span>
                  </div>

                  <!-- Tổng thanh toán -->
                  <div class="price-row border-top pt-2 mt-2 bg-light rounded px-3 py-2">
                      <span class="fs-5 fw-bold">Tổng thanh toán:</span>
                      <span id="final-total" class="fs-4 fw-bold text-danger">0 ₫</span>
                  </div>
                </div>
                <div class="payment-security">
                  <div class="security-badges">
                    <i class="bi bi-shield-check"></i>
                    <span>SSL Secured</span>
                  </div>
                  <div class="accepted-cards">
                    <i class="bi bi-credit-card"></i>
                    <span>All major cards accepted</span>
                  </div>
                </div>
              </div>

              <div class="help-section">
                <h6>Cần tư vấn?</h6>
                <p>Hãy liên hệ với chúng tôi qua</p>
                <div class="contact-info">
                  <div class="contact-item">
                    <i class="bi bi-telephone"></i>
                    <span>+1 (555) 123-4567</span>
                  </div>
                  <div class="contact-item">
                    <i class="bi bi-envelope"></i>
                    <span>support@example.com</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Travel Booking Section -->

  </main>

  @include('layout.footer')

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  @include('layout.preloader')
  <!-- ✅ Bootstrap Toast hiển thị thông báo -->
<div class="toast-container position-fixed top-0 start-50 p-3 translate-middle-x" style="z-index: 1100;">
  <div id="errorToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body" id="toastMessage">
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>
<div class="toast-container position-fixed top-0 start-50 p-3 translate-middle-x" style="z-index: 1100;">
  <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body" id="successMessage"></div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>
<script>
  // Truyền dữ liệu từ PHP sang JS toàn cục
  window.initialPrices = {
    adult: {{ $tour->giaTour->first()->nguoiLon }},
    child: {{ $tour->giaTour->first()->treEm }},
    baby: {{ $tour->giaTour->first()->emBe }},
    phongDon: {{ $tour->giaPhongDon }},
  };

  window.tourId = '{{ $tour->maTour }}';
</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {

      // --- Hiển thị lỗi (toast đỏ) ---
      @if ($errors->any())
          let errorMsg = `{!! implode('\n', $errors->all()) !!}`;
          document.getElementById('toastMessage').textContent = errorMsg;
          var errorToast = new bootstrap.Toast(document.getElementById('errorToast'));
          errorToast.show();
      @endif

      @if (session('error'))
          document.getElementById('toastMessage').textContent = "{{ session('error') }}";
          var errorToast2 = new bootstrap.Toast(document.getElementById('errorToast'));
          errorToast2.show();
      @endif


      // --- Hiển thị thành công (toast xanh lá) ---
      @if (session('success'))
          document.getElementById('successMessage').textContent = "{{ session('success') }}";
          var successToast = new bootstrap.Toast(document.getElementById('successToast'));
          successToast.show();
      @endif

  });
</script>

<script src="{{ asset('assets/js/counter.js') }}"></script>
<script src="{{ asset('assets/js/discount.js') }}"></script>

</body>

</html>