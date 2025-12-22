// update_counter.js (PHIÊN BẢN FULL – ĐÃ TÍCH HỢP CALENDAR + AUTO JUMP TO FIRST EVENT)

document.addEventListener('DOMContentLoaded', function () {
  
  function showErrorToast(message) {
      const toastEl = document.getElementById('errorToast');
      const toastBody = document.getElementById('toastMessage');

      if (!toastEl || !toastBody) {
          console.warn('Toast element không tồn tại!');
          alert(message);
          return;
      }

      if (typeof bootstrap === 'undefined') {
          console.error('Bootstrap chưa load!');
          alert(message);
          return;
      }

      toastBody.textContent = message;
      const toast = new bootstrap.Toast(toastEl, { delay: 6000 });
      toast.show();
  }

  // ==========================
  //  PHẦN ELEMENTS
  // ==========================
  const adultInput = document.getElementById('adult-input');
  const childInput = document.getElementById('child-input');
  const babyInput = document.getElementById('baby-input');

  const adultTotalTag = document.getElementById('adult-total');
  const childTotalTag = document.getElementById('child-total');
  const babyTotalTag = document.getElementById('baby-total');

  const grandTotalTag = document.getElementById('grand-total');
  const baseTotalInput = document.getElementById('base-total-input');

  const adultCountDisplay = document.getElementById('adult-count-display');
  const childCountDisplay = document.getElementById('child-count-display');
  const babyCountDisplay = document.getElementById('baby-count-display');
  const slotDisplay = document.getElementById('so-slot-display')

  // ==========================
  //  GIÁ HIỆN TẠI — SẼ BỊ GHI ĐÈ KHI CLICK CALENDAR
  // ==========================
  let currentPrices = {
    adult: parseInt(document.getElementById('adult-price').value) || 0,
    child: parseInt(document.getElementById('child-price').value) || 0,
    baby: parseInt(document.getElementById('baby-price').value) || 0
  };
  let giaPhongDon = window.initialPrices?.phongDon || 0;
  let soPhongDon = 0;


  // ==========================
  //  SỐ LƯỢNG BAN ĐẦU
  // ==========================
  let counts = {
    adult: parseInt(document.getElementById('adult-count').textContent) || 1,
    child: parseInt(document.getElementById('child-count').textContent) || 0,
    baby: parseInt(document.getElementById('baby-count').textContent) || 0
  };

  // ==========================
  //  HÀM TÍNH TỔNG GỐC
  // ==========================
  function calculateBaseTotal() {
    return counts.adult * currentPrices.adult +
           counts.child * currentPrices.child +
           counts.baby * currentPrices.baby;
  }

  // ==========================
  //  CẬP NHẬT HIỂN THỊ
  // ==========================
  function updateDisplay() {
    const baseTotal = calculateBaseTotal();
    const totalPhongDon = soPhongDon * giaPhongDon;
    const finalTotal = baseTotal + totalPhongDon;

    const format = (amount) => new Intl.NumberFormat('vi-VN', {
      style: 'currency',
      currency: 'VND'
    }).format(amount);

    // Người lớn
    document.getElementById('adult-unit-price').textContent = format(currentPrices.adult);
    document.getElementById('adult-count-strong').textContent = counts.adult;
    adultTotalTag.textContent = format(counts.adult * currentPrices.adult);
    adultCountDisplay.textContent = `× ${counts.adult}`;

    // Trẻ em
    const childRow = document.getElementById('child-price-row');
    if (counts.child > 0) {
      childRow.style.display = 'flex';
      document.getElementById('child-unit-price').textContent = format(currentPrices.child);
      document.getElementById('child-count-strong').textContent = counts.child;
      childTotalTag.textContent = format(counts.child * currentPrices.child);
      childCountDisplay.textContent = `× ${counts.child}`;
    } else {
      childRow.style.display = 'none';
    }

    // Em bé
    const babyRow = document.getElementById('baby-price-row');
    if (counts.baby > 0) {
      babyRow.style.display = 'flex';
      document.getElementById('baby-unit-price').textContent = format(currentPrices.baby);
      document.getElementById('baby-count-strong').textContent = counts.baby;
      babyTotalTag.textContent = format(counts.baby * currentPrices.baby);
      babyCountDisplay.textContent = `× ${counts.baby}`;
    } else {
      babyRow.style.display = 'none';
    }

    // Tổng tiền gốc
    grandTotalTag.textContent = format(finalTotal);
    baseTotalInput.value = finalTotal;


    // Input ẩn
    adultInput.value = counts.adult;
    childInput.value = counts.child;
    babyInput.value = counts.baby;

    // gọi giảm giá
    if (typeof window.refreshTotal === 'function') {
      window.refreshTotal();
    }
  }
  // ===============================
// ⭐ HÀM QUẢN LÝ FORM NHẬP THÔNG TIN KHÁCH
// ===============================
function addTravelerForm(type) {
    const list = document.getElementById("traveler-list");
    const tpl = document.getElementById(`tpl-${type}`);
    if (!tpl) return;

    const index = list.children.length + 1;
    const clone = tpl.content.cloneNode(true);

    clone.querySelector(".traveler-title").innerText =
        `${type === 'adult' ? 'Người lớn' : type === 'child' ? 'Trẻ em' : 'Em bé'} #${index}`;

    list.appendChild(clone);

    // xử lý phòng đơn nếu có
    const checkbox = list.querySelector(".traveler-item:last-child .phong-don-checkbox");
    if (checkbox) {
        checkbox.addEventListener("change", function () {
            if (this.checked) soPhongDon++;
            else soPhongDon--;
            updateDisplay();
        });
    }
}

function removeLastFormOfType(type) {
    const list = document.getElementById("traveler-list");
    const items = Array.from(list.children);

    // tìm item cuối cùng đúng loại
    for (let i = items.length - 1; i >= 0; i--) {
        const hiddenType = items[i].querySelector('input[name="loaiKhach[]"]');
        if (hiddenType && hiddenType.value === type) {

            // nếu có checkbox phòng đơn → trừ
            const checkbox = items[i].querySelector(".phong-don-checkbox");
            if (checkbox && checkbox.checked) {
                soPhongDon--;
            }

            list.removeChild(items[i]);
            break;
        }
    }
}


// ==========================
//  NÚT + / - VỚI KIỂM TRA SLOT NGHIÊM NGẶT
// ==========================
document.querySelectorAll('.btn-plus, .btn-minus').forEach(btn => {
    btn.addEventListener('click', function () {

        const type = this.getAttribute('data-target'); // adult, child, baby
        const change = this.classList.contains('btn-plus') ? 1 : -1;

        let newCount = counts[type] + change;

        // Không cho giảm người lớn < 1
        if (newCount < 0 || (type === 'adult' && newCount === 0)) {
            return;
        }

        let currentSlots = parseInt(slotDisplay.textContent) || 0;

        // ⭐ TĂNG → slot phải giảm
        if (change > 0) {
            if (currentSlots <= 0) {
                showErrorToast("Không còn chỗ trống!");
                return;
            }
            currentSlots--;
            addTravelerForm(type);   // ⭐ thêm form
        }

        // ⭐ GIẢM → slot phải tăng
        if (change < 0) {
            currentSlots++;
            removeLastFormOfType(type); // ⭐ xóa form
        }

        // Lưu lại
        counts[type] = newCount;
        slotDisplay.textContent = currentSlots;

        document.getElementById(type + '-count').textContent = newCount;

        updateDisplay();
    });
});



  // ==========================
  //  TÍCH HỢP FULLCALENDAR
  // ==========================
  const calendarEl = document.getElementById('calendar');
  let selectedEvent = null;

  if (calendarEl) {

    let movedToFirstEvent = false; // chỉ nhảy lần đầu

    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      locale: 'vi',
      timeZone: 'local',
      displayEventTime: false,
      height: 'auto',
      // headerToolbar: {
      // left: '',
      // center: 'title',
      // right: ''},
      events: `/api/tour-dates/${window.tourId}`,


      // TỰ ĐỘNG NHẢY ĐẾN THÁNG CÓ SỰ KIỆN ĐẦU TIÊN 
      eventsSet: function(events) {
        if (movedToFirstEvent || events.length === 0) return;

        const firstEvent = events.reduce((a, b) =>
          new Date(a.start) < new Date(b.start) ? a : b
        );

        if (firstEvent) {
          calendar.gotoDate(firstEvent.start); // ← NHẢY ĐẾN THÁNG CÓ SỰ KIỆN
          movedToFirstEvent = true;
        }
      },


      // CLICK EVENT
      eventClick: function (info) {
        const props = info.event.extendedProps;

        // ghi đè giá theo chuyến tour
        currentPrices = {
          adult: props.giaNguoiLon,
          child: props.giaTreEm,
          baby: props.giaEmBe
        };

        // hiển thị mã chuyến
        document.getElementById('ma-chuyen-display').textContent = props.maChuyen || '-';
        const maChuyenInput = document.getElementById('maChuyen-input');
        if (maChuyenInput) maChuyenInput.value = props.maChuyen || '';

        // hiển thị số chỗ còn lại
        if (slotDisplay) {
            let slots = props.soChoConLai ?? 0;

            // ⭐ Trừ đi 1 slot vì mặc định đã có 1 người lớn
            slots = slots - 1;
            if (slots < 0) slots = 0;

            slotDisplay.textContent = slots;
            availableSlots = parseInt(slots);
        }

        // format ngày
        function f(dateStr) {
          const d = new Date(dateStr);
          const y = d.getFullYear();
          const m = String(d.getMonth() + 1).padStart(2, '0');
          const day = String(d.getDate()).padStart(2, '0');
          return `${y}-${m}-${day}`;
        }

        function fVN(dateStr) {
          const d = new Date(dateStr);
          return `${String(d.getDate()).padStart(2, '0')}/${String(d.getMonth() + 1).padStart(2, '0')}/${d.getFullYear()}`;
        }

        // set input ngày
        document.querySelector('input[name="ngayBatDau"]').value = f(info.event.startStr);
        document.querySelector('input[name="ngayKetThuc"]').value = f(props.ngayKetThuc);

        // set hiển thị
        document.querySelector('.ngayBatDauDisplay').textContent = fVN(info.event.startStr);
        document.querySelector('.ngayKetThucDisplay').textContent = fVN(props.ngayKetThuc);

        // cập nhật UI
        updateDisplay();

        // highlight event đã chọn
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
  }
    // ==========================
  //  LOAD DANH SÁCH KHÁCH THAM GIA (TỪ DB)
  // ==========================
  if (window.khachThamGia && Array.isArray(window.khachThamGia)) {

      const list = document.getElementById("traveler-list");

      const tplAdult = document.getElementById("tpl-adult");
      const tplChild = document.getElementById("tpl-child");
      const tplBaby  = document.getElementById("tpl-baby");

      window.khachThamGia.forEach((khach, index) => {
          let tpl;

          // Phân loại theo tuổi
          if (khach.tuoi >= 13) tpl = tplAdult;
          else if (khach.tuoi >= 6) tpl = tplChild;
          else tpl = tplBaby;

          const clone = tpl.content.cloneNode(true);

          // Gắn title
          clone.querySelector(".traveler-title").innerText =
              `Khách #${index + 1}`;

          // Điền họ tên
          clone.querySelector('input[name="hoTenKhach[]"]').value =
              khach.hoTenKhach;

          // Điền giới tính
          clone.querySelector('select[name="gioiTinh[]"]').value =
              khach.gioiTinh;

          // Điền tuổi
          clone.querySelector('input[name="tuoi[]"]').value =
              khach.tuoi;

          // Nếu là người lớn → set phòng đơn
          const checkboxPhong = clone.querySelector(".phong-don-checkbox");
          if (checkboxPhong) {
              const isPhongDon = (khach.luaChonPhong === "PhongDon");
              checkboxPhong.checked = isPhongDon;

              if (isPhongDon) {
                  soPhongDon++;   // 🔥 QUAN TRỌNG
              }
          }


          list.appendChild(clone);
      });
  }
  // ==========================
//  LẮNG NGHE TOGGLE PHÒNG ĐƠN
// ==========================
document.addEventListener("change", function (e) {
    if (!e.target.classList.contains("phong-don-checkbox")) return;

    if (e.target.checked) {
        soPhongDon++;
    } else {
        soPhongDon--;
    }

    updateDisplay();
  });


  // ==========================
  //  KHỞI TẠO LẦN ĐẦU
  // ==========================
  updateDisplay();

});
