// discount.js
document.addEventListener("DOMContentLoaded", function () {
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

    // ⭐ BIẾN LƯU THÔNG TIN KHUYẾN MÃI
    window.discountInfo = {
        type: null,     // 'percent' hoặc 'fixed'
        value: 0,       // Giá trị % hoặc số tiền cố định
        maKM: null,
        tenKM: null,
        max: 0
    };

    window.currentDiscount = 0;

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

    // ⭐ TÍNH LẠI GIẢM GIÁ KHI SỐ LƯỢNG NGƯỜI THAY ĐỔI
    window.recalculateDiscount = function () {
        const total = parseInt(grandTotalInput.value) || 0;
        let discount = 0;

        // ⭐ 1) TÍNH GIẢM GIÁ THÔ
        if (window.discountInfo.type === 'percent' && window.discountInfo.value > 0) {
            discount = Math.floor(total * window.discountInfo.value / 100);
        } 
        else if (window.discountInfo.type === 'fixed' && window.discountInfo.value > 0) {
            discount = window.discountInfo.value;
        }

        // ⭐ 2) GIỚI HẠN BỞI giá trị tối đa (giaTriToiDa)
        if (window.discountInfo.max > 0) {
            discount = Math.min(discount, window.discountInfo.max);
        }

        // ⭐ 3) GIỚI HẠN BỞI TỔNG TIỀN
        window.currentDiscount = Math.min(discount, total);

        // ⭐ 4) Cập nhật giao diện
        if (window.currentDiscount > 0) {
            discountAmountTag.textContent = "- " + formatMoney(window.currentDiscount);
            discountRow.classList.remove("d-none");
        } else {
            discountRow.classList.add("d-none");
        }

        // ⭐ 5) cập nhật hidden inputs để gửi về server
        giamGiaInput.value = window.currentDiscount;
        maKMInput.value = window.discountInfo.maKM || "";

        return window.currentDiscount;
    };
    // ⭐ HÀM REFRESH TỔNG TIỀN CHÍNH - CHỈ TÍNH GIẢM GIÁ VÀ CẬP NHẬT TỔNG CUỐI
    window.refreshTotal = function () {
        // ⭐ QUAN TRỌNG: KHÔNG GỌI updateDisplay Ở ĐÂY
        
        // Tính lại giảm giá dựa trên tổng tiền mới
        window.recalculateDiscount();

        const grandTotal = parseInt(grandTotalInput.value) || 0;
        const final = Math.max(0, grandTotal - window.currentDiscount);

        // Cập nhật tổng thanh toán cuối cùng
        finalTotalTag.textContent = formatMoney(final);
    };

    // ===================== APPLY KHUYẾN MÃI =====================
    applyBtn.addEventListener("click", function () {
        clearError();
        const code = promoInput.value.trim().toUpperCase();

        if (!code) {
            showError("Vui lòng nhập mã giảm giá.");
            return;
        }

        applyBtn.disabled = true;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const requestData = {
            code: code,
            maTour: window.tourId,
            maChuyen: document.getElementById("maChuyen-input").value || null,
            tongTien: parseInt(grandTotalInput.value) || 0,
            maNguoiDung: window.userId ?? null,
        };

        console.log("Đang áp dụng mã:", code, requestData);

        fetch("/khuyenmai/apply", {
            method: "POST",
            headers: {
                "Accept": "application/json",
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify(requestData),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("Kết quả áp dụng mã:", data);
            
            if (!data.success) {
                showError(data.message || "Mã giảm giá không hợp lệ.");
                
                // Reset thông tin khuyến mãi
                window.discountInfo = { type: null, value: 0, maKM: null, tenKM: null };
                window.currentDiscount = 0;
                
                window.refreshTotal();
                return;
            }

            // ⭐ LƯU THÔNG TIN KHUYẾN MÃI ĐỂ TÍNH TOÁN LẠI SAU NÀY
            window.discountInfo = {
                type: data.type,        // 'percent' hoặc 'fixed'
                value: data.giaTri,     // Giá trị % hoặc số tiền
                max: data.giaTriToiDa ?? 0, 
                maKM: data.maKM,
                tenKM: data.tenKM
            };

            appliedCodeTag.textContent = code;
            if (data.tenKM) {
                appliedCodeTag.textContent = `${code} - ${data.tenKM}`;
            }
            
            promoSuccess.classList.remove("d-none");
            promoError.classList.add("d-none");

            // ⭐ TÍNH TOÁN VÀ HIỂN THỊ LẠI VỚI THÔNG TIN MỚI
            window.refreshTotal();
        })
        .catch(error => {
            console.error("Lỗi kết nối:", error);
            showError("Lỗi kết nối máy chủ: " + error.message);
        })
        .finally(() => {
            applyBtn.disabled = false;
        });
    });

    // ===================== GỠ MÃ =====================
    const removePromoBtn = document.getElementById("remove-promo-btn");
    if (removePromoBtn) {
        removePromoBtn.addEventListener("click", function () {
            // Reset hoàn toàn
            window.discountInfo = { type: null, value: 0, maKM: null, tenKM: null };
            window.currentDiscount = 0;

            discountRow.classList.add("d-none");
            discountAmountTag.textContent = "-0 ₫";
            promoSuccess.classList.add("d-none");
            appliedCodeTag.textContent = "";

            promoInput.value = "";

            window.refreshTotal();
            promoInput.focus();
        });
    }

    // ⭐ KHỞI TẠO BAN ĐẦU
    window.refreshTotal();
});