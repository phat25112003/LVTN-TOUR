@extends('admin.layouts.dashboard')

@section('content')

@push('styles')
    <style>
        .dashboard-container { max-width: 100%; margin: 0 auto; padding: 20px; }
        h2 { font-size: 1.5rem; color: #495057; margin-bottom: 15px; border-bottom: 2px solid #e9ecef; padding-bottom: 10px; }
        
        .metric-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr); 
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card { 
            color: white; 
            padding: 25px; 
            border-radius: 8px; 
            text-align: center; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.15); 
            transition: all 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px) scale(1.02); box-shadow: 0 8px 20px rgba(0,0,0,0.2); }
        .stat-card p:first-child { font-size: 0.9rem; margin-bottom: 5px; opacity: 0.9; }
        .stat-card p:last-child { font-size: 1.8rem; font-weight: bold; margin-top: 0; }
        
        .top-data-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 30px; 
        }

        .bottom-chart-row {
            margin-top: 20px;
        }

        .chart-box, .table-box {
            background-color: #ffffff; 
            padding: 20px; 
            border-radius: 8px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            min-height: 380px; 
        }
        .chart-box h2, .table-box h2 { font-size: 1.3rem; }

        .pie-chart-wrapper {
            position: relative;
            height: 300px; 
            max-width: 80%; 
            margin: 0 auto;
        }
        
        .revenue-chart-wrapper {
            position: relative;
            height: 350px; 
        }
        
        .table-box table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table-box th, .table-box td { text-align: left; padding: 10px; border-bottom: 1px solid #f1f1f1; font-size: 0.9em; }
        .table-box th { background-color: #f8f9fa; color: #333; }
        .table-box tr:hover { background-color: #fafafa; }
        
        .loading-placeholder { 
            height: 250px; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            color: #6c757d; 
            font-size: 1em;
            background-color: #f7f7f7;
            border-radius: 6px;
        }
    </style>
@endpush

@section('content')

    <div class="dashboard-container">
        <h1 class="text-center mb-4 fw-bold text-primary">Dashboard Tổng Quát</h1>

        <div class="metric-row">
            <div class="stat-card" style="background: linear-gradient(135deg, #1cc88a 0%, #17a673 100%);">
                <p>Tổng Doanh Thu</p>
                <p>{{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</p>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);">
                <p>Tổng số Tours</p>
                <p>{{ number_format($totalTours) }} Tours</p>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #36b9cc 0%, #2c9faf 100%);">
                <p>Tổng Đơn Đặt Chỗ</p>
                <p>{{ number_format($totalBookings) }} Đơn</p>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #e74a3b 0%, #c43329 100%);">
                <p>Tổng Người Dùng</p>
                <p>{{ number_format($totalUsers) }} Người</p>
            </div>
        </div>

        <div class="top-data-row">
            
            <div class="chart-box">
                <h2>💳 Tỷ Lệ Thanh Toán (%)</h2>
                <div class="pie-chart-wrapper"> 
                    <div class="loading-placeholder" id="payment-loading">Đang tải biểu đồ thanh toán...</div>
                    <canvas id="paymentChart" style="display: none;"></canvas>
                </div>
            </div>

            <div class="table-box">
                <h2>🏆 Top 5 Tour Được Đặt Nhiều Nhất</h2>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tiêu đề Tour</th>
                            <th>Lượt đặt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topBookedTours as $index => $tour)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $tour->tieuDe }}</td>
                            <td>{{ number_format($tour->total_bookings) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
        </div>

        <div class="bottom-chart-row">
            <div class="chart-box">
                <h2 id="revenue-title">📈 Doanh Thu Theo Tháng</h2>
                {{-- THÊM WRAPPER ĐỂ THU NHỎ CHIỀU CAO --}}
                <div class="revenue-chart-wrapper">
                    <div class="loading-placeholder" id="revenue-loading">Đang tải biểu đồ doanh thu...</div>
                    <canvas id="revenueChart" style="display: none;"></canvas>
                </div>
            </div>
        </div>
        
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
    
    <script>
        Chart.register(ChartDataLabels);

        const chartDataUrl = '{{ route('admin.dashboard.charts') }}';
        const formatCurrency = (value) => numeral(value).format('0,0') + ' VND';

        // Hàm vẽ biểu đồ Doanh thu (Bar Chart)
        function drawRevenueChart(data) {
            document.getElementById('revenue-loading').style.display = 'none';
            document.getElementById('revenueChart').style.display = 'block';
            document.getElementById('revenue-title').textContent = `📈 Doanh Thu Theo Tháng (Năm ${data.currentYear})`;

            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Doanh thu (VND)',
                        data: data.data,
                        backgroundColor: 'rgba(78, 115, 223, 0.7)', 
                        borderColor: 'rgba(78, 115, 223, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    // Tắt tỷ lệ khung hình để biểu đồ thu nhỏ theo height của revenue-chart-wrapper (350px)
                    maintainAspectRatio: false, 
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Doanh thu' },
                            ticks: {
                                callback: (value) => numeral(value).format('0a')
                            }
                        },
                        x: { grid: { display: false } }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: { label: (context) => context.dataset.label + ': ' + formatCurrency(context.parsed.y) } },
                        datalabels: { display: false }
                    }
                }
            });
        }
        
        // Hàm vẽ biểu đồ Thanh toán (Pie Chart)
        function drawPaymentChart(data) {
            document.getElementById('payment-loading').style.display = 'none';
            document.getElementById('paymentChart').style.display = 'block';

            const paymentCtx = document.getElementById('paymentChart').getContext('2d');
            new Chart(paymentCtx, {
                type: 'pie',
                data: {
                    labels: data.labels,
                    datasets: [{
                        data: data.data,
                        backgroundColor: ['#4e73df', '#1cc88a', '#f63eedff', '#e74a3b', '#36b9cc'],
                        hoverBackgroundColor: ['#2e59d9', '#17a673', '#f63eedff', '#be2617', '#2c9faf'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    responsive: true,
                    // Tắt tỷ lệ khung hình để biểu đồ thu nhỏ theo height của pie-chart-wrapper (300px)
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: { callbacks: { label: (context) => context.label + ': ' + context.formattedValue + ' %' } },
                        datalabels: {
                            color: '#fff',
                            formatter: (value) => value + ' %',
                            font: { weight: 'bold', size: 14 }
                        }
                    }
                }
            });
        }

        // --- Hàm tải dữ liệu biểu đồ bằng AJAX ---
        function loadChartData() {
            fetch(chartDataUrl)
                .then(response => {
                    if (!response.ok) { throw new Error('Network response was not ok'); }
                    return response.json();
                })
                .then(data => {
                    drawPaymentChart(data.paymentChart);
                    drawRevenueChart(data.revenueChart);
                })
                .catch(error => {
                    console.error('Lỗi tải dữ liệu biểu đồ:', error);
                    document.getElementById('revenue-loading').innerHTML = 'Lỗi tải dữ liệu. Vui lòng thử lại.';
                    document.getElementById('payment-loading').innerHTML = 'Lỗi tải dữ liệu. Vui lòng thử lại.';
                });
        }

        document.addEventListener('DOMContentLoaded', loadChartData);
    </script>
@endpush