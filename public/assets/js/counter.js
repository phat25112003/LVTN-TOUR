/* ✅ Xử lý sự kiện cộng/trừ số lượng */
document.addEventListener("DOMContentLoaded", function () {
  const showError = (msg) => {
    const errorDiv = document.getElementById("error-message");
    if (errorDiv) errorDiv.textContent = msg;
  };

  const updateTotalPrice = () => {
    const adultCount = parseInt(document.getElementById("adult-count").textContent);
    const childCount = parseInt(document.getElementById("child-count").textContent);
    const adultPrice = parseFloat(document.getElementById("adult-price").value) || 0;
    const childPrice = parseFloat(document.getElementById("child-price").value) || 0;
    const adultTotal = adultCount * adultPrice;
    const childTotal = childCount * childPrice;
    const grandTotal = adultTotal + childTotal;
    document.getElementById("adult-total").textContent = adultTotal.toLocaleString() + "Đ";
    document.getElementById("child-total").textContent = childTotal.toLocaleString() + "Đ";
    document.getElementById("grand-total").textContent = grandTotal.toLocaleString() + "Đ";
    document.getElementById("adult-input").value = adultCount;
    document.getElementById("child-input").value = childCount;
    document.getElementById("grand-total-input").value = grandTotal;
  };

  document.querySelectorAll(".btn-plus, .btn-minus").forEach((btn) => {
    btn.addEventListener("click", function () {
      const target = this.getAttribute("data-target"); // adult hoặc child
      const countSpan = document.getElementById(target + "-count");
      const input = document.getElementById(target + "-input");
      const adultCount = parseInt(document.getElementById("adult-count").textContent);
      const childCount = parseInt(document.getElementById("child-count").textContent);
      const slot = parseInt(document.getElementById("slot").value);
    const toastEl = document.getElementById("errorToast");
    const toastMessage = document.getElementById("toastMessage");
    let toastInstance;
        // Tổng người hiện tại (trước khi thay đổi)
        let totalPeople = adultCount + childCount;

      let value = parseInt(countSpan.textContent);
      const showToast = (msg) => {
        if (!toastEl || !toastMessage) return;
        toastMessage.textContent = msg;
        if (!toastInstance) toastInstance = new bootstrap.Toast(toastEl, { delay: 3000 });
        toastInstance.show();
    };

      // Nếu là nút cộng
      if (this.classList.contains("btn-plus")) {
        if (totalPeople >= slot) {
            showToast("❌ Số lượng người đã đạt tối đa (" + slot + " người)");
            return; // không cho tăng thêm
          }
        value++;
        showError("");
      } 
      // Nếu là nút trừ
      else if (this.classList.contains("btn-minus")) {
        if (target === "adult" && value <= 1) {
          // Không cho người lớn < 1
          showError("❌ Mỗi tour phải có ít nhất 1 người lớn");
          return;
        }
        if (value > 0) {
          value--;
          showError("");
        }
      }

      // Cập nhật lại hiển thị
      countSpan.textContent = value;
      if (input) input.value = value;
      updateTotalPrice();
    });
  });

  updateTotalPrice();
});
