<?php 
include 'app/views/shares/header.php';

// Lấy một số sản phẩm nổi bật
require_once 'app/config/database.php';
require_once 'app/models/ProductModel.php';
require_once 'app/helpers/SessionHelper.php';

$database = new Database();
$conn = $database->getConnection();
$productModel = new ProductModel($conn);
$featuredProducts = $productModel->getProducts() ?? [];
// Chỉ lấy 6 sản phẩm đầu
$featuredProducts = array_slice($featuredProducts, 0, 6);

// Get flash messages
$flashMessages = SessionHelper::getFlashMessages();
?>

<!-- Flash Messages -->
<?php foreach ($flashMessages as $message): ?>
    <div class="alert alert-<?php echo $message['type'] === 'error' ? 'danger' : $message['type']; ?> alert-dismissible fade show" role="alert">
        <?php echo $message['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endforeach; ?>

<!-- User Welcome Section -->
<?php if (SessionHelper::isLoggedIn()): ?>
<div class="alert alert-info mb-4">
    <div class="d-flex align-items-center">
        <i class="fas fa-user-circle fa-2x me-3"></i>
        <div>
            <h5 class="mb-1">Xin chào, <?php echo htmlspecialchars(SessionHelper::getFullName()); ?>!</h5>
            <p class="mb-0">
                Chào mừng bạn trở lại 
                <?php if (SessionHelper::isAdmin()): ?>
                    <span class="badge bg-danger">Quản trị viên</span>
                <?php endif; ?>
            </p>
        </div>
        <div class="ms-auto">
            <a href="/Project_4/User/profile" class="btn btn-sm btn-outline-primary me-2">
                <i class="fas fa-user"></i> Tài khoản
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Hero Section -->
<div class="hero-section bg-primary text-white py-5 mb-5 rounded-3">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Chào mừng đến với cửa hàng của chúng tôi!</h1>
                <p class="lead mb-4">Khám phá bộ sưu tập sản phẩm công nghệ cao cấp với giá tốt nhất thị trường.</p>
                <div class="d-flex gap-3">
                    <a href="/Project_4/Product" class="btn btn-light btn-lg">
                        <i class="bi bi-grid-3x3-gap me-2"></i>Xem sản phẩm
                    </a>
                    <a href="/Project_4/Product/cart" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-cart me-2"></i>Giỏ hàng
                        <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                            <span class="badge bg-danger ms-1">
                                <?php echo array_sum(array_column($_SESSION['cart'], 'quantity')); ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <i class="bi bi-shop display-1 text-white-50"></i>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="row mb-5">
    <div class="col-md-4 text-center mb-4">
        <div class="feature-card p-4 h-100 border rounded-3">
            <i class="bi bi-truck text-primary display-4 mb-3"></i>
            <h4>Giao hàng nhanh</h4>
            <p class="text-muted">Giao hàng miễn phí cho đơn hàng trên 500k trong nội thành</p>
        </div>
    </div>
    <div class="col-md-4 text-center mb-4">
        <div class="feature-card p-4 h-100 border rounded-3">
            <i class="bi bi-shield-check text-success display-4 mb-3"></i>
            <h4>Chất lượng đảm bảo</h4>
            <p class="text-muted">Tất cả sản phẩm đều chính hãng với bảo hành chính thức</p>
        </div>
    </div>
    <div class="col-md-4 text-center mb-4">
        <div class="feature-card p-4 h-100 border rounded-3">
            <i class="bi bi-headset text-info display-4 mb-3"></i>
            <h4>Hỗ trợ 24/7</h4>
            <p class="text-muted">Đội ngũ chăm sóc khách hàng luôn sẵn sàng hỗ trợ bạn</p>
        </div>
    </div>
</div>

<!-- Featured Products -->
<?php if (!empty($featuredProducts)): ?>
<div class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="display-6 fw-bold">
            <i class="bi bi-star-fill text-warning me-2"></i>
            Sản phẩm nổi bật
        </h2>
        <a href="/Project_4/Product" class="btn btn-outline-primary">
            Xem tất cả <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($featuredProducts as $product): ?>
        <div class="col">
            <div class="card h-100 shadow-sm border-0 product-card">
                <div class="position-relative product-img-wrapper">
                    <?php if (!empty($product->image)): ?>
                        <img src="/Project_4/public/uploads/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                            class="card-img-top" alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php else: ?>
                        <div class="d-flex justify-content-center align-items-center bg-light" style="height: 200px;">
                            <i class="bi bi-image text-secondary" style="font-size: 3rem;"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="position-absolute top-0 end-0 p-2">
                        <span class="badge bg-primary price-badge">
                            <?php echo number_format($product->price, 0, ',', '.'); ?> đ
                        </span>
                    </div>
                </div>
                
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">
                        <a href="/Project_4/Product/show/<?php echo $product->id; ?>" class="text-decoration-none text-dark">
                            <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </h5>
                    
                    <p class="card-text text-muted flex-grow-1">
                        <?php 
                        $description = strip_tags(html_entity_decode($product->description));
                        echo strlen($description) > 100 ? substr($description, 0, 100) . '...' : $description;
                        ?>
                    </p>
                    
                    <div class="mt-auto">
                        <div class="btn-group w-100" role="group">
                            <a href="/Project_4/Product/show/<?php echo $product->id; ?>" class="btn btn-outline-primary">
                                <i class="bi bi-eye me-1"></i> Chi tiết
                            </a>
                            <a href="/Project_4/Product/addToCart/<?php echo $product->id; ?>" class="btn btn-success">
                                <i class="bi bi-cart-plus me-1"></i> Thêm vào giỏ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Quick Stats -->
<div class="row text-center py-5 bg-light rounded-3">
    <div class="col-md-3 mb-3">
        <div class="stat-item">
            <i class="bi bi-box-seam text-primary display-4 mb-2"></i>
            <h3 class="fw-bold">1000+</h3>
            <p class="text-muted">Sản phẩm</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-item">
            <i class="bi bi-people text-success display-4 mb-2"></i>
            <h3 class="fw-bold">5000+</h3>
            <p class="text-muted">Khách hàng hài lòng</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-item">
            <i class="bi bi-award text-warning display-4 mb-2"></i>
            <h3 class="fw-bold">3</h3>
            <p class="text-muted">Năm kinh nghiệm</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-item">
            <i class="bi bi-star-fill text-info display-4 mb-2"></i>
            <h3 class="fw-bold">4.9/5</h3>
            <p class="text-muted">Đánh giá</p>
        </div>
    </div>
</div>

<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.feature-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

.product-img-wrapper {
    height: 200px;
    overflow: hidden;
}

.product-img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.product-card:hover .product-img-wrapper img {
    transform: scale(1.05);
}

.price-badge {
    font-size: 0.9rem;
    font-weight: 600;
    padding: 0.5rem 0.75rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.stat-item {
    padding: 1rem;
}

.stat-item i {
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .hero-section {
        text-align: center;
    }
    
    .display-4 {
        font-size: 2.5rem;
    }
}
</style>

<?php include 'app/views/shares/footer.php'; ?>
