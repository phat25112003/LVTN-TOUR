@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid mt-4">
    <h3 class="mb-4">Thêm Danh mục</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.danhmuc.store') }}" method="POST" class="bg-white p-4 rounded shadow-sm">
        @csrf
        <div class="mb-3">
            <label class="form-label">Tên Danh mục</label>
            <input type="text" name="tenDanhMuc" class="form-control" value="{{ old('tenDanhMuc') }}" required>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-success">Lưu</button>
            <a href="{{ route('admin.danhmuc.index') }}" class="btn btn-danger">Hủy</a>
        </div>
    </form>
</div>
@endsection
