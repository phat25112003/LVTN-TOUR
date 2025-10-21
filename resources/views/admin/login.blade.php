<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập Quản trị viên | TRAVELOKA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)),
                        url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1600&q=80')
                        no-repeat center center/cover;
            background-color: gray;
            height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            color: black;
        } 

        .login-box {
            background-color: wheat;
            padding: 40px;
            border-radius: 20px;
            width: 400px;
            text-align: center;
        }

        .login-box h2 {
            font-weight: 700;
            margin-bottom: 25px;
            color: #00bcd4;
        }

        .form-label {
            font-weight: 500;
            color: black;
            text-align: left;
            display: block;
        }

        .form-control {
            background-color: white;
            border: none;
            color: #fff;
            border-radius: 10px;
            padding: 10px;
        }

        .form-control:focus {
            background-color: white;
            box-shadow: 0 0 0 3px rgba(0,188,212,0.5);
        }

        .btn-primary {
            background-color: #00bcd4;
            border: none;
            border-radius: 25px;
            width: 100%;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-primary:hover {
            background-color: #0097a7;
            transform: translateY(-2px);
        }

        .alert {
            background-color: rgba(255, 77, 77, 0.9);
            border: none;
            border-radius: 10px;
            color: #fff;
            margin-bottom: 15px;
        }

        .brand {
            font-size: 2rem;
            font-weight: bold;
            color: #00bcd4;
            margin-bottom: 15px;
        }

        footer {
            position: absolute;
            bottom: 15px;
            text-align: center;
            width: 100%;
            color: #ccc;
            font-size: 0.9rem;
        }
    </style> 
</head>
<body>
    <div class="login-box">
        <div class="brand"></div>
        <h2>Đăng nhập hệ thống</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <div class="mb-3 text-start">
                <label for="tenDangNhap" class="form-label">Tên đăng nhập</label>
                <input type="text" class="form-control" id="tenDangNhap" name="tenDangNhap" placeholder="Nhập tên đăng nhập..." required>
            </div>
            <div class="mb-3 text-start">
                <label for="matKhau" class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" id="matKhau" name="matKhau" placeholder="Nhập mật khẩu..." required>
            </div>
            <button type="submit" class="btn btn-primary">Đăng nhập</button>
        </form>
    </div>

    <footer>
    </footer>
</body>
</html>
