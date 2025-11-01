@extends('admin.layouts.dashboard')

@section('content')
<div class="booking-container">
    <h2 class="text-center mb-4 fw-bold text-primary">Danh sách Booking</h2>
    {{-- (Các thông báo giữ nguyên) --}}
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
                    <th>Tên Người Đặt</th>
                    <th>Tour</th>
                    <th>Ngày Đặt</th>
                    <th>Ngày Khởi Hành</th>
                    <th>Ngày Kết Thúc</th>
                    <th>Người Lớn</th>
                    <th>Trẻ Em</th>
                    <th>Tổng Giá</th>
                    <th>Địa Chỉ</th>
                    <th>SĐT</th>
                    <th>Email</th>
                    <th>Trạng Thái Thanh Toán</th>
                    <th>Phương Thức Thanh Toán</th>
                    <th>Trạng Thái Xác Nhận</th>
                    
                    {{-- CỘT MỚI: TRẠNG THÁI HÓA ĐƠN --}}
                    <th>Trạng Thái HĐ</th> 
                    
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($datChos as $datCho)
                    <tr>
                        <td>{{ $datCho->hoTen ?? 'Không xác định' }}</td>
                        <td>{{ $datCho->tour->tieuDe ?? 'Không xác định' }}</td>
                        <td>{{ \Carbon\Carbon::parse($datCho->ngayDat)->format('d/m/Y H:i') }}</td>
                        <td>{{ \Carbon\Carbon::parse($datCho->ngayKhoiHanh)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($datCho->ngayKetThuc)->format('d/m/Y') }}</td>
                        <td>{{ $datCho->nguoiLon }}</td>
                        <td>{{ $datCho->treEm }}</td>
                        <td>{{ number_format($datCho->tongGia) }} VND</td>
                        <td>{{ $datCho->diaChi ?? 'N/A' }}</td>
                        <td>{{ $datCho->soDienThoai ?? 'N/A' }}</td>
                        <td>{{ $datCho->email ?? 'N/A' }}</td>
                        
                        {{-- CỘT TT THANH TOÁN --}}
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
                        
                        <td>{{ $datCho->phuongThucThanhToan ?? 'Chưa chọn' }}</td>
                        
                        {{-- CỘT TT XÁC NHẬN --}}
                        <td>
                            <span class="status {{ $datCho->xacNhan ? 'status-success' : 'status-pending' }}">
                                {{ $datCho->xacNhan ? 'Đã xác nhận' : 'Chưa xác nhận' }}
                            </span>
                        </td>
                        
                        <td>
                            @php
                                $ttHoaDon = $datCho->hoadon->trangThai ?? 'Chưa tạo Hóa Đơn';
                                $hdClass = ($ttHoaDon === 'Đã gửi') ? 'status-ok' : (($ttHoaDon === 'Chưa tạo Hóa Đơn') ? 'status-default' : 'status-warning');
                            @endphp
                            <span class="status {{ $hdClass }}">
                                {{ $ttHoaDon }}
                            </span>
                        </td>
                        
                        <td>
                            @php
                                $ttThanhToan = $datCho->thanhtoan->tinhTrangThanhToan ?? 'Chưa tạo Thanh Toán';
                            @endphp

                            {{-- NÚT XEM CHI TIẾT --}}
                            <a class="btn-action btn-view" style="text-decoration: none;"  href="{{ route('admin.datcho.show', $datCho->maDatCho) }}" style="display: inline-block;">
                                Xem
                            </a>
                            <div>---</div>
                            {{-- NÚT XÁC NHẬN ĐẶT CHỖ (chỉ hiển thị khi CHƯA xác nhận) --}}
                            @if (!$datCho->xacNhan)
                                {{-- Kiểm tra nếu đã thanh toán, hiển thị nút Xác Nhận chính --}}
                                @if ($ttThanhToan === 'Đã thanh toán')
                                    <form action="{{ route('admin.datcho.xacnhan', $datCho->maDatCho) }}" method="POST" class="inline-form">
                                        @csrf
                                        <button style="background-color:darkgreen;" type="submit" class="btn-action btn-edit">Xác Nhận Booking</button>
                                    </form>
                                @else
                                    <button class="btn-disabled mb-1" disabled title="Cần thanh toán trước">Chờ Thanh Toán</button>
                                @endif
                                <div>---</div>

                                {{-- NÚT XÁC NHẬN THANH TOÁN (chỉ hiển thị cho phương thức tại văn phòng) --}}
                                @if ($datCho->phuongThucThanhToan === 'tại văn phòng' && $ttThanhToan !== 'Đã thanh toán')
                                    <form action="{{ route('admin.datcho.xacnhan_thanhtoan', $datCho->maDatCho) }}" method="POST" class="inline-form">
                                        @csrf
                                        <button style="background-color:darkgoldenrod;" type="submit" class="btn-action btn-delete" onclick="return confirm('Xác nhận đã nhận tiền mặt tại văn phòng?');">
                                            Xác nhận Thanh Toán
                                        </button>
                                    </form>
                                @endif
                                
                            @else
                            @endif
                        </td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection