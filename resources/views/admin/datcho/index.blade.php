{{-- resources/views/admin/datcho/index.blade.php --}}
@extends('admin.layouts.dashboard')

@section('content')
<div class="booking-container">
    <h2 class="text-center mb-4 fw-bold text-primary">Danh sách Booking</h2>

    {{-- THÔNG BÁO – GIỮ NGUYÊN CLASS --}}
    @if (session('success'))
        <div class="notify notify-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="notify notify-error">{{ session('error') }}</div>
    @endif
    @if (session('warning'))
        <div class="notify notify-warning">{{ session('warning') }}</div>
    @endif

    <div class="table-container">
        <table class="booking-table">
            <thead>
                <tr>
                    <th>Họ Tên</th>
                    <th>Tour</th>
                    <th>Ngày Đặt</th>
                    <th>Tổng Người</th> {{-- GỘP 3 CỘT --}}
                    <th>Ngày Khởi Hành</th>
                    <th>Ngày Kết Thúc</th>
                    <th>Tổng Giá</th>
                    <th>Địa Chỉ</th>
                    <th>Số ĐT</th>
                    <th>Email</th>
                    <th>Phương Thức TT</th>
                    <th>Trạng Thái Thanh Toán</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($datChos as $datCho)
                <tr>
                    {{-- HỌ TÊN --}}
                    <td>{{ $datCho->hoTen ?? 'Khách vãng lai' }}</td>

                    {{-- TOUR --}}
                    <td>{{ $datCho->tour->tieuDe ?? 'Không xác định' }}</td>

                    {{-- NGÀY --}}
                    <td>{{ \Carbon\Carbon::parse($datCho->ngayDat)->format('d/m/Y H:i') }}</td>
                                        {{-- TỔNG NGƯỜI = Người lớn + Trẻ em + Em bé --}}
                    <td class="text-center fw-bold">
                        {{ $datCho->soNguoiLon + $datCho->soTreEm + $datCho->soEmBe }}
                    </td>

                    <td>{{ \Carbon\Carbon::parse($datCho->ngayKhoiHanh)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($datCho->ngayKetThuc)->format('d/m/Y') }}</td>

                    {{-- TỔNG GIÁ --}}
                    <td>{{ number_format($datCho->tongGia) }} VND</td>

                    {{-- ĐỊA CHỈ, SĐT, EMAIL --}}
                    <td>{{ Str::limit($datCho->diaChi ?? 'N/A', 30) }}</td>
                    <td>{{ $datCho->soDienThoai ?? 'N/A' }}</td>
                    <td>{{ $datCho->email ?? 'N/A' }}</td>

                    {{-- PHƯƠNG THỨC TT --}}
                    <td>{{ $datCho->phuongThucThanhToan ?? 'Chưa chọn' }}</td>

                    {{-- TRẠNG THÁI THANH TOÁN – GIỮ CLASS CŨ --}}
                    <td>
                        @php
                            $ttThanhToan = $datCho->thanhtoan->tinhTrangThanhToan ?? 'Chưa tạo TT';
                            $statusClass = ($ttThanhToan === 'Đã thanh toán') ? 'status-ok' : 'status-warning';
                            $statusText = ($ttThanhToan === 'Chưa tạo Thanh Toán') ? 'Chờ Thanh Toán' : $ttThanhToan;
                        @endphp
                        <span class="status {{ $statusClass }}">
                            {{ $statusText }}
                        </span>
                    </td>

                    {{-- HÀNH ĐỘNG – CHỈ GIỮ NÚT "XEM" --}}
                    <td>
                        <a class="btn-action btn-view" href="{{ route('admin.datcho.show', $datCho->maDatCho) }}">
                            Xem
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection