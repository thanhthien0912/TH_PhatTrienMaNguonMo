<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            max-width: 1200px;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        h1 {
            color: #343a40;
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 40px;
        }

        .card {
            border: 0;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 25px;
        }

        .card-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: #333;
        }

        .card-text {
            font-size: 1.1rem;
            color: #6c757d;
            margin-bottom: 15px;
        }

        .price {
            font-size: 1.3rem;
            font-weight: 700;
            color: #28a745;
        }

        .btn-custom-black {
            background-color: #000;
            color: #fff;
            border-radius: 6px;
            border: 1px solid #000;
            transition: background-color 0.3s;
            font-weight: 500;
        }

        .btn-custom-black:hover {
            background-color: #333;
            color: #fff;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.9rem;
        }

        .d-flex .gap-2 {
            display: flex;
            gap: 10px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }

        .col-md-4 {
            flex: 1 1 calc(33% - 30px);
            max-width: calc(33% - 30px);
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .col-md-4 {
                flex: 1 1 calc(50% - 30px);
                max-width: calc(50% - 30px);
            }
        }

        @media (max-width: 768px) {
            .col-md-4 {
                flex: 1 1 100%;
                max-width: 100%;
            }
            h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Danh sách sản phẩm</h1>
            <a href="/Project_1/Product/add" class="btn btn-custom-black">Thêm sản phẩm mới</a>
        </div>

        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product->getName(), ENT_QUOTES, 'UTF-8'); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($product->getDescription(), ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="price"><?php echo number_format($product->getPrice(), 0, ',', '.'); ?> VNĐ</p>
                            <div class="d-flex gap-2">
                                <a href="/Project_1/Product/edit/<?php echo $product->getID(); ?>" class="btn btn-custom-black btn-sm">Sửa</a>
                                <a href="/Project_1/Product/delete/<?php echo $product->getID(); ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');" class="btn btn-danger btn-sm">Xóa</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
