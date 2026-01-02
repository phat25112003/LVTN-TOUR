// counter.js

document.addEventListener('DOMContentLoaded', function () {
  const calendarEl = document.getElementById('calendar');

  if (!calendarEl) {
    console.error('Không tìm thấy phần tử #calendar');
    return;
  }

  // ⭐ ẨN STEP 3 – 5 KHI VỪA VÀO TRANG
  const step3 = document.getElementById("step-3");
  const step4 = document.getElementById("step-4");
  const step5 = document.getElementById("step-5");

  [step3, step4, step5].forEach(step => {
    step.classList.add("d-none");
    step.style.opacity = 0;
    step.style.transition = "opacity 0.5s ease";
  });

  let currentPrices = {
    adult: window.initialPrices.adult,
    child: window.initialPrices.child,
    baby: window.initialPrices.baby
  };

  let counts = {
    adult: parseInt(document.getElementById('adult-count').textContent) || 1,
    child: parseInt(document.getElementById('child-count').textContent) || 0,
    baby: parseInt(document.getElementById('baby-count').textContent) || 0
  };

  window.calculateBaseTotal = function() {
    return counts.adult * currentPrices.adult +
           counts.child * currentPrices.child +
           counts.baby * currentPrices.baby;
  };
  window.calculatePhongDonTotal = function () {
    const giaPhongDon = Number(window.initialPrices.phongDon);
    const checkboxes = document.querySelectorAll(".phong-don-checkbox:checked");
    return checkboxes.length * giaPhongDon; // truyền từ blade
  };

  window.updateDisplay = function () {
    const baseTotal = window.calculateBaseTotal();
    const phongDonTotal = window.calculatePhongDonTotal();
    const grandTotal = baseTotal + phongDonTotal;

    const format = (amount) => new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);

    document.getElementById('adult-unit-price').textContent = format(currentPrices.adult);
    document.getElementById('adult-count-strong').textContent = counts.adult;
    document.getElementById('adult-total').textContent = format(counts.adult * currentPrices.adult);
    document.getElementById('adult-count-display').textContent = `× ${counts.adult}`;

    const childRow = document.getElementById('child-price-row');
    if (counts.child > 0) {
        childRow.style.display = 'flex';
        document.getElementById('child-unit-price').textContent = format(currentPrices.child);
        document.getElementById('child-count-strong').textContent = counts.child;
        document.getElementById('child-total').textContent = format(counts.child * currentPrices.child);
        document.getElementById('child-count-display').textContent = `× ${counts.child}`;
    } else childRow.style.display = 'none';

    const babyRow = document.getElementById('baby-price-row');
    if (counts.baby > 0) {
        babyRow.style.display = 'flex';
        document.getElementById('baby-unit-price').textContent = format(currentPrices.baby);
        document.getElementById('baby-count-strong').textContent = counts.baby;
        document.getElementById('baby-total').textContent = format(counts.baby * currentPrices.baby);
        document.getElementById('baby-count-display').textContent = `× ${counts.baby}`;
    } else babyRow.style.display = 'none';

    document.getElementById('grand-total').textContent = format(grandTotal);
    document.getElementById('grand-total-input').value = grandTotal;

    document.getElementById('adult-input').value = counts.adult;
    document.getElementById('child-input').value = counts.child;
    document.getElementById('baby-input').value = counts.baby;
    const phongDonRow = document.getElementById('phong-don-price-row');
const phongDonCount = document.querySelectorAll('.phong-don-checkbox:checked').length;
const giaPhongDon = Number(window.initialPrices.phongDon);

if (phongDonCount > 0) {
  phongDonRow.classList.remove('d-none');

  document.getElementById('phong-don-unit-price').textContent =
    format(giaPhongDon);

  document.getElementById('phong-don-count-display').textContent =
    `× ${phongDonCount}`;

  document.getElementById('phong-don-total').textContent =
    format(phongDonCount * giaPhongDon);
} else {
  phongDonRow.classList.add('d-none');
}
  };

  function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
      style: 'currency',
      currency: 'VND'
    }).format(amount);
  }

  let selectedEvent = null;
  let movedToFirstEvent = false;

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    locale: 'vi',
    timeZone: 'local',
    displayEventTime: false,
    height: 'auto',


    events: `/api/tour-dates/${window.tourId}`,

    eventsSet: function(events) {
      if (movedToFirstEvent || events.length === 0) return;

      const firstEvent = events.reduce((a, b) =>
        new Date(a.start) < new Date(b.start) ? a : b
      );

      if (firstEvent) {
        calendar.gotoDate(firstEvent.start);
        movedToFirstEvent = true;
      }
    },

    eventDidMount: function (info) {
      info.el.style.borderColor = '#f95e4d';
      info.el.style.transition = 'background-color 0.3s ease';
    },

    eventClick: function (info) {
      const props = info.event.extendedProps;

      currentPrices = {
        adult: props.giaNguoiLon,
        child: props.giaTreEm,
        baby: props.giaEmBe
      };

      // ⭐ HIỆN STEP 3 – 5 VỚI FADE-IN + SCROLL
      [step3, step4, step5].forEach(step => {
        step.classList.remove("d-none");
        setTimeout(() => { step.style.opacity = 1; }, 50); // fade-in
      });

      step3.scrollIntoView({ behavior: "smooth", block: "start" });

      document.getElementById('ma-chuyen-display').textContent = props.maChuyen || '-';

      const maChuyenInput = document.getElementById('maChuyen-input');
      if (maChuyenInput) maChuyenInput.value = props.maChuyen || '';

      let slotHienThi = (props.soChoConLai ?? 0) - 1;
      if (slotHienThi < 0) slotHienThi = 0;
      document.getElementById('so-slot-display').textContent = slotHienThi;



      function formatDateForLaravel(dateStr) {
        const d = new Date(dateStr);
        return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
      }

      function formatDateForDisplay(dateStr) {
        const d = new Date(dateStr);
        return `${String(d.getDate()).padStart(2,'0')}/${String(d.getMonth()+1).padStart(2,'0')}/${d.getFullYear()}`;
      }

      document.querySelector('input[name="ngayBatDau"]').value = formatDateForLaravel(info.event.startStr);
      document.querySelector('input[name="ngayKetThuc"]').value = formatDateForLaravel(props.ngayKetThuc);

      const ngayBatDauEl = document.querySelector('.ngayBatDauDisplay');
      const ngayKetThucEl = document.querySelector('.ngayKetThucDisplay');
      if (ngayBatDauEl) ngayBatDauEl.textContent = formatDateForDisplay(info.event.startStr);
      if (ngayKetThucEl) ngayKetThucEl.textContent = formatDateForDisplay(props.ngayKetThuc);

      if (typeof window.updateDisplay === "function") window.updateDisplay();
      if (typeof window.refreshTotal === "function") window.refreshTotal();

      if (selectedEvent) {
        selectedEvent.setProp('backgroundColor', '#f95e4d');
        selectedEvent.setProp('borderColor', '#f95e4d');
      }

      info.event.setProp('backgroundColor', '#d94b3e');
      info.event.setProp('borderColor', '#d94b3e');

      selectedEvent = info.event;
    }
  });

  calendar.render();

  // Nút + / -
