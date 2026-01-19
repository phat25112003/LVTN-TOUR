{{-- resources/views/admin/tours/create_trips.blade.php --}}
@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid mt-4">
    <h3 class="text-center mb-4 fw-bold text-primary">
        Tạo Chuyến Cho Tour: {{ $tour->tieuDe }}
        <small class="text-muted d-block">(Thời gian: {{ $tour->thoiGian }})</small>
    </h3>

    <form action="{{ route('admin.tours.storeTrips', $tour->maTour) }}" method="POST" id="tripForm">
        @csrf

        <div id="tripContainer">
            <!-- MẪU CHUYẾN MỚI (ẨN) -->
            <template id="tripTemplate">
                <div class="card mb-3 border-primary trip-item">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <strong class="trip-number">Chuyến 1</strong>
                        <button type="button" class="btn btn-danger btn-sm remove-trip">Xóa</button>
                    </div>
                    <div class="card-body">
                        <!-- Hàng 1: Ngày + Điểm khởi hành -->
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label>Ngày bắt đầu <span class="text-danger">*</span></label>
                                <input type="date" name="ngayBatDau[]" class="form-control ngayBatDau" required>
                                <small class="text-danger d-block mt-1 error-ngay"></small>
                                @error('ngayBatDau.*')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label>Ngày kết thúc <span class="text-danger">*</span></label>
                                <input type="date" name="ngayKetThuc[]" class="form-control ngayKetThuc" required readonly>
                            </div>
                            <div class="col-md-4">
                                <label>Điểm khởi hành <span class="text-danger">*</span></label>
                                <input type="text" name="diemKhoiHanh[]" class="form-control" required>
                            </div>
                        </div>

                        <!-- Hàng 2: Số chỗ + Số khách tối thiểu -->
                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <label>Số chỗ tối đa <span class="text-danger">*</span></label>
                                <input type="number" name="soLuongToiDa[]" class="form-control" min="1" required>
                            </div>
                            <div class="col-md-6">
                                <label>Số khách tối thiểu <span class="text-danger">*</span></label>
                                <input type="number" name="so_khach_toi_thieu[]" class="form-control" min="1" value="10" required>
                                <small class="text-muted">Để chuyến được chạy (mặc định 10)</small>
                            </div>
                        </div>

                        <!-- Hướng dẫn viên, phương tiện, ghi chú -->
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
                                <select name="phuongTien[]" class="form-select">
                                    <option value="">-- Chọn phương tiện --</option>
                                    <option value="Xe du lịch">Xe du lịch</option>
                                    <option value="Xe bus">Xe bus</option>
                                    <option value="Limousine">Limousine</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-bold">Ghi chú</label>
                                <input type="text" name="ghiChu[]" class="form-control">
                            </div>
                        </div>

                        <!-- Giá -->
                        <div class="row g-3 mt-3 border-top pt-3 bg-light p-3 rounded">
                            <div class="col-md-4">
                                <label class="text-success fw-bold">Giá em bé</label>
                                <input type="text" class="form-control" value="Miễn phí" readonly>
                                <input type="hidden" name="giaEmBe[]" value="0">
                            </div>
                            <div class="col-md-4">
                                <label class="text-primary fw-bold">Giá trẻ em (70% người lớn)</label>
                                <input type="text" class="form-control giaTreEmPreview" readonly>
                                <input type="hidden" name="giaTreEm[]" class="giaTreEmHidden">
                            </div>
                            <div class="col-md-4">
                                <label class="text-danger fw-bold">Giá người lớn (VNĐ) <span class="text-danger">*</span></label>
                                <input type="number" name="giaNguoiLon[]" class="form-control giaNguoiLon" min="0" step="1000" required>
                            </div>
                        </div>

                        <!-- Trạng thái chuyến -->
                        <div class="row g-3 mt-3">
                            <div class="col-md-4">
                                <label class="fw-bold text-info">Trạng thái chuyến</label>
                                <select name="tinhTrangChuyen[]" class="form-select" required>
                                    <option value="ChuaDuKhach" selected>Chưa đủ khách</option>
                                    <option value="DuKhach">Đủ khách (sẽ chạy)</option>
                                    <option value="DaKhoiHanh">Đã khởi hành</option>
                                    <option value="Huy">Đã hủy</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- CHUYẾN ĐẦU TIÊN (HIỂN THỊ) -->
            <div class="card mb-3 border-primary trip-item">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <strong class="trip-number">Chuyến 1</strong>
                    <button type="button" class="btn btn-danger btn-sm remove-trip d-none">Xóa</button>
                </div>
                <div class="card-body">
                    <!-- Hàng 1 -->
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label>Ngày bắt đầu <span class="text-danger">*</span></label>
                            <input type="date" name="ngayBatDau[]" class="form-control ngayBatDau" required>
                            <small class="text-danger d-block mt-1 error-ngay"></small>
                            @error('ngayBatDau.*')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label>Ngày kết thúc <span class="text-danger">*</span></label>
                            <input type="date" name="ngayKetThuc[]" class="form-control ngayKetThuc" required readonly>
                        </div>
                        <div class="col-md-4">
                            <label>Điểm khởi hành <span class="text-danger">*</span></label>
                            <input type="text" name="diemKhoiHanh[]" class="form-control" required>
                        </div>
                    </div>

                    <!-- Hàng 2 -->
                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <label>Số chỗ tối đa <span class="text-danger">*</span></label>
                            <input type="number" name="soLuongToiDa[]" class="form-control" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label>Số khách tối thiểu <span class="text-danger">*</span></label>
                            <input type="number" name="so_khach_toi_thieu[]" class="form-control" min="1" value="10" required>
                            <small class="text-muted">Để chuyến được chạy (mặc định 10)</small>
                        </div>
                    </div>

                    <!-- HDV, phương tiện, ghi chú -->
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
                                <select name="phuongTien[]" class="form-select">
                                    <option value="">-- Chọn phương tiện --</option>
                                    <option value="Xe du lịch">Xe du lịch</option>
                                    <option value="Xe bus">Xe bus</option>
                                    <option value="Limousine">Limousine</option>
                                </select>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold">Ghi chú</label>
                            <input type="text" name="ghiChu[]" class="form-control">
                        </div>
                    </div>

                    <!-- Giá -->
                    <div class="row g-3 mt-3 border-top pt-3 bg-light p-3 rounded">
                        <div class="col-md-4">
                            <label class="text-success fw-bold">Giá em bé</label>
                            <input type="text" class="form-control" value="Miễn phí" readonly>
                            <input type="hidden" name="giaEmBe[]" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="text-primary fw-bold">Giá trẻ em (70% người lớn)</label>
                            <input type="text" class="form-control giaTreEmPreview" readonly>
                            <input type="hidden" name="giaTreEm[]" class="giaTreEmHidden">
                        </div>
                        <div class="col-md-4">
                            <label class="text-danger fw-bold">Giá người lớn (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" name="giaNguoiLon[]" class="form-control giaNguoiLon" min="0" step="1000" required>
                        </div>
                    </div>

                    <!-- Trạng thái -->
                    <div class="row g-3 mt-3">
                        <div class="col-md-4">
                            <label class="fw-bold text-info">Trạng thái chuyến</label>
                            <select name="tinhTrangChuyen[]" class="form-select" required>
                                <option value="ChuaDuKhach" selected>Chưa đủ khách</option>
                                <option value="DuKhach">Đủ khách (sẽ chạy)</option>
                                <option value="DaKhoiHanh">Đã khởi hành</option>
                                <option value="Huy">Đã hủy</option>
                            </select>
                        </div>
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
@endsection

@push('scripts')
<script>
const thoiGianStr = "{{ $tour->thoiGian }}".toLowerCase();
let soNgayTour = 1;

if (!thoiGianStr.includes('trong ngày')) {
    const match = thoiGianStr.match(/(\d+)\s*ngày/);
    soNgayTour = match ? parseInt(match[1]) : 1;
}

let tripCount = 1;

// ===== GIÁ TRẺ EM =====
function capNhatGiaTreEm(tripItem) {
    const giaNL = parseFloat(tripItem.querySelector('.giaNguoiLon').value) || 0;
    const giaTE = Math.round(giaNL * 0.7 / 10000) * 10000;

    tripItem.querySelector('.giaTreEmPreview').value =
        giaTE ? giaTE.toLocaleString('vi-VN') + ' VNĐ' : '';
    tripItem.querySelector('.giaTreEmHidden').value = giaTE;
}

// ===== NGÀY =====
function handleNgayBatDau(e) {
    const tripItem = e.target.closest('.trip-item');
    const errorEl = tripItem.querySelector('.error-ngay');
    errorEl.textContent = '';

    if (!e.target.value) return;

    const start = new Date(e.target.value);
    const end = new Date(start);
    end.setDate(start.getDate() + soNgayTour - 1);

    tripItem.querySelector('.ngayKetThuc').value =
        end.toISOString().split('T')[0];
}

// ===== THÊM CHUYẾN =====
document.getElementById('addTrip').addEventListener('click', () => {
    tripCount++;
    const tpl = document.getElementById('tripTemplate').content.cloneNode(true);
    const item = tpl.querySelector('.trip-item');

    item.querySelector('.trip-number').textContent = `Chuyến ${tripCount}`;
    item.querySelector('.remove-trip').classList.remove('d-none');

    item.querySelector('.ngayBatDau').addEventListener('change', handleNgayBatDau);
    item.querySelector('.giaNguoiLon')
        .addEventListener('input', () => capNhatGiaTreEm(item));

    document.getElementById('tripContainer').appendChild(item);
    updateUI();
});

// ===== XÓA =====
document.addEventListener('click', e => {
    if (e.target.classList.contains('remove-trip')) {
        if (document.querySelectorAll('.trip-item').length > 1) {
            e.target.closest('.trip-item').remove();
            updateUI();
        }
    }
});

function updateUI() {
    document.querySelectorAll('.trip-number').forEach((el, i) => {
        el.textContent = `Chuyến ${i + 1}`;
    });

    const items = document.querySelectorAll('.trip-item');
    items.forEach(item =>
        item.querySelector('.remove-trip')
            .classList.toggle('d-none', items.length === 1)
    );
}

// ===== INIT =====
document.querySelector('.ngayBatDau').addEventListener('change', handleNgayBatDau);
document.querySelector('.giaNguoiLon').addEventListener('input', function () {
    capNhatGiaTreEm(this.closest('.trip-item'));
});
</script>
@endpush
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush