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
    adult: parseInt(document.getElementById('adult-count').textContent) || 1,
  child: parseInt(document.getElementById('child-count').textContent) || 0,
  baby: parseInt(document.getElementById('baby-count').textContent) || 0
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

  let selectedEvent = null;

  // Khởi tạo FullCalendar
  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    locale: 'vi',
    timeZone: 'local',
    displayEventTime: false,  // Ẩn hoàn toàn phần giờ
    height: 'auto',
    events: `/api/tour-dates/${window.tourId}`,
    eventDidMount: function (info) {
    info.el.style.borderColor = '#f95e4d';
    info.el.style.transition = 'all 0.2s ease';
    info.el.style.transition = 'background-color 0.3s ease';},
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

    // 🗓️ Hàm định dạng ngày sang YYYY-MM-DD (để Laravel nhận diện)
    function formatDateForLaravel(dateStr) {
      const date = new Date(dateStr);
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const day = String(date.getDate()).padStart(2, '0');
      return `${year}-${month}-${day}`; // 2025-11-10
    }

    // Hàm hiển thị cho người dùng (giữ nguyên dd/MM/yyyy)
    function formatDateForDisplay(dateStr) {
      const date = new Date(dateStr);
      const day = String(date.getDate()).padStart(2, '0');
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const year = date.getFullYear();
      return `${day}/${month}/${year}`; // 10/11/2025
    }
    // Cập nhật ngày
const ngayBatDau_Laravel = formatDateForLaravel(info.event.startStr);
const ngayKetThuc_Laravel = formatDateForLaravel(props.ngayKetThuc);

const ngayBatDau_Display = formatDateForDisplay(info.event.startStr);
const ngayKetThuc_Display = formatDateForDisplay(props.ngayKetThuc);

// Cập nhật input hidden (gửi lên server)
document.querySelector('input[name="ngayBatDau"]').value = ngayBatDau_Laravel;
document.querySelector('input[name="ngayKetThuc"]').value = ngayKetThuc_Laravel;

// Cập nhật hiển thị cho người dùng
const ngayBatDauEl = document.querySelector('.ngayBatDauDisplay');
const ngayKetThucEl = document.querySelector('.ngayKetThucDisplay');
if (ngayBatDauEl) ngayBatDauEl.textContent = ngayBatDau_Display;
if (ngayKetThucEl) ngayKetThucEl.textContent = ngayKetThuc_Display;
    // Cập nhật lại tổng tiền với giá mới
    updateTotal();

    // Highlight event đã chọn
    if (selectedEvent) {
      // Trả màu cam nhạt cho event trước
      selectedEvent.setProp('backgroundColor', '#f95e4d');
      selectedEvent.setProp('borderColor', '#f95e4d');
    }

    // Làm event đang chọn tối màu hơn
    info.event.setProp('backgroundColor', '#d94b3e'); // cam đậm
    info.event.setProp('borderColor', '#d94b3e');

    selectedEvent = info.event;
  
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
