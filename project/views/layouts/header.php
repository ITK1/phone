<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlayTogether Pro Boost - Dịch Vụ Cày Thuê</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #6f42c1; /* Màu tím chủ đạo */
            --secondary-color: #0dcaf0; /* Màu xanh neon */
            --dark-bg: #0f0f1a;
            --card-bg: #1a1a2e;
        }
        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--dark-bg);
            color: #e0e0e0;
        }
        /* Navbar đẹp */
        .navbar {
            background-color: rgba(26, 26, 46, 0.95) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(111, 66, 193, 0.2);
        }
        .navbar-brand {
            font-weight: 800;
            color: var(--secondary-color) !important;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        /* Hero Banner */
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://wallpapers.com/images/featured/play-together-background-4k57alj237f3377k.jpg');
            background-size: cover;
            background-position: center;
            padding: 100px 0;
            border-bottom: 3px solid var(--primary-color);
        }
        /* Card dịch vụ */
        .service-card {
            background-color: var(--card-bg);
            border: 1px solid rgba(111, 66, 193, 0.1);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(111, 66, 193, 0.3);
            border-color: var(--primary-color);
        }
        .service-card img {
            height: 200px;
            object-fit: cover;
            border-bottom: 3px solid var(--primary-color);
        }
        .price-tag {
            color: var(--secondary-color);
            font-size: 1.5rem;
            font-weight: 800;
        }
        .btn-glow {
            background: linear-gradient(45deg, var(--primary-color), #8a2be2);
            border: none;
            color: white;
            font-weight: 700;
            padding: 10px 25px;
            border-radius: 50px;
            box-shadow: 0 0 15px rgba(111, 66, 193, 0.5);
        }
        .btn-glow:hover {
            background: linear-gradient(45deg, #8a2be2, var(--primary-color));
            color: white;
            box-shadow: 0 0 25px rgba(111, 66, 193, 0.8);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="fas fa-gamepad me-2"></i>PT GAMING PRO</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Trang Chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="#services">Dịch Vụ HOT</a></li>
                    <li class="nav-item ms-3"><a class="btn btn-outline-info btn-sm rounded-pill px-4" href="#">Liên Hệ Admin</a></li>
                </ul>
            </div>
        </div>
    </nav>