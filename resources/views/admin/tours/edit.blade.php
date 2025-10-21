@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid mt-4">
    <h3 class="mb-4">Chỉnh sửa Tour</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.tours.update', $tour->maTour) }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Tiêu đề</label>
                <input type="text" name="tieuDe" class="form-control" value="{{ $tour->tieuDe }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Thời gian</label>
                <input type="text" name="thoiGian" class="form-control" value="{{ $tour->thoiGian }}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="moTa" class="form-control" rows="3" required>{{ $tour->moTa }}</textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Ngày bắt đầu</label>
                <input type="date" name="ngayBatDau" class="form-control"
                 value="{{ \Carbon\Carbon::parse($tour->ngayBatDau)->format('Y-m-d') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Ngày kết thúc</label>
                <input type="date" name="ngayKetThuc" class="form-control"
                 value="{{ \Carbon\Carbon::parse($tour->ngayKetThuc)->format('Y-m-d') }}">
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Số lượng</label>
                <input type="number" name="soLuong" class="form-control" value="{{ $tour->soLuong }}" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Giá người lớn</label>
                <input type="number" name="giaNguoiLon" class="form-control" value="{{ $tour->giaNguoiLon }}" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Giá trẻ em</label>
                <input type="number" name="giaTreEm" class="form-control" value="{{ $tour->giaTreEm }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Điểm đến</label>
                <input type="text" name="diemDen" class="form-control" value="{{ $tour->diemDen }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Tình trạng</label>
                <select name="tinhTrang" class="form-select">
                    <option value="1" {{ $tour->tinhTrang ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ !$tour->tinhTrang ? 'selected' : '' }}>Ngưng</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Hình ảnh hiện tại</label><br>
            <div class="d-flex flex-wrap gap-2">
                @foreach ($hinhAnh as $hinh)
                    <div class="text-center">
                        <img src="{{ asset('storage/'.$hinh->duongDanHinh) }}" width="100" height="70" class="rounded border">
                        <div>
                            <input type="checkbox" name="hinhAnhXoa[]" value="{{ $hinh->maHinhAnh }}"> Xóa
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Thêm hình ảnh mới</label>
            <input type="file" name="hinhAnh[]" multiple class="form-control">
        </div>
            <div class="form-actions">
                <button type="submit" class="btnsuccess">Lưu Thay Đổi</button>
                <button type="submit" name="updateSchedule" value="1" class="btnschedule">Cập nhật lịch trình</button>
                <a href="{{ route('admin.tours.index') }}" class="btncancel">Hủy</a>
            </div>
    </form>
</div>
@endsection
