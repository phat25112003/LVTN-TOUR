@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid mt-4">
    <h3 class="text-center mb-4 fw-bold text-primary">Danh sách Tour Du Lịch</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Thanh chọn danh mục -->
    <form method="GET" action="{{ route('admin.tours.index') }}" class="mb-4">
        <div class="form-group d-flex align-items-center">
            <label for="maDanhMuc" class="mr-2">Danh Mục:</label>
            <select name="maDanhMuc" id="maDanhMuc" class="form-control" onchange="this.form.submit()">
                <option value="">Tất cả</option>
                @foreach ($danhmucs as $danhmuc)
                    <option value="{{ $danhmuc->maDanhMuc }}" {{ request('maDanhMuc') == $danhmuc->maDanhMuc ? 'selected' : '' }}>
                        {{ $danhmuc->tenDanhMuc }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    <a href="{{ route('admin.tours.create') }}" class="add-btn">+ Thêm Tour</a>

    <table class="table-custom">
        <thead>
            <tr>
                <th>Tiêu đề</th>
                <th>Thời gian</th>
                <th>Mô tả</th>
                <th>Điểm đến</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tours as $tour)
                <tr>
                    <td>{{ $tour->tieuDe }}</td>
                    <td>{{ $tour->thoiGian ?? '-' }}</td>
                    <td class="desc">{{ Str::limit($tour->moTa, 150) }}</td>
                    <td>{{ $tour->diemDen }}</td>
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