@extends('admin.layouts.dashboard')

@section('content')
<div class="dashboard-container">
    <h1 class="title">📊 Bảng Thống Kê Tổng Quan</h1>

    {{-- Thông báo --}}
    @if (session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert error">{{ session('error') }}</div>
    @endif

    {{-- Thống kê nhanh --}}
    <div class="stats-grid">
        @php
            $stats = [
                ['Tổng Số Tour', $totalTours ?? 0, 'fa-map-marker-alt', '#3498db'],
                ['Tổng Số Đặt Chỗ', $totalBookings ?? 0, 'fa-ticket-alt', '#2ecc71'],
                ['Tổng Doanh Thu', number_format($totalRevenue ?? 0, 0, ',', '.') . ' VNĐ', 'fa-money-bill-wave', '#f39c12'],
                ['Tổng Người Dùng', $totalUsers ?? 0, 'fa-users', '#9b59b6'],
            ];
        @endphp

        @foreach ($stats as [$title, $value, $icon, $color])
            <div class="stat-card" style="--color: {{ $color }}">
                <div class="icon"><i class="fas {{ $icon }}"></i></div>
                <div class="info">
                    <p>{{ $title }}</p>
                    <h3>{{ $value }}</h3>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Nhóm: Biểu đồ thanh toán + Bảng tour được đặt nhiều --}}
    <div class="charts-row combined-card">
        <div class="table-box top-tours">
            <label>🔥Top 5 Tour Được Đặt Nhiều Nhất</label>
            <table>
                <thead>
                    <tr>
                        <th>Tiêu Đề</th>
                        <th>Điểm Đến</th>
                        <th>Số Lượt Đặt</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topBookedTours as $tour)
                        <tr>
                            <td>{{ $tour->tieuDe }}</td>
                            <td>{{ $tour->diemDen }}</td>
                            <td>{{ $tour->total_bookings }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center">Không có dữ liệu.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="chart-box small-chart centered-content">
            <label>Tỷ Lệ Phương Thức Thanh Toán (%)</label>
            @if (!empty($paymentChart['data']['labels']))
                <canvas id="paymentChart" height="300" style="max-height: 300px;"></canvas>
            @else
                <p class="text-center text-muted">Không có dữ liệu phương thức thanh toán.</p>
            @endif
        </div>
    </div>

    <div class="table-box">
        <h3>🚌 Tour Đang Hoạt Động</h3>
        <table>
            <thead>
                <tr>
                    <th>Tiêu Đề</th>
                    <th>Điểm Đến</th>
                    <th>Giá Người Lớn</th>
                    <th>Giá Trẻ Em</th>
                </tr>
            </thead>
            <tbody>
                @forelse($toursDangHoatDong as $tour)
                    <tr>
                        <td>{{ $tour->tieuDe }}</td>
                        <td>{{ $tour->diemDen }}</td>
                        <td>{{ number_format($tour->giaNguoiLon ?? 0) }} VNĐ</td>
                        <td>{{ number_format($tour->giaTreEm ?? 0) }} VNĐ</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">Không có tour đang hoạt động.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- BIỂU ĐỒ DOANH THU TO Ở DƯỚI CÙNG -->
    <div class="chart-box big-chart">
        <h3>📈 Biểu Đồ Doanh Thu Theo Tháng</h3>
        @if (array_sum($revenueChart['data']['datasets'][0]['data']) > 0)
            <canvas id="revenueChart" height="150"></canvas>
        @else
            <p class="text-center text-muted">Không có dữ liệu doanh thu để hiển thị.</p>
        @endif
    </div>
</div>
@endsection

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    Chart.register(ChartDataLabels); // Đăng ký plugin

    const revenueCtx = document.getElementById("revenueChart");
    if (revenueCtx) {
        new Chart(revenueCtx, @json($revenueChart));
    } else {
        console.log('Không tìm thấy revenueChart canvas.');
    }

    const paymentCtx = document.getElementById("paymentChart");
    if (paymentCtx) {
        new Chart(paymentCtx, @json($paymentChart));
    } else {
        console.log('Không tìm thấy paymentChart canvas.');
    }
});
</script>
@endpush

@push('styles')
<style>

