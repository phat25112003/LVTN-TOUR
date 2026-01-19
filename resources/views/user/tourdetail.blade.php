<!DOCTYPE html>
<html lang="en">


@include('layout.head')
<body class="tour-details-page">

@include('layout.header')
  <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background" style="background-image: url('{{ asset('assets/img/travel/showcase-11.webp') }}');">
      <div class="container position-relative">
        <h1>Chi tiết hành trình</h1>
        <p>Khám phá mọi thông tin bạn cần để chuẩn bị cho chuyến đi hoàn hảo nhất.</p>
      </div>
    </div>
    <!-- End Page Title -->

    <!-- Travel Tour Details Section -->
    <section id="travel-tour-details" class="travel-tour-details section">

      <div class="container">

        <!-- Hero Banner -->
        <div class="tour-hero">
          <div class="hero-image-wrapper">
            <img src="{{ asset('storage/' . optional($tourdetail->hinhanh->first())->duongDanHinh) }}"alt="tour-image" class="hero-image">
            <div class="hero-overlay">
              <div class="hero-content">
                <h1>{{ $tourdetail->tieuDe }}</h1>
                <div class="hero-stats">
                  <span class="stat-item">
                    <i class="bi bi-clock"></i>
                    {{ $tourdetail->thoiGian }}
                  </span>
                  <span class="tour-type">{{ $tourdetail->danhmuc->tenDanhMuc }}</span>
                  <span class="tour-type">{{ $tourdetail->loaidulich->tenLoai }}</span>
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
                      <!-- <div class="highlight-item">
                        <i class="bi bi-palette"></i>
                        <span>Renaissance Art Tours</span>
                      </div> -->
                      <!-- <div class="highlight-item">
                        <i class="bi bi-cup-hot"></i>
                        <span>Culinary Experiences</span>
                      </div> -->
                      <div class="highlight-item">
                        <i class="bi bi-geo-fill"></i>
                        <span>Điểm khởi hành: {{ $tourdetail->chuyenTour->first()->diemKhoiHanh }} </span>
                      </div>
                      <div class="highlight-item">
                        <i class="bi bi-car-front"></i>
                        <span>Phương Tiện: {{ $tourdetail->chuyenTour->first()->phuongTien }} </span>
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
                    <div class="dates-grid d-flex">
                      <div class="col-md-6">
                        <div class="date-option">
                          <div class="date-info">
                            <span class="month">Giá Người Lớn</span>
                            <span class="dates">  > 13 tuổi  </span>
                          </div>
                          <div class="date-details">
                            <span class="price">{{ number_format($chuyenDauTien->giaTour->nguoiLon, 0, ',', '.') }}₫</span>
                          </div>
                        </div>
                        
                        <div class="date-option">
                          <div class="date-info">
                            <span class="month">Giá Trẻ Em</span>
                            <span class="dates"> 5 - 13 tuổi  </span>
                          </div>
                          <div class="date-details">
                            <span class="price">{{ number_format($chuyenDauTien->giaTour->treEm, 0, ',', '.') }}₫</span>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        
                        
                        <div class="date-option">
                          <div class="date-info">
                            <span class="month">Giá Em Bé</span>
                            <span class="dates"> 2 - 5 tuổi </span>
                          </div>
                          <div class="date-details">
                            <span class="price">{{ number_format($chuyenDauTien->giaTour->emBe, 0, ',', '.') }}₫</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
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
                      
                    </div>
                    <p>{{ $lt->noiDung }}</p>
                  <div class="day-features d-flex flex-column gap-3">

                      @if(!empty($lt->sang))
                      <div class="feature-item d-flex flex-column">
                          <h5 class="mb-1 fw-bold">Sáng</h5>
                          <span>{{ $lt->sang }}</span>
                      </div>
                      @endif

                      @if(!empty($lt->trua))
                      <div class="feature-item d-flex flex-column">
                          <h5 class="mb-1 fw-bold">Trưa</h5>
                          <span>{{ $lt->trua }}</span>
                      </div>
                      @endif

                      @if(!empty($lt->chieu))
                      <div class="feature-item d-flex flex-column">
                          <h5 class="mb-1 fw-bold">Chiều</h5>
                          <span>{{ $lt->chieu }}</span>
                      </div>
                      @endif

                      @if(!empty($lt->toi))
                      <div class="feature-item d-flex flex-column">
                          <h5 class="mb-1 fw-bold">Tối</h5>
                          <span>{{ $lt->toi }}</span>
                      </div>
                      @endif

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
      <span class="price-label">Chỉ từ</span>

      @if($chuyenDauTien && $chuyenDauTien->giaTour)
        <span class="price-amount">
          {{ number_format($chuyenDauTien->giaTour->nguoiLon, 0, ',', '.') }}₫
        </span>
      @else
        <span class="price-amount text-muted">
          Chưa có chuyến phù hợp
        </span>
      @endif
    </div>

    @if($chuyenDauTien)
      <a href="{{ route('dattour.create', [
            'maTour'   => $tourdetail->maTour,
            'maChuyen'=> $chuyenDauTien->maChuyen
        ]) }}" class="btn-reserve">
        Đặt chỗ ngay
      </a>
    @else
      <button class="btn-reserve" disabled>
        Tạm hết chuyến
      </button>
    @endif
  </div>
