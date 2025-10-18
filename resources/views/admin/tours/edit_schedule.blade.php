@extends('admin.layouts.dashboard')

@section('content')
<div class="custom-form-container">
    <h2 class="form-title">Sửa Lịch Trình cho Tour</h2>
    <h2 class="form-subtitle">{{ $tour->tieuDe }}</h2>

    <form action="{{ route('admin.tours.updateSchedule', $tour->maTour) }}" method="POST" class="custom-form">
        @csrf
        @php
            use Carbon\Carbon;
            $ngayBatDau = Carbon::parse($tour->ngayBatDau);
            $ngayKetThuc = Carbon::parse($tour->ngayKetThuc);
            $soNgay = $ngayBatDau->diffInDays($ngayKetThuc) + 1;
        @endphp

        @for ($i = 1; $i <= $soNgay; $i++)
            @php
                $ngayHienTai = $ngayBatDau->copy()->addDays($i - 1)->format('d/m/Y');
                $lich = $lichTrinh->where('ngay', $i)->first();
            @endphp

            <div class="day-block">
                <h3 class="day-title">Ngày {{ $i }} ({{ $ngayHienTai }})</h3>

                <div class="input-group">
                    <label for="huongDi{{ $i }}">Hướng đi</label>
                    <input type="text"
                           name="huongDi[{{ $i }}]"
                           id="huongDi{{ $i }}"
                           class="input-field"
                           value="{{ $lich->huongDi ?? '' }}"
                           required>
                </div>

                <div class="input-group">
                    <label for="noiDung{{ $i }}">Nội dung chuyến đi</label>
                    <textarea name="noiDung[{{ $i }}]"
                              id="noiDung{{ $i }}"
                              class="textarea-field"
                              rows="3"
                              required>{{ $lich->noiDung ?? '' }}</textarea>
                </div>
            </div>
        @endfor

        <div class="action-row">
            <button type="submit" class="btn-save">Lưu Lịch Trình</button>
            <a href="{{ route('admin.tours.index') }}" class="btn-cancel">Hủy</a>
        </div>
    </form>
</div>
@endsection
