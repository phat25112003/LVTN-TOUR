{{-- resources/views/admin/tours/edit_trips.blade.php --}}
@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid mt-4">
    <h3 class="text-center mb-4 fw-bold text-primary">
        Sửa Chuyến Cho Tour: {{ $tour->tieuDe }}
    </h3>

    <form action="{{ route('admin.tours.updateTrips', $tour->maTour) }}" method="POST" id="tripForm">
        @csrf
        @method('PUT')

        <div id="tripContainer">
            <!-- Mẫu chuyến (ẩn) -->
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
                                <input type="date" name="ngayBatDau[]" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label>Ngày kết thúc <span class="text-danger">*</span></label>
                                <input type="date" name="ngayKetThuc[]" class="form-control" required>
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

                        <div class="row g-3 mt-2">
                            <div class="col-md-4">
                                <label>Hướng dẫn viên</label>
                                <input type="text" name="huongDanVien[]" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label>Phương tiện</label>
                                <input type="text" name="phuongTien[]" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label>Ghi chú</label>
                                <input type="text" name="ghiChu[]" class="form-control">
                            </div>
                        </div>

                        <!-- TRẠNG THÁI CHUYẾN -->
                        <div class="row g-3 mt-3">
                            <div class="col-md-4">
                                <label class="fw-bold">Trạng thái chuyến</label>
                                <select name="tinhTrangChuyen[]" class="form-select" required>
                                    <option value="HoatDong" {{ old('tinhTrangChuyen', $chuyen->tinhTrangChuyen ?? 'HoatDong') == 'HoatDong' ? 'selected' : '' }}>
                                        Hoạt động
                                    </option>
                                    <option value="NgungChay" {{ old('tinhTrangChuyen', $chuyen->tinhTrangChuyen ?? 'HoatDong') == 'NgungChay' ? 'selected' : '' }}>
                                        Ngừng chạy
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-8"></div>
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
                            <input type="date" name="ngayBatDau[]" class="form-control" value="{{ $chuyen->ngayBatDau->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="fw-bold">Ngày kết thúc <span class="text-danger">*</span></label>
                            <input type="date" name="ngayKetThuc[]" class="form-control" value="{{ $chuyen->ngayKetThuc->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="fw-bold">Điểm khởi hành <span class="text-danger">*</span></label>
                            <input type="text" name="diemKhoiHanh[]" class="form-control" value="{{ $chuyen->diemKhoiHanh }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="fw-bold">Số chỗ tối đa <span class="text-danger">*</span></label>
                            <input type="number" name="soLuongToiDa[]" class="form-control" value="{{ $chuyen->soLuongToiDa }}" min="1" required>
                        </div>
                    </div>

                    <!-- GIÁ -->
                    <div class="row g-3 mt-3 border-top pt-3 bg-light p-2 rounded">
                        <div class="col-md-4">
                            <label class="text-success fw-bold">Giá em bé</label>
                            <input type="number" name="giaEmBe[]" class="form-control" value="{{ $chuyen->giaTour->emBe ?? 0 }}" min="0" step="0.01" required>
                        </div>
                        <div class="col-md-4">
                            <label class="text-primary fw-bold">Giá trẻ em</label>
                            <input type="number" name="giaTreEm[]" class="form-control" value="{{ $chuyen->giaTour->treEm ?? 0 }}" min="0" step="0.01" required>
                        </div>
                        <div class="col-md-4">
                            <label class="text-danger fw-bold">Giá người lớn</label>
                            <input type="number" name="giaNguoiLon[]" class="form-control" value="{{ $chuyen->giaTour->nguoiLon ?? 0 }}" min="0" step="0.01" required>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-4">
                            <label class="fw-bold">Hướng dẫn viên</label>
                            <input type="text" name="huongDanVien[]" class="form-control" value="{{ $chuyen->huongDanVien }}">
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold">Phương tiện</label>
                            <input type="text" name="phuongTien[]" class="form-control" value="{{ $chuyen->phuongTien }}">
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold">Ghi chú</label>
                            <input type="text" name="ghiChu[]" class="form-control" value="{{ $chuyen->ghiChu }}">
                        </div>
                    </div>

                    <!-- TRẠNG THÁI CHUYẾN -->
                    <div class="row g-3 mt-3">
                        <div class="col-md-4">
                            <label class="fw-bold">Trạng thái chuyến</label>
                            <select name="tinhTrangChuyen[]" class="form-select" required>
                                <option value="HoatDong" {{ old('tinhTrangChuyen', $chuyen->tinhTrangChuyen ?? 'HoatDong') == 'HoatDong' ? 'selected' : '' }}>
                                    Hoạt động
                                </option>
                                <option value="NgungChay" {{ old('tinhTrangChuyen', $chuyen->tinhTrangChuyen ?? 'HoatDong') == 'NgungChay' ? 'selected' : '' }}>
                                    Ngừng chạy
                                </option>
                            </select>
                        </div>
                        <div class="col-md-8"></div>
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
    const tripContainer = document.getElementById('tripContainer');
    
    let tripCount = tripContainer.querySelectorAll('.trip-item').length;

    document.getElementById('addTrip').addEventListener('click', function () {
        tripCount++;
        const template = document.getElementById('tripTemplate').content.cloneNode(true);
        template.querySelector('.trip-number').textContent = `Chuyến ${tripCount}`;
        template.querySelector('input[name="maChuyen[]"]').value = ''; // Mới
        document.getElementById('tripContainer').appendChild(template);
        updateRemoveButtons();
    });

    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-trip')) {
            e.target.closest('.trip-item').remove();
            tripCount--;
            updateTripNumbers();
            updateRemoveButtons();
        }
    });

    function updateTripNumbers() {
        document.querySelectorAll('.trip-number').forEach((el, index) => {
            el.textContent = `Chuyến ${index + 1}`;
        });
    }

    function updateRemoveButtons() {
        const items = document.querySelectorAll('.trip-item');
        items.forEach((item, index) => {
            const btn = item.querySelector('.remove-trip');
            if (items.length === 1) {
                btn.classList.add('d-none');
            } else {
                btn.classList.remove('d-none');
            }
        });
    }

    updateRemoveButtons();
</script>
@endpush

{{-- BỎ HIỆU ỨNG HOVER --}}
@push('styles')
<style>
    .card, .form-control, .btn, .trip-item { transition: none !important; box-shadow: none !important; }
    .card:hover, .trip-item:hover, .form-control:hover, .btn:hover { 
        transform: none !important; box-shadow: none !important; 
        background-color: inherit !important; color: inherit !important;
    }
    .form-control:focus { border-color: #80bdff !important; box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25) !important; }
</style>
@endpush
@endsection


