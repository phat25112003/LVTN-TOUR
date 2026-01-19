{{-- resources/views/admin/khach-chuyen/index.blade.php --}}
@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid mt-4">
    <h2 class="text-center mb-5 fw-bold text-primary">
        <i class="fa-solid fa-users-gear me-3"></i> Quản Lý ghép tour
    </h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($tours->isEmpty())
        <div class="text-center py-5">
            <i class="fa-solid fa-bus fa-5x text-muted mb-4 opacity-50"></i>
            <p class="fs-4 text-muted">Chưa có tour nào có khách tham gia</p>
        </div>
    @else
        <div class="accordion" id="tourAccordion">
            @foreach($tours as $tourIndex => $tour)
                <div class="accordion-item border shadow-sm mb-4 rounded">
                    <!-- TIÊU ĐỀ LỚN: TÊN TOUR -->
                    <h2 class="accordion-header" id="heading{{ $tour->maTour }}">
                        <button class="accordion-button fw-bold fs-5 text-primary collapsed" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#collapse{{ $tour->maTour }}" 
                                aria-expanded="false" 
                                aria-controls="collapse{{ $tour->maTour }}">
                            <i class="fa-solid fa-map-location-dot me-3"></i>
                            {{ $tour->tieuDe }}
                            <span class="badge bg-info ms-3">
                                {{ $tour->chuyentour->sum(function($chuyen) { 
                                    return $chuyen->datCho->sum(function($dc) { return $dc->khachThamGia->count(); }) + 
                                           $chuyen->khachGhep->count(); 
                                }) }} khách
                            </span>
                        </button>
                    </h2>

                    <!-- NỘI DUNG: CÁC CHUYẾN -->
                    <div id="collapse{{ $tour->maTour }}" 
                         class="accordion-collapse collapse" 
                         aria-labelledby="heading{{ $tour->maTour }}" 
                         data-bs-parent="#tourAccordion">
                        <div class="accordion-body p-0">
                            @foreach($tour->chuyentour as $chuyenIndex => $chuyen)
                                @php
                                    // Khách chính thức
                                    $khachChinh = $chuyen->datCho->flatMap->khachThamGia;

                                    // Khách ghép (dùng quan hệ khachGhep trong model ChuyenTour)
                                    $khachGhep = $chuyen->khachGhep ?? collect();

                                    // Gộp tất cả khách để hiển thị chung
                                    $allKhach = $khachChinh->merge($khachGhep);

                                    $tongKhach = $allKhach->count();
                                @endphp
                                <div class="card border-0 {{ $chuyenIndex > 0 ? 'border-top' : '' }}">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0 fw-bold text-success">
                                            <i class="fa-solid fa-bus me-2"></i>
                                            Chuyến {{ $loop->parent->iteration }}.{{ $loop->iteration + 1 }}: 
                                            {{ $chuyen->ngayBatDau->format('d/m/Y') }} → {{ $chuyen->ngayKetThuc->format('d/m/Y') }}
                                            <span class="badge bg-secondary ms-3">{{ $tongKhach }} khách</span>
                                        </h5>

                                        <!-- NÚT THÊM KHÁCH -->
                                        <button type="button" class="btn btn-sm btn-success" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalThemKhach" 
                                                data-machuyen="{{ $chuyen->maChuyen }}">
                                            <i class="fa-solid fa-plus me-1"></i> Thêm khách
                                        </button>

                                        <a style="background-color: #155724; color:#e3f2fd" href="{{ route('admin.khachchuyen.export.khachchuyen', $chuyen->maChuyen) }}"
                                        class="btn btn-light btn-sm me-3" title="Xuất Excel">
                                            <i class="fas fa-file-excel me-1"></i> Xuất Excel
                                        </a>
                                    </div>

