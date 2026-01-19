{{-- resources/views/admin/datcho/index.blade.php --}}
@extends('admin.layouts.dashboard')

@section('content')
<div class="booking-container">
    <h2 class="text-center mb-4 fw-bold text-primary">Danh Sách Booking</h2>

    <!-- Nút mở modal danh sách chuyến tour
    <button type="button" class="btn btn-success btn-lg shadow-sm mb-4" data-bs-toggle="modal" data-bs-target="#modalDanhSachChuyen">
        <i class="fas fa-bus me-2"></i> Xem Danh Sách Các Chuyến Tour
    </button> -->

    <!-- BỘ LỌC THEO NGÀY ĐẶT -->
    <div class="mb-4">
        <form action="{{ route('admin.datcho.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-bold">Từ ngày</label>
                <input type="date" name="from_date" class="form-control" 
                    value="{{ request('from_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Đến ngày</label>
                <input type="date" name="to_date" class="form-control" 
                    value="{{ request('to_date') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-filter me-2"></i> Lọc
                </button>
                <a href="{{ route('admin.datcho.index') }}" class="btn btn-secondary ms-2">
                    <i class="fa-solid fa-refresh me-2"></i> Bỏ lọc
                </a>
            </div>
        </form>
    </div>

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
                    $tongGia = $datCho->tongGia ?? 0;
                    $tongGiam = $datCho->khuyenMaiDaDung->sum('giaGiam') ?? 0;
                    $thanhTien = $tongGia - $tongGiam;

                    $thanhToan = $datCho->thanhtoan;
                    $tinhTrang = $thanhToan->tinhTrangThanhToan ?? 'Chưa thanh toán';

                    $badgeClass = match ($datCho->xacNhan) {
                        1  => 'bg-success',
                        0  => 'bg-warning text-dark',
                        -1 => 'bg-danger',
                    };

                    $label = match ($datCho->xacNhan) {
                        1  => 'Đã thanh toán',
                        0  => 'Chưa thanh toán',
                        -1 => 'Hết hạn thanh toán',
                    };
                @endphp

                <tr>
                    <td class="fw-600">
                        {{ $datCho->hoTen ?? 'Khách vãng lai' }}
                    </td>

                    <td>
                        {{ $datCho->tour?->tieuDe ?? 'Tour đã xóa' }}
                    </td>

                    <td>{{ \Carbon\Carbon::parse($datCho->ngayDat)->format('d/m/Y H:i') }}</td>

                    <td class="text-center fw-bold text-primary">
                        {{ $datCho->tong_nguoi ?? ($datCho->soNguoiLon + $datCho->soTreEm + $datCho->soEmBe) }}
                    </td>

                    <td>
                        @if($datCho->chuyentour)
                            {{ \Carbon\Carbon::parse($datCho->chuyentour->ngayBatDau)->format('d/m/Y') }}
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>

                    <td>
                        @if($datCho->chuyentour)
                            {{ \Carbon\Carbon::parse($datCho->chuyentour->ngayKetThuc)->format('d/m/Y') }}
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>

                    <td class="text-end" style="font-size: 14px; font-weight: 500;">
                        {{ number_format((int)$datCho->tongGia) }}đ
                    </td>

                    <td class="text-center">
                        @if($datCho->khuyenMaiDaDung->count() > 0)
                            @php $kmSuDung = $datCho->khuyenMaiDaDung->first(); @endphp
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

                    <td>
                        @if($thanhToan)
                            <span class="status status-info">
                                {{ ucfirst($thanhToan->phuongThucThanhToan) }}
                            </span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>

                    <td>
                        <span class="badge {{ $badgeClass }}">
                            {{ $label }}
                        </span>
                    </td>

                    <td>
                        <a class="btn-action btn-view"
                           href="{{ route('admin.datcho.show', $datCho->maDatCho) }}">
                            Xem Chi Tiết
                        </a>

                        <form action="{{ route('admin.datcho.destroy', $datCho->maDatCho) }}" method="POST" style="display: inline;" 
                              onsubmit="return confirm('Bạn chắc chắn muốn xóa đặt chỗ này?');">
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

        <!-- PHÂN TRANG ĐẸP, CĂN GIỮA (GIỐNG TRANG ĐỊA ĐIỂM) -->
        <div class="d-flex justify-content-center mt-4">
            <nav>
                <ul class="pagination pagination-sm">
                    <!-- Nút Previous -->
                    <li class="page-item {{ $datChos->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $datChos->previousPageUrl() }}" tabindex="-1">‹</a>
                    </li>

                    <!-- Các số trang -->
                    @foreach($datChos->getUrlRange(1, $datChos->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $datChos->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    <!-- Nút Next -->
                    <li class="page-item {{ $datChos->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $datChos->nextPageUrl() }}">›</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

{{-- Include các modal từ partials --}}
@include('admin.datcho.partials._modal_chuyen_tour')
@include('admin.datcho.partials._modal_khach_chuyen')
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    .pagination {
        gap: 8px;
    }
    .pagination .page-link {
        border-radius: 8px !important;
        padding: 8px 14px;
        font-size: 14px;
    }
    .pagination .page-item.active .page-link {
        background: #667eea;
        border-color: #667eea;
    }
</style>
@endpush