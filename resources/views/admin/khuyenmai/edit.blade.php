@extends('admin.layouts.dashboard')

@section('content')
<div class="container">
    <h2>Sửa Mã Khuyến Mãi</h2>

    @if ($errors->any())
        <div class="alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.khuyenmai.update', $khuyenMai->maKM) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Mã khuyến mãi -->
        <div>
            <label>Mã Khuyến Mãi <span class="text-danger">*</span></label>
            <input type="text" name="code" value="{{ old('code', $khuyenMai->code) }}" maxlength="20" required>
        </div>

        <!-- Tên khuyến mãi -->
        <div>
            <label>Tên Khuyến Mãi <span class="text-danger">*</span></label>
            <input type="text" name="tenKM" value="{{ old('tenKM', $khuyenMai->tenKM) }}" required>
        </div>

        <!-- Loại giảm -->
        <div>
            <label>Kiểu Giảm <span class="text-danger">*</span></label>
            <select name="loaiKM" id="loaiKM" required>
                <option value="percent" {{ old('loaiKM', $khuyenMai->loaiKM) === 'percent' ? 'selected' : '' }}>
                    Giảm theo phần trăm (%)
                </option>
                <option value="fixed" {{ old('loaiKM', $khuyenMai->loaiKM) === 'fixed' ? 'selected' : '' }}>
                    Giảm cố định (số tiền)
                </option>
                <option value="freeservice" {{ old('loaiKM', $khuyenMai->loaiKM) === 'freeservice' ? 'selected' : '' }}>
                    Miễn phí dịch vụ
                </option>
            </select>
        </div>

        <!-- Giá trị giảm -->
        <div>
            <label id="label-giaTri">Giá trị giảm <span class="text-danger">*</span></label>
            <input type="number" step="0.01" min="0.01" name="giaTri" value="{{ old('giaTri', $khuyenMai->giaTri) }}" required>
            <small class="text-muted">
                • %: nhập 30 = giảm 30%<br>
                • Cố định: nhập 500000 = giảm 500.000₫
            </small>
        </div>

        <!-- Giảm tối đa (chỉ hiện khi là %) -->
        <div id="giaTriToiDa-field" style="display: {{ old('loaiKM', $khuyenMai->loaiKM) === 'percent' ? 'block' : 'none' }};">
            <label>Giảm tối đa (₫)</label>
            <input type="number" min="0" name="giaTriToiDa" value="{{ old('giaTriToiDa', $khuyenMai->giaTriToiDa) }}" 
                   placeholder="VD: 4000000 (để trống = không giới hạn)">
            <small class="text-muted">Chỉ áp dụng khi chọn kiểu "Giảm theo phần trăm"</small>
        </div>

        <!-- Áp dụng cho -->
        <div>
            <label>Áp dụng cho <span class="text-danger">*</span></label>
            <select name="apDung" id="apDung" required>
                <option value="tat_ca"      {{ old('apDung', $khuyenMai->apDung) === 'tat_ca' ? 'selected' : '' }}>Tất cả tour</option>
                <option value="danh_muc"    {{ old('apDung', $khuyenMai->apDung) === 'danh_muc' ? 'selected' : '' }}>Theo danh mục</option>
                <option value="tour_cu_the" {{ old('apDung', $khuyenMai->apDung) === 'tour_cu_the' ? 'selected' : '' }}>Tour cụ thể</option>
                <option value="chuyen_cu_the" {{ old('apDung', $khuyenMai->apDung) === 'chuyen_cu_the' ? 'selected' : '' }}>Chuyến tour cụ thể</option>
            </select>
        </div>

        <!-- Danh mục -->
        <div id="danhMuc-field" style="display: {{ old('apDung', $khuyenMai->apDung) === 'danh_muc' ? 'block' : 'none' }};">
            <label>Chọn danh mục <span class="text-danger">*</span></label>
            <select name="danhMucIDs[]" multiple size="6" class="form-control">
                @foreach($danhmucs as $dm)
                    <option value="{{ $dm->maDanhMuc }}"
                        {{ (is_array(old('danhMucIDs')) ? in_array($dm->maDanhMuc, old('danhMucIDs')) : 
                           (is_array($khuyenMai->danhMucIDs) && in_array($dm->maDanhMuc, $khuyenMai->danhMucIDs))) ? 'selected' : '' }}>
                        {{ $dm->tenDanhMuc }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Giữ Ctrl (Windows) / Cmd (Mac) để chọn nhiều</small>
        </div>

        <!-- Tour cụ thể -->
        <div id="tour-field" style="display: {{ old('apDung', $khuyenMai->apDung) === 'tour_cu_the' ? 'block' : 'none' }};">
            <label>Chọn tour áp dụng <span class="text-danger">*</span></label>
            <select name="tourIDs[]" multiple size="8" class="form-control">
                @foreach($tours as $tour)
                    <option value="{{ $tour->maTour }}"
                        {{ (is_array(old('tourIDs')) ? in_array($tour->maTour, old('tourIDs')) : 
                           (is_array($khuyenMai->tourIDs) && in_array($tour->maTour, $khuyenMai->tourIDs))) ? 'selected' : '' }}>
                        {{ $tour->tieuDe }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Giữ Ctrl/Cmd để chọn nhiều</small>
        </div>

        <!-- Chuyến cụ thể -->
        <div id="chuyen-field" style="display: {{ old('apDung', $khuyenMai->apDung) === 'chuyen_cu_the' ? 'block' : 'none' }};">
            <label>Chọn chuyến tour <span class="text-danger">*</span></label>
            <select name="chuyenIDs[]" multiple size="8" class="form-control">
                @foreach($chuyens as $chuyen)
                    <option value="{{ $chuyen->maChuyen }}"
                        {{ (is_array(old('chuyenIDs')) ? in_array($chuyen->maChuyen, old('chuyenIDs')) : 
                           (is_array($khuyenMai->chuyenIDs) && in_array($chuyen->maChuyen, $khuyenMai->chuyenIDs))) ? 'selected' : '' }}>
                        [{{ $chuyen->maChuyen }}] {{ $chuyen->tour->tieuDe ?? 'Tour đã xóa' }} 
                        - {{ \Carbon\Carbon::parse($chuyen->ngayBatDau)->format('d/m/Y') }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Giữ Ctrl/Cmd để chọn nhiều</small>
        </div>

        <!-- Thời gian -->
        <div>
            <label>Ngày bắt đầu <span class="text-danger">*</span></label>
            <input type="datetime-local" name="ngayBatDau" 
                   value="{{ old('ngayBatDau', $khuyenMai->ngayBatDau->format('Y-m-d\TH:i')) }}" required>
        </div>
        <div>
            <label>Ngày kết thúc <span class="text-danger">*</span></label>
            <input type="datetime-local" name="ngayKetThuc" 
                   value="{{ old('ngayKetThuc', $khuyenMai->ngayKetThuc->format('Y-m-d\TH:i')) }}" required>
        </div>

        <!-- Số tiền tối thiểu -->
        <div>
            <label>Số tiền đơn hàng tối thiểu</label>
            <input type="number" min="0" name="soTienToiThieu" 
                   value="{{ old('soTienToiThieu', $khuyenMai->soTienToiThieu ?? 0) }}">
            <small class="text-muted">0 = không giới hạn</small>
        </div>

        <!-- Số lượng người tối thiểu -->
        <div>
            <label>Số lượng người tối thiểu</label>
            <input type="number" min="1" name="soLuongNguoiToiThieu" 
                   value="{{ old('soLuongNguoiToiThieu', $khuyenMai->soLuongNguoiToiThieu ?? 1) }}">
            <small class="text-muted">Mặc định: 1 người</small>
        </div>

        <!-- Giới hạn lượt dùng -->
        <div>
            <label>Số lượt sử dụng tối đa</label>
            <input type="number" min="1" name="soLuotSuDungToiDa" 
                   value="{{ old('soLuotSuDungToiDa', $khuyenMai->soLuotSuDungToiDa) }}" 
                   placeholder="Để trống = không giới hạn">
        </div>

        <!-- Trạng thái -->
        <div>
            <label>Trạng thái <span class="text-danger">*</span></label>
            <select name="trangThai" required>
                <option value="dang_chay" {{ old('trangThai', $khuyenMai->trangThai) === 'dang_chay' ? 'selected' : '' }}>Đang chạy</option>
                <option value="sap_chay"  {{ old('trangThai', $khuyenMai->trangThai) === 'sap_chay' ? 'selected' : '' }}>Sắp chạy</option>
                <option value="tam_dung"  {{ old('trangThai', $khuyenMai->trangThai) === 'tam_dung' ? 'selected' : '' }}>Tạm dừng</option>
                <option value="ket_thuc"  {{ old('trangThai', $khuyenMai->trangThai) === 'ket_thuc' ? 'selected' : '' }}>Kết thúc</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-save">Cập Nhật Mã</button>
            <a href="{{ route('admin.khuyenmai.index') }}" class="btn-cancel">Hủy</a>
        </div>
    </form>
</div>

<style>
    .container {
        max-width: 900px;
        margin: 30px auto;
        background: #fff;
        border: 1px solid #ddd;
        padding: 25px;
        border-radius: 6px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }
    h2 { font-size: 22px; font-weight: 600; margin-bottom: 25px; color: #333; text-align: center; }
    form div { margin-bottom: 15px; }
    label { font-weight: 500; margin-bottom: 6px; display: inline-block; }
    input[type="text"], input[type="number"], input[type="date"], input[type="datetime-local"], select {
        width: 100%; padding: 8px 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;
    }
    input:focus, select:focus { border-color: #007bff; outline: none; }
    small { font-size: 13px; line-height: 1.4; display: block; margin-top: 4px; }
    .form-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }
    .btn-save {
        background-color: #007bff; color: white; padding: 8px 16px; border: none;
        border-radius: 4px; font-size: 15px; cursor: pointer; font-weight: 600;
    }
    .btn-save:hover { background-color: #0056b3; }
    .btn-cancel {
        background-color: #dc3545; color: #fff; text-decoration: none;
        padding: 8px 16px; border-radius: 4px; font-weight: 600; display: inline-block;
    }
    .btn-cancel:hover { background-color: #b02a37; }
    .alert-danger {
        background: #f8d7da; border: 1px solid #f5c2c7; color: #842029;
        padding: 10px 15px; border-radius: 4px; margin-bottom: 20px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const loaiKM = document.getElementById('loaiKM');
    const giaTriToiDaField = document.getElementById('giaTriToiDa-field');
    const apDung = document.getElementById('apDung');
    const fields = {
        'danh_muc': document.getElementById('danhMuc-field'),
        'tour_cu_the': document.getElementById('tour-field'),
        'chuyen_cu_the': document.getElementById('chuyen-field')
    };

    function toggleFields() {
        Object.values(fields).forEach(f => f && (f.style.display = 'none'));
        giaTriToiDaField.style.display = loaiKM.value === 'percent' ? 'block' : 'none';

        const selected = apDung.value;
        if (selected !== 'tat_ca' && fields[selected]) {
            fields[selected].style.display = 'block';
        }
    }

    loaiKM.addEventListener('change', toggleFields);
    apDung.addEventListener('change', toggleFields);
    toggleFields();
});
</script>
@endsection
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush