<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');


class CategoryController
{
    private $categoryModel;
    private $db;    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
        
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Require admin access
        require_once 'app/helpers/SessionHelper.php';
        SessionHelper::requireLogin();
        if (!SessionHelper::isAdmin()) {
            SessionHelper::setError('Bạn không có quyền truy cập trang này!');
            header('Location: /Project_4/');
            exit();
        }
    }

    public function index()
    {
        $categories = $this->categoryModel->getCategories();
        include 'app/views/category/list.php';
    }

    // Add list method as alias for index
    public function list()
    {
        $this->index();
    }

    public function add()
    {
        include 'app/views/category/add.php';
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $result = $this->categoryModel->addCategory($name, $description);
            if (is_array($result)) {
                $errors = $result;
                include 'app/views/category/add.php';
            } else {
                header('Location: /Project_4/Category');
            }
        }
    }

    public function edit($id)
    {
        $category = $this->categoryModel->getCategoryById($id);
        if ($category) {
            include 'app/views/category/edit.php';
        } else {
            echo "Không tìm thấy danh mục.";
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $edit = $this->categoryModel->updateCategory($id, $name, $description);
            if ($edit) {
                header('Location: /Project_4/Category');
            } else {
                echo "Đã xảy ra lỗi khi lưu danh mục.";
            }
        }
    }    public function delete($id)
    {
        try {
            // Validate input
            if (empty($id) || !is_numeric($id)) {
                $_SESSION['error'] = "ID danh mục không hợp lệ.";
                header('Location: /Project_4/Category');
                exit();
            }
            
            // Check if category exists
            $category = $this->categoryModel->getCategoryById($id);
            if (!$category) {
                $_SESSION['error'] = "Danh mục không tồn tại.";
                header('Location: /Project_4/Category');
                exit();
            }
            
            // Get product count for better user feedback
            $productCount = $this->categoryModel->getProductCountByCategory($id);
            
            // Try to delete
            if ($this->categoryModel->deleteCategory($id)) {
                if ($productCount > 0) {
                    $_SESSION['success'] = "Đã xóa danh mục '{$category->name}' thành công. {$productCount} sản phẩm trong danh mục này đã được chuyển thành không có danh mục.";
                } else {
                    $_SESSION['success'] = "Đã xóa danh mục '{$category->name}' thành công.";
                }
                header('Location: /Project_4/Category');
                exit();
            } else {
                $_SESSION['error'] = "Đã xảy ra lỗi khi xóa danh mục '{$category->name}'. Vui lòng thử lại.";
                header('Location: /Project_4/Category');
                exit();
            }
        } catch (Exception $e) {
            error_log("Category deletion error in controller: " . $e->getMessage());
            $_SESSION['error'] = "Lỗi hệ thống khi xóa danh mục. Vui lòng thử lại sau.";
            header('Location: /Project_4/Category');
            exit();
        }
    }
}
?>