<?php
require_once 'app/models/ProductModel.php';
require_once 'app/config/database.php';

class ProductController {
    private $productModel;
    private $conn;
    private $itemsPerPage = 9; // Số sản phẩm trên mỗi trang

    public function __construct() {
        // Khởi tạo kết nối database
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->productModel = new ProductModel($this->conn);
        
        // Khởi tạo session nếu chưa có
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        $this->list();
    }    public function list() {
        require_once 'app/models/CategoryModel.php';
        $categoryModel = new CategoryModel($this->conn);
        $categories = $categoryModel->getCategories();

        // Lấy các tham số tìm kiếm và lọc
        $search = trim($_GET['search'] ?? '');
        $categoryId = $_GET['category_id'] ?? '';
        $priceMin = $_GET['price_min'] ?? '';
        $priceMax = $_GET['price_max'] ?? '';
        $sort = $_GET['sort'] ?? 'name_asc';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        
        // Tính offset cho phân trang
        $itemsPerPage = 9;
        $offset = ($page - 1) * $itemsPerPage;
        
        // Lấy tổng số sản phẩm và danh sách sản phẩm
        $totalItems = $this->productModel->countProducts($search, $categoryId, $priceMin, $priceMax);
        $products = $this->productModel->getProducts($search, $categoryId, $priceMin, $priceMax, $sort, $itemsPerPage, $offset);
        
        // Tính toán thông tin phân trang
        $totalPages = ceil($totalItems / $itemsPerPage);
        $startItem = $offset + 1;
        $endItem = min($offset + $itemsPerPage, $totalItems);
        $currentPage = $page;

        // Tạo các URL phân trang
        $params = $_GET;
        $pagination = [
            'prev' => null,
            'next' => null,
            'pages' => []
        ];
        
        if ($currentPage > 1) {
            $params['page'] = $currentPage - 1;
            $pagination['prev'] = '/Project_4/Product/list?' . http_build_query($params);
        }
        
        if ($currentPage < $totalPages) {
            $params['page'] = $currentPage + 1;
            $pagination['next'] = '/Project_4/Product/list?' . http_build_query($params);
        }
        
        for ($i = 1; $i <= $totalPages; $i++) {
            $params['page'] = $i;
            $pagination['pages'][] = [
                'number' => $i,
                'url' => '/Project_4/Product/list?' . http_build_query($params),
                'active' => $i === $currentPage
            ];
        }

        include 'app/views/product/list.php';
    }

    // Helper function để tạo URL phân trang
    private function buildPaginationUrl($page) {
        $params = $_GET;
        $params['page'] = $page;
        return '/Project_4/Product/list?' . http_build_query($params);
    }

    public function add() {
        // Kiểm tra quyền admin
        require_once 'app/helpers/SessionHelper.php';
        SessionHelper::requireLogin();
        if (!SessionHelper::isAdmin()) {
            header('Location: /Project_4/Product');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Xử lý thêm sản phẩm
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;
            $category_id = $_POST['category_id'] ?? null;
            $stock_quantity = isset($_POST['stock_quantity']) ? (int)$_POST['stock_quantity'] : 0;
            
            // Xử lý upload hình ảnh
            $image = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $uploadDir = 'public/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $imageExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $imageName = time() . '_' . $_FILES['image']['name'];
                $imagePath = $uploadDir . $imageName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                    $image = $imageName;
                }
            }
            
