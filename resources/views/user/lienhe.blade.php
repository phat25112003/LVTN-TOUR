<!DOCTYPE html>
<html lang="en">

@include('layout.head')

<body class="contact-page">

    @include('layout.header')

  <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background" style="background-image: url(assets/img/travel/showcase-11.webp);">
      <div class="container position-relative">
        <h1>Liên hệ</h1>
      </div>
    </div><!-- End Page Title -->

    <!-- Contact Section -->
    <section id="contact" class="contact section">

      <div class="container">
        <div class="contact-wrapper">
          <div class="contact-info-panel">
            <div class="contact-info-header">
              <h3>Thông tin liên lạc</h3>
              <p>Hãy liên hệ với chúng tôi nếu bạn có bất kỳ câu hỏi hoặc cần hỗ trợ nào. Chúng tôi luôn sẵn sàng giúp đỡ bạn!</p>
            </div>

            <div class="contact-info-cards">
              <div class="info-card">
                <div class="icon-container">
                  <i class="bi bi-pin-map-fill"></i>
                </div>
                <div class="card-content">
                  <h4>Địa Chỉ văn Phòng</h4>
                  <p>180 Cao Lỗ, Phường, Quận 8, Thành phố Hồ Chí Minh 700000</p>
                </div>
              </div>

              <div class="info-card">
                <div class="icon-container">
                  <i class="bi bi-envelope-open"></i>
                </div>
                <div class="card-content">
                  <h4>Email</h4>
                  <p>vutrungnguyen1101@gmail.com</p>
                </div>
              </div>

              <div class="info-card">
                <div class="icon-container">
                  <i class="bi bi-telephone-fill"></i>
                </div>
                <div class="card-content">
                  <h4>Số Điện Thoại</h4>
                  <p>09123456789</p>
                </div>
              </div>

              <div class="info-card">
                <div class="icon-container">
                  <i class="bi bi-clock-history"></i>
                </div>
                <div class="card-content">
                  <h4>Thời GIan Làm VIệc</h4>
                  <p>Thứ 2 - Thứ 7: 7h - 16h</p>
                </div>
              </div>
            </div>

            <div class="social-links-panel">
              <h5>Theo Dõi Chúng Tôi Trên Mạng Xã Hội</h5>
              <div class="social-icons">
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-twitter-x"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
                <a href="#"><i class="bi bi-linkedin"></i></a>
                <a href="#"><i class="bi bi-youtube"></i></a>
              </div>
            </div>
          </div>

          <div class="contact-form-panel">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.952974414819!2d106.6774780435811!3d10.738107869564669!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f62a90e5dbd%3A0x674d5126513db295!2sSaigon%20Technology%20University!5e0!3m2!1sen!2s!4v1765458196048!5m2!1sen!2s" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
        </div>
      </div>
    </section><!-- /Contact Section -->

  </main>

    @include('layout.footer')

    @include('layout.preloader')

</body>

</html>