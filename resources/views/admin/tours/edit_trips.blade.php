{{-- resources/views/admin/tours/edit_trips.blade.php --}}
@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid mt-4">
    <h3 class="text-center mb-4 fw-bold text-primary">
        Sửa Chuyến Cho Tour: {{ $tour->tieuDe }}
        <small class="text-muted d-block">(Thời gian: {{ $tour->thoiGian }})</small>
    </h3>

    <form action="{{ route('admin.tours.updateTrips', $tour->maTour) }}" method="POST" id="tripForm">
        @csrf
        @method('PUT')

        <div id="tripContainer">
            <!-- MẪU CHUYẾN (ẨN) -->
            <template id="tripTemplate">
                <div class="card mb-3 border-primary trip-item">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <strong class="trip-number">Chuyến 1</strong>
                        <button type="button" class="btn btn-danger btn-sm remove-trip">Xóa</button>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="maChuyen[]" value="">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label>Ngày bắt đầu <span class="text-danger">*</span></label>
                                <input type="date" name="ngayBatDau[]" class="form-control ngayBatDau" required>
                                <small class="text-danger d-block mt-1 error-ngay"></small>
                            </div>
                            <div class="col-md-3">
                                <label>Ngày kết thúc <span class="text-danger">*</span></label>
                                <input type="date" name="ngayKetThuc[]" class="form-control ngayKetThuc" required>
                            </div>
                            <div class="col-md-3">
                                <label>Điểm khởi hành <span class="text-danger">*</span></label>
                                <input type="text" name="diemKhoiHanh[]" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label>Số chỗ tối đa <span class="text-danger">*</span></label>
                                <input type="number" name="soLuongToiDa[]" class="form-control" min="1" required>
                            </div>
                        </div>

                        <!-- HƯỚNG DẪN VIÊN - TRONG TEMPLATE -->
                        <div class="row g-3 mt-3">
                            <div class="col-md-4">
                                <label class="fw-bold">Hướng dẫn viên</label>
                                <select name="maHDV[]" class="form-select">
                                    <option value="">-- Chọn HDV --</option>
                                    @foreach($huongDanViens as $hdv)
                                        <option value="{{ $hdv->maHDV }}">
                                            {{ $hdv->hoTen }} @if($hdv->soDienThoai) ({{ $hdv->soDienThoai }}) @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-bold">Phương tiện</label>
                                <input type="text" name="phuongTien[]" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="fw-bold">Ghi chú</label>
                                <input type="text" name="ghiChu[]" class="form-control">
                            </div>
                        </div>

                        <!-- GIÁ -->
                        <div class="row g-3 mt-3 border-top pt-3 bg-light p-2 rounded">
                            <div class="col-md-4">
                                <label class="text-success fw-bold">Giá em bé</label>
                                <input type="number" name="giaEmBe[]" class="form-control" min="0" step="0.01" placeholder="0.00" required>
                            </div>
                            <div class="col-md-4">
                                <label class="text-primary fw-bold">Giá trẻ em</label>
                                <input type="number" name="giaTreEm[]" class="form-control" min="0" step="0.01" placeholder="0.00" required>
                            </div>
                            <div class="col-md-4">
                                <label class="text-danger fw-bold">Giá người lớn</label>
                                <input type="number" name="giaNguoiLon[]" class="form-control" min="0" step="0.01" placeholder="0.00" required>
                            </div>
                        </div>

                        <!-- TRẠNG THÁI CHUYẾN -->
                        <div class="row g-3 mt-3">
                            <div class="col-md-4">
                                <label class="fw-bold text-info">Trạng thái chuyến</label>
                                <select name="tinhTrangChuyen[]" class="form-select" required>
                                    <option value="HoatDong">Hoạt động</option>
                                    <option value="NgungChay">Ngừng chạy</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- HIỂN THỊ CÁC CHUYẾN HIỆN CÓ -->
            @foreach($chuyenTours as $index => $chuyen)
            <div class="card mb-3 border-primary trip-item">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <strong class="trip-number">Chuyến {{ $index + 1 }}</strong>
                    <button type="button" class="btn btn-danger btn-sm remove-trip">Xóa</button>
                </div>
                <div class="card-body">
                    <input type="hidden" name="maChuyen[]" value="{{ $chuyen->maChuyen }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="fw-bold">Ngày bắt đầu <span class="text-danger">*</span></label>
                            <input type="date" name="ngayBatDau[]" class="form-control ngayBatDau" value="{{ old('ngayBatDau.' . $index, $chuyen->ngayBatDau->format('Y-m-d')) }}" required>
                            <small class="text-danger d-block mt-1 error-ngay"></small>
                        </div>
                        <div class="col-md-3">
                            <label class="fw-bold">Ngày kết thúc <span class="text-danger">*</span></label>
                            <input type="date" name="ngayKetThuc[]" class="form-control ngayKetThuc" value="{{ old('ngayKetThuc.' . $index, $chuyen->ngayKetThuc->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="fw-bold">Điểm khởi hành <span class="text-danger">*</span></label>
                            <input type="text" name="diemKhoiHanh[]" class="form-control" value="{{ old('diemKhoiHanh.' . $index, $chuyen->diemKhoiHanh) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="fw-bold">Số chỗ tối đa <span class="text-danger">*</span></label>
                            <input type="number" name="soLuongToiDa[]" class="form-control" value="{{ old('soLuongToiDa.' . $index, $chuyen->soLuongToiDa) }}" min="1" required>
                        </div>
                    </div>

                    <!-- HƯỚNG DẪN VIÊN - HIỂN THỊ ĐÃ CHỌN -->
                    <div class="row g-3 mt-3">
                        <div class="col-md-4">
                            <label class="fw-bold">Hướng dẫn viên</label>
                            <select name="maHDV[]" class="form-select">
                                <option value="">-- Chọn HDV --</option>
                                @foreach($huongDanViens as $hdv)
                                    <option value="{{ $hdv->maHDV }}"
                                        {{ old('maHDV.' . $index, $chuyen->maHDV ?? '') == $hdv->maHDV ? 'selected' : '' }}>
                                        {{ $hdv->hoTen }} @if($hdv->soDienThoai) ({{ $hdv->soDienThoai }}) @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold">Phương tiện</label>
                            <input type="text" name="phuongTien[]" class="form-control" value="{{ old('phuongTien.' . $index, $chuyen->phuongTien ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold">Ghi chú</label>
                            <input type="text" name="ghiChu[]" class="form-control" value="{{ old('ghiChu.' . $index, $chuyen->ghiChu ?? '') }}">
                        </div>
                    </div>

                    <!-- GIÁ -->
                    <div class="row g-3 mt-3 border-top pt-3 bg-light p-2 rounded">
                        <div class="col-md-4">
                            <label class="text-success fw-bold">Giá em bé</label>
                            <input type="number" name="giaEmBe[]" class="form-control" value="{{ old('giaEmBe.' . $index, $chuyen->giaTour->emBe ?? 0) }}" min="0" step="0.01" required>
                        </div>
                        <div class="col-md-4">
                            <label class="text-primary fw-bold">Giá trẻ em</label>
                            <input type="number" name="giaTreEm[]" class="form-control" value="{{ old('giaTreEm.' . $index, $chuyen->giaTour->treEm ?? 0) }}" min="0" step="0.01" required>
                        </div>
                        <div class="col-md-4">
                            <label class="text-danger fw-bold">Giá người lớn</label>
                            <input type="number" name="giaNguoiLon[]" class="form-control" value="{{ old('giaNguoiLon.' . $index, $chuyen->giaTour->nguoiLon ?? 0) }}" min="0" step="0.01" required>
                        </div>
                    </div>

                    <!-- TRẠNG THÁI CHUYẾN -->
                    <div class="row g-3 mt-3">
                        <div class="col-md-4">
                            <label class="fw-bold text-info">Trạng thái chuyến</label>
                            <select name="tinhTrangChuyen[]" class="form-select" required>
                                <option value="HoatDong" {{ old('tinhTrangChuyen.' . $index, $chuyen->tinhTrangChuyen) == 'HoatDong' ? 'selected' : '' }}>
                                    Hoạt động
                                </option>
                                <option value="NgungChay" {{ old('tinhTrangChuyen.' . $index, $chuyen->tinhTrangChuyen) == 'NgungChay' ? 'selected' : '' }}>
                                    Ngừng chạy
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-4">
            <button type="button" id="addTrip" class="btn btn-info btn-lg">Thêm Chuyến Mới</button>
            <button type="submit" class="btn btn-success btn-lg">Lưu Thay Đổi</button>
            <a href="{{ route('admin.tours.edit', $tour->maTour) }}" class="btn btn-secondary btn-lg">Quay Lại</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const thoiGianStr = "{{ $tour->thoiGian }}".toLowerCase();
    let soNgayTour = 1;
    if (thoiGianStr.includes('trong ngày')) {
        soNgayTour = 1;
    } else {
        const match = thoiGianStr.match(/(\d+)\s*ngày/);
        soNgayTour = match ? parseInt(match[1]) : 1;
    }


    const tripContainer = document.getElementById('tripContainer');
    
    let tripCount = tripContainer.querySelectorAll('.trip-item').length;

    function handleNgayBatDau(e) {
        const input = e.target;
        const tripItem = input.closest('.trip-item');
        const ngayBatDau = input.value;
        const ngayKetThucInput = tripItem.querySelector('input[name="ngayKetThuc[]"]');
        let errorEl = tripItem.querySelector('.error-ngay');

        if (!errorEl) {
            errorEl = document.createElement('small');
            errorEl.className = 'text-danger d-block mt-1 error-ngay';
            input.parentNode.appendChild(errorEl);
        }

        errorEl.textContent = '';
        ngayKetThucInput.value = '';

        if (!ngayBatDau) return;

        const start = new Date(ngayBatDau);
        const end = new Date(start);
        end.setDate(start.getDate() + soNgayTour - 1);
        const endStr = end.toISOString().split('T')[0];
        ngayKetThucInput.value = endStr;

        errorEl.className = 'text-success d-block mt-1';
        errorEl.textContent = `Kết thúc: ${end.toLocaleDateString('vi-VN')} (tổng ${soNgayTour} ngày)`;
    }

    document.querySelectorAll('input[name="ngayBatDau[]"]').forEach(input => {
        input.addEventListener('change', handleNgayBatDau);
    });

    document.getElementById('addTrip').addEventListener('click', function () {
        tripCount++;
        const template = document.getElementById('tripTemplate').content.cloneNode(true);
        const tripItem = template.querySelector('.trip-item');

        tripItem.querySelector('.trip-number').textContent = `Chuyến ${tripCount}`;
        tripItem.querySelector('input[name="maChuyen[]"]').value = '';
        tripItem.querySelector('select[name="tinhTrangChuyen[]"]').value = 'HoatDong';

        const newNgayBatDau = tripItem.querySelector('input[name="ngayBatDau[]"]');
        newNgayBatDau.addEventListener('change', handleNgayBatDau);

        document.getElementById('tripContainer').appendChild(tripItem);
        updateRemoveButtons();
        updateTripNumbers();
    });

    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-trip')) {
            if (document.querySelectorAll('.trip-item').length > 1) {
                e.target.closest('.trip-item').remove();
                tripCount--;
                updateTripNumbers();
                updateRemoveButtons();
            }
        }
    });

    function updateTripNumbers() {
        document.querySelectorAll('.trip-number').forEach((el, i) => {
            el.textContent = `Chuyến ${i + 1}`;
        });
    }

    function updateRemoveButtons() {
        const items = document.querySelectorAll('.trip-item');
        items.forEach((item, i) => {
            const btn = item.querySelector('.remove-trip');
            btn.classList.toggle('d-none', items.length === 1);
        });
    }

    document.getElementById('tripForm').addEventListener('submit', function (e) {
        const items = document.querySelectorAll('.trip-item');
        let hasError = false;

        items.forEach((item, index) => {
            const ngayBatDau = item.querySelector('input[name="ngayBatDau[]"]').value;
            const ngayKetThuc = item.querySelector('input[name="ngayKetThuc[]"]').value;

            if (!ngayBatDau || !ngayKetThuc) return;

            const start = new Date(ngayBatDau);
            const end = new Date(ngayKetThuc);
            const diffDays = Math.floor((end - start) / (1000 * 60 * 60 * 24)) + 1;

            if (diffDays > soNgayTour) {
                hasError = true;
                let errorEl = item.querySelector('.error-ngay');
                if (!errorEl) {
                    errorEl = document.createElement('small');
                    errorEl.className = 'text-danger d-block mt-1 error-ngay';
                    item.querySelector('input[name="ngayBatDau[]"]').parentNode.appendChild(errorEl);
                }
                errorEl.className = 'text-danger d-block mt-1 error-ngay';
                errorEl.textContent = `Chuyến ${index + 1}: Chỉ được tối đa ${soNgayTour} ngày! (Hiện tại: ${diffDays} ngày)`;
            }
        });

        if (hasError) {
            e.preventDefault();
            alert('Vui lòng sửa lỗi ngày trước khi lưu!');
        }
    });

    updateRemoveButtons();
</script>
@endpush

@push('styles')
<style>
    .card, .form-control, .btn, .trip-item { 
        transition: none !important; 
        box-shadow: none !important; 
    }
    .card:hover, .trip-item:hover, .form-control:hover, .btn:hover { 
        transform: none !important; 
        box-shadow: none !important; 
        background-color: inherit !important; 
        color: inherit !important;
    }
    .form-control:focus { 
        border-color: #80bdff !important; 
        outline: 0 !important; 
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25) !important; 
    }
</style>
@endpush
@endsection

