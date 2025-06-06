<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng thành công - My Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .success-icon {
            color: #28a745;
            font-size: 4rem;
        }
        .order-details {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <?php include 'app/views/shares/header.php'; ?>
    
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="text-center mb-5">
                    <i class="fas fa-check-circle success-icon"></i>
                    <h2 class="mt-3">Đặt hàng thành công!</h2>
                    <p class="text-muted">Cảm ơn bạn đã đặt hàng. Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất.</p>
                </div>

                <?php if (isset($_SESSION['last_order'])): ?>
                    <div class="order-details">
                        <h4><i class="fas fa-file-invoice"></i> Thông tin đơn hàng</h4>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Mã đơn hàng:</strong> #<?php echo $_SESSION['last_order']['id']; ?></p>
                                <p><strong>Khách hàng:</strong> <?php echo htmlspecialchars($_SESSION['last_order']['customer_name']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Tổng tiền:</strong> <span class="text-primary fs-5"><?php echo number_format($_SESSION['last_order']['total_amount'], 0, ',', '.'); ?> VND</span></p>
                                <p><strong>Trạng thái:</strong> <span class="badge bg-warning">Chờ xác nhận</span></p>
                            </div>
                        </div>
                        
                        <h5 class="mt-4">Chi tiết sản phẩm:</h5>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Số lượng</th>
                                        <th>Đơn giá</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($_SESSION['last_order']['items'] as $item): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                                            <td><?php echo $item['quantity']; ?></td>
                                            <td><?php echo number_format($item['price'], 0, ',', '.'); ?> VND</td>
                                            <td><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> VND</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="alert alert-info mt-4">
                        <h5><i class="fas fa-info-circle"></i> Thông tin quan trọng:</h5>
                        <ul class="mb-0">
                            <li>Chúng tôi sẽ gọi điện xác nhận đơn hàng trong vòng 24 giờ</li>
                            <li>Thời gian giao hàng: 2-3 ngày làm việc</li>
                            <li>Bạn có thể theo dõi đơn hàng qua email hoặc số điện thoại đã đăng ký</li>
                        </ul>
                    </div>

                    <?php unset($_SESSION['last_order']); ?>
                <?php endif; ?>

                <div class="text-center mt-4">
                    <a href="/Project_4/product/list" class="btn btn-primary btn-lg me-3">
                        <i class="fas fa-shopping-bag"></i> Tiếp tục mua sắm
                    </a>
                    <a href="/Project_4/product/orders" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-list"></i> Xem đơn hàng
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php include 'app/views/shares/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>