document.addEventListener("DOMContentLoaded", function () {
  const showError = (msg) => {
    const errorDiv = document.getElementById("error-message");
    if (errorDiv) errorDiv.textContent = msg;
  };

  const showToast = (msg) => {
    const toastEl = document.getElementById("errorToast");
    const toastMessage = document.getElementById("toastMessage");
    if (!toastEl || !toastMessage) return;
    toastMessage.textContent = msg;
    const toastInstance = new bootstrap.Toast(toastEl, { delay: 3000 });
    toastInstance.show();
  };

  // Biến lưu giá hiện tại (theo chuyến)
  let currentPrices = {
    adult: {{ $tour->giaTour->first()->nguoiLon }},
    child: {{ $tour->giaTour->first()->treEm }},
    baby: {{ $tour->giaTour->first()->emBe }}
  };

  // Hàm lấy số lượng
  const getCount = (type) => {
    const el = document.getElementById(`${type}-count`);
    return el ? parseInt(el.textContent) || 0 : 0;
  };

  // Hàm lấy giá (dùng currentPrices)
  const getPrice = (type) => currentPrices[type] || 0;

  // Cập nhật tổng tiền
  const updateTotalPrice = () => {
    const adultCount = getCount("adult");
    const childCount = getCount("child");
    const babyCount = getCount("baby");

    const adultTotal = adultCount * getPrice("adult");
    const childTotal = childCount * getPrice("child");
    const babyTotal = babyCount * getPrice("baby");
    const grandTotal = adultTotal + childTotal + babyTotal;

    const formatPrice = (price) => price.toLocaleString("vi-VN") + " ₫";

    const updateEl = (id, value) => {
      const el = document.getElementById(id);
      if (el) el.textContent = formatPrice(value);
    };

    updateEl("adult-total", adultTotal);
    updateEl("child-total", childTotal);
    updateEl("baby-total", babyTotal);
    updateEl("grand-total", grandTotal);

    // Cập nhật input ẩn
    document.getElementById("adult-input").value = adultCount;
    document.getElementById("child-input").value = childCount;
    document.getElementById("baby-input").value = babyCount;
    document.getElementById("grand-total-input").value = grandTotal;

    showError("");
  };

  // FullCalendar
  const calendarEl = document.getElementById('calendar');
  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    locale: 'vi',
    height: 'auto',
    events: '/api/tour-dates/{{ $tour->maTour }}',
    eventClick: function(info) {
      const props = info.event.extendedProps;

      // CẬP NHẬT GIÁ THEO CHUYẾN
      currentPrices = {
        adult: props.giaNguoiLon,
        child: props.giaTreEm,
        baby: props.giaEmBe
      };

      // Cập nhật ngày
      document.querySelector('input[name="ngayKhoiHanh"]').value = info.event.startStr;
      document.querySelector('input[name="ngayKetThuc"]').value = props.ngayKetThuc;
      document.querySelector('.booking-details .detail-row:nth-child(1) span:last-child').textContent = info.event.startStr;
      document.querySelector('.booking-details .detail-row:nth-child(2) span:last-child').textContent = props.ngayKetThuc;

      // Reset số lượng về mặc định khi đổi chuyến (tùy chọn)
      // document.getElementById('adult-count').textContent = Schatz 1;
      // document.getElementById('child-count').textContent = 0;
      // document.getElementById('baby-count').textContent = 0;

      updateTotalPrice();

      // Highlight
      calendar.getEvents().forEach(ev => ev.setProp('backgroundColor', ''));
      info.event.setProp('backgroundColor', '#007bff');
    }
  });
  calendar.render();

  // Xử lý + / -
  document.querySelectorAll(".btn-plus, .btn-minus").forEach((btn) => {
    btn.addEventListener("click", function () {
      const target = this.getAttribute("data-target");
      const countSpan = document.getElementById(`${target}-count`);
      if (!countSpan) return;

      let value = getCount(target);
      const slot = parseInt(document.getElementById("slot").value) || 0;

      if (this.classList.contains("btn-plus")) {
        value++;
      } else if (this.classList.contains("btn-minus")) {
        if (target === "adult" && value <= 1) {
          showError("Mỗi tour phải có ít nhất 1 người lớn");
          return;
        }
        if (value <= 0) return;
        value--;
      }

      // Tính tổng người SAU KHI ĐÃ THAY ĐỔI
      const totalPeople =
        (target === 'adult' ? value : getCount('adult')) +
        (target === 'child' ? value : getCount('child')) +
        (target === 'baby' ? value : getCount('baby'));

      if (totalPeople > slot) {
        showToast(`Chỉ còn ${slot} chỗ trống!`);
        return;
      }

      // Cập nhật giao diện
      countSpan.textContent = value;
      document.getElementById(`${target}-input`).value = value;

      updateTotalPrice();
    });
  });

  // Khởi tạo
  updateTotalPrice();
});