<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán - My Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .order-summary {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
        }
        .form-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include 'app/views/shares/header.php'; ?>
    
    <div class="container my-5">
        <div class="row">
            <div class="col-md-12">
                <h2><i class="fas fa-credit-card"></i> Thanh toán</h2>
                
                <?php if (isset($_SESSION['checkout_error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['checkout_error']; unset($_SESSION['checkout_error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <form method="POST" action="/Project_4/product/checkout">
            <div class="row">
                <!-- Thông tin giao hàng -->
                <div class="col-md-8">
                    <div class="form-section">
                        <h4><i class="fas fa-user"></i> Thông tin người nhận</h4>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_name" class="form-label">Họ và tên *</label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name" 
                                           value="<?php echo isset($_POST['customer_name']) ? htmlspecialchars($_POST['customer_name']) : ''; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_phone" class="form-label">Số điện thoại</label>
                                    <input type="tel" class="form-control" id="customer_phone" name="customer_phone" 
                                           value="<?php echo isset($_POST['customer_phone']) ? htmlspecialchars($_POST['customer_phone']) : ''; ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="customer_email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="customer_email" name="customer_email" 
                                   value="<?php echo isset($_POST['customer_email']) ? htmlspecialchars($_POST['customer_email']) : ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="customer_address" class="form-label">Địa chỉ giao hàng *</label>
                            <textarea class="form-control" id="customer_address" name="customer_address" rows="3" required><?php echo isset($_POST['customer_address']) ? htmlspecialchars($_POST['customer_address']) : ''; ?></textarea>
                        </div>
                    </div>

                    <div class="form-section">
                        <h4><i class="fas fa-credit-card"></i> Phương thức thanh toán</h4>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                                    <label class="form-check-label" for="cod">
                                        <i class="fas fa-truck"></i> Thanh toán khi nhận hàng
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer">
                                    <label class="form-check-label" for="bank_transfer">
                                        <i class="fas fa-university"></i> Chuyển khoản ngân hàng
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="credit_card" value="credit_card">
                                    <label class="form-check-label" for="credit_card">
                                        <i class="fas fa-credit-card"></i> Thẻ tín dụng
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h4><i class="fas fa-sticky-note"></i> Ghi chú</h4>
                        <hr>
                        <div class="mb-3">
                            <textarea class="form-control" name="notes" rows="3" placeholder="Ghi chú cho đơn hàng (tùy chọn)..."><?php echo isset($_POST['notes']) ? htmlspecialchars($_POST['notes']) : ''; ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Tóm tắt đơn hàng -->
                <div class="col-md-4">
                    <div class="order-summary sticky-top" style="top: 20px;">
                        <h4><i class="fas fa-file-invoice"></i> Đơn hàng của bạn</h4>
                        <hr>
                        
                        <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                            <?php
                            $total = 0;
                            foreach ($_SESSION['cart'] as $item): 
                                $subtotal = $item['price'] * $item['quantity'];
                                $total += $subtotal;
                            ?>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <small><?php echo htmlspecialchars($item['name']); ?></small><br>
                                        <small class="text-muted">Số lượng: <?php echo $item['quantity']; ?></small>
                                    </div>
                                    <div class="text-end">
                                        <small><?php echo number_format($subtotal, 0, ',', '.'); ?> VND</small>
                                    </div>
                                </div>
                                <hr class="my-2">
                            <?php endforeach; ?>
                            
                            <div class="d-flex justify-content-between">
                                <span>Tạm tính:</span>
                                <span><?php echo number_format($total, 0, ',', '.'); ?> VND</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Phí vận chuyển:</span>
                                <span class="text-success">Miễn phí</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Tổng cộng:</strong>
                                <strong class="text-primary fs-5"><?php echo number_format($total, 0, ',', '.'); ?> VND</strong>
                            </div>
                            
                            <div class="mt-4">
                                <button type="submit" class="btn btn-success btn-lg w-100">
                                    <i class="fas fa-check"></i> Đặt hàng
                                </button>
                                <a href="/Project_4/product/cart" class="btn btn-outline-secondary w-100 mt-2">
                                    <i class="fas fa-arrow-left"></i> Quay lại giỏ hàng
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="text-center">
                                <p>Giỏ hàng trống!</p>
                                <a href="/Project_4/product/list" class="btn btn-primary">Tiếp tục mua sắm</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <?php include 'app/views/shares/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>