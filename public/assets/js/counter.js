// booking-calendar.js

document.addEventListener('DOMContentLoaded', function () {
  const calendarEl = document.getElementById('calendar');

  // Kiểm tra xem calendar có tồn tại không
  if (!calendarEl) {
    console.error('Không tìm thấy phần tử #calendar');
    return;
  }

  // Giá mặc định ban đầu (từ chuyến đầu tiên)
  let currentPrices = {
    adult: window.initialPrices.adult,
    child: window.initialPrices.child,
    baby: window.initialPrices.baby
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
    events: `/api/tour-dates/${window.tourId}`,
    eventClick: function (info) {
      const props = info.event.extendedProps;

      // Cập nhật giá hiện tại từ chuyến được chọn
      currentPrices = {
        adult: props.giaNguoiLon,
        child: props.giaTreEm,
        baby: props.giaEmBe
      };
      document.getElementById('ma-chuyen-display').textContent = props.maChuyen || '-';
      const maChuyenInput = document.getElementById('maChuyen-input');
      if (maChuyenInput) {
        maChuyenInput.value = props.maChuyen || '';
      }
      // Cập nhật ngày
      document.querySelector('input[name="ngayKhoiHanh"]').value = info.event.startStr;
      document.querySelector('input[name="ngayKetThuc"]').value = props.ngayKetThuc;

      document.querySelector('.booking-details .detail-row:nth-child(1) span:last-child').textContent = info.event.startStr;
      document.querySelector('.booking-details .detail-row:nth-child(2) span:last-child').textContent = props.ngayKetThuc;

      // Cập nhật lại tổng tiền với giá mới
      updateTotal();

      // Highlight event đã chọn
      calendar.getEvents().forEach(ev => ev.setProp('backgroundColor', ''));
      info.event.setProp('backgroundColor', '#007bff');
    }
  });

  calendar.render();

  // Xử lý nút + / - số lượng hành khách
  document.querySelectorAll('.btn-plus, .btn-minus').forEach(btn => {
    btn.addEventListener('click', function () {
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