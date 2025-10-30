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
<a href="{{ route('admin.danhmuc.create') }}" class="add-btn">+ Thêm Danh Mục</a>
    <div class="table-container">
        <table class="booking-table">
            <thead>
                <tr>
                    <th>Tên Danh Muc</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($danhmucs as $dm)
                    <tr>
                        <td>{{ $dm->tenDanhMuc}}
                            <div class="buttons">
                                <a href="{{ route('admin.danhmuc.edit', $dm->maDanhMuc) }}" class="btn btn-edit">Sửa</a>
                                <form action="{{ route('admin.danhmuc.destroy', $dm->maDanhMuc) }}" method="POST" onsubmit="return confirm('Xóa danh mục này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete">Xóa</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
