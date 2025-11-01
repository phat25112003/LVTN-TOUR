// Hàm định dạng số tiền (được tách ra từ file Blade)
function number_format(number) {
    if (typeof number !== 'number') {
        number = parseInt(number);
    }
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// 1. Lấy dữ liệu từ biến toàn cục đã được gán trong Blade
// Dùng window.chartData (Tên biến này sẽ được gán trong file Blade)
const rawRevenueData = window.chartData || []; 

// 2. Khởi tạo biểu đồ khi DOM đã được tải
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart');
    
    if (ctx) {
        const revenueChart = new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                datasets: [{
                    label: 'Doanh Thu (VNĐ)',
                    data: rawRevenueData,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return number_format(value) + ' VNĐ';
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += number_format(context.parsed.y) + ' VNĐ';
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }
});