{{-- resources/views/admin/datcho/index.blade.php --}}
@extends('admin.layouts.dashboard')

@section('content')
<div class="booking-container">
    <h2 class="text-center mb-4 fw-bold text-primary">Danh Sách Booking</h2>

    {{-- THÔNG BÁO --}}
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
                    <th>Họ Tên</th>
                    <th>Tour</th>
                    <th>Ngày Đặt</th>
                    <th>Tổng Người</th>
                    <th>Khởi Hành</th>
                    <th>Kết Thúc</th>
                    <th>Thành Tiền</th>
                    <th>Mã KM</th>
                    <th>Phương Thức TT</th>
                    <th>Trạng Thái TT</th>
                    <th>Hành Động</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($datChos as $datCho)
                @php
                    // Thành tiền = tổng giá - giảm giá
                    $tongGia = $datCho->tongGia ?? 0;
                    $tongGiam = $datCho->khuyenMaiDaDung->sum('giaGiam') ?? 0;
                    $thanhTien = $tongGia - $tongGiam;

                    // Thanh toán
                    $thanhToan = $datCho->thanhtoan;
                    $tinhTrang = $thanhToan->tinhTrangThanhToan ?? 'Chưa thanh toán';

                    $statusClass = match ($tinhTrang) {
                        'Đã thanh toán' => 'status-success',
                        'Chưa thanh toán' => 'status-warning',
                        default => 'status-muted',
                    };
                @endphp

                <tr>
                    {{-- Họ tên --}}
                    <td class="fw-600">
                        {{ $datCho->hoTen ?? 'Khách vãng lai' }}
                    </td>

                    {{-- Tour --}}
                    <td>
                        {{ $datCho->tour?->tieuDe ?? 'Tour đã xóa' }}
                    </td>

                    {{-- Ngày đặt --}}
                    <td>{{ \Carbon\Carbon::parse($datCho->ngayDat)->format('d/m/Y H:i') }}</td>

                    {{-- Tổng người --}}
                    <td class="text-center fw-bold text-primary">
                        {{ $datCho->tong_nguoi ?? ($datCho->soNguoiLon + $datCho->soTreEm + $datCho->soEmBe) }}
                    </td>

                    {{-- Ngày khởi hành --}}
                    <td>
                        @if($datCho->chuyentour)
                            {{ \Carbon\Carbon::parse($datCho->chuyentour->ngayBatDau)->format('d/m/Y') }}
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>

                    {{-- Ngày kết thúc --}}
                    <td>
                        @if($datCho->chuyentour)
                            {{ \Carbon\Carbon::parse($datCho->chuyentour->ngayKetThuc)->format('d/m/Y') }}
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>

                    {{-- Thành tiền --}}
                    <td class="text-end" style="font-size: 14px; font-weight: 500;">
                        {{ number_format((int)$datCho->tongGia) }}đ
                    </td>


                    {{-- Mã khuyến mãi --}}
                    <td class="text-center">
                        @if($datCho->khuyenMaiDaDung->count() > 0)
                            @php
                                $kmSuDung = $datCho->khuyenMaiDaDung->first(); // lấy mã đầu tiên
                            @endphp

                            @if($kmSuDung->khuyenmai)
                                <span class="badge bg-success text-white px-3 py-1">
                                    {{ $kmSuDung->khuyenmai->code }}
                                </span>
                                <br>
                                <small class="text-success">
                                    {{ $kmSuDung->khuyenmai->tenKM }}
                                </small>
                            @endif
                        @else
                            <span class="text-muted">Không dùng mã</span>
                        @endif
                    </td>

                    {{-- Phương thức thanh toán --}}
                    <td>
                        @if($thanhToan)
                            <span class="status status-info">
                                {{ ucfirst($thanhToan->phuongThucThanhToan) }}
                            </span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>

                    {{-- Trạng thái thanh toán --}}
                    <td>
                        <span class="status {{ $statusClass }}">
                            {{ $tinhTrang }}
                        </span>
                    </td>

                    {{-- Hành động --}}
                    <td>
                        <a class="btn-action btn-view"
                           href="{{ route('admin.datcho.show', $datCho->maDatCho) }}">
                            Xem Chi Tiết
                        </a>

                        <form action="{{ route('admin.datcho.destroy', $datCho->maDatCho) }}" method="POST" style="display: inline;" onsubmit="return confirm('Bạn chắc chắn muốn xóa đặt chỗ này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete">Xóa</button>
                        </form>
                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="11" class="text-center py-4 text-muted">
                        Chưa có booking nào
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>
@endsection
