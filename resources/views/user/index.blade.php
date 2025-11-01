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
                  <h3>Creating Unforgettable Travel Experiences</h3>
                </div>
                <div class="about-content">
                  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa quae ab illo inventore veritatis.</p>
                  <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores.</p>

                  <div class="feature-list">
                    <div class="feature-item">
                      <i class="bi bi-check-circle-fill"></i>
                      <span>Expert local guides in every destination</span>
                    </div>
                    <div class="feature-item">
                      <i class="bi bi-check-circle-fill"></i>
                      <span>Customized itineraries for every traveler</span>
                    </div>
                    <div class="feature-item">
                      <i class="bi bi-check-circle-fill"></i>
                      <span>24/7 customer support throughout your journey</span>
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
            <h3>What Makes Us Different</h3>
            <p>Neque porro quisquam est qui dolorem ipsum quia dolor sit amet consectetur adipisci velit</p>
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
                  <h4>Expert Navigation</h4>
                  <p>Quis nostrum exercitationem ullam corporis suscipit laboriosam</p>
                </div>
              </div>

              <div class="col-lg-3 col-md-6">
                <div class="feature-box">
                  <div class="feature-icon-wrapper">
                    <div class="feature-icon">
                      <i class="bi bi-heart-fill"></i>
                    </div>
                  </div>
                  <h4>Personalized Care</h4>
                  <p>Excepteur sint occaecat cupidatat non proident sunt in culpa</p>
                </div>
              </div>

              <div class="col-lg-3 col-md-6">
                <div class="feature-box">
                  <div class="feature-icon-wrapper">
                    <div class="feature-icon">
                      <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                  </div>
                  <h4>Instant Booking</h4>
                  <p>Ut enim ad minim veniam quis nostrud exercitation ullamco</p>
                </div>
              </div>

              <div class="col-lg-3 col-md-6">
                <div class="feature-box">
                  <div class="feature-icon-wrapper">
                    <div class="feature-icon">
                      <i class="bi bi-globe-americas"></i>
                    </div>
                  </div>
                  <h4>Worldwide Coverage</h4>
                  <p>Duis aute irure dolor in reprehenderit in voluptate velit esse</p>
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
        <h2>Featured Destinations</h2>
        <div><span>Check Our</span> <span class="description-title">Featured Destinations</span></div>
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
                  <div class="tours-count">Lượng người tham gia còn: {{ $tour->soLuong }}</div>
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
              <h3>Can't Decide Where to Go?</h3>
              <p>Our travel experts are here to help you find the perfect destination based on your preferences, budget, and travel style.</p>
              <div class="cta-buttons">
                <a href="{{ route('tour.list', ['query' => '']) }}" class="btn btn-primary">View All Destinations</a>
                <a href="contact.html" class="btn btn-outline">Talk to Expert</a>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Featured Destinations Section -->

    <!-- Featured Tours Section -->
    <section id="featured-tours" class="featured-tours section">

      <!-- Section Title -->
      <div class="container section-title">
        <h2>Featured Tours</h2>
        <div><span>Check Our</span> <span class="description-title">Featured Tours</span></div>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row g-4">
          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="destination-card">
              <div class="destination-overlay">
                <img src="assets/img/travel/tour-12.webp" alt="Mountain Adventure" class="img-fluid" loading="lazy">
                <div class="card-overlay">
                  <div class="badge-container">
                    <span class="featured-badge">Best Seller</span>
                    <span class="price-tag">$3,290</span>
                  </div>
                  <div class="card-details">
                    <h5>Alpine Mountain Adventure</h5>
                    <div class="meta-info">
                      <span><i class="bi bi-calendar3"></i> 7 Days</span>
                      <span><i class="bi bi-geo-alt"></i> Switzerland</span>
                    </div>
                    <p>Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam quis nostrud.</p>
                    <div class="action-row">
                      <a href="booking.html" class="explore-btn">Explore Tour</a>
                      <div class="rating-stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-half"></i>
                        <small>4.7</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End Tour Item -->

          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="destination-card">
              <div class="destination-overlay">
                <img src="assets/img/travel/tour-15.webp" alt="Island Hopping" class="img-fluid" loading="lazy">
                <div class="card-overlay">
                  <div class="badge-container">
                    <span class="featured-badge hot">Hot Deal</span>
                    <span class="price-tag">$1,850</span>
                  </div>
                  <div class="card-details">
                    <h5>Tropical Island Hopping</h5>
                    <div class="meta-info">
                      <span><i class="bi bi-calendar3"></i> 6 Days</span>
                      <span><i class="bi bi-geo-alt"></i> Philippines</span>
                    </div>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis auctor magna vel risus tincidunt consequat.</p>
                    <div class="action-row">
                      <a href="booking.html" class="explore-btn">Explore Tour</a>
                      <div class="rating-stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <small>4.9</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End Tour Item -->

          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="destination-card">
              <div class="destination-overlay">
                <img src="assets/img/travel/tour-18.webp" alt="Cultural Heritage" class="img-fluid" loading="lazy">
                <div class="card-overlay">
                  <div class="badge-container">
                    <span class="featured-badge cultural">Cultural</span>
                    <span class="price-tag">$2,640</span>
                  </div>
                  <div class="card-details">
                    <h5>Ancient Cultural Heritage</h5>
                    <div class="meta-info">
                      <span><i class="bi bi-calendar3"></i> 9 Days</span>
                      <span><i class="bi bi-geo-alt"></i> Cambodia</span>
                    </div>
                    <p>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae donec velit neque.</p>
                    <div class="action-row">
                      <a href="booking.html" class="explore-btn">Explore Tour</a>
                      <div class="rating-stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star"></i>
                        <small>4.5</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End Tour Item -->

          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="destination-card">
              <div class="destination-overlay">
                <img src="assets/img/travel/tour-22.webp" alt="Safari Experience" class="img-fluid" loading="lazy">
                <div class="card-overlay">
                  <div class="badge-container">
                    <span class="featured-badge limited">Limited</span>
                    <span class="price-tag">$4,120</span>
                  </div>
                  <div class="card-details">
                    <h5>Wildlife Safari Experience</h5>
                    <div class="meta-info">
                      <span><i class="bi bi-calendar3"></i> 12 Days</span>
                      <span><i class="bi bi-geo-alt"></i> Kenya</span>
                    </div>
                    <p>Nulla facilisi morbi tempus iaculis urna id volutpat lacus laoreet non curabitur gravida arcu ac tortor.</p>
                    <div class="action-row">
                      <a href="booking.html" class="explore-btn">Explore Tour</a>
                      <div class="rating-stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <small>4.8</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End Tour Item -->

          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="destination-card">
              <div class="destination-overlay">
                <img src="assets/img/travel/tour-25.webp" alt="Northern Lights" class="img-fluid" loading="lazy">
                <div class="card-overlay">
                  <div class="badge-container">
                    <span class="featured-badge new">New Tour</span>
                    <span class="price-tag">$3,750</span>
                  </div>
                  <div class="card-details">
                    <h5>Northern Lights Quest</h5>
                    <div class="meta-info">
                      <span><i class="bi bi-calendar3"></i> 8 Days</span>
                      <span><i class="bi bi-geo-alt"></i> Norway</span>
                    </div>
                    <p>Fusce ut placerat orci nulla pellentesque dignissim enim sit amet venenatis urna cursus eget nunc scelerisque.</p>
                    <div class="action-row">
                      <a href="booking.html" class="explore-btn">Explore Tour</a>
                      <div class="rating-stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-half"></i>
                        <small>4.6</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End Tour Item -->

          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="destination-card">
              <div class="destination-overlay">
                <img src="assets/img/travel/tour-28.webp" alt="Desert Journey" class="img-fluid" loading="lazy">
                <div class="card-overlay">
                  <div class="badge-container">
                    <span class="featured-badge adventure">Adventure</span>
                    <span class="price-tag">$2,180</span>
                  </div>
                  <div class="card-details">
                    <h5>Golden Desert Journey</h5>
                    <div class="meta-info">
                      <span><i class="bi bi-calendar3"></i> 6 Days</span>
                      <span><i class="bi bi-geo-alt"></i> Morocco</span>
                    </div>
                    <p>Mauris blandit aliquet elit eget tincidunt nibh pulvinar a proin gravida hendrerit lectus a molestie.</p>
                    <div class="action-row">
                      <a href="booking.html" class="explore-btn">Explore Tour</a>
                      <div class="rating-stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star"></i>
                        <small>4.4</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End Tour Item -->

          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="destination-card">
              <div class="destination-overlay">
                <img src="assets/img/travel/tour-20.webp" alt="City Explorer" class="img-fluid" loading="lazy">
                <div class="card-overlay">
                  <div class="badge-container">
                    <span class="featured-badge popular">Popular</span>
                    <span class="price-tag">$1,560</span>
                  </div>
                  <div class="card-details">
                    <h5>European City Explorer</h5>
                    <div class="meta-info">
                      <span><i class="bi bi-calendar3"></i> 10 Days</span>
                      <span><i class="bi bi-geo-alt"></i> Europe</span>
                    </div>
                    <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas vestibulum.</p>
                    <div class="action-row">
                      <a href="booking.html" class="explore-btn">Explore Tour</a>
                      <div class="rating-stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-half"></i>
                        <small>4.7</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End Tour Item -->

          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="destination-card">
              <div class="destination-overlay">
                <img src="assets/img/travel/tour-17.webp" alt="Coastal Paradise" class="img-fluid" loading="lazy">
                <div class="card-overlay">
                  <div class="badge-container">
                    <span class="featured-badge luxury">Luxury</span>
                    <span class="price-tag">$5,890</span>
                  </div>
                  <div class="card-details">
                    <h5>Coastal Paradise Retreat</h5>
                    <div class="meta-info">
                      <span><i class="bi bi-calendar3"></i> 11 Days</span>
                      <span><i class="bi bi-geo-alt"></i> Maldives</span>
                    </div>
                    <p>Curabitur arcu erat accumsan id imperdiet et porttitor at sem donec rutrum congue leo eget malesuada.</p>
                    <div class="action-row">
                      <a href="booking.html" class="explore-btn">Explore Tour</a>
                      <div class="rating-stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <small>5.0</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End Tour Item -->

        </div>

        <div class="text-center mt-5">
          <a href="tours.html" class="discover-more-btn">Discover More Adventures</a>
        </div>

      </div>

    </section><!-- /Featured Tours Section -->

    <!-- Testimonials Home Section -->
    <section id="testimonials-home" class="testimonials-home section">

      <!-- Section Title -->
      <div class="container section-title">
        <h2>Testimonials</h2>
        <div><span>What Our Customers</span> <span class="description-title">Are Saying</span></div>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="swiper init-swiper">
          <script type="application/json" class="swiper-config">
            {
              "loop": true,
              "speed": 600,
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": "auto",
              "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
              }
            }
          </script>
          <div class="swiper-wrapper">

            <div class="swiper-slide">
              <div class="testimonial-item">
                <div class="row gy-4 justify-content-center">
                  <div class="col-lg-6">
                    <div class="testimonial-content">
                      <p>
                        <i class="bi bi-quote quote-icon-left"></i>
                        <span>Proin iaculis purus consequat sem cure digni ssim donec porttitora entum suscipit rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et. Maecen aliquam, risus at semper.</span>
                        <i class="bi bi-quote quote-icon-right"></i>
                      </p>
                      <h3>Saul Goodman</h3>
                      <h4>Ceo &amp; Founder</h4>
                      <div class="stars">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-2 text-center">
                    <img src="assets/img/person/person-m-9.webp" class="img-fluid testimonial-img" alt="">
                  </div>
                </div>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <div class="row gy-4 justify-content-center">
                  <div class="col-lg-6">
                    <div class="testimonial-content">
                      <p>
                        <i class="bi bi-quote quote-icon-left"></i>
                        <span>Export tempor illum tamen malis malis eram quae irure esse labore quem cillum quid cillum eram malis quorum velit fore eram velit sunt aliqua noster fugiat irure amet legam anim culpa.</span>
                        <i class="bi bi-quote quote-icon-right"></i>
                      </p>
                      <h3>Sara Wilsson</h3>
                      <h4>Designer</h4>
                      <div class="stars">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-2 text-center">
                    <img src="assets/img/person/person-f-5.webp" class="img-fluid testimonial-img" alt="">
                  </div>
                </div>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <div class="row gy-4 justify-content-center">
                  <div class="col-lg-6">
                    <div class="testimonial-content">
                      <p>
                        <i class="bi bi-quote quote-icon-left"></i>
                        <span>Enim nisi quem export duis labore cillum quae magna enim sint quorum nulla quem veniam duis minim tempor labore quem eram duis noster aute amet eram fore quis sint minim.</span>
                        <i class="bi bi-quote quote-icon-right"></i>
                      </p>
                      <h3>Jena Karlis</h3>
                      <h4>Store Owner</h4>
                      <div class="stars">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-2 text-center">
                    <img src="assets/img/person/person-f-12.webp" class="img-fluid testimonial-img" alt="">
                  </div>
                </div>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <div class="row gy-4 justify-content-center">
                  <div class="col-lg-6">
                    <div class="testimonial-content">
                      <p>
                        <i class="bi bi-quote quote-icon-left"></i>
                        <span>Fugiat enim eram quae cillum dolore dolor amet nulla culpa multos export minim fugiat minim velit minim dolor enim duis veniam ipsum anim magna sunt elit fore quem dolore labore illum veniam.</span>
                        <i class="bi bi-quote quote-icon-right"></i>
                      </p>
                      <h3>John Larson</h3>
                      <h4>Entrepreneur</h4>
                      <div class="stars">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-2 text-center">
                    <img src="assets/img/person/person-m-12.webp" class="img-fluid testimonial-img" alt="">
                  </div>
                </div>
              </div>
            </div><!-- End testimonial item -->

          </div>
          <div class="swiper-pagination"></div>
        </div>

      </div>

    </section><!-- /Testimonials Home Section -->

    <!-- Call To Action Section -->
    <section id="call-to-action" class="call-to-action section">

      <div class="container">

        <div class="row justify-content-center">
          <div class="col-lg-8 text-center">
            <h2>Ready to Start Your Next Adventure?</h2>
            <p>Discover breathtaking destinations, create unforgettable memories, and explore the world with our expertly crafted travel packages. From exotic beaches to mountain peaks, your perfect journey awaits.</p>
            <div class="cta-buttons">
              <a href="destinations.html" class="btn-primary">Explore Destinations</a>
              <a href="tours.html" class="btn-secondary">Plan Your Trip</a>
            </div>
          </div>
        </div>

        <div class="row mt-5">
          <div class="col-lg-3 col-md-6">
            <div class="feature-item text-center">
              <div class="icon">
                <i class="bi bi-globe"></i>
              </div>
              <h4>50+ Destinations</h4>
              <p>Explore amazing destinations across all continents</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6">
            <div class="feature-item text-center">
              <div class="icon">
                <i class="bi bi-shield-check"></i>
              </div>
              <h4>Safe &amp; Secure</h4>
              <p>Travel with confidence with our safety guarantee</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6">
            <div class="feature-item text-center">
              <div class="icon">
                <i class="bi bi-headset"></i>
              </div>
              <h4>24/7 Support</h4>
              <p>Round-the-clock assistance whenever you need it</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6">
            <div class="feature-item text-center">
              <div class="icon">
                <i class="bi bi-percent"></i>
              </div>
              <h4>Best Prices</h4>
              <p>Competitive rates with exclusive deals and offers</p>
            </div>
          </div>
        </div>

        <div class="stats-section">
          <div class="row text-center">
            <div class="col-lg-3 col-md-6">
              <div class="stat-item">
                <span class="stat-number purecounter" data-purecounter-start="0" data-purecounter-end="15000" data-purecounter-duration="2"></span>
                <span class="stat-label">Happy Travelers</span>
              </div>
            </div>

            <div class="col-lg-3 col-md-6">
              <div class="stat-item">
                <span class="stat-number purecounter" data-purecounter-start="0" data-purecounter-end="127" data-purecounter-duration="2"></span>
                <span class="stat-label">Countries Covered</span>
              </div>
            </div>

            <div class="col-lg-3 col-md-6">
              <div class="stat-item">
                <span class="stat-number purecounter" data-purecounter-start="0" data-purecounter-end="98" data-purecounter-duration="2"></span>
                <span class="stat-label">Satisfaction Rate</span>
              </div>
            </div>

            <div class="col-lg-3 col-md-6">
              <div class="stat-item">
                <span class="stat-number purecounter" data-purecounter-start="0" data-purecounter-end="12" data-purecounter-duration="2"></span>
                <span class="stat-label">Years Experience</span>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Call To Action Section -->

  </main>
@include('layout.footer')

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  @include('layout.preloader')

</body>

</html>