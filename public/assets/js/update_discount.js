document.addEventListener("DOMContentLoaded", function () {
    // ... giữ nguyên phần khai báo element trên đầu file của bạn ...
    const grandTotalInput = document.getElementById("grand-total-input");
    const finalTotalTag = document.getElementById("final-total");

    const discountRow = document.getElementById("discount-row");
    const discountAmountTag = document.getElementById("discount-amount");

    const promoInput = document.getElementById("promo-code-input");
    const promoSuccess = document.getElementById("promo-success");
    const promoError = document.getElementById("promo-error");
    const appliedCodeTag = document.getElementById("applied-code");

    const maKMInput = document.getElementById("maKM-input");
    const giamGiaInput = document.getElementById("giaGiam-input");

    window.discountInfo = {
        type: null,
        value: 0,
        maKM: null,
        tenKM: null,
        max: 0
    };
    window.currentDiscount = 0;

    function formatMoney(amount) {
        return Number(amount).toLocaleString("vi-VN") + " ₫";
    }

    // ----- helper: parse số an toàn (loại bỏ dấu . , ₫ và khoảng trắng) -----
    function parseNumberSafe(v) {
        if (v === null || v === undefined) return 0;
        if (typeof v === 'number') return v;
        // loại bỏ tất cả ký tự không phải số và dấu trừ
        const cleaned = String(v).replace(/[^\d\-]/g, '');
        return cleaned === '' ? 0 : Number(cleaned);
    }

    // ================== RECALCULATE DISCOUNT (sửa) ==================
    window.recalculateDiscount = function (baseTotal) {
        baseTotal = parseNumberSafe(baseTotal || 0);

        let discount = 0;

        // đảm bảo discountInfo.value là số
        const val = parseNumberSafe(window.discountInfo.value);
        const maxCap = parseNumberSafe(window.discountInfo.max);

        if (window.discountInfo.type === "percent" && val > 0) {
            // tính phần trăm trước, dùng Math.floor để tránh số lẻ
            discount = Math.floor(baseTotal * val / 100);
        } else if (window.discountInfo.type === "fixed" && val > 0) {
            discount = val;
        }

        // --- apply max cap nếu tồn tại ---
        if (maxCap > 0) {
            // clamp discount vào [0, maxCap]
            discount = Math.min(discount, maxCap);
        }

        // cuối cùng, không thể lớn hơn baseTotal
        discount = Math.min(discount, baseTotal);

        // ngăn âm
        discount = Math.max(0, discount);

        window.currentDiscount = discount;

        // cập nhật UI
        if (window.currentDiscount > 0) {
            discountAmountTag.textContent = "- " + formatMoney(window.currentDiscount);
            discountRow.classList.remove("d-none");
        } else {
            discountRow.classList.add("d-none");
            discountAmountTag.textContent = "-0 ₫";
        }

        // ghi vào hidden để submit
        giamGiaInput.value = window.currentDiscount;
        maKMInput.value = window.discountInfo.maKM || "";

        // debug log (bỏ khi đã ok)
        console.debug("[recalculateDiscount] baseTotal:", baseTotal,
                      "type:", window.discountInfo.type,
                      "value:", val,
                      "max:", maxCap,
                      "discountApplied:", window.currentDiscount);

        return window.currentDiscount;
    };

    // ================== REFRESH TOTAL (giữ) ==================
    window.refreshTotal = function () {
        const baseTotal = parseNumberSafe(document.getElementById("base-total-input").value) || 0;
        window.recalculateDiscount(baseTotal);

        const final = Math.max(0, baseTotal - window.currentDiscount);
        finalTotalTag.textContent = formatMoney(final);

        // lưu để submit
        grandTotalInput.value = final;
        giamGiaInput.value = window.currentDiscount;
    };

    // ================== LOAD SAVED PROMO (sửa, robust) ==================
    (function loadSavedPromo() {
        try {
            const savedMaKM = (document.getElementById("maKM-input")?.value ?? '').toString().trim();
            const savedLoai = (document.getElementById("loaiKM-input")?.value ?? '').toString().trim();
            const savedGiaTriRaw = document.getElementById("giaTriKM-input")?.value ?? '0';
            const savedMaxRaw = document.getElementById("giaTriToiDa-input")?.value ?? 
                                document.getElementById("giaGiam-input")?.dataset?.max ?? '0';

            const savedGiaTri = parseNumberSafe(savedGiaTriRaw);
            const savedMax = parseNumberSafe(savedMaxRaw);

            console.debug("[loadSavedPromo] maKM:", savedMaKM, "loai:", savedLoai, "giaTri:", savedGiaTri, "max:", savedMax);

            if (!savedMaKM) {
                // không có mã => không áp
                return;
            }

            window.discountInfo = {
                type: savedLoai || null,
                value: savedGiaTri || 0,
                max: savedMax || 0,
                maKM: savedMaKM,
                tenKM: ""
            };

            // tính lại ngay
            window.refreshTotal();

            if (window.currentDiscount > 0) {
                discountRow.classList.remove("d-none");
                discountAmountTag.textContent = "- " + formatMoney(window.currentDiscount);
                promoSuccess.classList.remove("d-none");
                appliedCodeTag.textContent = savedMaKM;
            } else {
                discountRow.classList.add("d-none");
            }

        } catch (err) {
            console.error("loadSavedPromo error:", err);
        }
    })();

    // rest: remove promo, submit etc - giữ nguyên
    const removePromoBtn = document.getElementById("remove-promo-btn");
    if (removePromoBtn) {
        removePromoBtn.addEventListener("click", function () {
            window.discountInfo = { type: null, value: 0, maKM: null, tenKM: null, max: 0 };
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

    // initial calc
    refreshTotal();

    const form = document.querySelector("form");
    if (form) {
        form.addEventListener("submit", function () {
            window.refreshTotal();
        });
    }
});