</div>

        </div>
        <div class="comments-container">
            @auth
            <div class="comments-header">
                <h2>Đánh giá & Bình luận</h2>
                <p>Chia sẻ trải nghiệm của bạn về tour du lịch</p>
            </div>
            
            <div class="comment-form">
                <form action="{{ route('tour.binhluan.store', ['tour' => $tourdetail->maTour]) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="rating"></label>
                        <label for="noi_dung">Nội dung bình luận:</label>
                        <textarea name="noiDung" id="noi_dung" placeholder="Hãy chia sẻ cảm nhận của bạn về tour này..." required></textarea>
                        <div class="form-group">
                        <div class="rating-container">
                            <div class="star-rating">
                                <input type="radio" id="star5" name="danhGia" value="5" checked>
                                <label for="star5">★</label>
                                <input type="radio" id="star4" name="danhGia" value="4">
                                <label for="star4">★</label>
                                <input type="radio" id="star3" name="danhGia" value="3">
                                <label for="star3">★</label>
                                <input type="radio" id="star2" name="danhGia" value="2">
                                <label for="star2">★</label>
                                <input type="radio" id="star1" name="danhGia" value="1">
                                <label for="star1">★</label>
                            </div>
                        </div>
                    </div> 
                    </div>               
                    <button type="submit" class="submit-btn">
                        <span>Gửi đánh giá</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22 2L11 13" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M22 2L15 22L11 13L2 9L22 2Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </form>
            </div>
            @endauth
            <div class="comments-list">
                <h3>Bình luận từ khách hàng</h3>
                
                @if(count($tourdetail->binhluan) > 0)
                    @foreach($tourdetail->binhluan as $binhluan)
                        <div class="comment-item">
                            <div class="comment-header">
                                <div class="user-info">
                                    <div class="user-avatar">
                                        {{ substr($binhluan->nguoiDung->hoTen, 0, 1) }}
                                    </div>
                                    <div class="user-name">{{ $binhluan->nguoiDung->hoTen }}</div>
                                </div>
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $binhluan->danhGia)
                                            ★
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                    <span>({{ $binhluan->danhGia }} sao)</span>
                                </div>
                            </div>
                            <div class="comment-content">
                                {{ $binhluan->noidung }}
                            </div>
                            <div class="comment-date">
                                {{ $binhluan->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="no-comments">
                        <p>Chưa có bình luận nào. Hãy là người đầu tiên đánh giá!</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Visual Gallery -->
        <div class="visual-gallery">
          <h2>Khoảnh Khắc Hành Trình</h2>
          <div class="gallery-grid">
            <div class="gallery-piece large">
              <a href="{{ asset('storage/' . optional($tourdetail->hinhanh->first())->duongDanHinh) }}" class="glightbox">
                <img src="{{ asset('storage/' . optional($tourdetail->hinhanh->first())->duongDanHinh) }}" alt="Italian Countryside" class="img-fluid" loading="lazy">
              </a>
            </div>
            @foreach ($tourdetail->hinhanh->skip(1)->take(20) as $ha)
            <div class="gallery-piece">
              <a href="{{ asset('storage/' . $ha->duongDanHinh) }}" class="glightbox">
                <img src="{{ asset('storage/' . $ha->duongDanHinh) }}" alt="Local Cuisine" class="img-fluid" loading="lazy">
              </a>
            </div>
            @endforeach
          </div>
        </div>

      </div>

    </section><!-- /Travel Tour Details Section -->

  </main>
  @if(session('success') || session('error'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toastEl = document.getElementById('mainToast');
    const toastMsg = document.getElementById('toastMessage');

    toastMsg.innerText = @json(session('success') ?? session('error'));

    new bootstrap.Toast(toastEl, { delay: 4000 }).show();
});
</script>
@endif

@if(session('success') || session('error'))
<div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3" style="z-index:1100">
    <div id="mainToast"
         class="toast text-white {{ session('success') ? 'bg-success' : 'bg-danger' }}"
         role="alert">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage"></div>
            <button class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
@endif

@include('layout.footer')

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  @include('layout.preloader')  
</body>

</html>