@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid mt-4">
    <h3 style="text-decoration: none;" class="mb-4">Danh sách Tour Du Lịch</h3>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<a href="{{ route('admin.tours.create') }}" class="add-btn">+ Thêm Tour</a>

<table class="table-custom">
    <thead>
        <tr>
            <th>Tiêu đề</th>
            <th>Thời gian</th>
            <th>Mô tả</th>
            <th>Ngày bắt đầu</th>
            <th>Ngày kết thúc</th>
            <th>Số lượng</th>
            <th>Giá người lớn</th>
            <th>Giá trẻ em</th>
            <th>Điểm đến</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tours as $tour)
            <tr>
                <td>{{ $tour->tieuDe }}</td>
                <td>{{ $tour->thoiGian ?? '-' }}</td>
                <td class="desc">{{ $tour->moTa }}</td>
                <td>{{ $tour->ngayBatDau ?? '-' }}</td>
                <td>{{ $tour->ngayKetThuc ?? '-' }}</td>
                <td>{{ $tour->soLuong }}</td>
                <td>{{ number_format($tour->giaNguoiLon) }} VND</td>
                <td>{{ number_format($tour->giaTreEm) }} VND</td>
                <td>{{ $tour->diemDen }}</td>
                <td>
                    <span class="status-badge {{ $tour->tinhTrang ? 'status-active' : 'status-inactive' }}">
                        {{ $tour->tinhTrang ? 'Hoạt động' : 'Ngưng' }}
                    </span>
                </td>
                
                <td>
                    <div class="action-buttons">
                        <a href="{{ route('admin.tours.show', $tour->maTour) }}" class="btn-action btn-view">Xem</a>
                        <a href="{{ route('admin.tours.edit', $tour->maTour) }}" class="btn-action btn-edit">Sửa</a>
                        <form action="{{ route('admin.tours.destroy', $tour->maTour) }}" method="POST" onsubmit="return confirm('Xóa tour này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete">Xóa</button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>
@endsection