<div class="card-body p-0">
    @php
        // Khách chính thức từ đặt chỗ
        $khachChinh = $chuyen->datCho->flatMap->khachThamGia;

        // Khách ghép từ quan hệ khachGhep
        $khachGhep = $chuyen->khachGhep ?? collect();

        // Gộp tất cả khách để hiển thị chung
        $allKhach = $khachChinh->merge($khachGhep);

        $tongKhach = $allKhach->count();
    @endphp

    @if($tongKhach > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th>Họ tên</th>
                        <th>Tuổi</th>
                        <th>Giới tính</th>
                        <th>Phòng</th>
                        <th>Mã Booking</th>
                        <th>Thanh toán</th>
                        <th width="15%">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @php $stt = 1; @endphp
                    @foreach($allKhach as $khach)
                        <tr>
                            <td class="text-center">{{ $stt++ }}</td>
                            <td><strong>{{ $khach->hoTenKhach }}</strong></td>
                            <td class="text-center">{{ $khach->tuoi }}</td>
                            <td class="text-center">
                                <span class="badge {{ $khach->gioiTinh == 'Nam' ? 'bg-primary' : 'bg-pink' }}">
                                    {{ $khach->gioiTinh }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $khach->luaChonPhong == 'PhongDon' ? 'bg-warning text-dark' : 'bg-info' }}">
                                    {{ $khach->luaChonPhong == 'PhongDon' ? 'Phòng đơn' : 'Ghép' }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($khach->maDatCho)
                                    <span class="badge bg-dark">#000{{ $khach->maDatCho }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">Khách ghép</span>
                                @endif
                            </td>
                            <!-- THANH TOÁN - DỰA VÀO XÁC NHẬN CỦA ĐẶT CHỖ -->
                            <td class="text-center">
                                @if($khach->maDatCho && $khach->datCho->xacNhan == 1)
                                    <span class="badge bg-success">Đã TT</span>
                                @else
                                    <span class="badge bg-warning text-dark">Chưa TT</span>
                                @endif
                            </td>
                            <td>
                                <!-- Nút Sửa -->
                                <button type="button" class="btn btn-sm btn-warning" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalSuaKhach"
                                        data-makhach="{{ $khach->maKhach }}"
                                        data-hoten="{{ $khach->hoTenKhach }}"
                                        data-tuoi="{{ $khach->tuoi }}"
                                        data-gioitinh="{{ $khach->gioiTinh }}"
                                        data-phong="{{ $khach->luaChonPhong }}">
                                    <i class="fa-solid fa-edit"></i>
                                </button>

                                <!-- Nút Xóa - CHỈ HIỂN THỊ NẾU KHÔNG CÓ MÃ ĐẶT CHỖ (KHÁCH GHÉP) -->
                                @if(!$khach->maDatCho)
                                    <form action="{{ route('admin.khachchuyen.destroy', $khach->maKhach) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Xóa khách ghép {{ addslashes($khach->hoTenKhach) }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small ms-2" title="Không thể xóa khách thuộc đơn đặt chỗ chính thức">
                                        <i class="fa-solid fa-lock"></i>
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-4 text-muted">
            <i class="fa-solid fa-users-slash fa-3x mb-3 opacity-50"></i>
            <p>Chưa có khách nào tham gia chuyến này</p>
        </div>
    @endif
</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- MODAL THÊM KHÁCH -->
<div class="modal fade" id="modalThemKhach" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.khachchuyen.store') }}" method="POST" class="modal-content">
            @csrf
            <input type="hidden" name="maChuyen" id="maChuyenThem">
            <div class="modal-header">
                <h5 class="modal-title">Thêm Khách Ghép</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                    <input type="text" name="hoTenKhach" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">Tuổi <span class="text-danger">*</span></label>
                        <input type="number" name="tuoi" class="form-control" min="1" max="120" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Giới tính <span class="text-danger">*</span></label>
                        <select name="gioiTinh" class="form-select" required>
                            <option value="Nam">Nam</option>
                            <option value="Nu">Nữ</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Lựa chọn phòng <span class="text-danger">*</span></label>
                    <select name="luaChonPhong" class="form-select" required>
                        <option value="Ghep">Ghép phòng</option>
                        <option value="PhongDon">Phòng đơn (phụ thu)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn btn-success">Thêm khách</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL SỬA KHÁCH -->
<div class="modal fade" id="modalSuaKhach" tabindex="-1">
    <div class="modal-dialog">
        <form action="#" method="POST" id="formSuaKhach" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Sửa Thông Tin Khách</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="maKhach" id="maKhachSua">
                <div class="mb-3">
                    <label class="form-label">Họ tên</label>
                    <input type="text" name="hoTenKhach" id="hoTenSua" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">Tuổi</label>
                        <input type="number" name="tuoi" id="tuoiSua" class="form-control" min="1" max="120" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Giới tính</label>
                        <select name="gioiTinh" id="gioiTinhSua" class="form-select" required>
                            <option value="Nam">Nam</option>
                            <option value="Nu">Nữ</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Lựa chọn phòng</label>
                    <select name="luaChonPhong" id="phongSua" class="form-select" required>
                        <option value="Ghep">Ghép phòng</option>
                        <option value="PhongDon">Phòng đơn</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn btn-warning">Cập nhật</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Modal thêm khách: điền maChuyen
    const modalThemKhach = document.getElementById('modalThemKhach');
    modalThemKhach.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const maChuyen = button.getAttribute('data-machuyen');
        const input = modalThemKhach.querySelector('#maChuyenThem');
        input.value = maChuyen;
    });

    // Modal sửa khách: điền dữ liệu
    const modalSuaKhach = document.getElementById('modalSuaKhach');
    modalSuaKhach.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const form = modalSuaKhach.querySelector('#formSuaKhach');
        
        form.action = `/admin/khach-chuyen/khach/${button.getAttribute('data-makhach')}`;

        document.getElementById('maKhachSua').value = button.getAttribute('data-makhach');
        document.getElementById('hoTenSua').value = button.getAttribute('data-hoten');
        document.getElementById('tuoiSua').value = button.getAttribute('data-tuoi');
        document.getElementById('gioiTinhSua').value = button.getAttribute('data-gioitinh');
        document.getElementById('phongSua').value = button.getAttribute('data-phong');
    });
</script>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    .accordion-button:not(.collapsed) {
        background-color: #e3f2fd;
        color: #1976d2;
    }
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
    }
    .status-active { background: #d4edda; color: #155724; }
    .status-inactive { background: #f8d7da; color: #721c24; }
    .badge-pink { background: #f8d7ea; color: #721c24; }
    code {
        background: #f1f3f5;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 13px;
    }
    .card-header {
        background: #f8f9fa !important;
    }
    .bg-pink{
        background-color:deeppink;
    }
</style>
@endpush