            $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image, $stock_quantity);
            
            if (is_array($result)) {
                // Có lỗi validation
                $errors = $result;
                include 'app/views/product/add.php';
            } else {
                // Thành công
                header('Location: /Project_4/Product');
                exit();
            }
        } else {
            // Hiển thị form thêm sản phẩm
            require_once 'app/models/CategoryModel.php';
            $categoryModel = new CategoryModel($this->conn);
            $categories = $categoryModel->getCategories();
            include 'app/views/product/add.php';
        }
    }    public function edit($id) {
        // Kiểm tra quyền admin
        require_once 'app/helpers/SessionHelper.php';
        SessionHelper::requireLogin();
        if (!SessionHelper::isAdmin()) {
            header('Location: /Project_4/Product');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Xử lý cập nhật sản phẩm
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;
            $category_id = $_POST['category_id'] ?? null;
            $stock_quantity = isset($_POST['stock_quantity']) ? (int)$_POST['stock_quantity'] : 0;
            
            // Xử lý upload hình ảnh mới (nếu có)
            $image = $_POST['current_image'] ?? null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $uploadDir = 'public/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $imageExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $imageName = time() . '_' . $_FILES['image']['name'];
                $imagePath = $uploadDir . $imageName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                    // Xóa hình ảnh cũ nếu có
                    if ($image && file_exists('public/uploads/' . $image)) {
                        unlink('public/uploads/' . $image);
                    }
                    $image = $imageName;
                }
            }
            
            $result = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image, $stock_quantity);
            
            if (is_array($result)) {
                // Có lỗi validation
                $errors = $result;
                $product = $this->productModel->getProductById($id);
                require_once 'app/models/CategoryModel.php';
                $categoryModel = new CategoryModel($this->conn);
                $categories = $categoryModel->getCategories();
                include 'app/views/product/edit.php';
            } else {
                // Thành công
                header('Location: /Project_4/Product');
                exit();
            }
        } else {
            // Hiển thị form sửa sản phẩm
            $product = $this->productModel->getProductById($id);
            if (!$product) {
                header('Location: /Project_4/Product');
                exit();
            }
            
            require_once 'app/models/CategoryModel.php';
            $categoryModel = new CategoryModel($this->conn);
            $categories = $categoryModel->getCategories();
            include 'app/views/product/edit.php';
        }
    }    public function delete($id) {
        // Kiểm tra quyền admin
        require_once 'app/helpers/SessionHelper.php';
        SessionHelper::requireLogin();
        if (!SessionHelper::isAdmin()) {
            header('Location: /Project_4/Product');
            exit();
        }
        
        $product = $this->productModel->getProductById($id);
        if ($product) {
            // Xóa hình ảnh nếu có
            if ($product->image && file_exists('public/uploads/' . $product->image)) {
                unlink('public/uploads/' . $product->image);
            }
            
            $this->productModel->deleteProduct($id);
        }
        
        header('Location: /Project_4/Product');
        exit();
    }

    public function addToCart($id = null) {
        if (!$id && isset($_POST['product_id'])) {
            $id = $_POST['product_id'];
        }
        
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        if (!$id) {
            header('Location: /Project_4/product/list');
            exit();
        }
        
        $product = $this->productModel->getProductById($id);
        if ($product) {
            // Kiểm tra số lượng tồn kho
            if ($product->stock_quantity <= 0) {
                $_SESSION['cart_error'] = 'Sản phẩm đã hết hàng!';
                header('Location: /Project_4/product/show/' . $id);
                exit();
            }
            
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            $productId = $product->id;
            $currentQuantity = isset($_SESSION['cart'][$productId]) ? $_SESSION['cart'][$productId]['quantity'] : 0;
            $newQuantity = $currentQuantity + $quantity;
            
            // Kiểm tra nếu số lượng mới vượt quá tồn kho
            if ($newQuantity > $product->stock_quantity) {
                $_SESSION['cart_error'] = 'Số lượng yêu cầu vượt quá số lượng tồn kho! Chỉ còn ' . $product->stock_quantity . ' sản phẩm.';
                header('Location: /Project_4/product/show/' . $id);
                exit();
            }
            
            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
            } else {
                $_SESSION['cart'][$productId] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $quantity,
                    'image' => $product->image ?? ''
                ];
            }
            
            $_SESSION['cart_message'] = 'Đã thêm sản phẩm vào giỏ hàng!';
        }
        
        header('Location: /Project_4/product/cart');
        exit();
    }

    public function updateCart() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['quantities'])) {
            $hasError = false;
            
            foreach ($_POST['quantities'] as $productId => $quantity) {
                $quantity = (int)$quantity;
                if ($quantity > 0) {
                    if (isset($_SESSION['cart'][$productId])) {
                        // Kiểm tra số lượng tồn kho
                        $product = $this->productModel->getProductById($productId);
                        if ($product && $quantity <= $product->stock_quantity) {
                            $_SESSION['cart'][$productId]['quantity'] = $quantity;
                        } else {
                            // Nếu số lượng vượt quá tồn kho, giữ nguyên số lượng cũ
                            $hasError = true;
                            $_SESSION['cart_error'] = 'Một số sản phẩm có số lượng vượt quá tồn kho!';
                            // Giới hạn số lượng tối đa là số lượng tồn kho
                            if ($product) {
                                $_SESSION['cart'][$productId]['quantity'] = min($quantity, $product->stock_quantity);
                            }
                        }
                    }
                } else {
                    unset($_SESSION['cart'][$productId]);
                }
            }
            
            if (!$hasError) {
                $_SESSION['cart_message'] = 'Đã cập nhật giỏ hàng!';
            }
        }
        
        header('Location: /Project_4/product/cart');
        exit();
    }

    public function removeFromCart($id) {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
            $_SESSION['cart_message'] = 'Đã xóa sản phẩm khỏi giỏ hàng!';
        }
        
        header('Location: /Project_4/product/cart');
        exit();
    }

    public function clearCart() {
        unset($_SESSION['cart']);
        $_SESSION['cart_message'] = 'Đã xóa toàn bộ giỏ hàng!';
        
        header('Location: /Project_4/product/cart');
        exit();
    }

    public function cart() {
        include 'app/views/product/cart.php';
    }    public function checkout() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Xử lý đặt hàng
            $customerName = $_POST['customer_name'] ?? '';
            $customerEmail = $_POST['customer_email'] ?? '';
            $customerPhone = $_POST['customer_phone'] ?? '';
            $customerAddress = $_POST['customer_address'] ?? '';
            $paymentMethod = $_POST['payment_method'] ?? 'cod';
            $notes = $_POST['notes'] ?? '';

            if (empty($customerName) || empty($customerEmail) || empty($customerAddress)) {
                $_SESSION['checkout_error'] = 'Vui lòng điền đầy đủ thông tin!';
                include 'app/views/product/checkout.php';
                return;
            }

            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                $_SESSION['checkout_error'] = 'Giỏ hàng trống!';
                header('Location: /Project_4/product/cart');
                exit();
            }

            // Tính tổng tiền
            $totalAmount = 0;
            foreach ($_SESSION['cart'] as $item) {
                $totalAmount += $item['price'] * $item['quantity'];
            }

            try {
                // Bắt đầu transaction
                $this->conn->beginTransaction();

                // Lưu đơn hàng vào bảng orders
                $stmt = $this->conn->prepare("
                    INSERT INTO orders (customer_name, customer_email, customer_phone, customer_address, 
                                      total_amount, payment_method, notes, status, payment_status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', 'unpaid')
                ");
                $stmt->execute([$customerName, $customerEmail, $customerPhone, $customerAddress, 
                               $totalAmount, $paymentMethod, $notes]);
                
                $orderId = $this->conn->lastInsertId();

                // Lưu chi tiết đơn hàng vào order_details
                foreach ($_SESSION['cart'] as $item) {
                    $subtotal = $item['price'] * $item['quantity'];
                    $stmt = $this->conn->prepare("
                        INSERT INTO order_details (order_id, product_id, product_name, quantity, unit_price, subtotal) 
                        VALUES (?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([$orderId, $item['id'], $item['name'], $item['quantity'], $item['price'], $subtotal]);
                }

                // Commit transaction
                $this->conn->commit();

                // Lưu thông tin đơn hàng vào session để hiển thị
                $_SESSION['last_order'] = [
                    'id' => $orderId,
                    'customer_name' => $customerName,
                    'total_amount' => $totalAmount,
                    'items' => $_SESSION['cart']
                ];

                // Xóa giỏ hàng sau khi đặt hàng thành công
                unset($_SESSION['cart']);
                
                header('Location: /Project_4/product/orderConfirmation');
                exit();

            } catch (Exception $e) {
                // Rollback nếu có lỗi
                $this->conn->rollback();
                $_SESSION['checkout_error'] = 'Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại!';
                include 'app/views/product/checkout.php';
            }
        } else {
            // Hiển thị trang checkout
            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                header('Location: /Project_4/product/cart');
                exit();
            }
            include 'app/views/product/checkout.php';
        }
    }

    public function orderConfirmation() {
        include 'app/views/product/orderConfirmation.php';
    }    public function show($id) {
        require_once 'app/models/CategoryModel.php';
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            header('Location: /Project_4/product/list');
            exit();
        }
        include 'app/views/product/show.php';
    }

    public function orders() {
        // Lấy danh sách đơn hàng (cần có hệ thống đăng nhập để lọc theo user)
        $stmt = $this->conn->prepare("
            SELECT o.*, COUNT(od.id) as item_count 
            FROM orders o 
            LEFT JOIN order_details od ON o.id = od.order_id 
            GROUP BY o.id 
            ORDER BY o.created_at DESC
        ");
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        include 'app/views/product/orders.php';
    }

    public function orderDetail($id) {
        // Lấy thông tin đơn hàng
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$order) {
            header('Location: /Project_4/product/orders');
            exit();
        }

        // Lấy chi tiết đơn hàng
        $stmt = $this->conn->prepare("
            SELECT od.*, p.image 
            FROM order_details od 
            LEFT JOIN products p ON od.product_id = p.id 
            WHERE od.order_id = ?
        ");
        $stmt->execute([$id]);
        $orderDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'app/views/product/orderDetail.php';
    }

    // Phương thức helper để tính tổng số lượng trong giỏ hàng
    public function getCartItemCount() {
        if (!isset($_SESSION['cart'])) {
            return 0;
        }
        
        $count = 0;
        foreach ($_SESSION['cart'] as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }

    // Phương thức helper để tính tổng tiền trong giỏ hàng  
    public function getCartTotal() {
        if (!isset($_SESSION['cart'])) {
            return 0;
        }
        
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}