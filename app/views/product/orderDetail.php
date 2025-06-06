<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng #<?php echo $order['id']; ?> - My Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .order-info {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
        }
        .invoice-style {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        @media print {
            .no-print { display: none !important; }
            .invoice-style { box-shadow: none; border: none; }
        }
    </style>
</head>
<body>
    <?php include 'app/views/shares/header.php'; ?>
    
    <div class="container my-5">
        <div class="row no-print">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/Project_4/product/list">Sản phẩm</a></li>
                        <li class="breadcrumb-item"><a href="/Project_4/product/orders">Đơn hàng</a></li>
                        <li class="breadcrumb-item active">Chi tiết đơn hàng #<?php echo $order['id']; ?></li>
                    </ol>
                </nav>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-file-invoice"></i> Chi tiết đơn hàng #<?php echo $order['id']; ?></h2>
                    <div>
                        <button onclick="window.print()" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-print"></i> In đơn hàng
                        </button>
                        <a href="/Project_4/product/orders" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="invoice-style">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h3 class="text-primary">MY STORE</h3>
                    <p class="mb-0">
                        <i class="fas fa-map-marker-alt"></i> 123 Đường ABC, Quận XYZ, TP.HCM<br>
                        <i class="fas fa-phone"></i> 0123-456-789<br>
                        <i class="fas fa-envelope"></i> info@mystore.com
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <h4>HÓA ĐƠN BÁN HÀNG</h4>
                    <p class="mb-0">
                        <strong>Mã đơn hàng:</strong> #<?php echo $order['id']; ?><br>
                        <strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                    </p>
                </div>
            </div>

            <hr>

            <!-- Customer Info -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5><i class="fas fa-user"></i> Thông tin khách hàng</h5>
                    <div class="order-info">
                        <p><strong>Họ tên:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($order['customer_email']); ?></p>
                        <?php if (!empty($order['customer_phone'])): ?>
                            <p><strong>Điện thoại:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
                        <?php endif; ?>
                        <p><strong>Địa chỉ giao hàng:</strong><br><?php echo nl2br(htmlspecialchars($order['customer_address'])); ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5><i class="fas fa-info-circle"></i> Thông tin đơn hàng</h5>
                    <div class="order-info">
                        <p>
                            <strong>Trạng thái:</strong> 
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
                        </p>
                        <p>
                            <strong>Thanh toán:</strong> 
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
                            $paymentMethodText = [
                                'cod' => 'Thanh toán khi nhận hàng',
                                'bank_transfer' => 'Chuyển khoản ngân hàng',
                                'credit_card' => 'Thẻ tín dụng'
                            ];
                            ?>
                            <span class="badge bg-<?php echo $paymentClass[$order['payment_status']]; ?>">
                                <?php echo $paymentText[$order['payment_status']]; ?>
                            </span>
                        </p>
                        <p><strong>Phương thức:</strong> <?php echo $paymentMethodText[$order['payment_method']] ?? $order['payment_method']; ?></p>
                        <?php if (!empty($order['notes'])): ?>
                            <p><strong>Ghi chú:</strong><br><?php echo nl2br(htmlspecialchars($order['notes'])); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Order Details -->
            <h5><i class="fas fa-list"></i> Chi tiết sản phẩm</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>STT</th>
                            <th>Sản phẩm</th>
                            <th>Hình ảnh</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-end">Đơn giá</th>
                            <th class="text-end">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $index = 1;
                        $totalAmount = 0;
                        foreach ($orderDetails as $detail): 
                            $subtotal = $detail['subtotal'];
                            $totalAmount += $subtotal;
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $index++; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($detail['product_name']); ?></strong>
                                </td>
                                <td class="text-center">
                                    <?php if (!empty($detail['image'])): ?>
                                        <img src="<?php echo htmlspecialchars($detail['image']); ?>" 
                                             alt="<?php echo htmlspecialchars($detail['product_name']); ?>" 
                                             class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                    <?php else: ?>
                                        <i class="fas fa-image text-muted"></i>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?php echo $detail['quantity']; ?></td>
                                <td class="text-end"><?php echo number_format($detail['unit_price'], 0, ',', '.'); ?> VND</td>
                                <td class="text-end"><?php echo number_format($subtotal, 0, ',', '.'); ?> VND</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="5" class="text-end"><strong>Tổng cộng:</strong></td>
                            <td class="text-end"><strong class="text-primary fs-5"><?php echo number_format($totalAmount, 0, ',', '.'); ?> VND</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Footer -->
            <div class="row mt-5">
                <div class="col-md-6">
                    <p class="text-muted">
                        <small>
                            <i class="fas fa-info-circle"></i> 
                            Cảm ơn bạn đã mua hàng tại My Store!<br>
                            Mọi thắc mắc xin liên hệ: 0123-456-789
                        </small>
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="text-muted">
                        <small>
                            Ngày in: <?php echo date('d/m/Y H:i'); ?><br>
                            <i class="fas fa-globe"></i> www.mystore.com
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <?php include 'app/views/shares/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
