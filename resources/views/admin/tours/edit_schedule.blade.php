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

                <div class="meal-section">
                    <div class="meal-group">
                        <label for="sang{{ $i }}" class="meal-label"><strong>Sáng:</strong></label>
                        <textarea name="sang[{{ $i }}]"
                                  id="sang{{ $i }}"
                                  class="textarea-field"
                                  rows="2">{{ $lich->sang ?? '' }}</textarea>
                    </div>
                    <div class="meal-group">
                        <label for="trua{{ $i }}" class="meal-label"><strong>Trưa:</strong></label>
                        <textarea name="trua[{{ $i }}]"
                                  id="trua{{ $i }}"
                                  class="textarea-field"
                                  rows="2">{{ $lich->trua ?? '' }}</textarea>
                    </div>
                    <div class="meal-group">
                        <label for="chieu{{ $i }}" class="meal-label"><strong>Chiều:</strong></label>
                        <textarea name="chieu[{{ $i }}]"
                                  id="chieu{{ $i }}"
                                  class="textarea-field"
                                  rows="2">{{ $lich->chieu ?? '' }}</textarea>
                    </div>
                    <div class="meal-group">
                        <label for="toi{{ $i }}" class="meal-label"><strong>Tối:</strong></label>
                        <textarea name="toi[{{ $i }}]"
                                  id="toi{{ $i }}"
                                  class="textarea-field"
                                  rows="2">{{ $lich->toi ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        @endfor

        <div class="action-row">
            <button type="submit" class="btn-save">Lưu Lịch Trình</button>
            <a href="{{ route('admin.tours.index') }}" class="btn-cancel">Hủy</a>
        </div>
    </form>
</div>

<style>
/* ===== Khung tổng ===== */
.custom-form-container {
    background-color: #f5f7fa;
    padding: 20px;
    border-radius: 8px;
    font-family: "Segoe UI", sans-serif;
}

/* ===== Tiêu đề ===== */
.form-title {
    font-size: 22px;
    font-weight: 600;
    color: #2b9084;
    margin-bottom: 5px;
}

.form-subtitle {
    font-size: 18px;
    color: #555;
    margin-bottom: 20px;
}

/* ===== Form ===== */
.custom-form {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

/* ===== Day Block ===== */
.day-block {
    background: #fdfdfd;
    padding: 15px;
    margin-bottom: 18px;
    border-left: 4px solid #2b9084;
    border-radius: 6px;
}

.day-title {
    margin-bottom: 10px;
    color: #2b9084;
    font-weight: 600;
}

/* ===== Input & Textarea ===== */
.input-group, .meal-section {
    margin-bottom: 15px;
}

.input-group label, .meal-label {
    display: block;
    font-weight: 600;
    margin-bottom: 5px;
    color: #333;
}

.input-field, .textarea-field {
    width: 100%;
    padding: 8px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    font-size: 14px;
    transition: border 0.2s;
}

.input-field:focus, .textarea-field:focus {
    border-color: #2b9084;
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(43, 144, 132, 0.25);
}

/* ===== Meal Section ===== */
.meal-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 15px;
}

.meal-group {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 6px;
    border-left: 3px solid #2b9084;
}

/* ===== Nút hành động ===== */
.action-row {
    display: flex;
    gap: 12px;
    justify-content: flex-start;
    margin-top: 20px;
}

.btn-save {
    background-color: #2b9084;
    color: #fff;
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
}

.btn-save:hover {
    background-color: #22776b;
}

.btn-cancel {
    background-color: #dc3545;
    color: #fff;
    text-decoration: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 600;
}

.btn-cancel:hover {
    background-color: #b02a37;
}

/* ===== Responsive ===== */
@media (max-width: 768px) {
    .form-title { font-size: 18px; }
    .form-subtitle { font-size: 16px; }
    .meal-section { grid-template-columns: 1fr; }
    .btn-save, .btn-cancel { font-size: 13px; padding: 6px 12px; }
}
</style>
@endsection