// assets/js/discount.js (phiên bản đã sửa – hoạt động đúng)

document.addEventListener("DOMContentLoaded", function () {

    // Không lấy giá cố định nữa! Giá sẽ được lấy từ booking-calendar.js
    const grandTotalInput = document.getElementById("grand-total-input");
    const finalTotalTag = document.getElementById("final-total");

    const discountRow = document.getElementById("discount-row");
    const discountAmountTag = document.getElementById("discount-amount");

    const promoInput = document.getElementById("promo-code-input");
    const applyBtn = document.getElementById("apply-promo-btn");

    const promoSuccess = document.getElementById("promo-success");
    const promoError = document.getElementById("promo-error");
    const appliedCodeTag = document.getElementById("applied-code");

    const maKMInput = document.getElementById("maKM-input");
    const giamGiaInput = document.getElementById("giaGiam-input");

    // Biến toàn cục để booking-calendar.js có thể đọc
    window.currentDiscount = 0;

    // Helper
    function formatMoney(value) {
        return value.toLocaleString("vi-VN") + " ₫";
    }

    function showError(message) {
        promoError.textContent = message;
        promoError.classList.remove("d-none");
        promoSuccess.classList.add("d-none");
    }

    function clearError() {
        promoError.classList.add("d-none");
        promoError.textContent = "";
    }

    // Hàm cập nhật hiển thị giảm giá + tổng cuối cùng
    function applyDiscountVisual() {
        const grandTotal = parseInt(grandTotalInput.value) || 0;
        const finalTotal = Math.max(0, grandTotal - window.currentDiscount);

        if (window.currentDiscount > 0) {
            discountAmountTag.textContent = "- " + formatMoney(window.currentDiscount);
            discountRow.classList.remove("d-none");
        } else {
            discountRow.classList.add("d-none");
        }

        finalTotalTag.textContent = formatMoney(finalTotal);
    }

    // Gọi lại updateTotal từ booking-calendar.js (nếu có)
    function refreshTotal() {
        if (typeof window.updateTotal === "function") {
            window.updateTotal();
        }
        applyDiscountVisual();
    }

    // Áp dụng mã khuyến mãi
    applyBtn.addEventListener("click", function () {
        clearError();

        const code = promoInput.value.trim().toUpperCase();
        if (!code) {
            showError("Vui lòng nhập mã giảm giá.");
            return;
        }

        applyBtn.disabled = true;
        const applyTextEl = applyBtn.querySelector(".apply-text");
        const origText = applyTextEl ? applyTextEl.textContent : null;
        if (applyTextEl) applyTextEl.textContent = "Đang kiểm tra...";

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch("/khuyenmai/apply", {
            method: "POST",
            headers: {
                "Accept": "application/json",
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({
                code: code,
                maTour: window.tourId,
                maChuyen: document.getElementById("maChuyen-input").value || null,
                tongTien: parseInt(grandTotalInput.value) || 0,
                maNguoiDung: window.userId ?? null
            })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                showError(data.message || "Mã giảm giá không hợp lệ.");
                window.currentDiscount = 0;
                maKMInput.value = "";
                giamGiaInput.value = 0;
                discountRow.classList.add("d-none");
                refreshTotal();
                return;
            }

            // Thành công → lưu discount
            window.currentDiscount = parseInt(data.giaGiam ?? 0);
            maKMInput.value = data.maKM ?? "";
            giamGiaInput.value = window.currentDiscount;
            appliedCodeTag.textContent = code;

            promoSuccess.classList.remove("d-none");
            promoError.classList.add("d-none");

            // Quan trọng: Gọi lại hàm tính tổng từ booking-calendar.js
            refreshTotal();
        })
        .catch(err => {
            console.error(err);
            showError("Lỗi kết nối, vui lòng thử lại.");
            window.currentDiscount = 0;
            maKMInput.value = "";
            giamGiaInput.value = 0;
            discountRow.classList.add("d-none");
            refreshTotal();
        })
        .finally(() => {
            applyBtn.disabled = false;
            if (applyTextEl && origText) applyTextEl.textContent = origText;
        });
    });

// ==================== NÚT GỠ MÃ GIẢM GIÁ ====================
const removePromoBtn = document.getElementById("remove-promo-btn");

if (removePromoBtn) {
    removePromoBtn.addEventListener("click", function () {
        // Reset toàn bộ
        window.currentDiscount = 0;
        document.getElementById("maKM-input").value = "";
        document.getElementById("giaGiam-input").value = 0;

        // Ẩn dòng giảm giá
        document.getElementById("discount-row").classList.add("d-none");
        document.getElementById("discount-amount").textContent = "-0 ₫";

        // Ẩn thông báo thành công + xóa mã đã hiển thị
        document.getElementById("promo-success").classList.add("d-none");
        document.getElementById("applied-code").textContent = "";

        // Xóa text trong ô nhập mã
        document.getElementById("promo-code-input").value = "";

        // Cập nhật lại tổng tiền
        refreshTotal();

        // Focus lại ô nhập để khách nhập mã khác
        document.getElementById("promo-code-input").focus();
    });
}
});