
document.addEventListener("DOMContentLoaded", function() {
  const paymentMethods = document.querySelectorAll(".payment-method");

  paymentMethods.forEach(method => {
    method.addEventListener("click", () => {
      // Bỏ active ở tất cả
      paymentMethods.forEach(m => m.classList.remove("active"));

      // Gán active cho phần được chọn
      method.classList.add("active");

      // Đánh dấu radio input tương ứng
      const input = method.querySelector("input[type='radio']");
      if (input) input.checked = true;
    });
  });
});

