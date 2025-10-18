@extends('admin.layouts.dashboard')

@section('content')
<div class="booking-container">
    <h2 class="text-center mb-4 fw-bold text-primary">Danh sách Booking</h2>
    @if (session('success'))
        <div class="notify notify-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="notify notify-error">{{ session('error') }}</div>
    @endif

    <div class="table-container">
        <table class="booking-table">
            <thead>
                <tr>
                    <th>Tên Người Đặt</th>
                    <th>Tour</th>
                    <th>Ngày Đặt</th>
                    <th>Ngày Khởi Hành</th>
                    <th>Ngày Kết Thúc</th>
                    <th>Người Lớn</th>
                    <th>Trẻ Em</th>
                    <th>Tổng Giá</th>
                    <th>Thanh Toán</th>
                    <th>Trạng Thái</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($datChos as $datCho)
                    <tr>
                        <td>{{ $datCho->nguoiDung->hoTen ?? 'Không xác định' }}</td>
                        <td>{{ $datCho->tour->tieuDe ?? 'Không xác định' }}</td>
                        <td>{{ $datCho->ngayDat }}</td>
                        <td>{{ $datCho->ngayKhoiHanh }}</td>
                        <td>{{ $datCho->ngayKetThuc }}</td>
                        <td>{{ $datCho->nguoiLon }}</td>
                        <td>{{ $datCho->treEm }}</td>
                        <td>{{ number_format($datCho->tongGia) }} VND</td>
                        <td>{{ $datCho->phuongThucThanhToan ?? 'Chưa chọn' }}</td>
                        <td>
                            <span class="status {{ $datCho->xacNhan ? 'status-success' : 'status-pending' }}">
                                {{ $datCho->xacNhan ? 'Đã xác nhận' : 'Chưa xác nhận' }}
                            </span>
                        </td>
                        <td>
                            @if (!$datCho->xacNhan)
                                <form action="{{ route('admin.datcho.xacnhan', $datCho->maDatCho) }}" method="POST" class="inline-form">
                                    @csrf
                                    <button type="submit" class="btn-confirm">Xác Nhận</button>
                                </form>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
