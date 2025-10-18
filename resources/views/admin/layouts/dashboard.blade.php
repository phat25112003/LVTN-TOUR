<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a2e0f1f6f4.js" crossorigin="anonymous"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <!-- Mobile menu toggle -->
    <button class="mobile-menu-toggle" onclick="toggleSidebar()">
        <i class="fa-solid fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="admin-info">
            @if ($admin)
                <img src="{{ $admin->avatar ? asset('storage/avatars/'.$admin->avatar) : asset('images/avatars/default.jpg') }}"
                    alt="Avatar" class="admin-avatar rounded-circle border shadow-sm">
                <p class="admin-name mt-2">{{ $admin->tenDangNhap }}</p>
            @else
                <img src="{{ asset('images/avatars/default.jpg') }}" class="admin-avatar rounded-circle border shadow-sm">
                <p class="admin-name mt-2 text-muted">Chưa đăng nhập</p>
            @endif
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('admin.profile') }}" class="{{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                    <i class="fa-solid fa-user"></i> Thông tin cá nhân</a>
            </li>
            <li><a href="{{ route('admin.tours.index') }}" class="{{ request()->routeIs('admin.tours.*') ? 'active' : '' }}"><i class="fa-solid fa-map-location-dot"></i> Quản lý Tour</a></li>
            <li><a href="{{ route('admin.nguoidung.index') }}" class="{{ request()->routeIs('admin.nguoidung.index') ? 'active' : '' }}"><i class="fa-solid fa-users"></i>Quản lý người dùng</a></li>
            <li><a href="{{ route('admin.datcho.index') }}" class="{{ request()->routeIs('admin.datcho.index') ? 'active' : '' }}"><i class="fa-solid fa-ticket"></i>Quản lý đặt tour</a></li>
            <li><a href="#"><i class="fa-solid fa-file-invoice-dollar"></i> Hóa đơn</a></li>
            <li><a href="#"><i class="fa-solid fa-gift"></i> Khuyến mãi</a></li>
            <li><a href="#"><i class="fa-solid fa-comments"></i> Tin nhắn</a></li>
            <li><a href="#"><i class="fa-solid fa-chart-line"></i> Thống kê</a></li>
            <li><a href="#"><i class="fa-solid fa-user-shield"></i> Quản trị viên</a></li>
            <form action="{{ route('admin.logout') }}" method="POST" class="logout-form text-center">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger logout-btn">
                    <i class="fa-solid fa-right-from-bracket me-1"></i> Đăng xuất
                </button>
            </form>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content" id="main-content">
        @yield('content')
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('mobile-open');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>