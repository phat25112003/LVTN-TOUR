<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang Chính - TRAVELOKA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Toàn trang */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                        url('https://images.unsplash.com/photo-1526778548025-fa2f459cd5c1?auto=format&fit=crop&w=1600&q=80')
                        no-repeat center center/cover;
            height: 100vh;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Khối nội dung chính */
        .welcome-box {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px 50px;
            text-align: center;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
            max-width: 700px;
        }

        h1 {
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: #00bcd4;
        }

        .subtitle {
            font-size: 1.2rem;
            font-style: italic;
            color: #d0f0ff;
            margin-bottom: 30px;
        }

        .rules {
            background: rgba(0, 0, 0, 0.3);
            border-left: 5px solid #00bcd4;
            padding: 15px;
            border-radius: 10px;
            text-align: left;
            margin-top: 20px;
        }

        .rules h4 {
            color: #ffcc70;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .rules ul {
            padding-left: 20px;
        }

        .rules li {
            margin-bottom: 5px;
        }

        .btn-primary {
            background-color: #00bcd4;
            border: none;
            border-radius: 30px;
            padding: 12px 30px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0288a7;
            transform: translateY(-3px);
        }

        footer {
            position: absolute;
            bottom: 15px;
            font-size: 0.9rem;
            color: #d0f0ff;
            width: 100%;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="welcome-box">
        <h1>Chào mừng đến với <strong>TRAVELOKA</strong></h1>
        <p class="subtitle">“Khám phá thế giới – Chạm vào đam mê du lịch của bạn.”</p>

        <p>
            TRAVELOKA là hệ thống đặt tour du lịch thông minh giúp bạn <strong>dễ dàng tìm kiếm</strong>,
            <strong>đặt chỗ nhanh chóng</strong> và <strong>trải nghiệm trọn vẹn</strong> những hành trình tuyệt vời nhất.
        </p>

        <div class="rules">
            <h4>Quy tắc và Giá trị cốt lõi</h4>
            <ul>
                <li><strong>Trung thực:</strong> Mọi thông tin tour minh bạch, rõ ràng.</li>
                <li><strong>Tận tâm:</strong> Hỗ trợ khách hàng 24/7 trước – trong – sau chuyến đi.</li>
                <li><strong>Bền vững:</strong> Bảo vệ môi trường và phát triển du lịch xanh.</li>
                <li><strong>Đổi mới:</strong> Không ngừng cải tiến để mang lại trải nghiệm tốt hơn.</li>
            </ul>
        </div>

        <a href="{{ route('admin.login') }}" class="btn btn-primary mt-4">
            Đăng nhập Admin
        </a>
    </div>

    <footer>
        © {{ date('Y') }} Traveloka Việt Nam – Hành trình của bạn, niềm tự hào của chúng tôi.
    </footer>
</body>
</html>
