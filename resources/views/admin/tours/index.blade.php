@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid mt-4">
    <h2 class="text-center mb-4 fw-bold text-primary">Quản lý Tour</h2>

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('admin.tours.create') }}" class="btn btn-success">+ Thêm Tour</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <div class="table-container">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>Tiêu đề</th>
                    <th>Mô tả</th>
                    <th>Số lượng</th>
                    <th>Giá người lớn</th>
                    <th>Giá trẻ em</th>
                    <th>Thời lượng</th>
                    <th>Điểm đến</th>
                    <th>Tình trạng</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tours as $tour)
                    <tr>
                        <td>{{ $tour->tieuDe }}</td>
                        <td class="description-column">{{ $tour->moTa }}</td>
                        <td>{{ $tour->soLuong }}</td>
                        <td>{{ number_format($tour->giaNguoiLon) }} VNĐ</td>
                        <td>{{ number_format($tour->giaTreEm) }} VNĐ</td>
                        <td>{{ $tour->thoiLuong }} ngày</td>
                        <td>{{ $tour->diemDen }}</td>
                        <td>
                            @if ($tour->tinhTrang)
                                <span class="status-badge active">Hoạt động</span>
                            @else
                                <span class="status-badge inactive">Ngưng</span>
                            @endif
                        </td>

                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.tours.edit', $tour->maTour) }}" class="btn-custom btn-edit">Sửa</a>
                                <form action="{{ route('admin.tours.destroy', $tour->maTour) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-custom btn-delete" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                                </form>
                                <a href="{{ route('admin.tours.show', $tour->maTour) }}" class="btn-custom btn-view">Chi tiết</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
