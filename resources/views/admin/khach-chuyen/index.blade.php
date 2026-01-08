{{-- resources/views/admin/khach-chuyen/index.blade.php --}}
@extends('admin.layouts.dashboard')

@section('content')
<div style="padding: 20px; font-family: Arial, sans-serif;">

    <h2 style="text-align: center; margin-bottom: 30px; color: #007bff; font-weight: bold;">
        Quản Lý Khách Tham Gia Theo Chuyến Tour
    </h2>

    {{-- THÔNG BÁO --}}
    @if (session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb; text-align: center;">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb; text-align: center;">
            {{ session('error') }}
        </div>
    @endif

    <!-- Bảng danh sách chuyến tour -->
    <div style="background: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden;">
        <div style="background: #007bff; color: white; padding: 15px; font-size: 18px; font-weight: bold;">
            <i class="fas fa-bus" style="margin-right: 10px;"></i> Danh Sách Chuyến Tour Có Khách Tham Gia
        </div>

        <div style="overflow-x: auto;">
            @php
                $tours = \App\Models\Tour::whereHas('chuyentour.datCho.khachThamGia')
                             ->with('chuyentour')
                             ->get();
            @endphp

            @if($tours->count() > 0)
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">STT</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Tên Tour</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Mã Chuyến</th>
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Thời Gian</th>
                            <th style="padding: 12px; text-align: center; border-bottom: 2px solid #dee2e6;">Số Khách</th>
                            <th style="padding: 12px; text-align: center; border-bottom: 2px solid #dee2e6;">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tours as $tourIndex => $tour)
                            @foreach($tour->chuyentour as $chuyenIndex => $chuyen)
                                @php
                                    $soKhach = $chuyen->datCho->sum(fn($dc) => $dc->soNguoiLon + $dc->soTreEm + $dc->soEmBe);
                                    $stt = $loop->parent->index * 100 + $loop->index + 1;
                                @endphp
                                <tr style="border-bottom: 1px solid #dee2e6;">
                                    <td style="padding: 12px; text-align: center;">{{ $stt }}</td>
                                    <td style="padding: 12px;">{{ $tour->tieuDe }}</td>
                                    <td style="padding: 12px; text-align: center;">#00{{ $chuyen->maChuyen }}</td>
                                    <td style="padding: 12px;">
                                        {{ \Carbon\Carbon::parse($chuyen->ngayBatDau)->format('d/m/Y') }} → 
                                        {{ \Carbon\Carbon::parse($chuyen->ngayKetThuc)->format('d/m/Y') }}
                                    </td>
                                    <td style="padding: 12px; text-align: center;">
                                        <span style="background: #007bff; color: white; padding: 5px 12px; border-radius: 20px; font-size: 14px;">
                                            {{ $soKhach }} khách
                                        </span>
                                    </td>
                                    <td style="padding: 12px; text-align: center;">
                                        <button type="button" class="action-btn btn-view-khach"
                                                onclick="document.getElementById('modalKhach{{ $chuyen->maChuyen }}').style.display='block'">
                                            <i class="fas fa-users"></i> Xem Khách
                                        </button>
                                        <a href="{{ route('admin.datcho.export.khachchuyen', $chuyen->maChuyen) }}" class="action-btn btn-export">
                                            <i class="fas fa-file-excel">Xuất</i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            @else
                <div style="text-align: center; padding: 60px; color: #6c757d;">
                    <i class="fas fa-bus" style="font-size: 80px; opacity: 0.5; margin-bottom: 20px;"></i>
                    <p style="font-size: 20px;">Chưa có chuyến tour nào có khách tham gia</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- CSS riêng cho nút và modal -->
