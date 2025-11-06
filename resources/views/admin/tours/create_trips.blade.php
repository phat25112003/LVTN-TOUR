{{-- resources/views/admin/tours/create_trips.blade.php --}}
@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid mt-4">
    <h3 class="text-center mb-4 fw-bold text-primary">
        Tạo Chuyến Cho Tour: {{ $tour->tieuDe }}
    </h3>

    <form action="{{ route('admin.tours.storeTrips', $tour->maTour) }}" method="POST" id="tripForm">
        @csrf

        <div id="tripContainer">
            <!-- MẪU CHUYẾN (ẨN) -->
            <template id="tripTemplate">
                <div class="card mb-3 border-primary trip-item">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <strong class="trip-number">Chuyến 1</strong>
                        <button type="button" class="btn btn-danger btn-sm remove-trip">Xóa</button>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="fw-bold">Ngày bắt đầu <span class="text-danger">*</span></label>
                                <input type="date" name="ngayBatDau[]" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label class="fw-bold">Ngày kết thúc <span class="text-danger">*</span></label>
                                <input type="date" name="ngayKetThuc[]" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label class="fw-bold">Điểm khởi hành <span class="text-danger">*</span></label>
                                <input type="text" name="diemKhoiHanh[]" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label class="fw-bold">Số chỗ tối đa <span class="text-danger">*</span></label>
                                <input type="number" name="soLuongToiDa[]" class="form-control" min="1" required>
                            </div>
                        </div>

                        <!-- PHẦN GIÁ -->
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
                                <label class="fw-bold">Hướng dẫn viên</label>
                                <input type="text" name="huongDanVien[]" class="form-control">
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

                                                <!-- TRẠNG THÁI CHUYẾN -->
                        <div class="row g-3 mt-3">
                            <div class="col-md-4">
                                <label class="fw-bold text-info">Trạng thái chuyến</label>
                                <select name="tinhTrangChuyen[]" class="form-select" required>
                                    <option value="HoatDong" selected>Hoạt động</option>
                                    <option value="NgungChay">Ngừng chạy</option>
                                </select>
                            </div>
                            <div class="col-md-8"></div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- CHUYẾN ĐẦU TIÊN -->
            <div class="card mb-3 border-primary trip-item">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <strong class="trip-number">Chuyến 1</strong>
                    <button type="button" class="btn btn-danger btn-sm remove-trip d-none">Xóa</button>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="fw-bold">Ngày bắt đầu <span class="text-danger">*</span></label>
                            <input type="date" name="ngayBatDau[]" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="fw-bold">Ngày kết thúc <span class="text-danger">*</span></label>
                            <input type="date" name="ngayKetThuc[]" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="fw-bold">Điểm khởi hành <span class="text-danger">*</span></label>
                            <input type="text" name="diemKhoiHanh[]" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="fw-bold">Số chỗ tối đa <span class="text-danger">*</span></label>
                            <input type="number" name="soLuongToiDa[]" class="form-control" min="1" required>
                        </div>
                    </div>

                    <!-- PHẦN GIÁ -->
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
                            <label class="fw-bold">Hướng dẫn viên</label>
                            <input type="text" name="huongDanVien[]" class="form-control">
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

                    <!-- TRẠNG THÁI CHUYẾN -->
                    <div class="row g-3 mt-3">
                        <div class="col-md-4">
                            <label class="fw-bold text-info">Trạng thái chuyến</label>
                            <select name="tinhTrangChuyen[]" class="form-select" required>
                                <option value="HoatDong" selected>Hoạt động</option>
                                <option value="NgungChay">Ngừng chạy</option>
                            </select>
                        </div>
                        <div class="col-md-8"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <button type="button" id="addTrip" class="btn btn-info btn-lg">Thêm Chuyến</button>
            <button type="submit" class="btn btn-success btn-lg">Hoàn Tất Tour</button>
            <a href="{{ route('admin.tours.index') }}" class="btn btn-secondary btn-lg">Hủy</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    let tripCount = 1;

    document.getElementById('addTrip').addEventListener('click', function () {
        tripCount++;
        const template = document.getElementById('tripTemplate').content.cloneNode(true);
        template.querySelector('.trip-number').textContent = `Chuyến ${tripCount}`;
        template.querySelector('.remove-trip').classList.remove('d-none');
        template.querySelector('select[name="tinhTrangChuyen[]"]').value = 'HoatDong'; // Mặc định
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

@push('styles')
<style>
    /* BỎ TẤT CẢ HIỆU ỨNG HOVER */
    .card, .form-control, .form-control:focus, .btn, .trip-item, input, select, textarea {
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
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
    }
</style>
@endpush
@endsection