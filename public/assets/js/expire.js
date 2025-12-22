document.addEventListener("DOMContentLoaded", function () {

    if (!window.countdowns) return;

    window.countdowns.forEach(function (item) {
        const expire = new Date(item.expire_at).getTime();
        const el = document.getElementById("countdown-" + item.id);

        if (!el) return; // nếu phần tử chưa mở collapse thì bỏ qua

        setInterval(function () {
            const now = new Date().getTime();
            const distance = expire - now;

            if (distance <= 0) {
                el.innerHTML = "Đã hết hạn!";
                el.classList.add('text-danger');
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            let text = "";
            if (days > 0) text += days + " ngày ";
            text += hours + " giờ " + minutes + " phút " + seconds + " giây";

            el.textContent = text;

        }, 1000);
    });

});
