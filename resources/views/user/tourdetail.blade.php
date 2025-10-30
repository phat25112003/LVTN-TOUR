<!DOCTYPE html>
<html lang="en">


@include('layout.head')
<body class="tour-details-page">

@include('layout.header')
  <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background" style="background-image: url(assets/img/travel/showcase-11.webp);">
      <div class="container position-relative">
        <h1>Tour Details</h1>
        <p>Esse dolorum voluptatum ullam est sint nemo et est ipsa porro placeat quibusdam quia assumenda numquam molestias.</p>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.html">Home</a></li>
            <li class="current">Tour Details</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Travel Tour Details Section -->
    <section id="travel-tour-details" class="travel-tour-details section">

      <div class="container">

        <!-- Hero Banner -->
        <div class="tour-hero">
          <div class="hero-image-wrapper">
            <img src="{{ asset('assets/img/travel/showcase-7.webp') }}" alt="Mediterranean Coast Adventure" class="hero-image">
            <div class="hero-overlay">
              <div class="hero-content">
                <span class="tour-type">{{ $tourdetail->danhmuc->tenDanhMuc }}</span>
                <h1>Mediterranean Coast Discovery</h1>
                <p class="hero-subtitle">Immerse yourself in ancient history and coastal splendor across Italy's most captivating destinations</p>
                <div class="hero-stats">
                  <span class="stat-item">
                    <i class="bi bi-clock"></i>
                    10 Days
                  </span>
                  <span class="stat-item">
                    <i class="bi bi-geo-alt"></i>
                    Rome • Florence • Amalfi
                  </span>
                  <span class="stat-item">
                    <i class="bi bi-people"></i>
                    Max 16 Guests
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="tour-layout">
          <div class="tour-details">
            <!-- Tour Essence -->
            <div class="tour-essence">
              <div class="row align-items-center">
                <div class="col-lg-8">
                  <div class="essence-content">
                    <h2>{{ $tourdetail->tieuDe }}</h2>
                    <p>{{ $tourdetail->moTa }}</p>

                    <div class="highlights-compact">
                      <div class="highlight-item">
                        <i class="bi bi-palette"></i>
                        <span>Renaissance Art Tours</span>
                      </div>
                      <div class="highlight-item">
                        <i class="bi bi-cup-hot"></i>
                        <span>Culinary Experiences</span>
                      </div>
                      <div class="highlight-item">
                        <i class="bi bi-building"></i>
                        <span>Boutique Accommodations</span>
                      </div>
                      <div class="highlight-item">
                        <i class="bi bi-car-front"></i>
                        <span>Private Transport</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Inclusions Overview -->
            <div class="inclusions-overview">
              <div class="row">
                <div class="col-lg-6">
                  <div class="included-section">
                    <h3>Your Journey Includes</h3>
                    <div class="inclusion-list">
                      <div class="inclusion-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>9 nights boutique hotel accommodation</span>
                      </div>
                      <div class="inclusion-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Daily breakfast and 4 specialty dinners</span>
                      </div>
                      <div class="inclusion-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Private transfers and high-speed rail</span>
                      </div>
                      <div class="inclusion-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Expert local guides and art historians</span>
                      </div>
                      <div class="inclusion-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Skip-the-line museum entries</span>
                      </div>
                      <div class="inclusion-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Curated cultural experiences</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="not-included-section">
                    <h3>Additional Considerations</h3>
                    <div class="exclusion-list">
                      <div class="exclusion-item">
                        <i class="bi bi-x-circle"></i>
                        <span>International flights to/from Italy</span>
                      </div>
                      <div class="exclusion-item">
                        <i class="bi bi-x-circle"></i>
                        <span>Travel insurance (recommended)</span>
                      </div>
                      <div class="exclusion-item">
                        <i class="bi bi-x-circle"></i>
                        <span>Lunches and personal dining choices</span>
                      </div>
                      <div class="exclusion-item">
                        <i class="bi bi-x-circle"></i>
                        <span>Personal shopping and souvenirs</span>
                      </div>
                      <div class="exclusion-item">
                        <i class="bi bi-x-circle"></i>
                        <span>Optional activities and upgrades</span>
                      </div>
                      <div class="exclusion-item">
                        <i class="bi bi-x-circle"></i>
                        <span>Gratuities for guides and drivers</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Booking Section -->
            <div class="booking-section" id="booking">
              <div class="row">
                <div class="col-lg-8">
                  <div class="departure-dates">
                    <h3>Bảng giá chi tiết</h3>
                    <div class="dates-grid">

                      <div class="date-option">
                        <div class="date-info">
                          <span class="month">Giá Người Lớn</span>
                          <span class="dates">  > 18 </span>
                        </div>
                        <div class="date-details">
                          <span class="price">{{ $tourdetail->giaNguoiLon }}</span>
                          <span class="availability available">Available</span>
                        </div>
                      </div>

                      <div class="date-option">
                        <div class="date-info">
                          <span class="month">Giá Trẻ Em</span>
                          <span class="dates"> < 18 </span>
                        </div>
                        <div class="date-details">
                          <span class="price">{{ $tourdetail->giaTreEm }}</span>
                          <span class="availability limited">4 spots left</span>
                        </div>
                      </div>

                      <!-- <div class="date-option">
                        <div class="date-info">
                          <span class="month">June</span>
                          <span class="dates">10 - 19</span>
                        </div>
                        <div class="date-details">
                          <span class="price">€3,850</span>
                          <span class="availability available">Available</span>
                        </div>
                      </div>

                      <div class="date-option">
                        <div class="date-info">
                          <span class="month">September</span>
                          <span class="dates">5 - 14</span>
                        </div>
                        <div class="date-details">
                          <span class="price">€3,750</span>
                          <span class="availability available">Available</span>
                        </div>
                      </div>

                      <div class="date-option">
                        <div class="date-info">
                          <span class="month">October</span>
                          <span class="dates">15 - 24</span>
                        </div>
                        <div class="date-details">
                          <span class="price">€3,550</span>
                          <span class="availability limited">2 spots left</span>
                        </div>
                      </div> -->

                    </div>
                  </div>
                </div>

                <!-- <div class="col-lg-4">
                  <div class="booking-form-card">
                    <h3>Secure Your Place</h3>

                    <form action="forms/tour-booking.php" method="post" class="php-email-form">

                      <div class="form-group">
                        <label for="preferred-date">Preferred Departure</label>
                        <select name="departure_date" id="preferred-date" class="form-control" required="">
                          <option value="">Select departure date</option>
                          <option value="April 15-24">April 15-24, 2024</option>
                          <option value="May 20-29">May 20-29, 2024</option>
                          <option value="June 10-19">June 10-19, 2024</option>
                          <option value="September 5-14">September 5-14, 2024</option>
                          <option value="October 15-24">October 15-24, 2024</option>
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="guest-count">Number of Travelers</label>
                        <select name="travelers" id="guest-count" class="form-control" required="">
                          <option value="">Select travelers</option>
                          <option value="1">1 Person</option>
                          <option value="2">2 People</option>
                          <option value="3">3 People</option>
                          <option value="4">4 People</option>
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="contact-name">Your Name</label>
                        <input type="text" name="name" id="contact-name" class="form-control" required="">
                      </div>

                      <div class="form-group">
                        <label for="contact-email">Email Address</label>
                        <input type="email" name="email" id="contact-email" class="form-control" required="">
                      </div>

                      <div class="form-group">
                        <label for="contact-phone">Phone Number</label>
                        <input type="tel" name="phone" id="contact-phone" class="form-control">
                      </div>

                      <div class="form-group">
                        <label for="special-notes">Special Requests</label>
                        <textarea name="message" id="special-notes" rows="3" class="form-control" placeholder="Dietary requirements, celebrations, accessibility needs..."></textarea>
                      </div>

                      <div class="loading">Loading</div>
                      <div class="error-message"></div>
                      <div class="sent-message">Your booking request has been submitted successfully!</div>

                      <button type="submit" class="btn-submit">Submit Booking Request</button>
                    </form>

                    <div class="booking-assurance">
                      <div class="assurance-item">
                        <i class="bi bi-shield-check"></i>
                        <span>Secure booking process</span>
                      </div>
                      <div class="assurance-item">
                        <i class="bi bi-telephone"></i>
                        <span>Expert travel consultants</span>
                      </div>
                    </div>
                  </div>
                </div> -->
              </div>
            </div>
            <!-- Journey Timeline -->
            <div class="journey-timeline">
              <h2>Lịch trình chuyến đi</h2>

              
              <div class="timeline-wrapper">
                @foreach ($tourdetail->lichtrinh as $lt)
                <div class="timeline-item">
                  <div class="timeline-marker">
                    <span class="day-number">{{ $lt->ngay }}</span>
                  </div>
                  <div class="timeline-content">
                    <div class="day-header">
                      <h3>{{ $lt->huongDi }}</h3>
                      <span class="location">Rome</span>
                    </div>
                    <p>{{ $lt->noiDung }}</p>
                    <div class="day-features">
                      <span class="feature-tag">Hotel Del Greco</span>
                      <span class="feature-tag">Welcome Dinner</span>
                    </div>
                  </div>
                </div>
                @endforeach
                
              </div>
            </div>
          </div>
          <div class="tour-booking">
                <div class="pricing-card">
                  <div class="price-header">
                    <span class="price-label">From</span>
                    <span class="price-amount">€3,450</span>
                  </div>
                  <p class="price-description">per person, twin accommodation</p>
                  <a href="{{ route('dattour.create', ['maTour'=>$tourdetail->maTour] )}}" class="btn-reserve">Đặt chỗ ngay</a>
                  <div class="booking-notes">
                    <span><i class="bi bi-shield-check"></i>Free cancellation up to 48h</span>
                  </div>
                </div>

            </div>
        </div>







        <!-- Visual Gallery -->
        <div class="visual-gallery">
          <h2>Moments to Remember</h2>
          <div class="gallery-grid">
            <div class="gallery-piece large">
              <a href="{{ asset('storage/' . $tourdetail->hinhanh->first()->duongDanHinh) }}" class="glightbox">
                <img src="{{ asset('storage/' . $tourdetail->hinhanh->first()->duongDanHinh) }}" alt="Italian Countryside" class="img-fluid" loading="lazy">
              </a>
            </div>
            @foreach ($tourdetail->hinhanh->skip(1)->take(4) as $ha)
            <div class="gallery-piece">
              <a href="{{ asset('storage/' . $ha->duongDanHinh) }}" class="glightbox">
                <img src="{{ asset('storage/' . $ha->duongDanHinh) }}" alt="Local Cuisine" class="img-fluid" loading="lazy">
              </a>
            </div>
            @endforeach
            <div class="gallery-piece medium">
              <a href="{{ asset('assets/img/travel/tour-10.webp') }}" class="glightbox">
                <img src="{{ asset('storage/' . $tourdetail->hinhanh->skip(5)->first()->duongDanHinh) }}" alt="Scenic Landscapes" class="img-fluid" loading="lazy">
              </a>
            </div>
          </div>
        </div>

        <!-- Final Call to Action -->
        <div class="final-call">
          <div class="call-content">
            <h2>Your Italian Odyssey Awaits</h2>
            <p>Embark on a journey that transcends ordinary travel. Let us craft memories that will last a lifetime.</p>
            <div class="call-actions">
              <a href="#booking" class="btn-primary-cta">Begin Your Journey</a>
              <a href="tel:+1-855-742-8639" class="btn-contact">
                <i class="bi bi-telephone"></i>
                Call +1 (855) 742-8639
              </a>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Travel Tour Details Section -->

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