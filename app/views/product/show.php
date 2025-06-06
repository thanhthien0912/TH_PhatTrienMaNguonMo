<?php include 'app/views/shares/header.php'; ?>

<div class="content-wrapper">

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/Project_4" class="text-decoration-none">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="/Project_4/Product" class="text-decoration-none">Sản phẩm</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chi tiết sản phẩm</li>
    </ol>
</nav>

<?php if (is_object($product)): ?>
<div class="card shadow-sm mb-4 border-0 rounded-3 overflow-hidden">
    <div class="card-body p-0">
        <div class="row g-0">
            <!-- Product Image -->
            <div class="col-md-5">
                <div class="product-gallery p-4 h-100 d-flex align-items-center justify-content-center bg-light">
                    <?php if (!empty($product->image)): ?>
                        <img src="/Project_4/public/uploads/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                             alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" 
                             class="img-fluid rounded-3 product-image">
                    <?php else: ?>
                        <div class="text-center">
                            <i class="bi bi-image text-secondary mb-3" style="font-size: 5rem;"></i>
                            <p class="text-muted">Không có hình ảnh</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="col-md-7">
                <div class="p-4 p-md-5">                    <div class="d-flex justify-content-between align-items-start">
                        <h1 class="display-6 fw-bold mb-3"><?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?></h1>
                        <?php if (SessionHelper::isLoggedIn() && SessionHelper::isAdmin()): ?>
                        <div class="d-flex">
                            <a href="/Project_4/Product/edit/<?php echo $product->id; ?>" class="btn btn-primary me-2">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="/Project_4/Product/delete/<?php echo $product->id; ?>" 
                               onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');" 
                               class="btn btn-danger">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-4">
                        <span class="fs-2 fw-bold text-primary"><?php echo number_format($product->price, 0, ',', '.'); ?> đ</span>
                    </div>
                    
                    <?php 
                    // Get category name
                    $categoryName = '';
                    if (!empty($product->category_id)) {
                        $categoryModel = new CategoryModel((new Database())->getConnection());
                        $category = $categoryModel->getCategoryById($product->category_id);
                        if ($category) {
                            $categoryName = $category->name;
                        }
                    }
                    if (!empty($categoryName)): ?>
                    <div class="mb-4">
                        <h6 class="fw-bold text-muted mb-2">Danh mục:</h6>
                        <span class="badge bg-secondary fs-6">
                            <i class="bi bi-tag-fill me-1"></i>
                            <?php echo htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold text-muted mb-2">Mô tả sản phẩm:</h6>
                        <div class="product-description p-3 border rounded-3 bg-light">
                            <?php echo html_entity_decode($product->description); ?>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold text-muted mb-2">Tình trạng:</h6>
                        <?php if ($product->stock_quantity > 0): ?>
                            <span class="badge bg-success fs-6">
                                <i class="bi bi-check-circle-fill me-1"></i>
                                Còn hàng (<?php echo $product->stock_quantity; ?> sản phẩm)
                            </span>
                        <?php else: ?>
                            <span class="badge bg-danger fs-6">
                                <i class="bi bi-x-circle-fill me-1"></i>
                                Hết hàng
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (isset($_SESSION['cart_error'])): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <?php echo $_SESSION['cart_error']; unset($_SESSION['cart_error']); ?>
                    </div>
                    <?php endif; ?>
                    
                      <div class="mt-4 pt-3 border-top">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <form action="/Project_4/Product/addToCart" method="POST" class="d-flex align-items-center gap-3">
                                    <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                                    <div class="quantity-input">
                                        <label for="quantity" class="form-label mb-0 me-2">Số lượng:</label>
                                        <div class="input-group" style="width: 120px;">
                                            <button type="button" class="btn btn-outline-secondary" onclick="decreaseQuantity()" <?php echo $product->stock_quantity <= 0 ? 'disabled' : ''; ?>>-</button>
                                            <input type="number" id="quantity" name="quantity" class="form-control text-center" 
                                                   value="1" min="1" max="<?php echo $product->stock_quantity; ?>" 
                                                   <?php echo $product->stock_quantity <= 0 ? 'disabled' : ''; ?>>
                                            <button type="button" class="btn btn-outline-secondary" onclick="increaseQuantity()" <?php echo $product->stock_quantity <= 0 ? 'disabled' : ''; ?>>+</button>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-lg" <?php echo $product->stock_quantity <= 0 ? 'disabled' : ''; ?>>
                                        <i class="bi bi-cart-plus me-2"></i>Thêm vào giỏ hàng
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                <a href="/Project_4/Product" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i> Quay lại danh sách
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
    <div class="alert alert-danger shadow-sm">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-1"></i>
            <div>
                <h4 class="alert-heading">Không tìm thấy!</h4>
                <p class="mb-0">Không tìm thấy thông tin sản phẩm bạn đang tìm kiếm.</p>
            </div>
        </div>
        <hr>
        <div class="text-end">
            <a href="/Project_4/Product" class="btn btn-outline-danger">
                <i class="bi bi-arrow-left me-2"></i> Quay lại danh sách sản phẩm
            </a>
        </div>
    </div>
<?php endif; ?>

<style>
.product-image {
    max-height: 400px;
    width: auto;
    object-fit: contain;
}

.product-description {
    max-width: 100%;
    padding: 1.5rem;
    background-color: #f9f9f9;
    border-radius: 10px;
    margin-bottom: 1.5rem;
}

.product-description img {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
    margin: 10px 0;
}

.product-gallery {
    background-color: #f8f9fa;
    border-radius: 10px;
    position: relative;
    min-height: 400px;
    padding: 2rem;
}

@media (max-width: 767.98px) {
    .product-gallery {
        min-height: 300px;
        padding: 1.5rem;
    }
}

.quantity-input .input-group {
    max-width: 120px;
}

.quantity-input .btn {
    padding: 0.375rem 0.75rem;
}

.breadcrumb {
    padding: 0.75rem 1rem;
    background-color: #f8f9fa;
    border-radius: 0.5rem;
    margin-bottom: 2rem;
}

.card {
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 2rem;
}

.badge {
    margin-right: 5px;
    margin-bottom: 5px;
}
</style>

<script>
function increaseQuantity() {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    const maxValue = parseInt(input.getAttribute('max'));
    if (currentValue < maxValue) {
        input.value = currentValue + 1;
    }
}

function decreaseQuantity() {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    if (currentValue > 1) {
        input.value = currentValue - 1;
    }
}

// Validate quantity input
document.getElementById('quantity').addEventListener('input', function() {
    let value = parseInt(this.value);
    const maxValue = parseInt(this.getAttribute('max'));
    if (isNaN(value) || value < 1) {
        this.value = 1;
    } else if (value > maxValue) {
        this.value = maxValue;
    }
});
</script>

</div>

<?php include 'app/views/shares/footer.php'; ?>