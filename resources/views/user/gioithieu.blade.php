<!DOCTYPE html>
<html lang="en">

@include('layout.head')

<body class="about-page">

    @include('layout.header')

  <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background" style="background-image: url(assets/img/travel/showcase-11.webp);">
      <div class="container position-relative">
        <h1>Giới thiệu</h1>
        <p>Esse dolorum voluptatum ullam est sint nemo et est ipsa porro placeat quibusdam quia assumenda numquam molestias.</p>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="{{ route('home') }}">Home</a></li>
            <li class="current">About</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- About Section -->
    <section id="about" class="about section">

      <div class="container">

        <div class="row g-5 align-items-center">

          <div class="col-lg-6">
            <div class="content">
              <h2 class="mb-4">Về chúng tôi</h2>
              <p class="lead mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</p>
              <p class="mb-5">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>

              <div class="row g-4 mb-5">
                <div class="col-6">
                  <div class="stat-item text-center">
                    <div class="stat-number">350+</div>
                    <div class="stat-label">Tour đã thực hiện</div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="stat-item text-center">
                    <div class="stat-number">25+</div>
                    <div class="stat-label">Tỉnh thành</div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="stat-item text-center">
                    <div class="stat-number">8</div>
                    <div class="stat-label">Kinh nghiệm</div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="stat-item text-center">
                    <div class="stat-number">98%</div>
                    <div class="stat-label">Khách hàng hài lòng</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="image-stack">
              <div class="image-main">
                <img src="assets/img/travel/showcase-3.webp" alt="Travel Experience" class="img-fluid">
              </div>
              <div class="image-overlay">
                <img src="assets/img/travel/misc-12.webp" alt="Happy Travelers" class="img-fluid">
              </div>
            </div>
          </div>

        </div>

        <div class="row g-4 mt-5">

          <div class="col-lg-4 col-md-6">
            <div class="feature-item text-center">
              <div class="feature-icon">
                <i class="bi bi-award"></i>
              </div>
              <h5>Expert Local Guides</h5>
              <p>Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore</p>
            </div>
          </div>

          <div class="col-lg-4 col-md-6">
            <div class="feature-item text-center">
              <div class="feature-icon">
                <i class="bi bi-headset"></i>
              </div>
              <h5>24/7 Customer Support</h5>
              <p>Quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat duis aute irure</p>
            </div>
          </div>

          <div class="col-lg-4 col-md-6">
            <div class="feature-item text-center">
              <div class="feature-icon">
                <i class="bi bi-shield-check"></i>
              </div>
              <h5>Best Price Guarantee</h5>
              <p>Excepteur sint occaecat cupidatat non proident sunt in culpa qui officia deserunt mollit anim</p>
            </div>
          </div>

          <div class="col-lg-4 col-md-6">
            <div class="feature-item text-center">
              <div class="feature-icon">
                <i class="bi bi-geo-alt"></i>
              </div>
              <h5>Local Expertise</h5>
              <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium</p>
            </div>
          </div>

          <div class="col-lg-4 col-md-6">
            <div class="feature-item text-center">
              <div class="feature-icon">
                <i class="bi bi-calendar-check"></i>
              </div>
              <h5>Flexible Booking</h5>
              <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum</p>
            </div>
          </div>

          <div class="col-lg-4 col-md-6">
            <div class="feature-item text-center">
              <div class="feature-icon">
                <i class="bi bi-leaf"></i>
              </div>
              <h5>Sustainable Travel</h5>
              <p>Neque porro quisquam est qui dolorem ipsum quia dolor sit amet consectetur adipisci velit</p>
            </div>
          </div>

        </div>

      </div>

    </section><!-- /About Section -->
        <!-- Slider Testimonials Section -->
    <section id="slider-testimonials" class="slider-testimonials section">

      <!-- Section Title -->
      <div class="container section-title">
        <h2>Testimonials</h2>
        <div><span>Check Our</span> <span class="description-title">Testimonials</span></div>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="testimonials-14 swiper init-swiper">
          <script type="application/json" class="swiper-config">
            {
              "loop": true,
              "speed": 600,
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": 3,
              "spaceBetween": 24,
              "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
              },
              "breakpoints": {
                "320": {
                  "slidesPerView": 1,
                  "spaceBetween": 16
                },
                "768": {
                  "slidesPerView": 2,
                  "spaceBetween": 24
                },
                "1200": {
                  "slidesPerView": 3,
                  "spaceBetween": 24
                }
              }
            }
          </script>

          <div class="swiper-wrapper">
            @foreach($hdv as $hdv)
            <!-- Testimonial Item 1 -->
            <div class="swiper-slide">
              <div class="testimonial-item">
                <div class="stars">
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                </div>
                <p>{{$hdv->ghiChu}}</p>
                <div class="profile">
                  <img src="assets/img/person/person-m-9.webp" class="testimonial-img" alt="" loading="lazy">
                  <div class="info">
                    <h4>{{$hdv->hoTen}} <i class="bi bi-patch-check-fill"></i></h4>
                    <span>@marcuschen</span>
                  </div>
                </div>
              </div>
            </div><!-- End testimonial item -->
            @endforeach
          </div>

          <div class="swiper-pagination"></div>

        </div>

      </div>

    </section><!-- /Slider Testimonials Section -->
  </main>

    @include('layout.footer')

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>