@extends('admin.layouts.dashboard')

@section('content')
<div class="booking-container">
    <h2 class="text-center mb-4 fw-bold text-primary">Danh sách Booking</h2>
    @if (session('success'))
        <div class="notify notify-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="notify notify-error">{{ session('error') }}</div>
    @endif
<form action="{{ route('admin.danhmuc.update', $danhmuc->maDanhMuc) }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
@csrf
@method('PUT')
    <div class="table-container">
        <input type="text" name="tenDanhMuc" class="form-control" value="{{ $danhmuc->tenDanhMuc }}" required>
    </div>
    <button type="submit" class="btn btn-success mt-3">Lưu</button>
</div>
@endsection
