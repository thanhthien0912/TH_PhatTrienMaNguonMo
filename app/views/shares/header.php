<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Custom Styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            margin-bottom: 20px;
        }

        .navbar-nav .nav-item .nav-link {
            font-size: 1.1rem;
            padding: 10px 20px;
            transition: background-color 0.3s ease;
        }

        .navbar-nav .nav-item .nav-link:hover {
            background-color: #007bff;
            color: white;
            border-radius: 5px;
        }

        .container {
            max-width: 1200px;
            margin-top: 30px;
        }

        .container h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
        }

        .footer {
            background-color: #f8f9fa;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
            border-top: 1px solid #e0e0e0;
        }

        .footer p {
            color: #777;
        }

        /* Custom button style */
        .btn-icon {
            display: flex;
            align-items: center;
        }

        .btn-icon i {
            margin-right: 5px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/Project_2/product/"><i class="fas fa-list"></i> Danh sách sản phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/Project_2/product/add"><i class="fas fa-plus-circle"></i> Thêm sản phẩm</a>
                </li>
                
            </ul>
       
    </footer>
        </div>
    </nav>

   

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