.charts-row label {
  display: block;             
  text-align: center;         
  font-size: 22px;            
  font-weight: bold;          
  margin: 10px 0;    
  color: red;

}        

.dashboard-container {
    padding: 30px;
    font-family: "Inter", sans-serif;
    background: #f5f7fa;
    min-height: 100vh;
    color: #2c3e50;
}

.title {
    text-align: center;
    font-size: 2rem;
    color: #34495e;
    margin-bottom: 30px;
    font-weight: 700;
}

.alert {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-weight: 500;
}
.alert.success {
    background-color: #e6f7ee;
    color: #389e67;
    border: 1px solid #b7ebc7;
}
.alert.error {
    background-color: #fff1f0;
    color: #f5222d;
    border: 1px solid #ffccc7;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
    gap: 20px;
}
.stat-card {
    display: flex;
    align-items: center;
    gap: 15px;
    background: white;
    border-radius: 12px;
    padding: 18px 22px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    border-top: 5px solid var(--color);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.15);
}
.stat-card .icon { font-size: 1.8rem; color: var(--color); }
.stat-card .info p { margin: 0; font-size: 0.9rem; color: #7f8c8d; }
.stat-card .info h3 { margin: 4px 0 0; color: #2c3e50; font-weight: 700; }

.charts-row {
    display: flex;
    align-items: stretch;
    gap: 25px;
    margin-top: 40px;
}
.charts-row.combined-card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.charts-row.combined-card .chart-box.small-chart {
    flex: 1; 
    padding: 0; 
    background: none; 
    box-shadow: none;
    border-radius: 0;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
}

/* === CSS CĂN GIỮA BIỂU ĐỒ === */
.charts-row.combined-card .chart-box.small-chart.centered-content {
    justify-content: center;
    align-items: center;
    text-align: center;
}
.charts-row.combined-card .chart-box.small-chart.centered-content h3 {
    border-bottom: none;
    margin-bottom: 20px;
    font-size: 1.1rem;
    color: #34495e;
    font-weight: 700;
    width: 100%;
}
.charts-row.combined-card .chart-box.small-chart canvas {
    max-height: 250px;
    width: 100%;
}
/* ============================= */

.charts-row.combined-card .table-box.top-tours {
    flex: 1.5; 
    padding: 0; 
    background: none; 
    box-shadow: none;
    border-radius: 0;
    border-right: 1px solid #e0e6ed;
    padding-right: 25px;
    padding-left: 0;
    display: flex;
    flex-direction: column;
}
.charts-row.combined-card .table-box.top-tours h3 {
    border-bottom: 2px solid #ecf0f1;
    padding-bottom: 10px;
    margin-bottom: 15px;
    font-size: 1.1rem;
    color: #34495e;
    font-weight: 700;
}

.table-box {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    margin-top: 25px;
    display: flex;
    flex-direction: column;
}
.table-box h3 {
    margin-bottom: 15px;
    font-weight: 700;
    color: #2980b9;
}
.table-box table {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid #dcdde1;
}
.table-box th, .table-box td {
    padding: 10px;
    border: 1px solid #dcdde1;
    text-align: left;
    font-size: 0.9rem;
}
.table-box th {
    background: #4a69bd;
    color: white;
    font-weight: 600;
}
.table-box tr:nth-child(even) { background: #f8f9fb; }
.table-box tr:hover { background: #e8f4ff; }

.chart-box.big-chart {
    background: white;
    padding: 25px;
    border-radius: 14px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    margin-top: 40px;
}
.chart-box.big-chart h3 {
    font-size: 1.4rem;
    margin-bottom: 20px;
    text-align: center;
    color: #34495e;
}

@media (max-width: 1000px) {
    .charts-row {
        flex-direction: column;
    }
    .charts-row.combined-card {
        padding: 20px;
    }
    .charts-row.combined-card .table-box.top-tours {
        border-right: none;
        border-bottom: 1px solid #e0e6ed;
        padding-right: 0;
        padding-bottom: 20px;
        margin-bottom: 20px;
    }
    .charts-row.combined-card .chart-box.small-chart {
        padding-left: 0;
    }
    .charts-row.combined-card .chart-box.small-chart canvas {
        max-height: 300px;
    }
}
</style>
@endpush