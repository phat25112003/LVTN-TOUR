{{-- resources/views/admin/huongdanvien/show.blade.php --}}
@extends('admin.layouts.dashboard')

@section('content')
<div class="profile-container">
    <div class="profile-card">

        <!-- ẢNH CHỮ NHẬT TO RÕ -->
        <div class="profile-photo">
            <img src="{{ $hdv->avatar_url }}" alt="{{ $hdv->hoTen }}">
        </div>

        <!-- TÊN + CHỨC VỤ -->
        <div class="profile-name">
            <h3>{{ $hdv->hoTen }}</h3>
            <p>Hướng Dẫn Viên</p>
        </div>

        <!-- THÔNG TIN CHI TIẾT -->
        <div class="profile-info">
            <div class="info-row">
                <span class="info-label">Số điện thoại</span>
                <span class="info-value">{{ $hdv->soDienThoai }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value">{{ $hdv->email ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Địa chỉ</span>
                <span class="info-value">{{ $hdv->diaChi ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">CCCD</span>
                <span class="info-value">{{ $hdv->soCCCD ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Ngày sinh</span>
                <span class="info-value">{{ $hdv->ngaySinh ? date('d/m/Y', strtotime($hdv->ngaySinh)) : '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Giới tính</span>
                <span class="info-value">{{ $hdv->gioiTinh == 'Nam' ? 'Nam' : 'Nữ' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Trạng thái</span>
                <span class="info-value">{{ $hdv->trangThai == 'HoatDong' ? 'Hoạt động' : 'Ngừng' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Số chuyến</span>
                <span class="info-value">{{ $hdv->chuyenTours->count() }}</span>
            </div>
            <div class="info-row full">
                <span class="info-label">Ghi chú</span>
                <p class="info-note">{{ $hdv->ghiChu ?? 'Không có ghi chú.' }}</p>
            </div>
        </div>

        <!-- 2 NÚT BẰNG NHAU, CHỮ NHẬT -->
        <div class="profile-actions">
            <a href="{{ route('admin.huongdanvien.edit', $hdv->maHDV) }}" class="btn btn-edit">Sửa</a>
            <a href="{{ route('admin.huongdanvien.index') }}" class="btn btn-back">Quay lại</a>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
    /* === TOÀN BỘ TRANG – KHÔNG BOOTSTRAP === */
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    .profile-container {
        max-width: 800px;
        margin: 40px auto;
        padding: 0 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .profile-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    /* === ẢNH === */
    .profile-photo {
        text-align: center;
        padding: 30px 20px 20px;
        background: #f8f9fa;
    }
    .profile-photo img {
        width: 280px;
        height: 360px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
    }

    /* === TÊN === */
    .profile-name {
        text-align: center;
        padding: 20px 20px 30px;
        border-bottom: 1px solid #eee;
    }
    .profile-name h3 {
        font-size: 26px;
        font-weight: 600;
        color: #212529;
        margin-bottom: 6px;
    }
    .profile-name p {
        color: #6c757d;
        font-size: 16px;
    }

    /* === THÔNG TIN === */
    .profile-info {
        padding: 30px 40px;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 14px 0;
        border-bottom: 1px dashed #e9ecef;
        font-size: 15px;
    }
    .info-row:last-of-type {
        border-bottom: none;
    }
    .info-row.full {
        flex-direction: column;
    }
    .info-label {
        font-weight: 600;
        color: #343a40;
        min-width: 130px;
    }
    .info-value {
        color: #495057;
        text-align: right;
        flex: 1;
    }
    .info-note {
        margin-top: 8px;
        color: #6c757d;
        font-size: 14px;
        line-height: 1.5;
    }

    /* === 2 NÚT BẰNG NHAU, CHỮ NHẬT === */
    .profile-actions {
        display: flex;
        justify-content: center;
        gap: 20px;
        padding: 30px 40px;
        background: #f8f9fa;
    }
    .btn {
        min-width: 140px;
        padding: 12px 20px;
        font-size: 15px;
        font-weight: 500;
        text-align: center;
        text-decoration: none;
        border-radius: 8px;
        border: 2px solid;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .btn-edit {
        background: #877c04ff;
        color: #ffffffff;
        border-color: #000000ff;
    }
    .btn-edit:hover {
        background: #8cb200ff;
        color: #fff;
    }
    .btn-back {
        background: #ab919171;
        color: #6c757d;
        border-color: #6c757d;
    }
    .btn-back:hover {
        background: #6c757d;
        color: #fff;
    }

    /* === RESPONSIVE === */
    @media (max-width: 600px) {
        .profile-photo img {
            width: 200px;
            height: 280px;
        }
        .profile-info, .profile-actions {
            padding: 20px;
        }
        .info-row {
            flex-direction: column;
            text-align: left;
        }
        .info-value {
            text-align: left;
            margin-top: 4px;
        }
        .profile-actions {
            flex-direction: column;
        }
        .btn {
            width: 100%;
        }
    }
</style>
@endpush