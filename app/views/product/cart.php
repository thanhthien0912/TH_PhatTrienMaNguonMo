<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng - My Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .cart-item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
        }
        .quantity-input {
            width: 80px;
        }
        .cart-summary {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <?php include 'app/views/shares/header.php'; ?>
    
    <div class="container my-5">
        <div class="row">
            <div class="col-md-12">
                <h2><i class="fas fa-shopping-cart"></i> Giỏ hàng của bạn</h2>
                
                <?php if (isset($_SESSION['cart_message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['cart_message']; unset($_SESSION['cart_message']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                    <form method="POST" action="/Project_4/product/updateCart">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th>Thành tiền</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total = 0;
                                    foreach ($_SESSION['cart'] as $item): 
                                        $subtotal = $item['price'] * $item['quantity'];
                                        $total += $subtotal;
                                    ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if (!empty($item['image'])): ?>
                                                        <img src="<?php echo htmlspecialchars($item['image']); ?>" 
                                                             alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                                             class="cart-item-image me-3">
                                                    <?php else: ?>
                                                        <div class="cart-item-image me-3 bg-light d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <h6 class="mb-0"><?php echo htmlspecialchars($item['name']); ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <strong><?php echo number_format($item['price'], 0, ',', '.'); ?> VND</strong>
                                            </td>
                                            <td>
                                                <input type="number" 
                                                       name="quantities[<?php echo $item['id']; ?>]" 
                                                       value="<?php echo $item['quantity']; ?>" 
                                                       min="1" 
                                                       class="form-control quantity-input">
                                            </td>
                                            <td>
                                                <strong class="text-primary"><?php echo number_format($subtotal, 0, ',', '.'); ?> VND</strong>
                                            </td>
                                            <td>
                                                <a href="/Project_4/product/removeFromCart/<?php echo $item['id']; ?>" 
                                                   class="btn btn-outline-danger btn-sm"
                                                   onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-sync"></i> Cập nhật giỏ hàng
                                </button>
                                <a href="/Project_4/product/clearCart" 
                                   class="btn btn-outline-danger ms-2"
                                   onclick="return confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')">
                                    <i class="fas fa-trash"></i> Xóa toàn bộ
                                </a>
                            </div>
                            <div class="col-md-6">
                                <div class="cart-summary">
                                    <h4>Tóm tắt đơn hàng</h4>
                                    <hr>
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
                                    <div class="mt-3">
                                        <a href="/Project_4/product/checkout" class="btn btn-success btn-lg w-100">
                                            <i class="fas fa-credit-card"></i> Tiến hành thanh toán
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-cart fa-5x text-muted mb-3"></i>
                        <h4>Giỏ hàng của bạn đang trống!</h4>
                        <p class="text-muted">Hãy thêm một số sản phẩm vào giỏ hàng để tiếp tục.</p>
                        <a href="/Project_4/product/list" class="btn btn-primary btn-lg">
                            <i class="fas fa-shopping-bag"></i> Tiếp tục mua sắm
                        </a>
                    </div>
                <?php endif; ?>
                
                <div class="mt-4">
                    <a href="/Project_4/product/list" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php include 'app/views/shares/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>