<style>
    .action-btn {
        padding: 8px 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
        margin: 0 4px;
        display: inline-block;
        min-width: 40px;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        color: white;
    }
    .btn-view-khach { background: #17a2b8; }
    .btn-view-khach:hover { background: #138496; transform: translateY(-2px); box-shadow: 0 6px 12px rgba(23,162,184,0.4); }
    .btn-export { background: #28a745; }
    .btn-export:hover { background: #218838; transform: translateY(-2px); box-shadow: 0 6px 12px rgba(40,167,69,0.4); }
    .btn-edit { background: #ffc107; color: #212529; }
    .btn-edit:hover { background: #e0a800; transform: translateY(-2px); box-shadow: 0 6px 12px rgba(255,193,7,0.4); }
    .btn-delete { background: #dc3545; color: white; }
    .btn-delete:hover { background: #c82333; transform: translateY(-2px); box-shadow: 0 6px 12px rgba(220,53,69,0.4); }
    .btn-add { background: #28a745; color: white; }
    .btn-add:hover { background: #218838; transform: translateY(-2px); box-shadow: 0 6px 12px rgba(40,167,69,0.4); }

    .custom-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0; top: 0;
        width: 100%; height: 100%;
        background-color: rgba(0,0,0,0.5);
        overflow: auto;
    }
    .custom-modal-content {
        background: white;
        margin: 5% auto;
        width: 90%;
        max-width: 1200px;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
    .custom-modal-header {
        background: #17a2b8;
        color: white;
        padding: 15px 20px;
        border-radius: 10px 10px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .custom-close {
        color: white;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    .custom-close:hover { opacity: 0.7; }
    .custom-modal-body { padding: 20px; }
    .custom-table {
        width: 100%;
        border-collapse: collapse;
    }
    .custom-table th, .custom-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #dee2e6;
    }
    .custom-table th {
        background: #f8f9fa;
        font-weight: bold;
    }
</style>

@php
    $tours = \App\Models\Tour::whereHas('chuyentour.datCho.khachThamGia')
                 ->with([
                     'chuyentour.datCho.khachThamGia',
                     'chuyentour.datCho.thanhtoan'
                 ])
                 ->get();
@endphp

@foreach($tours as $tour)
    @foreach($tour->chuyentour as $chuyen)
        @php
            $allKhach = $chuyen->datCho->pluck('khachThamGia')->flatten();
            $khachs = $allKhach->sortBy('maKhach')->values();
        @endphp

        <div class="custom-modal" id="modalKhach{{ $chuyen->maChuyen }}">
            <div class="custom-modal-content">
                <div class="custom-modal-header">
                    <h5>Khách Tham Gia – Chuyến #00{{ $chuyen->maChuyen }}</h5>
                    <span class="custom-close" onclick="document.getElementById('modalKhach{{ $chuyen->maChuyen }}').style.display='none'">&times;</span>
                </div>
                <div class="custom-modal-body">
                    <p style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee; color: #6c757d;">
                        <strong>Tour:</strong> {{ $tour->tieuDe }}<br>
                        <strong>Thời gian:</strong>
                        {{ \Carbon\Carbon::parse($chuyen->ngayBatDau)->format('d/m/Y') }} →
                        {{ \Carbon\Carbon::parse($chuyen->ngayKetThuc)->format('d/m/Y') }}
                    </p>

                    <!-- Nút Thêm Khách -->
                    <div style="text-align: right; margin-bottom: 20px;">
                        <button type="button" class="action-btn btn-add"
                                onclick="document.getElementById('addKhach{{ $chuyen->maChuyen }}').style.display='block'">
                            <i class="fas fa-plus"></i> Thêm Khách Mới
                        </button>
                    </div>

                    @if($khachs->count() > 0)
                        <div style="overflow-x: auto;">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Họ Tên</th>
                                        <th>Tuổi</th>
                                        <th>Giới Tính</th>
                                        <th>Phòng</th>
                                        <th>Mã Booking</th>
                                        <th>Trạng thái TT</th>
                                        <th>Mã Vé</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($khachs as $index => $khach)
                                        @php
                                            $datChoCuaKhach = $chuyen->datCho->firstWhere('maDatCho', $khach->maDatCho);
                                            $trangThaiTT = $datChoCuaKhach?->thanhtoan?->tinhTrangThanhToan ?? 'Chưa thanh toán';
                                            $isDaThanhToan = $trangThaiTT === 'Đã thanh toán';
                                        @endphp
                                        <tr>
                                            <td style="text-align: center;">{{ $index + 1 }}</td>
                                            <td style="font-weight: 600;">{{ $khach->hoTenKhach }}</td>
                                            <td style="text-align: center;">{{ $khach->tuoi }}</td>
                                            <td style="text-align: center;">
                                                <span style="background: {{ $khach->gioiTinh == 'Nam' ? '#007bff' : '#dc3545' }}; color: white; padding: 4px 10px; border-radius: 12px; font-size: 12px;">
                                                    {{ $khach->gioiTinh == 'Nam' ? 'Nam' : 'Nữ' }}
                                                </span>
                                            </td>
                                            <td style="text-align: center;">
                                                {{ $khach->luaChonPhong == 'PhongDon' ? 'Phòng đơn' : 'Ghép phòng' }}
                                            </td>
                                            <td style="text-align: center;">
                                                <a href="{{ route('admin.datcho.show', $khach->maDatCho) }}" style="color: #007bff; text-decoration: none;">
                                                    #{{ str_pad($khach->maDatCho, 6, '0', STR_PAD_LEFT) }}
                                                </a>
                                            </td>
                                            <td style="text-align: center;">
                                                <span style="background: {{ $isDaThanhToan ? '#28a745' : '#ffc107' }}; color: white; padding: 4px 10px; border-radius: 12px; font-size: 12px;">
                                                    {{ $trangThaiTT }}
                                                </span>
                                            </td>
                                            <td style="text-align: center;">{{ $khach->maVe }}</td>
                                            <td style="text-align: center;">
                                                <button type="button" class="action-btn btn-edit"
                                                        onclick="document.getElementById('editKhach{{ $khach->maKhach }}').style.display='block'">
                                                    <i class="fas fa-edit">Sửa</i>
                                                </button>
                                                <button type="button" class="action-btn btn-delete"
                                                        onclick="confirmDeleteKhach({{ $khach->maKhach }})">
                                                    <i class="fas fa-trash">Xóa</i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Modal Sửa Khách -->
                                        <div class="custom-modal" id="editKhach{{ $khach->maKhach }}">
                                            <div class="custom-modal-content" style="max-width: 600px;">
                                                <div class="custom-modal-header" style="background: #ffc107; color: #212529;">
                                                    <h5>Sửa khách: {{ $khach->hoTenKhach }}</h5>
                                                    <span class="custom-close" onclick="document.getElementById('editKhach{{ $khach->maKhach }}').style.display='none'">&times;</span>
                                                </div>
                                                <div class="custom-modal-body">
                                                    <form action="{{ route('admin.khachchuyen.update', $khach->maKhach) }}" method="POST">
                                                        @csrf @method('PUT')
                                                        <div style="margin-bottom: 15px;">
                                                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Họ tên</label>
                                                            <input name="hoTenKhach" type="text" value="{{ $khach->hoTenKhach }}" required
                                                                   style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 5px;">
                                                        </div>
                                                        <div style="margin-bottom: 15px;">
                                                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Tuổi</label>
                                                            <input name="tuoi" type="number" min="1" value="{{ $khach->tuoi }}" required
                                                                   style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 5px;">
                                                        </div>
                                                        <div style="margin-bottom: 15px;">
                                                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Giới tính</label>
                                                            <select name="gioiTinh" required style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 5px;">
                                                                <option value="Nam" {{ $khach->gioiTinh == 'Nam' ? 'selected' : '' }}>Nam</option>
                                                                <option value="Nu" {{ $khach->gioiTinh == 'Nu' ? 'selected' : '' }}>Nữ</option>
                                                            </select>
                                                        </div>
                                                        <div style="margin-bottom: 15px;">
                                                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Phòng</label>
                                                            <select name="luaChonPhong" required style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 5px;">
                                                                <option value="Ghep" {{ $khach->luaChonPhong == 'Ghep' ? 'selected' : '' }}>Ghép phòng</option>
                                                                <option value="PhongDon" {{ $khach->luaChonPhong == 'PhongDon' ? 'selected' : '' }}>Phòng đơn</option>
                                                            </select>
                                                        </div>
                                                        <div style="margin-bottom: 20px;">
                                                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Mã vé</label>
                                                            <input name="maVe" type="text" value="{{ $khach->maVe }}" required
                                                                   style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 5px;">
                                                        </div>
                                                        <div style="text-align: right;">
                                                            <button type="button" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; margin-right: 10px;"
                                                                    onclick="document.getElementById('editKhach{{ $khach->maKhach }}').style.display='none'">
                                                                Hủy
                                                            </button>
                                                            <button type="submit" style="padding: 10px 20px; background: #ffc107; color: #212529; border: none; border-radius: 5px;">
                                                                Cập nhật
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div style="text-align: right; margin-top: 30px; font-size: 20px; color: #007bff; font-weight: bold;">
                            Tổng cộng: {{ $khachs->count() }} khách
                        </div>
                    @else
                        <div style="text-align: center; padding: 80px; color: #6c757d;">
                            <i class="fas fa-users-slash" style="font-size: 80px; opacity: 0.5; margin-bottom: 20px;"></i>
                            <p style="font-size: 20px;">Chưa có khách nào đăng ký cho chuyến này</p>
                        </div>
                    @endif
                </div>

                <div style="padding: 15px; text-align: right; background: #f8f9fa; border-radius: 0 0 10px 10px;">
                    <button type="button" style="padding: 10px 25px; background: #6c757d; color: white; border: none; border-radius: 5px;"
                            onclick="document.getElementById('modalKhach{{ $chuyen->maChuyen }}').style.display='none'">
                        Đóng
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Thêm Khách Mới -->
        <div class="custom-modal" id="addKhach{{ $chuyen->maChuyen }}">
            <div class="custom-modal-content" style="max-width: 600px;">
                <div class="custom-modal-header" style="background: #28a745; color: white;">
                    <h5>Thêm Khách Mới – Chuyến #00{{ $chuyen->maChuyen }}</h5>
                    <span class="custom-close" onclick="document.getElementById('addKhach{{ $chuyen->maChuyen }}').style.display='none'">&times;</span>
                </div>
                <div class="custom-modal-body">
                    <form action="{{ route('admin.khachchuyen.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="maChuyen" value="{{ $chuyen->maChuyen }}">

                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Họ tên</label>
                            <input name="hoTenKhach" type="text" required
                                   style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 5px;">
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Tuổi</label>
                            <input name="tuoi" type="number" min="1" required
                                   style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 5px;">
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Giới tính</label>
                            <select name="gioiTinh" required style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 5px;">
                                <option value="Nam">Nam</option>
                                <option value="Nu">Nữ</option>
                            </select>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Phòng</label>
                            <select name="luaChonPhong" required style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 5px;">
                                <option value="Ghep">Ghép phòng</option>
                                <option value="PhongDon">Phòng đơn</option>
                            </select>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Mã vé</label>
                            <input name="maVe" type="text" required
                                   style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 5px;">
                        </div>
                        <div style="text-align: right;">
                            <button type="button" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; margin-right: 10px;"
                                    onclick="document.getElementById('addKhach{{ $chuyen->maChuyen }}').style.display='none'">
                                Hủy
                            </button>
                            <button type="submit" style="padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 5px;">
                                Thêm Khách
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endforeach

<!-- Script xóa khách -->
<script>
function confirmDeleteKhach(maKhach) {
    if (confirm('Xóa khách này? Hành động này không thể hoàn tác!')) {
        let form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ url("admin/khach-chuyen/khach") }}/' + maKhach;

        let csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);

        let method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';
        form.appendChild(method);

        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush