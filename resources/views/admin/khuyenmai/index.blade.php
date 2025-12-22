@extends('admin.layouts.dashboard')

@section('content')
    <div class="admin-main-container">
        <h2 class="promo-page-title">Quản lý Khuyến Mãi</h2>

        @if (session('success'))
            <div class="notify notify-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('admin.khuyenmai.create') }}" class="add-btn">+ Thêm Khuyến Mãi</a>
        
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
                    @foreach ($khuyenMais as $km)
                        <tr>
                            {{-- Mã KM --}}
                            <td class="fw-bold text-primary">{{ $km->code }}</td>

                            {{-- Tên KM --}}
                            <td>{{ $km->tenKM }}</td>

                            {{-- Mức giảm --}}
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

                            {{-- Thời gian --}}
                            <td>
                                {{ \Carbon\Carbon::parse($km->ngayBatDau)->format('d/m/Y') }} <br>
                                → {{ \Carbon\Carbon::parse($km->ngayKetThuc)->format('d/m/Y') }}
                            </td>

                            {{-- Áp dụng cho --}}
                            <td>
                                @switch($km->apDung)
                                    @case('tat_ca')      <span class="badge bg-secondary">Tất cả tour</span> @break
                                    @case('danh_muc')    <span class="badge bg-warning text-dark">Danh mục</span> @break
                                    @case('tour_cu_the') <span class="badge bg-primary">Tour cụ thể</span> @break
                                    @case('chuyen_cu_the') <span class="badge bg-danger">Chuyến cụ thể</span> @break
                                    @default             <span class="text-muted">—</span>
                                @endswitch
                            </td>

                            {{-- TRẠNG THÁI – CHỈ HIỂN THỊ, KHÔNG CHO CLICK --}}
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

                            {{-- Hành động – giữ nguyên 100% --}}
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
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
