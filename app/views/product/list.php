<?php 
require_once 'app/helpers/SessionHelper.php';
include 'app/views/shares/header.php'; 
?>

<div class="content-wrapper">

<style>
.product-img-wrapper {
    height: 200px;
    overflow: hidden;
    border-radius: 10px 10px 0 0;
}

.product-img-container {
    display: block;
    height: 100%;
}

.product-img-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-img-container:hover img {
    transform: scale(1.05);
}

.product-description-preview {
    max-height: 60px;
    min-height: 60px;
    overflow: hidden;
    position: relative;
    font-size: 0.9rem;
    color: #6c757d;
    line-height: 1.4;
    margin-bottom: 1.5rem;
    padding: 0.5rem;
    background-color: #f9f9f9;
    border-radius: 5px;
}

.product-description-preview:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 24px;
    background: linear-gradient(rgba(255,255,255,0), rgba(255,255,255,1));
}

.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    margin-bottom: 30px;
    border-radius: 10px;
    overflow: hidden;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

.card-body {
    padding: 1.5rem;
}

.price-badge {
    font-size: 1rem;
    padding: 0.5rem 1rem;
    margin: 0.5rem;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.card .btn-group {
    width: 100%;
    margin-top: 1rem;
}

.card .btn-group .btn {
    flex: 1;
    border-radius: 5px;
    margin: 0 2px;
}

.img-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.1);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-img-container:hover .img-overlay {
    opacity: 1;
}

.no-image {
    background-color: #f8f9fa;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Table View Styles */
.table-responsive {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.table {
    margin-bottom: 0;
}

.table thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    padding: 12px 15px;
    font-weight: 600;
}

.table tbody td {
    padding: 12px 15px;
    vertical-align: middle;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

/* Filter Card */
.filter-card {
    margin-bottom: 2rem;
    border-radius: 10px;
    overflow: hidden;
}

/* Pagination */
.pagination {
    margin-top: 2rem;
}

.pagination .page-link {
    padding: 0.5rem 0.75rem;
    margin: 0 3px;
    border-radius: 5px;
}

.pagination .page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
}

/* Responsive Adjustments */
@media (max-width: 767.98px) {
    .product-img-wrapper {
        height: 180px;
    }
    
    .card-body {
        padding: 1.25rem;
    }
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="display-5 fw-bold"><i class="bi bi-grid-3x3-gap me-2"></i>Danh sách sản phẩm</h1>
    </div>
    <div class="d-flex align-items-center">
        <div class="btn-group me-3" role="group" aria-label="Chế độ xem">
            <button type="button" class="btn btn-outline-primary active" id="cardViewBtn">
                <i class="bi bi-grid-3x3-gap me-1"></i> Thẻ
            </button>
            <button type="button" class="btn btn-outline-primary" id="tableViewBtn">
                <i class="bi bi-table me-1"></i> Bảng
            </button>
        </div>
        <?php if (SessionHelper::isLoggedIn() && SessionHelper::isAdmin()): ?>
        <a href="/Project_4/Product/add" class="btn btn-success btn-lg">
            <i class="bi bi-plus-circle me-2"></i>Thêm sản phẩm mới
        </a>
        <?php endif; ?>
    </div>
</div>

<!-- Form tìm kiếm và lọc -->
<div class="card shadow-sm mb-4 filter-card">
    <div class="card-body">
        <form method="GET" action="/Project_4/Product/list" id="filterForm">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="search" placeholder="Tìm kiếm sản phẩm..." 
                               value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <select class="form-select" name="category_id">
                        <option value="">Tất cả danh mục</option>
                        <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category->id; ?>" 
                                <?php echo (isset($_GET['category_id']) && $_GET['category_id'] == $category->id) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category->name); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="number" class="form-control" name="price_min" placeholder="Giá từ..." 
                               value="<?php echo htmlspecialchars($_GET['price_min'] ?? ''); ?>">
                        <span class="input-group-text">đến</span>
                        <input type="number" class="form-control" name="price_max" placeholder="Giá đến..." 
                               value="<?php echo htmlspecialchars($_GET['price_max'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="col-md-1">
                    <select class="form-select" name="sort">
                        <option value="name_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'name_asc') ? 'selected' : ''; ?>>A-Z</option>
                        <option value="name_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'name_desc') ? 'selected' : ''; ?>>Z-A</option>
                        <option value="price_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : ''; ?>>Giá tăng</option>
                        <option value="price_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : ''; ?>>Giá giảm</option>
                    </select>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-12 text-end">
                    <a href="/Project_4/Product/list" class="btn btn-light me-2">
                        <i class="bi bi-x-circle me-1"></i>Xóa bộ lọc
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel me-1"></i>Lọc
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if (empty($products)): ?>
    <div class="alert alert-info shadow-sm d-flex align-items-center" role="alert">
        <i class="bi bi-info-circle-fill me-2 fs-4"></i>
        <div>Không tìm thấy sản phẩm nào phù hợp với điều kiện tìm kiếm!</div>
    </div>
<?php else: ?>
    <!-- Card View (Default) -->
    <div id="cardView" class="view-content">
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($products as $product): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="position-relative product-img-wrapper">
                            <?php if (!empty($product->image)): ?>
                                <a href="/Project_4/Product/show/<?php echo $product->id; ?>" class="product-img-container">
                                    <img src="/Project_4/public/uploads/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                                        class="card-img-top" alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>">
                                    <div class="img-overlay"></div>
                                </a>
                            <?php else: ?>
                                <a href="/Project_4/Product/show/<?php echo $product->id; ?>" class="product-img-container no-image">
                                    <div class="d-flex justify-content-center align-items-center h-100">
                                        <i class="bi bi-image text-secondary" style="font-size: 3rem;"></i>
                                    </div>
                                </a>
                            <?php endif; ?>
                            
                            <div class="position-absolute top-0 end-0 p-2">
                                <span class="badge bg-primary price-badge">
                                    <?php echo number_format($product->price, 0, ',', '.'); ?> đ
                                </span>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <h5 class="card-title text-truncate">
                                <a href="/Project_4/Product/show/<?php echo $product->id; ?>" class="text-decoration-none text-dark">
                                    <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            </h5>
                            
                            <?php if (!empty($product->category_name)): ?>
                            <p class="mb-2">
                                <span class="badge bg-secondary">
                                    <i class="bi bi-tag-fill me-1"></i>
                                    <?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                                
                                <?php if (isset($product->stock_quantity)): ?>
                                <span class="badge <?php echo $product->stock_quantity > 0 ? 'bg-success' : 'bg-danger'; ?> ms-1">
                                    <i class="bi <?php echo $product->stock_quantity > 0 ? 'bi-check-circle-fill' : 'bi-x-circle-fill'; ?> me-1"></i>
                                    <?php echo $product->stock_quantity > 0 ? 'Còn hàng (' . $product->stock_quantity . ')' : 'Hết hàng'; ?>
                                </span>
                                <?php endif; ?>
                            </p>
                            <?php else: ?>
                            <p class="mb-2">
                                <?php if (isset($product->stock_quantity)): ?>
                                <span class="badge <?php echo $product->stock_quantity > 0 ? 'bg-success' : 'bg-danger'; ?>">
                                    <i class="bi <?php echo $product->stock_quantity > 0 ? 'bi-check-circle-fill' : 'bi-x-circle-fill'; ?> me-1"></i>
                                    <?php echo $product->stock_quantity > 0 ? 'Còn hàng (' . $product->stock_quantity . ')' : 'Hết hàng'; ?>
                                </span>
                                <?php endif; ?>
                            </p>
                            <?php endif; ?>
                            
                            <div class="card-text mb-3">
                                <div class="product-description-preview">
                                    <?php 
                                    $description = strip_tags(html_entity_decode($product->description));
                                    echo strlen($description) > 100 ? substr($description, 0, 100) . '...' : $description;
                                    ?>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <?php if (SessionHelper::isLoggedIn() && SessionHelper::isAdmin()): ?>
                                <div class="btn-group">
                                    <a href="/Project_4/Product/edit/<?php echo $product->id; ?>" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="/Project_4/Product/delete/<?php echo $product->id; ?>" 
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');" 
                                       class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                                <?php endif; ?>
                                
                                <div class="btn-group flex-grow-1 <?php echo (SessionHelper::isLoggedIn() && SessionHelper::isAdmin()) ? 'ms-2' : ''; ?>">
                                    <a href="/Project_4/Product/show/<?php echo $product->id; ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i> Chi tiết
                                    </a>
                                    <a href="/Project_4/Product/addToCart/<?php echo $product->id; ?>" 
                                       class="btn btn-sm btn-success <?php echo (isset($product->stock_quantity) && $product->stock_quantity <= 0) ? 'disabled' : ''; ?>"
                                       <?php echo (isset($product->stock_quantity) && $product->stock_quantity <= 0) ? 'aria-disabled="true" tabindex="-1"' : ''; ?>>
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
    
    <!-- Table View -->
    <div id="tableView" class="view-content d-none">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th>Tồn kho</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td style="width: 100px;">
                            <?php if (!empty($product->image)): ?>
                                <img src="/Project_4/public/uploads/<?php echo htmlspecialchars($product->image); ?>" 
                                     class="img-thumbnail" alt="<?php echo htmlspecialchars($product->name); ?>"
                                     style="max-height: 50px;">
                            <?php else: ?>
                                <div class="text-center text-muted">
                                    <i class="bi bi-image" style="font-size: 2rem;"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="/Project_4/Product/show/<?php echo $product->id; ?>" class="text-decoration-none">
                                <?php echo htmlspecialchars($product->name); ?>
                            </a>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($product->category_name ?? 'Chưa phân loại'); ?>
                        </td>
                        <td><?php echo number_format($product->price, 0, ',', '.'); ?> đ</td>
                        <td>
                            <?php if (isset($product->stock_quantity)): ?>
                                <span class="badge <?php echo $product->stock_quantity > 0 ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo $product->stock_quantity > 0 ? $product->stock_quantity : 'Hết hàng'; ?>
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Không xác định</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="/Project_4/Product/show/<?php echo $product->id; ?>" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <?php if (SessionHelper::isLoggedIn() && SessionHelper::isAdmin()): ?>
                                <a href="/Project_4/Product/edit/<?php echo $product->id; ?>" 
                                   class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="/Project_4/Product/delete/<?php echo $product->id; ?>" 
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');" 
                                   class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Phân trang -->
    <?php if ($totalPages > 1): ?>
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted">
            Hiển thị <?php echo $startItem; ?>-<?php echo $endItem; ?> trong số <?php echo $totalItems; ?> sản phẩm
        </div>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-end mb-0">
                <?php if ($pagination['prev']): ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo $pagination['prev']; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php endif; ?>
                
                <?php foreach ($pagination['pages'] as $page): ?>
                <li class="page-item <?php echo $page['active'] ? 'active' : ''; ?>">
                    <a class="page-link" href="<?php echo $page['url']; ?>"><?php echo $page['number']; ?></a>
                </li>
                <?php endforeach; ?>
                
                <?php if ($pagination['next']): ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo $pagination['next']; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
<?php endif; ?>

<script>
// Xử lý chuyển đổi chế độ xem
document.getElementById('cardViewBtn').addEventListener('click', function() {
    document.getElementById('cardView').classList.remove('d-none');
    document.getElementById('tableView').classList.add('d-none');
    this.classList.add('active');
    document.getElementById('tableViewBtn').classList.remove('active');
    localStorage.setItem('productViewMode', 'card');
});

document.getElementById('tableViewBtn').addEventListener('click', function() {
    document.getElementById('tableView').classList.remove('d-none');
    document.getElementById('cardView').classList.add('d-none');
    this.classList.add('active');
    document.getElementById('cardViewBtn').classList.remove('active');
    localStorage.setItem('productViewMode', 'table');
});

// Khôi phục chế độ xem đã lưu
document.addEventListener('DOMContentLoaded', function() {
    const viewMode = localStorage.getItem('productViewMode') || 'card';
    if (viewMode === 'table') {
        document.getElementById('tableViewBtn').click();
    }
});

// Submit form khi thay đổi các select
document.querySelectorAll('#filterForm select').forEach(function(select) {
    select.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
});

// Xử lý form tìm kiếm
let searchTimeout;
document.querySelector('input[name="search"]').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        document.getElementById('filterForm').submit();
    }, 500);
});

// Xử lý nhập giá
document.querySelectorAll('input[name="price_min"], input[name="price_max"]').forEach(function(input) {
    input.addEventListener('change', function() {
        const priceMin = parseFloat(document.querySelector('input[name="price_min"]').value) || 0;
        const priceMax = parseFloat(document.querySelector('input[name="price_max"]').value) || Infinity;
        
        if (priceMax && priceMin > priceMax) {
            alert('Giá tối thiểu không thể lớn hơn giá tối đa!');
            this.value = '';
            return;
        }
        
        document.getElementById('filterForm').submit();
    });
});
</script>

</div>

<?php include 'app/views/shares/footer.php'; ?>