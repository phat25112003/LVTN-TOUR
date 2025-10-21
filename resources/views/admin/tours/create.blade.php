@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid mt-4">
    <h3>Thêm Tour Mới</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Lỗi!</strong> Vui lòng kiểm tra lại thông tin nhập.
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.tours.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Tiêu đề Tour</label>
                <input type="text" name="tieuDe" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Thời gian (ví dụ: 3 ngày 2 đêm)</label>
                <input type="text" name="thoiGian" class="form-control">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="moTa" rows="3" class="form-control" required></textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Ngày bắt đầu</label>
                <input type="date" name="ngayBatDau" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Ngày kết thúc</label>
                <input type="date" name="ngayKetThuc" class="form-control">
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Số lượng</label>
                <input type="number" name="soLuong" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Giá người lớn (VNĐ)</label>
                <input type="number" name="giaNguoiLon" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Giá trẻ em (VNĐ)</label>
                <input type="number" name="giaTreEm" class="form-control" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Điểm đến</label>
                <input type="text" name="diemDen" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Tình trạng</label>
                <select name="tinhTrang" class="form-select">
                    <option value="1">Hoạt động</option>
                    <option value="0">Ngưng</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Hình ảnh Tour</label>
            <input type="file" name="hinhAnh[]" multiple class="form-control">
        </div>


            <div class="form-actions">
                <button type="submit" class="btnsuccess">Lưu và Tạo Lịch Trình</button>
                <a href="{{ route('admin.tours.index') }}" class="btncancel">Hủy</a>
            </div>
    </form>
</div>
@endsection
