@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid mt-4">
    <h3>Thêm Tour Mới</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Lỗi!</strong> Vui lòng kiểm tra lại thông tin nhập.
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.danhmuc.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
        @csrf
        <div class="mb-3">
            <label class="form-label">Danh Mục</label>
            <input type="text" name="tenDanhMuc" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Lưu</button>
    </form>
</div>
@endsection
