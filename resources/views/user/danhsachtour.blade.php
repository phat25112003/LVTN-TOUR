<!DOCTYPE html>
<html lang="en">

@include('layout.head')

<body class="tours-page">

  @include('layout.header')

  <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background" style="background-image: url(assets/img/travel/showcase-11.webp);">
      <div class="container position-relative">
        <h1>Danh Sách Tour</h1>
        <p>Trải nghiệm du lịch được tuyển chọn kỹ lưỡng, biến hành trình thành những câu chuyện khó quên. Tìm theo sở thích của bạn và tìm kiếm chuyến đi hoàn hảo.</p>
        
      </div>
    </div><!-- End Page Title -->

    <!-- Travel Tours Section -->
    <section id="travel-tours" class="travel-tours section">

      <div class="container">

        <!-- Hero Introduction -->

        <!-- Search & Filters -->
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


        <!-- Tours Grid -->
        <div class="row mb-5">
          <div class="col-12">
            <div class="tours-header">
              @if($query == "")
              <h3 class="section-subtitle">Tất cả các tour</h3>
              @elseif($tours->isEmpty())
              <h3 class="section-subtitle">Không tìm thấy tour cho: "{{ $query }}"</h3>
              @else
              <h3 class="section-subtitle">Kết quả tìm kiếm của: "{{ $query }}"</h3>
              @endif
              <div class="view-toggle">
                <button class="toggle-btn active" data-view="grid">
                  <i class="bi bi-grid-3x3-gap"></i>
                </button>
                <button class="toggle-btn" data-view="list">
                  <i class="bi bi-list"></i>
                </button>
              </div>
            </div>

            <div class="tours-grid">
                @foreach($tours as $tour)
                <div class="tour-item">
                  <div class="tour-image">
                    @if ($tour->hinhanh->isNotEmpty())
                        <img src="{{ asset('storage/' . $tour->hinhanh->first()->duongDanHinh) }}" 
                            alt="{{ $tour->tieuDe }}" 
                            class="img-fluid">
                    @else
                        <img src="{{ asset('assets/img/default-tour.jpg') }}" 
                            alt="No image available" 
                            class="img-fluid">
                    @endif

                    <div class="tour-availability">{{$tour->loaidulich->tenLoai }}</div>
                  </div>
                  <div class="tour-details">
                    <h4>{{ $tour->tieuDe }}</h4>
                    <p>{{ Str::before($tour->moTa, '.') }}.</p>
                    <div class="tour-highlights">
                      <span><i class="bi bi-clock"></i> {{ $tour->thoiGian }}</span>
                      <span><i class="bi bi-star-fill"></i> 4.7</span>
                    </div>
                    <div class="tour-pricing">
                      <span class="price">{{ number_format($tour->giatour->first()->nguoiLon, 0, ',', '.') }}₫</span>
                      <span class="per">/người</span>
                      
                    </div>
                    <a href="{{ route('tour.detail', $tour->maTour) }}" class="explore-btn">
                    Tìm hiểu ngay <i class="bi bi-arrow-right"></i></a>
                  </div>
                </div>
                @endforeach
                
              </div>
            </div>
          </div>
        </div>
      </div>

    </section><!-- /Travel Tours Section -->

  </main>

  <footer id="footer" class="footer position-relative dark-background">

    <div class="container">
      <div class="row gy-5">

        <div class="col-lg-4">
          <div class="footer-content">
            <a href="index.html" class="logo d-flex align-items-center mb-4">
              <span class="sitename">TravelTime</span>
            </a>
            <p class="mb-4">Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae. Donec velit neque auctor sit amet aliquam vel ullamcorper sit amet ligula.</p>

            <div class="newsletter-form">
              <h5>Stay Updated</h5>
              <form action="forms/newsletter.php" method="post" class="php-email-form">
                <div class="input-group">
                  <input type="email" name="email" class="form-control" placeholder="Enter your email" required="">
                  <button type="submit" class="btn-subscribe">
                    <i class="bi bi-send"></i>
                  </button>
                </div>
                <div class="loading">Loading</div>
                <div class="error-message"></div>
                <div class="sent-message">Thank you for subscribing!</div>
              </form>
            </div>
          </div>
        </div>

        <div class="col-lg-2 col-6">
          <div class="footer-links">
            <h4>Company</h4>
            <ul>
              <li><a href="#"><i class="bi bi-chevron-right"></i> About</a></li>
              <li><a href="#"><i class="bi bi-chevron-right"></i> Careers</a></li>
              <li><a href="#"><i class="bi bi-chevron-right"></i> Press</a></li>
              <li><a href="#"><i class="bi bi-chevron-right"></i> Blog</a></li>
              <li><a href="#"><i class="bi bi-chevron-right"></i> Contact</a></li>
            </ul>
          </div>
        </div>

        <div class="col-lg-2 col-6">
          <div class="footer-links">
            <h4>Solutions</h4>
            <ul>
              <li><a href="#"><i class="bi bi-chevron-right"></i> Digital Strategy</a></li>
              <li><a href="#"><i class="bi bi-chevron-right"></i> Cloud Computing</a></li>
              <li><a href="#"><i class="bi bi-chevron-right"></i> Data Analytics</a></li>
              <li><a href="#"><i class="bi bi-chevron-right"></i> AI Solutions</a></li>
              <li><a href="#"><i class="bi bi-chevron-right"></i> Cybersecurity</a></li>
            </ul>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="footer-contact">
            <h4>Get in Touch</h4>
            <div class="contact-item">
              <div class="contact-icon">
                <i class="bi bi-geo-alt"></i>
              </div>
              <div class="contact-info">
                <p>2847 Maple Avenue<br>Los Angeles, CA 90210<br>United States</p>
              </div>
            </div>

            <div class="contact-item">
              <div class="contact-icon">
                <i class="bi bi-telephone"></i>
              </div>
              <div class="contact-info">
                <p>+1 (555) 987-6543</p>
              </div>
            </div>

            <div class="contact-item">
              <div class="contact-icon">
                <i class="bi bi-envelope"></i>
              </div>
              <div class="contact-info">
                <p>contact@example.com</p>
              </div>
            </div>

            <div class="social-links">
              <a href="#"><i class="bi bi-facebook"></i></a>
              <a href="#"><i class="bi bi-twitter-x"></i></a>
              <a href="#"><i class="bi bi-linkedin"></i></a>
              <a href="#"><i class="bi bi-youtube"></i></a>
              <a href="#"><i class="bi bi-github"></i></a>
            </div>
          </div>
        </div>

      </div>
    </div>

    <div class="footer-bottom">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6">
            <div class="copyright">
              <p>© <span>Copyright</span> <strong class="px-1 sitename">MyWebsite</strong> <span>All Rights Reserved</span></p>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="footer-bottom-links">
              <a href="#">Privacy Policy</a>
              <a href="#">Terms of Service</a>
              <a href="#">Cookie Policy</a>
            </div>
            <div class="credits">
              <!-- All the links in the footer should remain intact. -->
              <!-- You can delete the links only if you've purchased the pro version. -->
              <!-- Licensing information: https://bootstrapmade.com/license/ -->
              <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
              Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
            </div>
          </div>
        </div>
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  @include('layout.preloader')

</body>

</html>