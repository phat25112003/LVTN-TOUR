<!DOCTYPE html>
<html lang="en">

@include('layout.head')
<body class="faq-page">

    @include('layout.header')

  <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background" style="background-image: url('{{ asset('assets/img/travel/showcase-11.webp') }}');">
      <div class="container position-relative">
        <h1>Chính sách & Điều khoản</h1>
        <p>Quy định và chính sách áp dụng cho khách hàng khi tham gia tour du lịch của công ty chúng tôi.</p>

        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.html">Home</a></li>
            <li class="current">Faq</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Faq Section -->
    <section id="faq" class="faq section">

      <div class="container">

        <div class="row gy-4 justify-content-between">
          <div class="col-lg-8">

        <div class="faq-list">

        <div class="faq-item faq-active">
            <h3>1. Chính sách ghép tour khi không đủ số lượng khách</h3>
            <div class="faq-content">
            <p>
                Trong trường hợp số lượng khách đăng ký tour không đạt mức tối thiểu để chuyến đi được khởi hành,
                công ty có quyền ghép tour với các đoàn khác có cùng lịch trình, tuyến điểm hoặc thời gian tương đương.
                Việc ghép tour vẫn đảm bảo chất lượng dịch vụ, quyền lợi và lịch trình cơ bản của khách hàng.
            </p>
            </div>
            <i class="bi bi-plus faq-toggle"></i>
        </div>

        <div class="faq-item">
            <h3>2. Trường hợp không thể ghép tour</h3>
            <div class="faq-content">
            <p>
                Nếu không thể ghép tour do đặc thù lịch trình hoặc không có đoàn phù hợp,
                công ty sẽ thông báo cho khách hàng trước ngày khởi hành và đề xuất:
                đổi sang chuyến khác, bảo lưu tiền hoặc hoàn tiền theo thỏa thuận.
            </p>
            </div>
            <i class="bi bi-plus faq-toggle"></i>
        </div>

        <div class="faq-item">
            <h3>3. Chính sách hủy tour của khách hàng</h3>
            <div class="faq-content">
            <p>
                Trường hợp khách hàng chủ động hủy tour, mức phí hủy sẽ được áp dụng tùy theo thời điểm thông báo:
            </p>
            <ul>
                <li>Hủy trước 10 ngày so với ngày khởi hành: không mất phí.</li>
                <li>Hủy từ 7 – 9 ngày trước ngày khởi hành: phụ phí 30% giá tour.</li>
                <li>Hủy từ 4 – 6 ngày trước ngày khởi hành: phụ phí 50% giá tour.</li>
                <li>Hủy trong vòng 3 ngày hoặc không tham gia: phụ phí 100% giá tour.</li>
            </ul>
            </div>
            <i class="bi bi-plus faq-toggle"></i>
        </div>

        <div class="faq-item">
            <h3>4. Trường hợp bất khả kháng</h3>
            <div class="faq-content">
            <p>
                Trong các trường hợp bất khả kháng như thiên tai, dịch bệnh, chiến tranh, quy định của cơ quan chức năng,
                công ty sẽ phối hợp với khách hàng để thay đổi lịch trình, dời ngày khởi hành hoặc hoàn tiền theo thực tế phát sinh.
            </p>
            </div>
            <i class="bi bi-plus faq-toggle"></i>
        </div>

        <div class="faq-item">
            <h3>5. Quyền và trách nhiệm của khách hàng</h3>
            <div class="faq-content">
            <p>
                Khách hàng có trách nhiệm cung cấp đầy đủ thông tin cá nhân, tuân thủ nội quy tour,
                đúng giờ và giữ gìn hình ảnh chung của đoàn.
                Công ty không chịu trách nhiệm đối với các vi phạm cá nhân gây ảnh hưởng đến chuyến đi.
            </p>
            </div>
            <i class="bi bi-plus faq-toggle"></i>
        </div>

        </div>


          </div>

          <div class="col-lg-4">
            <div class="faq-card">
            <i class="bi bi-shield-check"></i>
            <h3>Cam kết của chúng tôi</h3>
            <p>
                Công ty luôn đặt quyền lợi và trải nghiệm của khách hàng lên hàng đầu,
                minh bạch trong chính sách, rõ ràng trong điều khoản và hỗ trợ kịp thời
                trong suốt quá trình tham gia tour.
            </p>
            <a href="" class="btn btn-primary">Liên hệ hỗ trợ</a>
            </div>

          </div>
        </div>

      </div>

    </section><!-- /Faq Section -->

  </main>

    @include('layout.footer')

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    @include('layout.preloader')

</body>

</html>