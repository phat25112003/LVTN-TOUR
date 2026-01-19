{{-- resources/views/admin/khuyenmai/index.blade.php --}}
@extends('admin.layouts.dashboard')

@section('content')
<div class="admin-main-container">
    <h2 class="promo-page-title text-center mb-4 fw-bold text-primary">
        <i class="fa-solid fa-gift me-2"></i> Quản Lý Khuyến Mãi
    </h2>

    @if (session('success'))
        <div class="notify notify-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="notify notify-error">{{ session('error') }}</div>
    @endif

    <!-- NÚT THÊM + TÌM KIẾM -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <a href="{{ route('admin.khuyenmai.create') }}" class="add-btn">
            + Thêm Khuyến Mãi
        </a>

        <!-- TÌM KIẾM THEO CODE -->
        <form action="{{ route('admin.khuyenmai.index') }}" method="GET" class="d-flex">
            <div class="input-group" style="max-width: 300px;">
                <input type="text" name="search" class="form-control" placeholder="Tìm theo mã khuyến mãi..."
                       value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit">
                    <i class="fa-solid fa-search"></i>
                </button>
            </div>
            @if(request('search'))
                <a href="{{ route('admin.khuyenmai.index') }}" class="btn btn-secondary ms-2">
                    <i class="fa-solid fa-times"></i>
                </a>
            @endif
        </form>
    </div>

    <div class="admin-card">
        <table class="promo-admin-table">
            <thead>
                <tr>
                    <th>Mã Khuyến Mãi</th>
                    <th>Tên Khuyến Mãi</th>
                    <th>Mức Giảm</th>
                    <th>Thời Gian</th>
                    <th>Áp Dụng Cho</th>
                    <th>Trạng Thái</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($khuyenMais as $km)
                    <tr>
                        <td class="fw-bold text-primary">{{ $km->code }}</td>

                        <td>{{ $km->tenKM }}</td>

                        <td>
                            @if($km->loaiKM === 'percent')
                                {{ $km->giaTri }}%
                                @if($km->giaTriToiDa > 0)
                                    <br><small class="text-muted">(tối đa {{ number_format($km->giaTriToiDa) }}₫)</small>
                                @endif
                            @elseif($km->loaiKM === 'fixed')
                                {{ number_format($km->giaTri) }} ₫
                            @else
                                Dịch vụ miễn phí
                            @endif
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($km->ngayBatDau)->format('d/m/Y') }} <br>
                            → {{ \Carbon\Carbon::parse($km->ngayKetThuc)->format('d/m/Y') }}
                        </td>

                        <td>
                            @switch($km->apDung)
                                @case('tat_ca')      <span class="badge bg-secondary">Tất cả tour</span> @break
                                @case('danh_muc')    <span class="badge bg-warning text-dark">Danh mục</span> @break
                                @case('tour_cu_the') <span class="badge bg-primary">Tour cụ thể</span> @break
                                @case('chuyen_cu_the') <span class="badge bg-danger">Chuyến cụ thể</span> @break
                                @default             <span class="text-muted">—</span>
                            @endswitch
                        </td>

                        <td>
                            @php
                                $statusText = match($km->trangThai) {
                                    'dang_chay' => 'Đang chạy',
                                    'sap_chay'  => 'Sắp chạy',
                                    'tam_dung'  => 'Tạm dừng',
                                    'ket_thuc'  => 'Kết thúc',
                                    default     => '—'
                                };

                                $statusClass = match($km->trangThai) {
                                    'dang_chay' => 'status-active',
                                    'sap_chay'  => 'status-upcoming',
                                    'tam_dung'  => 'status-inactive',
                                    'ket_thuc'  => 'status-ended',
                                    default     => 'status-unknown'
                                };
                            @endphp

                            <span class="status-badge {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </td>

                        <td class="action-buttons">
                            <a href="{{ route('admin.khuyenmai.edit', $km->maKM) }}" class="btn-action btn-edit">Sửa</a>

                            <form action="{{ route('admin.khuyenmai.destroy', $km->maKM) }}" method="POST" class="inline-form" 
                                  onsubmit="return confirm('Bạn có chắc muốn xóa mã {{ $km->code }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-gift fa-3x mb-3 opacity-50"></i>
                            <p>Chưa có khuyến mãi nào.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- PHÂN TRANG ĐẸP, CĂN GIỮA -->
        <div class="d-flex justify-content-center mt-4">
            <nav>
                <ul class="pagination pagination-sm">
                    <li class="page-item {{ $khuyenMais->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $khuyenMais->previousPageUrl() }}" tabindex="-1">‹</a>
                    </li>

                    @foreach($khuyenMais->getUrlRange(1, $khuyenMais->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $khuyenMais->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    <li class="page-item {{ $khuyenMais->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $khuyenMais->nextPageUrl() }}">›</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
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