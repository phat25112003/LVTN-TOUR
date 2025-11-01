@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4 fw-bold text-primary">Chi Tiết Đặt Chỗ #{{ $datCho->maDatCho }}</h2>
            <a href="{{ route('admin.datcho.index') }}" class="btn btn-secondary mb-3">
                &larr; Quay lại Danh sách
            </a>

            {{-- Hiển thị thông báo --}}
            @if (session('success'))
                <div class="notify notify-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="notify notify-error">{{ session('error') }}</div>
            @endif

            <div class="row">
                {{-- Cột 1: Thông tin Đặt Chỗ và Tour --}}
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 bg-primary text-white">
                            <h6 class="m-0 fw-bold">Thông tin Tour & Đặt Chỗ</h6>
                        </div>
                        <div class="card-body">
                            <p><strong>Tour:</strong> {{ $datCho->tour->tieuDe ?? 'N/A' }}</p>
                            <p><strong>Ngày Khởi Hành:</strong> {{ \Carbon\Carbon::parse($datCho->ngayKhoiHanh)->format('d/m/Y') }}</p>
                            <p><strong>Ngày Kết Thúc:</strong> {{ \Carbon\Carbon::parse($datCho->ngayKetThuc)->format('d/m/Y') }}</p>
                            <hr>
                            <p><strong>Ngày Đặt:</strong> {{ \Carbon\Carbon::parse($datCho->ngayDat)->format('d/m/Y H:i:s') }}</p>
                            <p><strong>Người Lớn:</strong> {{ $datCho->nguoiLon }}</p>
                            <p><strong>Trẻ Em:</strong> {{ $datCho->treEm }}</p>
                            <p class="text-danger fw-bold"><strong>Tổng Giá:</strong> {{ number_format($datCho->tongGia) }} VND</p>
                        </div>
                    </div>
                </div>

                {{-- Cột 2: Thông tin Khách Hàng và Trạng Thái --}}
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 bg-success text-white">
                            <h6 class="m-0 fw-bold">Thông tin Khách Hàng</h6>
                        </div>
                        <div class="card-body">
                            <p><strong>Tên Khách Hàng:</strong> {{ $datCho->nguoiDung->hoTen ?? 'N/A' }}</p>
                            <p><strong>Số Điện Thoại:</strong> {{ $datCho->soDienThoai ?? 'N/A' }}</p>
                            <p><strong>Email:</strong> {{ $datCho->email ?? 'N/A' }}</p>
                            <p><strong>Địa Chỉ:</strong> {{ $datCho->diaChi ?? 'N/A' }}</p>
                            <hr>
                            <p><strong>Trạng Thái Xác Nhận:</strong> 
                                <span class="status {{ $datCho->xacNhan ? 'status-success' : 'status-pending' }}">
                                    {{ $datCho->xacNhan ? 'Đã xác nhận' : 'Chưa xác nhận' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Hàng 2: Thanh Toán và Hóa Đơn --}}
                <div class="col-md-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 bg-info text-white">
                            <h6 class="m-0 fw-bold">Thông tin Thanh Toán và Hóa Đơn</h6>
                        </div>
                        <div class="card-body">
                            
                            {{-- THÔNG TIN THANH TOÁN --}}
                            <h5>Thanh Toán:</h5>
                            <p><strong>Phương Thức:</strong> {{ $datCho->phuongThucThanhToan ?? 'N/A' }}</p>
                            @php
                                $ttThanhToan = $datCho->thanhtoan->tinhTrangThanhToan ?? 'Chưa tạo TT';
                                $isPaid = ($ttThanhToan === 'Đã thanh toán');
                            @endphp
                            <p><strong>Trạng Thái TT:</strong> 
                                <span class="status {{ $isPaid ? 'status-ok' : 'status-warning' }}">
                                    {{ $ttThanhToan }}
                                </span>
                                @if ($datCho->phuongThucThanhToan === 'tại văn phòng' && !$isPaid)
                                    <form action="{{ route('admin.datcho.xacnhan_thanhtoan', $datCho->maDatCho) }}" method="POST" class="inline-form ms-3">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-secondary" onclick="return confirm('Xác nhận đã nhận tiền mặt?');">
                                            Cập nhật đã TT
                                        </button>
                                    </form>
                                @endif
                            </p>
                            @if ($isPaid)
                                <p><strong>Mã Giao Dịch:</strong> {{ $datCho->thanhtoan->maGiaoDich ?? 'N/A' }}</p>
                                <p><strong>Ngày Thanh Toán:</strong> {{ \Carbon\Carbon::parse($datCho->thanhtoan->ngayThanhToan)->format('d/m/Y H:i:s') }}</p>
                            @endif
                            <hr>

                            {{-- THÔNG TIN HÓA ĐƠN VÀ NÚT GỬI HÓA ĐƠN --}}
                            <h5>Hóa Đơn:</h5>
                            @php
                                $ttHoaDon = $datCho->hoadon->trangThai ?? 'Chưa tạo HĐ';
                                $hdClass = ($ttHoaDon === 'Đã gửi') ? 'status-ok' : (($ttHoaDon === 'Chưa tạo HĐ') ? 'status-default' : 'status-warning');
                            @endphp
                            <p><strong>Trạng Thái HĐ:</strong> 
                                <span class="status {{ $hdClass }}">
                                    {{ $ttHoaDon }}
                                </span>
                            </p>

                            @if ($isPaid && $datCho->xacNhan)
                                {{-- Nút Xuất Hóa Đơn chỉ hiển thị khi đã Thanh Toán và Xác Nhận --}}
                                <form action="{{ route('admin.datcho.send_invoice', $datCho->maDatCho) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary" onclick="return confirm('Bạn có chắc chắn muốn gửi Hóa Đơn qua email cho khách hàng không?');">
                                        {{ $ttHoaDon === 'Đã gửi' ? 'Gửi lại Hóa Đơn Email' : 'Gửi Hóa Đơn Email' }}
                                    </button>
                                </form>
                            @else
                                <p class="text-danger mt-3">
                                    Chức năng **Gửi Hóa Đơn Email** yêu cầu: 
                                    <br>1. Đặt chỗ phải **Đã xác nhận**. 
                                    <br>2. Đặt chỗ phải **Đã thanh toán**.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection