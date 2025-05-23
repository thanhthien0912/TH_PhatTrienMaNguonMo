<?php include 'app/views/shares/header.php'; ?>

<style>
.custom-badge {
    background-color: #ffcc00;
    color: black; /* Màu chữ */
}
.card-img-top {
    max-height: 200px;
    object-fit: cover;
}
</style>

<div class="container mt-5">
    <h1 class="text-center mb-4 text-primary">Danh Sách Sản Phẩm</h1>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <span class="text-muted">Tổng số sản phẩm: <?php echo count($products); ?></span>
    </div>

    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-primary">
                    
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="/Project_2/Product/show/<?php echo $product->id; ?>" class="text-primary text-decoration-none">
                                <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </h5>
                        <h6 class="card-subtitle mb-2 text-muted">
                            <span class="badge bg-white"><?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?></span>
                        </h6>
                        <p class="card-text text-truncate" style="max-height: 3.6em; overflow: hidden;">
                            <?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                        <p class="text-danger fw-bold">Giá: <?php echo number_format($product->price, 0, ',', '.'); ?> VND</p>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="/Project_2/Product/edit/<?php echo $product->id; ?>" class="btn btn-outline-warning btn-sm">Sửa</a>
                        <a href="/Project_2/Product/delete/<?php echo $product->id; ?>" 
                           class="btn btn-outline-danger btn-sm" 
                           onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
