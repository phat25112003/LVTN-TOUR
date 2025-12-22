@extends('admin.layouts.dashboard')

@section('content')

    <div class="dashboard-container">
        <h1 class="text-center mb-4 fw-bold text-primary">Dashboard Tổng Quát</h1>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div>
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 style="color: white" >BÁO CÁO DOANH THU</h3>
                </div>
                <div class="card-body p-5">
                    <form method="POST" action="{{ route('admin.baocao.xuat') }}" class="row g-4">
                        @csrf

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Từ ngày</label>
                            <input type="date" name="tu_ngay" class="form-control" value="{{ old('tu_ngay', now()->subDays(30)->format('Y-m-d')) }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Đến ngày</label>
                            <input type="date" name="den_ngay" class="form-control" value="{{ old('den_ngay', now()->format('Y-m-d')) }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Định dạng xuất</label>
                            <div class="d-flex gap-4 mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="loai" value="pdf" checked>
                                    <label class="form-check-label">PDF</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="loai" value="excel">
                                    <label class="form-check-label">Excel</label>
                                </div>
                            </div>
                        </div>

                        <!-- TÙY CHỌN MỚI -->
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="chiDoanhThuDaThanhToan" name="chi_doanh_thu_da_tt" value="1" checked>
                                <label class="form-check-label fw-bold text-primary" for="chiDoanhThuDaThanhToan">
                                    Chỉ tính doanh thu từ các đơn đã thanh toán (khuyến nghị)
                                </label>
                            </div>
                            <small class="text-muted">Nếu bỏ chọn, hiển thị luôn chưa thanh toán.</small>
                        </div>

                        <div class="col-12 text-center mt-4">
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                Xuất Báo Cáo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
        <!-- 4 Ô CHÍNH -->
        <div class="metric-row">
            <div class="stat-card" style="background: linear-gradient(135deg, #1cc88a, #17a673);">
                <p>Tổng Doanh Thu</p>
                <p>{{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</p>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #f6c23e, #dda20a);">
                <p>Tổng Tours</p>
                <p>{{ number_format($totalTours) }}</p>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #36b9cc, #2c9faf);">
                <p>Tổng Đơn Hàng</p>
                <p>{{ number_format($totalBookings) }}</p>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #e74a3b, #c43329);">
                <p>Tổng Khách Hàng</p>
                <p>{{ number_format($totalUsers) }}</p>
            </div>
        </div>

        <!-- 4 Ô NHỎ SIÊU CHUẨN -->
        <div class="mini-stats">
            <!-- HÔM NAY -->
            <div class="mini-card today">
                <h5>Doanh thu hôm nay</h5>
                <p>
                    {{ number_format(
                        \App\Models\DatCho::whereDate('ngayDat', today())
                            ->join('thanhtoan', 'datcho.maDatCho', '=', 'thanhtoan.maDatCho')
                            ->sum('thanhtoan.soTien')
                    ) }} ₫
                </p>
            </div>

            <!-- THÁNG NÀY -->
            <div class="mini-card month">
                <h5>Doanh thu tháng {{ now()->format('m/Y') }}</h5>
                <p>
                    {{ number_format(
                        \App\Models\DatCho::whereMonth('ngayDat', now()->month)
                            ->whereYear('ngayDat', now()->year)
                            ->join('thanhtoan', 'datcho.maDatCho', '=', 'thanhtoan.maDatCho')
                            ->sum('thanhtoan.soTien')
                    ) }} ₫
                </p>
            </div>

            <!-- ĐƠN HÔM NAY -->
            <div class="mini-card orders">
                <h5>Đơn đặt hôm nay</h5>
                <p>
                    {{ \App\Models\DatCho::whereDate('ngayDat', today())->count() }}
                    <small style="font-size:0.7rem;display:block;color:#666;">
                        ({{ \App\Models\DatCho::whereDate('ngayDat', today())
                            ->join('thanhtoan', 'datcho.maDatCho', '=', 'thanhtoan.maDatCho')
                            ->count() }} đã thanh toán)
                    </small>
                </p>
            </div>

            <!-- TRUNG BÌNH/ĐƠN (toàn thời gian) -->
            <div class="mini-card avg">
                <h5>Trung bình mỗi đơn</h5>
                <p>
                    @php
                        $totalPaidOrders = \App\Models\Thanhtoan::count();
                        $totalRevenue    = \App\Models\Thanhtoan::sum('soTien');
                        $avg = $totalPaidOrders > 0 ? $totalRevenue / $totalPaidOrders : 0;
                    @endphp
                    {{ number_format($avg) }} ₫
                </p>
            </div>
        </div>

        <!-- BIỂU ĐỒ TRÒN + BẢNG TOP 5 TOUR – BỐ CỤC DỌC, SIÊU TO, SIÊU ĐẸP -->
        <div class="vertical-stats-container">

            <!-- 1. BIỂU ĐỒ TRÒN THANH TOÁN – FULL RỘNG -->
            <div class="chart-box full-width-chart">
                <h2>Tỷ Lệ Phương Thức Thanh Toán</h2>
                <div class="pie-chart-wrapper">
                    <div class="loading-placeholder" id="payment-loading">Đang tải biểu đồ thanh toán...</div>
                    <canvas id="paymentChart" style="display: none;"></canvas>
                </div>
            </div>

            <!-- 2. BẢNG TOP 5 TOUR – TO RÕ, CĂNG TRÀN MÀN HÌNH -->
            <div class="table-box full-width-table">
                <h2>Top 5 Tour Được Đặt Nhiều Nhất</h2>
                <div class="table-responsive-wrapper">
                    <table class="top-tours-table">
                        <thead>
                            <tr>
                                <th width="80">STT</th>
                                <th>Tiêu đề Tour</th>
                                <th width="150">Lượt đặt</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topBookedTours as $index => $tour)
                            <tr>
                                <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                <td class="tour-name">{{ $tour->tieuDe }}</td>
                                <td class="text-center text-danger fw-bold fs-5">{{ number_format($tour->total_bookings) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- HƯỚNG DẪN VIÊN – HÀNG NGANG ĐƠN GIẢN + NÚT TRÁI/PHẢI -->
        <div class="guides-simple-section">
            <h2> Hướng Dẫn Viên Hoạt Động</h2>

            @if($activeHuongDanViens->count() > 0)
                <div class="guides-slider-container">
                    <button class="slider-btn prev-btn" onclick="slideGuides(-1)">←</button>
                    
                    <div class="guides-slider-wrapper">
                        <div class="guides-slider-track" id="guidesSliderTrack">
                            @foreach($activeHuongDanViens as $hdv)
                                <div class="guide-simple-card">
                                    <img src="{{ $hdv->avatar_url ?? asset('images/default-avatar.jpg') }}"
                                        alt="{{ $hdv->hoTen }}"
                                        class="guide-avatar"
                                        onerror="this.onerror=null;this.src='https://i.imgur.com/8Qz8o0Z.png'">
                                    <div class="guide-simple-info">
                                        <h4>{{ $hdv->hoTen }}</h4>
                                        <p>{{ $hdv->chuyen_tours_count ?? 0 }} chuyến đang dẫn</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <button class="slider-btn next-btn" onclick="slideGuides(1)">→</button>
                </div>
            @else
                <p class="text-center text-muted py-5">Chưa có hướng dẫn viên nào đang hoạt động.</p>
            @endif
        </div>

        <!-- BIỂU ĐỒ DOANH THU TÙY CHỌN -->
        <div class="bottom-chart-row">
            <div class="chart-box">
                <h2 id="revenue-title">📈 Doanh Thu Theo Thời Gian</h2>
                <form id="revenue-filter-form" class="filter-form">
                    <select name="range">
                        <option value="7days">7 ngày qua</option>
                        <option value="30days">30 ngày qua</option>
                        <option value="year" selected>Năm nay</option>
                        <option value="custom">Tùy chỉnh</option>
                    </select>

                    <input type="date" name="tu_ngay" id="tu_ngay" style="display:none;">
                    <input type="date" name="den_ngay" id="den_ngay" style="display:none;">

                    <button type="submit" class="btn-filter">Lọc</button>
                </form>
                <div class="revenue-chart-wrapper">
                    <div class="loading-placeholder" id="revenue-loading">Đang tải biểu đồ doanh thu...</div>
                    <canvas id="revenueChart" style="display: none;"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .dashboard-container {
        max-width: 100%;
        margin: 0 auto;
        padding: 20px;
    }

    /* ===== NÚT XUẤT BÁO CÁO ===== */
    .report-btn {
        display: inline-block;
        padding: 12px 30px;
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        font-weight: 600;
        border-radius: 50px;
        text-decoration: none;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        transition: all 0.3s ease;
    }
    /* .report-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
        color: white;
    } */

    /* ===== THỐNG KÊ CHÍNH (4 Ô LỚN) ===== */
    .metric-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }
    .stat-card {
        color: white;
        padding: 25px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
    }
    /* .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.25);
    } */
    .stat-card p:first-child {
        font-size: 0.95rem;
        opacity: 0.9;
        margin-bottom: 8px;
    }
    .stat-card p:last-child {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
    }

    /* ===== 4 Ô NHỎ (HÔM NAY, THÁNG NÀY...) ===== */
    .mini-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin: 30px 0;
    }
    .mini-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border-left: 5px solid;
        transition: all 0.3s ease;
    }
    /* .mini-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    } */
    .mini-card h5 {
        font-size: 0.9rem;
        color: #555;
        margin: 0 0 8px 0;
        font-weight: 600;
    }
    .mini-card p {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        color: #2c3e50;
    }

    /* MÀU RIÊNG CHO TỪNG Ô NHỎ */
    .mini-card.today    { border-left-color: #e74c3c; }
    .mini-card.month    { border-left-color: #f39c12; }
    .mini-card.orders   { border-left-color: #3498db; }
    .mini-card.avg      { border-left-color: #27ae60; }

    /* ===== BIỂU ĐỒ & BẢNG ===== */
    .top-data-row {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 25px;
        margin-bottom: 30px;
    }
    .chart-box, .table-box {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    .chart-box h2, .table-box h2 {
        font-size: 1.35rem;
        color: #2c3e50;
        margin-bottom: 20px;
    }

    /* ===== FORM LỌC DOANH THU ===== */
    .filter-form {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .filter-form select,
    .filter-form input[type="date"] {
        padding: 10px 14px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 0.95rem;
    }
    .filter-form button {
        padding: 10px 20px;
        background: #007bff;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
    }
    .filter-form button:hover {
        background: #0056b3;
    }

    /* ===== CAROUSEL HDV ===== */
    .guides-auto-section h2 {
        font-size: 1.5rem;
        color: #2c3e50;
        margin-bottom: 25px;
        position: relative;
        padding-bottom: 10px;
    }
    .guides-auto-section h2::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 70px;
        height: 4px;
        background: #007bff;
        border-radius: 2px;
    }

    .guides-simple-section h2 {
        font-size: 1.5rem;
        color: #2c3e50;
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 3px solid #007bff;
        display: inline-block;
    }

    .guides-slider-container {
        position: relative;
        max-width: 100%;
        margin: 0 auto;
    }

    .guides-slider-wrapper {
        overflow: hidden;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .guides-slider-track {
        display: flex;
        transition: transform 0.4s ease;
    }

    .guide-simple-card {
        min-width: 220px;
        background: white;
        border-right: 1px solid #eee;
        text-align: center;
        padding: 15px;
    }

    .guide-simple-card:last-child {
        border-right: none;
    }

    .guide-simple-card img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #f0f0f0;
        margin-bottom: 12px;
    }

    .guide-simple-info h4 {
        margin: 0 0 8px 0;
        font-size: 1.1rem;
        color: #2c3e50;
        font-weight: 600;
    }

    .guide-simple-info p {
        margin: 0;
        color: #666;
        font-size: 0.95rem;
    }

    .slider-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0,0,0,0.5);
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        font-size: 1.5rem;
        cursor: pointer;
        z-index: 10;
        transition: all 0.3s ease;
    }

    /* .slider-btn:hover {
        background: rgba(0,0,0,0.8);
        transform: translateY(-50%) scale(1.1);
    } */

    .prev-btn { left: 10px; }
    .next-btn { right: 10px; }

    /* ===== BẢNG TOP 5 TOUR ===== */
    .table-box {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .table-box h2 {
        font-size: 1.35rem;
        color: #2c3e50;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .table-box table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.95rem;
        color: #333;
    }

    .table-box table th,
    .table-box table td {
        padding: 14px 16px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .table-box table th {
        background-color: #000000ff;
        font-weight: 600;
        color: #ffffffff;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        border-top: 2px solid #333;
    }

    /* .table-box table tbody tr:hover {
        background-color: #f5f5f5;
        transition: background-color 0.2s ease;
    } */

    .table-box table tbody tr:last-child td {
        border-bottom: 2px solid #333;
    }

    /* Viền ngoài bảng */
    .table-box table {
        color: black;
        border: 2px solid #000000ff;
        border-radius: 8px;
        overflow: hidden;
    }
    /* ===== BỐ CỤC DỌC MỚI – BIỂU ĐỒ TRÒN + BẢNG TOP 5 TOUR ===== */
    .vertical-stats-container {
        display: flex;
        flex-direction: column;
        gap: 30px;
        margin: 40px 0;
    }

    .full-width-chart,
    .full-width-table {
        background: white;
        padding: white;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        border: 1px solid #e0e0e0;
    }

    .full-width-chart h2,
    .full-width-table h2 {
        font-size: 1.5rem;
        color: #2c3e50;
        margin-bottom: 25px;
        font-weight: 700;
        text-align: center;
        padding-bottom: 12px;
        border-bottom: 3px solid #007bff;
        display: inline-block;
    }

    /* Biểu đồ tròn to hơn */
    .pie-chart-wrapper {
        position: relative;
        height: 420px;
        max-width: 600px;
        margin: 0 auto;
    }

    CSS.card.shadow-lg {
        transition: none !important;
    }
    .card.shadow-lg:hover {
        transform: none !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
    }

    /* Responsive đẹp */
    @media (max-width: 768px) {
        .pie-chart-wrapper { height: 300px; }
        .full-width-chart, .full-width-table { padding: 20px; }
    }

    
</style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
    
    <script>
        Chart.register(ChartDataLabels);

        const chartDataUrl = '{{ route('admin.dashboard.charts') }}';
        const formatCurrency = (value) => numeral(value).format('0,0') + ' VND';

        // Hàm vẽ biểu đồ Doanh thu
        function drawRevenueChart(data) {
            document.getElementById('revenue-loading').style.display = 'none';
            document.getElementById('revenueChart').style.display = 'block';
            document.getElementById('revenue-title').textContent = `📈 ${data.title}`;

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
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Doanh thu' },
                            ticks: { callback: (value) => numeral(value).format('0a') }
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

        // Hàm vẽ biểu đồ Thanh toán
        function drawPaymentChart(data) {
            // Ẩn loading, hiện canvas
            document.getElementById('payment-loading').style.display = 'none';
            document.getElementById('paymentChart').style.display = 'block';

            if (window.paymentChartInstance) {
                window.paymentChartInstance.destroy();
            }

            const ctx = document.getElementById('paymentChart').getContext('2d');

            window.paymentChartInstance = new Chart(ctx, {
                type: 'doughnut', // đẹp hơn pie
                data: {
                    labels: data.labels,
                    datasets: [{
                        data: data.data,
                        backgroundColor: data.colors || ['#4e73df', '#1cc88a', '#af1e9d', '#e74a3b', '#36b9cc'],
                        borderColor: '#fff',
                        borderWidth: 4,
                        hoverOffset: 15,
                        weight: 0.8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                font: { size: 14, family: 'Segoe UI', weight: '600' },
                                color: '#2c3e50',
                                generateLabels: function(chart) {
                                    const dataset = chart.data.datasets[0];
                                    const total = dataset.data.reduce((a, b) => a + b, 0);
                                    return chart.data.labels.map((label, i) => {
                                        const value = dataset.data[i];
                                        const percent = total > 0 ? Math.round((value / total) * 100) : 0;
                                        return {
                                            text: `${label}: ${value} đơn (${percent}%)`,
                                            fillStyle: dataset.backgroundColor[i],
                                            strokeStyle: '#fff',
                                            lineWidth: 3,
                                            fontColor: '#000000ff'
                                        };
                                    });
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percent = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `${label}: ${value} đơn (${percent}%)`;
                                }
                            }
                        }
                    },
                    animation: {
                        animateRotate: true,
                        animateScale: true
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(() => {
                if (window.paymentChartInstance) {
                    window.paymentChartInstance.options.plugins.datalabels = { display: false };
                    window.paymentChartInstance.update();
                }
            }, 500);
        });

        // Hàm tải dữ liệu biểu đồ
        function loadChartData(params = {}) {
            const url = new URL(chartDataUrl);
            Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

            fetch(url)
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

        // === ===
        document.addEventListener('DOMContentLoaded', function () {
            const form        = document.getElementById('revenue-filter-form');
            const rangeSelect = document.querySelector('select[name="range"]');
            const tuNgay      = document.getElementById('tu_ngay');
            const denNgay     = document.getElementById('den_ngay');

            // 1. Hiện/ẩn 2 ô ngày khi chọn Tùy chỉnh
            rangeSelect.addEventListener('change', function () {
                if (this.value === 'custom') {
                    tuNgay.style.display = 'inline-block';
                    denNgay.style.display = 'inline-block';
                } else {
                    tuNgay.style.display = 'none';
                    denNgay.style.display = 'none';
                }
            });

            // 2. BẤM NÚT LỌC MỚI CHẠY – ĐÃ FIX 100% CHO "TÙY CHỈNH"
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                let params = { range: rangeSelect.value };

                // TRƯỜNG HỢP TÙY CHỈNH → BẮT BUỘC GỬI tu_ngay + den_ngay
                if (rangeSelect.value === 'custom') {
                    if (!tuNgay.value || !denNgay.value) {
                        alert('Vui lòng chọn đầy đủ Từ ngày - Đến ngày!');
                        return;
                    }
                    if (tuNgay.value > denNgay.value) {
                        alert('Ngày bắt đầu không được lớn hơn ngày kết thúc!');
                        return;
                    }

                    params.tu_ngay  = tuNgay.value;
                    params.den_ngay = denNgay.value;
                }

                // GỌI AJAX – BIỂU ĐỒ ĐỔI NGAY, KHÔNG RELOAD
                loadChartData(params);
            });

            // 3. Load lần đầu (mặc định Năm nay)
            loadChartData({ range: 'year' });
        });


        //////////////////////////////////////////////////////////////////////////////
        let guideSlideIndex = 0;
        const slideWidth = 220; // = min-width của .guide-simple-card

        function slideGuides(direction) {
            const track = document.getElementById('guidesSliderTrack');
            const totalCards = track.children.length;
            const visibleCards = Math.floor(track.parentElement.offsetWidth / slideWidth);

            guideSlideIndex += direction;

            if (guideSlideIndex < 0) guideSlideIndex = 0;
            if (guideSlideIndex > totalCards - visibleCards) {
                guideSlideIndex = totalCards - visibleCards;
            }

            track.style.transform = `translateX(-${guideSlideIndex * slideWidth}px)`;
        }
        window.addEventListener('resize', () => slideGuides(0));
    </script>
@endpush