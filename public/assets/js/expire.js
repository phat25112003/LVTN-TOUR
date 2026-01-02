document.addEventListener("DOMContentLoaded", function () {

    if (!window.countdowns) return;

    window.countdowns.forEach(function (item) {
        const expire = new Date(item.ngayhethan).getTime();
        const el = document.getElementById("countdown-" + item.id);

        if (!el) return;

        const timer = setInterval(function () {
            const now = Date.now();
            const distance = expire - now;

            if (distance <= 0) {
                clearInterval(timer);
                el.innerHTML = "Đã hết hạn!";
                el.classList.add('text-danger');
                return;
            }

            const days = Math.floor(distance / 86400000);
            const hours = Math.floor((distance % 86400000) / 3600000);
            const minutes = Math.floor((distance % 3600000) / 60000);
            const seconds = Math.floor((distance % 60000) / 1000);

            let text = "";
            if (days > 0) text += days + " ngày ";
            text += hours + " giờ " + minutes + " phút " + seconds + " giây";

            el.textContent = text;
        }, 1000);
    });

});
