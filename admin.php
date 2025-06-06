<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Project_4</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .dashboard-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 15px;
            transition: all 0.3s ease;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            background: rgba(255,255,255,0.15);
        }
        .stat-card {
            text-align: center;
            color: white;
            padding: 30px 20px;
        }
        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            margin: 10px 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        .action-card {
            color: white;
            padding: 25px;
            text-align: center;
            height: 100%;
        }
        .action-card h5 {
            margin-bottom: 15px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }
        .action-card p {
            opacity: 0.9;
            margin-bottom: 20px;
        }
        .btn-dashboard {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            margin: 5px;
        }
        .btn-dashboard:hover {
            background: rgba(255,255,255,0.3);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .navbar {
            background: rgba(255,255,255,0.1) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        .navbar-brand, .nav-link {
            color: white !important;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }
        h1, h2 {
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .alert-custom {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/Project_4">
                <i class="bi bi-shop"></i> Project_4 Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/Project_4"><i class="bi bi-house"></i> Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Project_4/Product"><i class="bi bi-box"></i> Sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Project_4/Product/orders"><i class="bi bi-receipt"></i> Đơn hàng</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="text-center mb-5">
            <h1><i class="bi bi-speedometer2"></i> Admin Dashboard</h1>
            <p class="lead text-white-50">Quản lý hệ thống e-commerce Project_4</p>
        </div>

        <?php
        // Get database statistics
        try {
            require_once 'app/config/database.php';
            $database = new Database();
            $conn = $database->getConnection();
            
            // Get statistics
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM products");
            $stmt->execute();
            $productCount = $stmt->fetch(PDO::FETCH_OBJ)->count;
            
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM categories");
            $stmt->execute();
            $categoryCount = $stmt->fetch(PDO::FETCH_OBJ)->count;
            
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM orders");
            $stmt->execute();
            $orderCount = $stmt->fetch(PDO::FETCH_OBJ)->count;
            
            $stmt = $conn->prepare("SELECT SUM(total_amount) as total FROM orders WHERE payment_status = 'paid'");
            $stmt->execute();
            $revenue = $stmt->fetch(PDO::FETCH_OBJ)->total ?? 0;
            
        } catch (Exception $e) {
            $productCount = $categoryCount = $orderCount = 0;
            $revenue = 0;
            echo "<div class='alert alert-custom'><i class='bi bi-exclamation-triangle'></i> Lỗi kết nối database: " . $e->getMessage() . "</div>";
        }
        ?>

        <!-- Statistics Cards -->
        <div class="row mb-5">
            <div class="col-md-3 mb-4">
                <div class="dashboard-card stat-card">
                    <i class="bi bi-box-seam" style="font-size: 3rem; opacity: 0.7;"></i>
                    <div class="stat-number"><?php echo number_format($productCount); ?></div>
                    <div class="stat-label">Sản phẩm</div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="dashboard-card stat-card">
                    <i class="bi bi-tags" style="font-size: 3rem; opacity: 0.7;"></i>
                    <div class="stat-number"><?php echo number_format($categoryCount); ?></div>
                    <div class="stat-label">Danh mục</div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="dashboard-card stat-card">
                    <i class="bi bi-cart-check" style="font-size: 3rem; opacity: 0.7;"></i>
                    <div class="stat-number"><?php echo number_format($orderCount); ?></div>
                    <div class="stat-label">Đơn hàng</div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="dashboard-card stat-card">
                    <i class="bi bi-currency-dollar" style="font-size: 3rem; opacity: 0.7;"></i>
                    <div class="stat-number"><?php echo number_format($revenue / 1000000, 1); ?>M</div>
                    <div class="stat-label">Doanh thu (VNĐ)</div>
                </div>
            </div>
        </div>

        <!-- Management Actions -->
        <div class="row mb-5">
            <div class="col-md-4 mb-4">
                <div class="dashboard-card action-card">
                    <i class="bi bi-box-seam" style="font-size: 3rem; opacity: 0.7;"></i>
                    <h5>Quản lý Sản phẩm</h5>
                    <p>Thêm, sửa, xóa sản phẩm và quản lý kho hàng</p>
                    <a href="/Project_4/Product" class="btn-dashboard">
                        <i class="bi bi-list"></i> Danh sách sản phẩm
                    </a>
                    <a href="/Project_4/Product/add" class="btn-dashboard">
                        <i class="bi bi-plus-circle"></i> Thêm sản phẩm
                    </a>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="dashboard-card action-card">
                    <i class="bi bi-receipt" style="font-size: 3rem; opacity: 0.7;"></i>
                    <h5>Quản lý Đơn hàng</h5>
                    <p>Xem và xử lý các đơn hàng từ khách hàng</p>
                    <a href="/Project_4/Product/orders" class="btn-dashboard">
                        <i class="bi bi-list-check"></i> Danh sách đơn hàng
                    </a>
                    <a href="/Project_4/Product/cart" class="btn-dashboard">
                        <i class="bi bi-cart"></i> Giỏ hàng test
                    </a>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="dashboard-card action-card">
                    <i class="bi bi-gear" style="font-size: 3rem; opacity: 0.7;"></i>
                    <h5>Cấu hình Hệ thống</h5>
                    <p>Backup, restore và quản lý dữ liệu hệ thống</p>
                    <a href="/Project_4/database_backup.php" class="btn-dashboard">
                        <i class="bi bi-download"></i> Backup DB
                    </a>
                    <a href="/Project_4/sample_data.php" class="btn-dashboard">
                        <i class="bi bi-database"></i> Sample Data
                    </a>
                </div>
            </div>
        </div>

        <!-- Testing & Development -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="dashboard-card" style="padding: 30px;">
                    <h2><i class="bi bi-bug"></i> Testing & Development</h2>
                    <p class="text-white-50 mb-4">Các công cụ test và debug hệ thống</p>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h5><i class="bi bi-check-circle"></i> System Tests</h5>
                            <a href="/Project_4/test_complete.php" class="btn-dashboard">
                                <i class="bi bi-clipboard-check"></i> Complete Test
                            </a>
                            <a href="/Project_4/test_crud.php" class="btn-dashboard">
                                <i class="bi bi-arrow-repeat"></i> CRUD Test
                            </a>
                            <a href="/Project_4/test_system.php" class="btn-dashboard">
                                <i class="bi bi-speedometer"></i> System Test
                            </a>
                            <a href="/Project_4/test_db.php" class="btn-dashboard">
                                <i class="bi bi-database-check"></i> DB Test
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h5><i class="bi bi-file-text"></i> Documentation</h5>
                            <a href="/Project_4/README.md" class="btn-dashboard">
                                <i class="bi bi-book"></i> README
                            </a>
                            <a href="/Project_4/PROJECT_STATUS.md" class="btn-dashboard">
                                <i class="bi bi-clipboard-data"></i> Project Status
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Info -->
        <div class="row">
            <div class="col-12">
                <div class="alert alert-custom">
                    <h5><i class="bi bi-info-circle"></i> System Information</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Project:</strong> E-commerce System
                        </div>
                        <div class="col-md-3">
                            <strong>Version:</strong> 1.0.0
                        </div>
                        <div class="col-md-3">
                            <strong>Status:</strong> <span style="color: #28a745;">Production Ready</span>
                        </div>
                        <div class="col-md-3">
                            <strong>Last Updated:</strong> <?php echo date('Y-m-d H:i'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
