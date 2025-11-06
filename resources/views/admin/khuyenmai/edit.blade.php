@extends('admin.layouts.dashboard')

@section('content')
    <div class="container">
        <h2>Sửa Khuyến Mãi</h2>
        @if ($errors->any())
            <div class="alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('admin.khuyenmai.update', $khuyenMai->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div>
                <label>Mã Khuyến Mãi</label>
                <input type="text" name="maKhuyenMai" value="{{ old('maKhuyenMai', $khuyenMai->maKhuyenMai) }}" required>
            </div>
            <div>
                <label>Tên Khuyến Mãi</label>
                <input type="text" name="tenKhuyenMai" value="{{ old('tenKhuyenMai', $khuyenMai->tenKhuyenMai) }}" required>
            </div>
            <div>
                <label>Mức Giảm</label>
                <input type="number" step="0.01" name="mucGiam" value="{{ old('mucGiam', $khuyenMai->mucGiam) }}" required>
            </div>
            <div>
                <label>Loại Giảm</label>
                <select name="loaiGiam" required>
                    <option value="Phan tram" {{ old('loaiGiam', $khuyenMai->loaiGiam) === 'Phan tram' ? 'selected' : '' }}>Phần trăm</option>
                    <option value="Tien mat" {{ old('loaiGiam', $khuyenMai->loaiGiam) === 'Tien mat' ? 'selected' : '' }}>Tiền mặt</option>
                </select>
            </div>
            <div>
                <label>Ngày Bắt Đầu</label>
                <input type="date" name="ngayBatDau" value="{{ old('ngayBatDau', $khuyenMai->ngayBatDau) }}" required>
            </div>
            <div>
                <label>Ngày Kết Thúc</label>
                <input type="date" name="ngayKetThuc" value="{{ old('ngayKetThuc', $khuyenMai->ngayKetThuc) }}" required>
            </div>
            <div>
               <div><input type="checkbox" name="apDungTatCaTour" value="1" {{ old('apDungTatCaTour') ? 'checked' : '' }}> <label style="font-weight: bold;">Áp dụng cho tất cả tour</label></div>
            </div>
            <div>
                <label>Chọn Tour (Tùy chọn, bỏ qua nếu áp dụng cho tất cả)</label>
                <table class="tour-table">
                    <thead>
                        <tr>
                            <th>Chọn</th>
                            <th>Tiêu Đề</th>
                            <th>Điểm Đến</th>
                            <th>Giá Người Lớn</th>
                            <th>Giá Trẻ Em</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tours as $tour)
                            <tr>
                                <td><input type="radio" name="maTour" value="{{ $tour->maTour }}" {{ old('maTour', $khuyenMai->maTour) == $tour->maTour ? 'checked' : '' }}></td>
                                <td>{{ $tour->tieuDe }}</td>
                                <td>{{ $tour->diemDen }}</td>
                                <td>{{ number_format($tour->giaNguoiLon, 0) }} VND</td>
                                <td>{{ number_format($tour->giaTreEm, 0) }} VND</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td><input type="radio" name="maTour" value="" {{ old('maTour', $khuyenMai->maTour) === '' ? 'checked' : '' }}></td>
                            <td colspan="4">Không áp dụng</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="form-actions">
                <button type="submit">Cập nhật</button>
                <a href="{{ route('admin.khuyenmai.index') }}" class="btn-cancel">Hủy</a>
            </div>
        </form>
    </div>

    <style>
    /* Giao diện khung form */
    .container {
        max-width: 900px;
        margin: 30px auto;
        background: #fff;
        border: 1px solid #ddd;
        padding: 25px;
        border-radius: 6px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    h2 {
        font-size: 22px;
        font-weight: 600;
        margin-bottom: 25px;
        color: #333;
        text-align: center;
    }

    /* Form cơ bản */
    form div {
        margin-bottom: 15px;
    }

    label {
        font-weight: 500;
        margin-bottom: 6px;
        display: inline-block;
    }

    input[type="text"],
    input[type="number"],
    input[type="date"],
    select {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
    }

    input[type="text"]:focus,
    input[type="number"]:focus,
    input[type="date"]:focus,
    select:focus {
        border-color: #007bff;
        outline: none;
    }

    input[type="checkbox"],
    input[type="radio"] {
        margin-right: 6px;
        cursor: pointer;
    }

    /* Khu vực nút */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }

    /* Nút cập nhật */
    button[type="submit"] {
        background-color: #007bff;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        font-size: 15px;
        cursor: pointer;
        font-weight: 600;
        transition: background-color 0.2s ease;
    }

    button[type="submit"]:hover {
        background-color: #0056b3;
    }

    /* Nút hủy */
    .btn-cancel {
        background-color: #dc3545;
        color: #fff;
        text-decoration: none;
        padding: 8px 16px;
        border-radius: 4px;
        font-weight: 600;
        display: inline-block;
        transition: background-color 0.2s ease;
    }

    .btn-cancel:hover {
        background-color: #b02a37;
    }

    /* Bảng tour */
    .tour-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        font-size: 14px;
    }

    .tour-table th,
    .tour-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }

    .tour-table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }

    .tour-table tr:hover {
        background-color: #f1f1f1;
    }

    /* Thông báo lỗi */
    .alert-danger {
        background: #f8d7da;
        border: 1px solid #f5c2c7;
        color: #842029;
        padding: 10px 15px;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    .alert-danger ul {
        margin: 0;
        padding-left: 20px;
    }
</style>
@endsection
