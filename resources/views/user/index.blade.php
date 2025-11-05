<!DOCTYPE html>
<html lang="en">


@include('layout.head')
<body class="index-page">

@include('layout.header')

  <main class="main">

    <!-- Travel Hero Section -->
    <section id="travel-hero" class="travel-hero section dark-background">

      <div class="container">

        <div class="row align-items-center">
          <div class="col-lg-12">
            <div class="content">
              <h1>Cùng Bạn trải nghiệm Cuộc Sống</h1>
              <p class="lead">Khám phá những điểm đến ngoạn mục và tạo nên những kỷ niệm khó quên với các tour du lịch được thiết kế chuyên nghiệp của chúng tôi.</p>
              <div class="d-flex flex-wrap gap-3 mt-4">
                <!-- <a href="destinations.html" class="btn btn-primary">Start Exploring</a>
                <a href="tours.html" class="btn btn-outline-light">View Tours</a> -->
              </div>

              <form action="{{ route('tour.list') }}" method="GET">
              @method('GET')
                <section id="travel-tours" class="travel-tours section">
                  <div class="row justify-content-center mb-5">
                    <div class="col-lg-10">
                      <div class="search-container">
                        <div class="search-bar">
                          <input type="text" name="query" class="form-control" placeholder="Tìm kiếm tour..." required>
                          <button class="search-btn" type="submit">
                            <i class="bi bi-search"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </section>
              </form>
            </div>
          </div>

        </div>

      </div>

    </section><!-- /Travel Hero Section -->

    <!-- Why Us Section -->
    <section id="why-us" class="why-us section">

      <div class="container">

        <!-- Main Content Grid -->
        <div class="content-grid">
          <div class="row g-4 align-items-stretch">

            <!-- About Section -->
            <div class="col-lg-6">
              <div class="about-block">
                <div class="about-header">
                  <span class="section-badge">About Us</span>
                  <h3>Tạo ra những trải nghiệm du lịch khó quên</h3>
                </div>
                <div class="about-content">
                  <p>Chúng tôi tin rằng mỗi chuyến đi đều là một hành trình khám phá và tận hưởng. Với đội ngũ chuyên gia du lịch giàu kinh nghiệm, chúng tôi mang đến cho bạn những hành trình được thiết kế tỉ mỉ, giúp bạn trải nghiệm trọn vẹn vẻ đẹp, văn hóa và con người ở mỗi điểm đến.</p>
                  <p>Mỗi chuyến đi là một câu chuyện, và chúng tôi ở đây để cùng bạn viết nên những trang ký ức khó quên. Từ những bãi biển yên bình đến thành phố sôi động, chúng tôi giúp bạn khám phá thế giới theo cách riêng — thoải mái, trọn vẹn và đầy cảm hứng.</p>

                  <div class="feature-list">
                    <div class="feature-item">
                      <i class="bi bi-check-circle-fill"></i>
                      <span>Hướng dẫn viên địa phương chuyên nghiệp</span>
                    </div>
                    <div class="feature-item">
                      <i class="bi bi-check-circle-fill"></i>
                      <span>Lịch trình linh hoạt, thiết kế riêng</span>
                    </div>
                    <div class="feature-item">
                      <i class="bi bi-check-circle-fill"></i>
                      <span>Hỗ trợ tận tâm 24/7</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Image Showcase -->
            <div class="col-lg-6">
              <div class="image-showcase">
                <div class="main-image">
                  <img src="assets/img/travel/showcase-12.webp" alt="Travel Adventure" class="img-fluid rounded-3">
                  <div class="overlay-badge">
                    <div class="badge-content">
                      <i class="bi bi-award-fill"></i>
                      <div class="badge-text">
                        <strong>Award Winner</strong>
                        <span>Best Travel Agency 2024</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="floating-card">
                  <img src="assets/img/travel/misc-8.webp" alt="Happy Travelers" class="img-fluid rounded-2">
                  <div class="card-content">
                    <div class="rating">
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <span>4.9/5</span>
                    </div>
                    <p>"Amazing experience!"</p>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div><!-- End Main Content Grid -->

        <!-- Why Choose Us Section -->
        <div class="why-choose-wrapper">
          <div class="section-header text-center">
            <span class="section-badge">Why Choose Us</span>
            <h3>Tại sao chọn chúng tôi</h3>
            <p>Khám phá lý do khiến hành trình cùng chúng tôi trở nên khác biệt và đáng nhớ hơn bao giờ hết.</p>
          </div>

          <div class="features-container">
            <div class="row g-4">

              <div class="col-lg-3 col-md-6">
                <div class="feature-box">
                  <div class="feature-icon-wrapper">
                    <div class="feature-icon">
                      <i class="bi bi-compass"></i>
                    </div>
                  </div>
                  <h4>Định vị chuyên nghiệp</h4>
                  <p>Đội ngũ chuyên gia giàu kinh nghiệm sẽ giúp bạn định hướng, lên kế hoạch và tận hưởng chuyến đi trọn vẹn nhất.</p>
                </div>
              </div>

              <div class="col-lg-3 col-md-6">
                <div class="feature-box">
                  <div class="feature-icon-wrapper">
                    <div class="feature-icon">
                      <i class="bi bi-heart-fill"></i>
                    </div>
                  </div>
                  <h4>Chăm sóc khách hàng</h4>
                  <p>Mỗi khách hàng là một câu chuyện riêng — chúng tôi luôn lắng nghe và tạo nên hành trình theo đúng mong muốn của bạn.</p>
                </div>
              </div>

              <div class="col-lg-3 col-md-6">
                <div class="feature-box">
                  <div class="feature-icon-wrapper">
                    <div class="feature-icon">
                      <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                  </div>
                  <h4>Đặt Tour Tức Thì</h4>
                  <p>Nhanh chóng, tiện lợi, không chờ đợi. Đặt tour chỉ trong vài cú nhấp chuột.</p>
                </div>
              </div>

              <div class="col-lg-3 col-md-6">
                <div class="feature-box">
                  <div class="feature-icon-wrapper">
                    <div class="feature-icon">
                      <i class="bi bi-globe-americas"></i>
                    </div>
                  </div>
                  <h4>Phủ sóng toàn quốc</h4>
                  <p>Dù bạn muốn đi đâu, chúng tôi đều có mặt để đưa bạn đến mọi miền đất nước.</p>
                </div>
              </div>

            </div>
          </div>
        </div><!-- End Why Choose Us Section -->

      </div>

    </section><!-- /Why Us Section -->

    <!-- Featured Destinations Section -->
    <section id="featured-destinations" class="featured-destinations section">

      <!-- Section Title -->
      <div class="container section-title">
        <h2>Điểm Đến Nổi Bật</h2>
        <div><span>Khám phá những hành trình đặc sắc</span> <span class="description-title">được chúng tôi tuyển chọn dành riêng cho bạn.</span></div>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">
        @foreach ($tours as $tour)
        
        <div class="col-lg-4 col-md-6">
            <div class="destination-card">
              <div class="image-wrapper">

                @if ($tour->hinhanh->isNotEmpty())
                    <img src="{{ asset('storage/' . $tour->hinhanh->first()->duongDanHinh) }}"
                        alt="Destination"
                        class="img-fluid">
                @else
                    <img src="{{ asset('assets/img/default-tour.jpg') }}"
                        alt="No image available"
                        class="img-fluid">
                @endif


                  alt="Destination" 
                  class="img-fluid">
                <div class="overlay">
                  <div class="badge">Popular</div>
                </div>
              </div>
              <div class="content">
                <h4>{{ $tour->tieuDe }}</h4>
                <p>{{ Str::before($tour->moTa, '.') }}.</p>
                <div class="features">
                  <span class="feature-tag">{{ $tour->danhmuc->tenDanhMuc}}</span>
                </div>
                <div class="card-footer">
                  <div class="tours-count">{{ $tour->thoiGian }}</div>
                  <div class="tours-count">Sô lượng còn: </div>
                  <a href="{{ route('tour.detail', $tour->maTour) }}" class="explore-btn">
                    Tìm hiểu ngay <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
              </div>
            </div>
          </div><!-- End Destination Card -->
        @endforeach
        </div>

        <div class="destinations-cta">
          <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
              <h3>Vẫn băn khoăn chưa biết điểm đến tiếp theo của mình là đâu?</h3>
              <p>Các tổng đài viên của chúng tôi sẽ giúp bạn tìm ra điểm đến hoàn hảo, phù hợp với sở thích, ngân sách và phong cách du lịch riêng của bạn.</p>
              <div class="cta-buttons">
                <a href="{{ route('tour.list', ['query' => '']) }}" class="btn btn-primary">Xem tất cả các tour</a>
                <a href="contact.html" class="btn btn-outline">Nhận tư vấn từ tổng đài viên</a>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Featured Destinations Section -->


  </main>
@include('layout.footer')

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  @include('layout.preloader')

</body>

</html>