<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa sản phẩm</title>
    <link rel="stylesheet" href="/Project_1/public/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(45deg, #f3f4f6, #e2e8f0);
            margin: 0;
            padding: 0;
            height: 100vh; /* Chiều cao 100% của viewport */
            display: flex;
            justify-content: center; /* Căn giữa theo chiều ngang */
            align-items: center; /* Căn giữa theo chiều dọc */
        }

        .container {
            max-width: 900px;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%; /* Đảm bảo phần tử chiếm toàn bộ chiều rộng khi màn hình nhỏ */
        }

        h1 {
            color: #343a40;
            font-size: 2.5rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 40px;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            font-size: 1.1rem;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px 20px;
            font-size: 1.1rem;
            border: 1px solid #ced4da;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #66afe9;
            outline: none;
            box-shadow: 0 0 8px rgba(102, 175, 233, 0.6);
        }

        .btn {
            padding: 12px 30px;
            font-size: 1.2rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-3px);
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-3px);
        }

        .mb-3 {
            margin-bottom: 1.8rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .text-center {
            text-align: center;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            h1 {
                font-size: 2rem;
            }

            .btn {
                font-size: 1rem;
                padding: 10px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Sửa sản phẩm</h1>
        <form method="POST" action="/Project_1/Product/edit/<?php echo $product->getID(); ?>" onsubmit="return validateForm();">
            <div class="form-group mb-3">
                <label for="name" class="form-label">Tên sản phẩm:</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($product->getName(), ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group mb-3">
                <label for="description" class="form-label">Mô tả:</label>
                <textarea id="description" name="description" class="form-control" rows="5" required><?php echo htmlspecialchars($product->getDescription(), ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>
            <div class="form-group mb-3">
                <label for="price" class="form-label">Giá:</label>
                <input type="number" id="price" name="price" class="form-control" value="<?php echo htmlspecialchars($product->getPrice(), ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                <a href="/Project_1/Product/list" class="btn btn-secondary ml-3">Quay lại danh sách sản phẩm</a>
            </div>
        </form>
    </div>

    <script>
        function validateForm() {
            var name = document.getElementById('name').value;
            var description = document.getElementById('description').value;
            var price = document.getElementById('price').value;

            // Check if name is empty
            if (name.trim() === '') {
                alert("Tên sản phẩm không thể để trống!");
                return false;
            }

            // Check if description is empty
            if (description.trim() === '') {
                alert("Mô tả sản phẩm không thể để trống!");
                return false;
            }

            // Check if price is a valid number
            if (isNaN(price) || price <= 0) {
                alert("Giá sản phẩm phải là một số dương hợp lệ!");
                return false;
            }

            return true; // Form is valid, proceed with submission
        }
    </script>

    <script src="/Project_1/public/js/bootstrap.bundle.min.js"></script>
</body>

</html>
