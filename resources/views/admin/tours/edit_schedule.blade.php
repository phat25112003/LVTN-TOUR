<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Lịch Trình</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Sửa Lịch Trình cho Tour: {{ $tour->tieuDe }}</h2>
        <form action="{{ route('admin.tours.updateSchedule', $tour->maTour) }}" method="POST">
            @csrf
            @for ($i = 1; $i <= $thoiLuong; $i++)
                <div class="mb-3">
                    <h4>Ngày {{ $i }}</h4>
                    <div class="mb-3">
                        <label for="huongDi{{ $i }}" class="form-label">Hướng đi</label>
                        <input type="text" name="huongDi[{{ $i }}]" id="huongDi{{ $i }}" class="form-control" value="{{ $lichTrinh->where('ngay', $i)->first()->huongDi ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="noiDung{{ $i }}" class="form-label">Nội dung chuyến đi</label>
                        <textarea name="noiDung[{{ $i }}]" id="noiDung{{ $i }}" class="form-control" rows="3" required>{{ $lichTrinh->where('ngay', $i)->first()->noiDung ?? '' }}</textarea>
                    </div>
                </div>
            @endfor
            <button type="submit" class="btn btn-success">Cập nhật Lịch Trình</button>
            <a href="{{ route('admin.tours.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</body>
</html>