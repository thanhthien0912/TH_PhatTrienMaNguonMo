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
    <h1 class="text-center mb-4 text-primary">Sửa Sản Phẩm</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="/Project_2/Product/update" enctype="multipart/form-data" onsubmit="return validateForm();">
        <input type="hidden" name="id" value="<?php echo $product->id; ?>">

        <div class="card p-4 shadow-sm">
            <div class="form-group mb-3">
                <label for="name" class="form-label">Tên Sản Phẩm:</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Nhập tên sản phẩm" required>
            </div>

            <div class="form-group mb-3">
                <label for="description" class="form-label">Mô Tả:</label>
                <textarea id="description" name="description" class="form-control" rows="4" placeholder="Mô tả sản phẩm" required><?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>

            <div class="form-group mb-3">
                <label for="price" class="form-label">Giá:</label>
                <input type="number" id="price" name="price" class="form-control" step="0.01" value="<?php echo htmlspecialchars($product->price, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Nhập giá sản phẩm" required>
            </div>

            <div class="form-group mb-3">
                <label for="category_id" class="form-label">Danh Mục:</label>
                <select id="category_id" name="category_id" class="form-control" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category->id; ?>" <?php echo $category->id == $product->category_id ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            

            <button type="submit" class="btn btn-success btn-block">Lưu Thay Đổi</button>
        </div>
    </form>

    <a href="/Project_2/Product/" class="btn btn-secondary mt-3 w-100">Quay lại danh sách sản phẩm</a>
</div>

<?php include 'app/views/shares/footer.php'; ?>
