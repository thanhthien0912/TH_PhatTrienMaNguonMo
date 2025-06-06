<?php
// Include SessionHelper for authentication
require_once 'app/helpers/SessionHelper.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            padding-bottom: 50px;
            padding-top: 10px;
        }
        
        /* Giới hạn chiều rộng tối đa của nội dung */
        .content-wrapper {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px;
        }
        
        /* Container fluid với padding */
        .container-fluid {
            padding-left: 30px;
            padding-right: 30px;
        }
        
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
            margin-bottom: 30px !important;
        }
        .card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .btn {
            border-radius: 5px;
            font-weight: 500;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            box-shadow: 0 4px 6px rgba(40, 167, 69, 0.2);
        }
        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
            transform: translateY(-1px);
        }
        .btn-primary {
            box-shadow: 0 4px 6px rgba(0, 123, 255, 0.2);
        }
        .btn-primary:hover {
            transform: translateY(-1px);
        }
        .btn-danger {
            box-shadow: 0 4px 6px rgba(220, 53, 69, 0.2);
        }
        .btn-danger:hover {
            transform: translateY(-1px);
        }
        .btn-info {
            box-shadow: 0 4px 6px rgba(23, 162, 184, 0.2);
        }
        .btn-info:hover {
            transform: translateY(-1px);
        }
        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
            color: #343a40;
            margin-bottom: 20px;
        }
        .card-title {
            font-weight: 600;
            color: #212529;
        }
        .card-text {
            color: #6c757d;
        }
        .badge {
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 30px;
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
        }
        .table thead {
            background-color: #f8f9fa;
        }
        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 25px;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }
        .form-select:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }
        .nav-link {
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.4rem 0.8rem !important;
        }
        .nav-link:hover {
            color: #fff !important;
            background-color: rgba(255,255,255,0.1);
            border-radius: 5px;
        }
        .active-nav {
            background-color: rgba(255,255,255,0.2);
            border-radius: 5px;
        }
        .container {
            max-width: 1200px;
            padding: 0 30px;
            margin: 0 auto;
            width: 100%;
        }
        
        @media (min-width: 1400px) {
            .container {
                max-width: 1140px;
            }
        }
        
        .navbar-brand {
            font-size: 1.1rem;
        }
        .dropdown-item {
            font-size: 0.9rem;
            padding: 0.4rem 1rem;
        }
        .mx-1 {
            margin-left: 0.15rem !important;
            margin-right: 0.15rem !important;
        }
        
        /* Thêm padding cho các thành phần chính */
        .card-body {
            padding: 1.5rem;
        }
        .table {
            margin-bottom: 0;
        }
        .table td, .table th {
            padding: 0.75rem 1.25rem;
            vertical-align: middle;
        }
        .mb-4 {
            margin-bottom: 2rem !important;
        }
        .mt-4 {
            margin-top: 2rem !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/Project_4">
                <i class="bi bi-shop me-2 fs-3"></i>
                <span class="fw-bold">Quản lý sản phẩm</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item mx-1">
                        <a class="nav-link px-3 py-2 <?php echo (strpos($_SERVER['REQUEST_URI'], '/Product') !== false && strpos($_SERVER['REQUEST_URI'], '/Product/add') === false) ? 'active-nav' : ''; ?>" href="/Project_4/Product/">
                            <i class="bi bi-grid me-1"></i> Sản phẩm
                        </a>
                    </li>
                    
                    <?php if (SessionHelper::isLoggedIn() && SessionHelper::isAdmin()): ?>
                    <li class="nav-item mx-1">
                        <a class="nav-link px-3 py-2 <?php echo (strpos($_SERVER['REQUEST_URI'], '/Product/add') !== false) ? 'active-nav' : ''; ?>" href="/Project_4/Product/add">
                            <i class="bi bi-plus-circle me-1"></i> Thêm SP
                        </a>
                    </li>
                    <li class="nav-item mx-1">
                        <a class="nav-link px-3 py-2 <?php echo (strpos($_SERVER['REQUEST_URI'], '/Category') !== false) ? 'active-nav' : ''; ?>" href="/Project_4/Category/">
                            <i class="bi bi-tag me-1"></i> Danh mục
                        </a>
                    </li>
                    <li class="nav-item mx-1">
                        <a class="nav-link px-3 py-2 <?php echo (strpos($_SERVER['REQUEST_URI'], '/User/list') !== false) ? 'active-nav' : ''; ?>" href="/Project_4/User/list">
                            <i class="bi bi-people me-1"></i> Người dùng
                        </a>
                    </li>
                    <li class="nav-item mx-1">
                        <a class="nav-link px-3 py-2 <?php echo (strpos($_SERVER['REQUEST_URI'], '/Product/orders') !== false) ? 'active-nav' : ''; ?>" href="/Project_4/Product/orders">
                            <i class="bi bi-receipt me-1"></i> Đơn hàng
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <li class="nav-item mx-1">
                        <a class="nav-link px-3 py-2 position-relative <?php echo (strpos($_SERVER['REQUEST_URI'], '/Product/cart') !== false) ? 'active-nav' : ''; ?>" href="/Project_4/Product/cart">
                            <i class="bi bi-cart me-1"></i> Giỏ hàng
                            <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?php echo array_sum(array_column($_SESSION['cart'], 'quantity')); ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>
                
                <!-- User Authentication Menu -->
                <ul class="navbar-nav ms-auto">
                    <?php if (SessionHelper::isLoggedIn()): ?>
                        <!-- Logged in user menu -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle px-3 py-2 d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <?php 
                                $avatar = SessionHelper::getAvatar();
                                if (!empty($avatar) && file_exists('public/uploads/avatars/' . $avatar)): 
                                ?>
                                    <img src="/Project_4/public/uploads/avatars/<?php echo htmlspecialchars($avatar); ?>" 
                                         alt="Avatar" class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">
                                <?php else: ?>
                                    <i class="fas fa-user-circle me-2"></i>
                                <?php endif; ?>
                                <?php echo htmlspecialchars(SessionHelper::getFullName()); ?>
                                <?php if (SessionHelper::isAdmin()): ?>
                                    <span class="badge bg-danger ms-1">Admin</span>
                                <?php endif; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="/Project_4/User/profile">
                                        <i class="fas fa-user me-2"></i> Thông tin tài khoản
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="/Project_4/User/logout">
                                        <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- Not logged in menu -->
                        <li class="nav-item mx-1">
                            <a class="nav-link px-3 py-2 <?php echo (strpos($_SERVER['REQUEST_URI'], '/User/showLogin') !== false) ? 'active-nav' : ''; ?>" href="/Project_4/User/showLogin">
                                <i class="fas fa-sign-in-alt me-1"></i> Đăng nhập
                            </a>
                        </li>
                        <li class="nav-item mx-1">
                            <a class="nav-link px-3 py-2 <?php echo (strpos($_SERVER['REQUEST_URI'], '/User/showRegister') !== false) ? 'active-nav' : ''; ?>" href="/Project_4/User/showRegister">
                                <i class="fas fa-user-plus me-1"></i> Đăng ký
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container content-wrapper mt-4"></div>