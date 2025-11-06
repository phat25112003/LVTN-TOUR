@extends('admin.layouts.dashboard')

@section('content')
<div class="schedule-wrapper">
    <h2 class="schedule-title">Thêm Lịch Trình cho Tour: {{ $tour->tieuDe }}</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.tours.storeSchedule', $tour->maTour) }}" method="POST" class="schedule-form">
        @csrf

        @for ($i = 1; $i <= $soNgay; $i++)
            <div class="day-block">
                <h3>Ngày {{ $i }}</h3>
                <div class="form-group">
                    <label for="huongDi{{ $i }}">Hướng đi</label>
                    <input type="text" name="huongDi[{{ $i }}]" id="huongDi{{ $i }}" class="form-control" required>
                </div>
                <div class="meal-section">
                    <div class="meal-group">
                        <label class="meal-label"><strong>Sáng:</strong></label>
                        <textarea name="sang[{{ $i }}]" id="sang{{ $i }}" rows="2" class="form-control"></textarea>
                    </div>
                    <div class="meal-group">
                        <label class="meal-label"><strong>Trưa:</strong></label>
                        <textarea name="trua[{{ $i }}]" id="trua{{ $i }}" rows="2" class="form-control"></textarea>
                    </div>
                    <div class="meal-group">
                        <label class="meal-label"><strong>Chiều:</strong></label>
                        <textarea name="chieu[{{ $i }}]" id="chieu{{ $i }}" rows="2" class="form-control"></textarea>
                    </div>
                    <div class="meal-group">
                        <label class="meal-label"><strong>Tối:</strong></label>
                        <textarea name="toi[{{ $i }}]" id="toi{{ $i }}" rows="2" class="form-control"></textarea>
                    </div>
                </div>
            </div>
        @endfor

        <div class="form-actions">
            <button type="submit" class="btn-save">Lưu Lịch Trình</button>
            <a href="{{ route('admin.tours.index') }}" class="btn-cancel">Hủy</a>
        </div>
    </form>
</div>

<style>
/* ===== Khung tổng ===== */
.schedule-wrapper {
    background-color: #f5f7fa;
    padding: 20px;
    border-radius: 8px;
    font-family: "Segoe UI", sans-serif;
}

/* ===== Tiêu đề ===== */
.schedule-title {
    font-size: 22px;
    font-weight: 600;
    color: #2b9084;
    margin-bottom: 20px;
}

/* ===== Thông báo ===== */
.alert {
    padding: 10px 14px;
    border-radius: 5px;
    margin-bottom: 15px;
}
.alert-success { background-color: #d4edda; color: #155724; }
.alert-danger { background-color: #f8d7da; color: #721c24; }

/* ===== Form ===== */
.schedule-form {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.day-block {
    background: #fdfdfd;
    padding: 15px;
    margin-bottom: 18px;
    border-left: 4px solid #2b9084;
    border-radius: 6px;
}

.day-block h3 {
    margin-bottom: 10px;
    color: #2b9084;
}

/* ===== Input & Textarea ===== */
.form-group {
    margin-bottom: 10px;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 4px;
    color: #333;
}

.form-control {
    width: 100%;
    padding: 8px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    font-size: 14px;
    transition: border 0.2s;
}

.form-control:focus {
    border-color: #2b9084;
    outline: none;
}

/* ===== Meal Section ===== */
.meal-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.meal-group {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 6px;
    border-left: 3px solid #2b9084;
}

.meal-label {
    font-weight: 600;
    color: #2b9084;
    margin-bottom: 5px;
    display: block;
}

/* ===== Nút hành động ===== */
.form-actions {
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
    .schedule-title { font-size: 18px; }
    .meal-section { grid-template-columns: 1fr; }
    .btn-save, .btn-cancel { font-size: 13px; padding: 6px 12px; }
}
</style>
@endsection