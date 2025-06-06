<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách đơn hàng - My Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'app/views/shares/header.php'; ?>
    
    <div class="container my-5">
        <div class="row">
            <div class="col-md-12">
                <h2><i class="fas fa-list"></i> Danh sách đơn hàng</h2>
                
                <?php if (!empty($orders)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Mã đơn hàng</th>
                                    <th>Khách hàng</th>
                                    <th>Email</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Thanh toán</th>
                                    <th>Ngày đặt</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><strong>#<?php echo $order['id']; ?></strong></td>
                                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                        <td><?php echo htmlspecialchars($order['customer_email']); ?></td>
                                        <td><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> VND</td>
                                        <td>
                                            <?php
                                            $statusClass = [
                                                'pending' => 'warning',
                                                'confirmed' => 'info',
                                                'processing' => 'primary',
                                                'shipped' => 'secondary',
                                                'delivered' => 'success',
                                                'cancelled' => 'danger'
                                            ];
                                            $statusText = [
                                                'pending' => 'Chờ xác nhận',
                                                'confirmed' => 'Đã xác nhận',
                                                'processing' => 'Đang xử lý',
                                                'shipped' => 'Đã gửi hàng',
                                                'delivered' => 'Đã giao hàng',
                                                'cancelled' => 'Đã hủy'
                                            ];
                                            ?>
                                            <span class="badge bg-<?php echo $statusClass[$order['status']]; ?>">
                                                <?php echo $statusText[$order['status']]; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                            $paymentClass = [
                                                'unpaid' => 'warning',
                                                'paid' => 'success',
                                                'refunded' => 'info'
                                            ];
                                            $paymentText = [
                                                'unpaid' => 'Chưa thanh toán',
                                                'paid' => 'Đã thanh toán',
                                                'refunded' => 'Đã hoàn tiền'
                                            ];
                                            ?>
                                            <span class="badge bg-<?php echo $paymentClass[$order['payment_status']]; ?>">
                                                <?php echo $paymentText[$order['payment_status']]; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                        <td>
                                            <a href="/Project_4/product/orderDetail/<?php echo $order['id']; ?>" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i> Xem
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-file-invoice fa-5x text-muted mb-3"></i>
                        <h4>Chưa có đơn hàng nào!</h4>
                        <p class="text-muted">Danh sách đơn hàng sẽ hiển thị tại đây khi có khách hàng đặt hàng.</p>
                        <a href="/Project_4/product/list" class="btn btn-primary">
                            <i class="fas fa-shopping-bag"></i> Xem sản phẩm
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include 'app/views/shares/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