const slotInput = document.getElementById('so-slot-display');

document.querySelectorAll('.btn-plus, .btn-minus').forEach(btn => {
  btn.addEventListener('click', function () {
    const target = this.getAttribute('data-target');
    const isPlus = this.classList.contains('btn-plus');
    let currentSlot = parseInt(slotInput.textContent) || 0;

    // ⭐ TĂNG SỐ NGƯỜI
    if (isPlus) {
      if (currentSlot <= 0) {
        document.getElementById('toastMessage').textContent =
          "Bạn đã vượt quá số chỗ còn lại của chuyến đi."; 
        var errorToast = new bootstrap.Toast(document.getElementById('errorToast')); 
        errorToast.show(); 
        return;
      }

      counts[target] += 1;
      slotInput.textContent = currentSlot - 1; // TRỪ SLOT
    }

    // ⭐ GIẢM SỐ NGƯỜI
    if (!isPlus) {
      if (counts[target] <= 0) return;

      counts[target] -= 1;
      slotInput.textContent = currentSlot + 1; // CỘNG SLOT TRỞ LẠI
    }

    // Cập nhật UI
    document.getElementById(target + '-count').textContent = counts[target];

    if (typeof window.updateDisplay === "function") window.updateDisplay();
    if (typeof window.refreshTotal === "function") window.refreshTotal();

    updateTravelerList();
  });
});

// =======================
// ⭐ FORM DANH SÁCH HÀNH KHÁCH (UL / LI)
// =======================

const travelerList = document.getElementById("traveler-list");

// Hàm cập nhật <ul>
function updateTravelerList() {
  if (!travelerList) return;

  travelerList.innerHTML = ""; // Xóa tất cả

  // Người lớn
  for (let i = 1; i <= counts.adult; i++) {
    addTravelerItem("adult", i);
  }

  // Trẻ em
  for (let i = 1; i <= counts.child; i++) {
    addTravelerItem("child", i);
  }

  // Em bé
  for (let i = 1; i <= counts.baby; i++) {
    addTravelerItem("baby", i);
  }
  if (typeof window.updateDisplay === "function") window.updateDisplay();

}

// Hàm thêm từng <li>
function addTravelerItem(type, index) {
  const tpl = document.getElementById(`tpl-${type}`);
  if (!tpl) return;

  const clone = tpl.content.cloneNode(true);

  // Set title
  const title = clone.querySelector(".traveler-title");
  if (type === "adult") title.textContent = `Người lớn #${index}`;
  if (type === "child") title.textContent = `Trẻ em #${index}`;
  if (type === "baby") title.textContent = `Em bé #${index}`;

  // Append trước
  travelerList.appendChild(clone);

  // GẮN SỰ KIỆN CHO CHECKBOX VỪA TẠO
  const newItem = travelerList.querySelector(".traveler-item:last-child");
  const checkbox = newItem.querySelector(".phong-don-checkbox");

  if (checkbox) {
    checkbox.addEventListener("change", function () {
      window.updateDisplay();
      if (typeof window.refreshTotal === "function") window.refreshTotal();
    });
  }
}


// =======================
// ⭐ TÍCH HỢP VÀO NÚT + / –
// =======================

// Lần đầu tải trang → tạo ngay danh sách form
updateTravelerList();

  
});
