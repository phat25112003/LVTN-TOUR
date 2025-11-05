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
                    <input type="hidden" name="ngayKhoiHanh" value="{{ $tour->chuyentour->first()->ngayBatDau }}">
                    <input type="hidden" name="ngayKetThuc" value="{{ $tour->chuyentour->first()->ngayKetThuc }}">
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
                <script>
                  document.addEventListener('DOMContentLoaded', function() {
                    const calendarEl = document.getElementById('calendar');

                    // Giá mặc định ban đầu (từ chuyến đầu tiên)
                    let currentPrices = {
                      adult: {{ $tour->giaTour->first()->nguoiLon }},
                      child: {{ $tour->giaTour->first()->treEm }},
                      baby: {{ $tour->giaTour->first()->emBe }}
                    };

                    // Số lượng hành khách
                    let counts = {
                      adult: 1,
                      child: 0,
                      baby: 0
                    };

                    // Cập nhật tổng tiền
                    function updateTotal() {
                      const total = 
                        counts.adult * currentPrices.adult +
                        counts.child * currentPrices.child +
                        counts.baby * currentPrices.baby;

                      document.getElementById('adult-total').textContent = formatCurrency(counts.adult * currentPrices.adult);
                      document.getElementById('child-total').textContent = formatCurrency(counts.child * currentPrices.child);
                      document.getElementById('baby-total').textContent = formatCurrency(counts.baby * currentPrices.baby);
                      document.getElementById('grand-total').textContent = formatCurrency(total);

                      // Cập nhật hidden inputs
                      document.getElementById('adult-input').value = counts.adult;
                      document.getElementById('child-input').value = counts.child;
                      document.getElementById('baby-input').value = counts.baby;
                      document.getElementById('grand-total-input').value = total;
                    }

                    // Format tiền tệ
                    function formatCurrency(amount) {
                      return new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                      }).format(amount);
                    }

                    // Khởi tạo FullCalendar
                    const calendar = new FullCalendar.Calendar(calendarEl, {
                      initialView: 'dayGridMonth',
                      locale: 'vi',
                      timeZone: 'local',
                      height: 'auto',
                      events: '/api/tour-dates/{{ $tour->maTour }}',
                      eventClick: function(info) {
                        const props = info.event.extendedProps;

                        // Cập nhật giá hiện tại từ chuyến được chọn
                        currentPrices = {
                          adult: props.giaNguoiLon,
                          child: props.giaTreEm,
                          baby: props.giaEmBe
                        };

                        // Cập nhật ngày
                        document.querySelector('input[name="ngayKhoiHanh"]').value = info.event.startStr;
                        document.querySelector('input[name="ngayKetThuc"]').value = props.ngayKetThuc;

                        document.querySelector('.booking-details .detail-row:nth-child(1) span:last-child').textContent = info.event.startStr;
                        document.querySelector('.booking-details .detail-row:nth-child(2) span:last-child').textContent = props.ngayKetThuc;

                        // Cập nhật lại tổng tiền với giá mới
                        updateTotal();

                        // Highlight event đã chọn (tùy chọn)
                        calendar.getEvents().forEach(ev => ev.setProp('backgroundColor', ''));
                        info.event.setProp('backgroundColor', '#007bff');
                      }
                    });

                    calendar.render();

                    // Xử lý nút + / - số lượng hành khách
                    document.querySelectorAll('.btn-plus, .btn-minus').forEach(btn => {
                      btn.addEventListener('click', function() {
                        const target = this.getAttribute('data-target');
                        const change = this.classList.contains('btn-plus') ? 1 : -1;

                        if (counts[target] + change >= 0) {
                          counts[target] += change;
                          document.getElementById(target + '-count').textContent = counts[target];
                          updateTotal();
                        }
                      });
                    });

                    // Khởi tạo tổng tiền lần đầu
                    updateTotal();
                  });
                </script>
                <div class="booking-step" id="step-3">
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
                  </div>
                </div>
                <div class="booking-step" id="step-4">
                  <div class="step-header">
                    <h3>Phương thức thanh toán</h3>
                    <p>Vui lòng lựa chọn phương thúc thanh toán của bạn</p>
                  </div>

                  <div class="step-content">
                    <div class="payment-methods">
                      <div class="payment-method active">
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
                <div class="booking-step" id="step-5">
                  <div class="step-header">
                    <h3>Kiểm tra &amp; Xác nhận đơn đặt</h3>
                    <p>Vui lòng xem lại thông tin đặt tour của bạn trước khi xác nhận</p>
                  </div>

                  <div class="step-content">
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

          <div class="col-lg-4">
            <div class="booking-summary">
              <div class="summary-header">
                <h4>Tóm Tắt Đơn Đặt</h4>
              </div>
              <div class="summary-content">
                <div class="selected-tour">
                  <img src="{{ asset('storage/' . $tour->hinhanh->first()->duongDanHinh) }}" alt="Tour" class="img-fluid">
                  <div class="tour-info">
                    <h5>{{ $tour->tieuDe }}</h5>
                    <p>{{ $tour->thoiGian }}</p>
                    <div class="tour-rating">
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-half"></i>
                      <span>4.8 (324 reviews)</span>
                    </div>
                  </div>
                </div>
                <div class="booking-details">
                  <div class="detail-row">
                    <span>Ngày Bắt Đầu:</span>
                    <span>--/--/----</span>
                  </div>
                  <div class="detail-row">
                    <span>Ngày Kết Thúc:</span>
                    <span>--/--/----</span>
                  </div>
                </div>
                <div class="price-breakdown">
                  <input type="hidden" id="adult-price" value="{{ $tour->giaTour->first()->nguoiLon }}">
                  <input type="hidden" id="child-price" value="{{ $tour->giaTour->first()->treEm }}">
                  <input type="hidden" id="baby-price" value="{{ $tour->giaTour->first()->emBe }}">
                  <input type="hidden" id="slot" value="{{ $tour->chuyentour->first()->soLuongToiDa }}">
                  <h6>Chi Tiết Giá</h6>
                  <div class="price-row">
                    <span>Giá người lớn</span>
                    <span id="adult-total"></span>
                  </div>


                  <div class="price-row">
                    <span>Giá trẻ em</span>
                    <span id="child-total"></span>
                  </div>
                  <div class="price-row">
                    <span>Giá em bé</span>
                    <span id="baby-total"></span>
                  </div>
                  <!-- <div class="price-row">
                    <span>Travel Insurance</span>
                    <span>$89</span>
                  </div>
                  <div class="price-row">
                    <span>Airport Transfer</span>
                    <span>$45</span>
                  </div>
                  <div class="price-row">
                    <span>Taxes &amp; Fees</span>
                    <span>$156</span>
                  </div> -->
                  <div class="price-total">
                    <span>Tổng tiền</span>
                    <span id="grand-total"></span>
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
                <h6>Need Help?</h6>
                <p>Our travel experts are here to assist you</p>
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

</body>

</html>