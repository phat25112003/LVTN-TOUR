document.addEventListener("DOMContentLoaded", function () {
    // ================== ELEMENTS ==================
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

    // ================== VARIABLES ==================
    window.discountInfo = {
        type: null, // 'percent' hoặc 'fixed'
        value: 0,
        maKM: null,
        tenKM: null
    };

    window.currentDiscount = 0;

    // ================== HELPERS ==================
    function formatMoney(amount) {
        return amount.toLocaleString("vi-VN") + " ₫";
    }

    function showError(msg) {
        promoError.textContent = msg;
        promoError.classList.remove("d-none");
        promoSuccess.classList.add("d-none");
    }

    function clearError() {
        promoError.classList.add("d-none");
        promoError.textContent = "";
    }

    // ================== RECALCULATE DISCOUNT ==================
    window.recalculateDiscount = function (baseTotal) {
        let discount = 0;

        if (window.discountInfo.type === "percent" && window.discountInfo.value > 0) {
            discount = Math.floor(baseTotal * window.discountInfo.value / 100);
        } else if (window.discountInfo.type === "fixed" && window.discountInfo.value > 0) {
            discount = window.discountInfo.value;
        }

        window.currentDiscount = Math.min(discount, baseTotal);

        if (window.currentDiscount > 0) {
            discountAmountTag.textContent = "- " + formatMoney(window.currentDiscount);
            discountRow.classList.remove("d-none");
        } else {
            discountRow.classList.add("d-none");
        }

        giamGiaInput.value = window.currentDiscount;
        maKMInput.value = window.discountInfo.maKM || "";

        return window.currentDiscount;
    };

    // ================== REFRESH TOTAL ==================
    window.refreshTotal = function () {
        const baseTotal = parseInt(document.getElementById("base-total-input").value) || 0;
        window.recalculateDiscount(baseTotal);

        const final = Math.max(0, baseTotal - window.currentDiscount);
        finalTotalTag.textContent = formatMoney(final);

        // ✅ Ghi tổng cuối cùng vào input tongGia để lưu DB
        grandTotalInput.value = final;
        giamGiaInput.value = window.currentDiscount;
    };

    // ================== APPLY PROMO ==================
    if (applyBtn) {
        applyBtn.addEventListener("click", function () {
            clearError();
            const code = promoInput.value.trim().toUpperCase();
            if (!code) {
                showError("Vui lòng nhập mã giảm giá.");
                return;
            }

            // Giả lập: giảm 10% để test
            window.discountInfo = {
                type: "percent",
                value: 10,
                maKM: code,
                tenKM: ""
            };

            refreshTotal();
            promoSuccess.classList.remove("d-none");
            appliedCodeTag.textContent = code;
        });
    }

    // ================== REMOVE PROMO ==================
    const removePromoBtn = document.getElementById("remove-promo-btn");
    if (removePromoBtn) {
        removePromoBtn.addEventListener("click", function () {
            window.discountInfo = { type: null, value: 0, maKM: null, tenKM: null };
            window.currentDiscount = 0;
            discountRow.classList.add("d-none");
            discountAmountTag.textContent = "-0 ₫";
            promoSuccess.classList.add("d-none");
            appliedCodeTag.textContent = "";
            promoInput.value = "";
            refreshTotal();
            promoInput.focus();
        });
    }

    // ================== LOAD SAVED PROMO ==================
    (function loadSavedPromo() {
        const savedMaKM = maKMInput.value;
        const savedLoai = document.getElementById("loaiKM-input").value;
        const savedGiaTri = parseFloat(document.getElementById("giaTriKM-input").value) || 0;

        if (!savedMaKM) return;

        window.discountInfo = {
            type: savedLoai,
            value: savedGiaTri,
            maKM: savedMaKM,
            tenKM: ""
        };

        refreshTotal();

        discountRow.classList.remove("d-none");
        discountAmountTag.textContent = "- " + formatMoney(window.currentDiscount);
        promoSuccess.classList.remove("d-none");
        appliedCodeTag.textContent = savedMaKM;
    })();

    // ================== INITIAL CALC ==================
    refreshTotal();

    // ================== BEFORE SUBMIT ==================
    const form = document.querySelector("form");
    if (form) {
        form.addEventListener("submit", function () {
            // Đảm bảo cập nhật lại tổng cuối cùng trước khi gửi
            window.refreshTotal();
        });
    }
});
