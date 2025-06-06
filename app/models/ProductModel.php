<?php
class ProductModel
{
    private $conn;
    private $table_name = "products";
    
    public function __construct($db)
    {
        $this->conn = $db;
    }
      public function getProducts($search = '', $categoryId = '', $priceMin = '', $priceMax = '', $sort = 'name_asc', $limit = null, $offset = 0)
    {
        try {
            $sql = "SELECT p.*, c.name as category_name 
                    FROM products p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    WHERE 1=1";
            $params = [];
            
            // Thêm điều kiện tìm kiếm
            if (!empty($search)) {
                $sql .= " AND (p.name LIKE :search OR p.description LIKE :search)";
                $params[':search'] = "%{$search}%";
            }
            
            // Lọc theo danh mục
            if (!empty($categoryId)) {
                $sql .= " AND p.category_id = :category_id";
                $params[':category_id'] = $categoryId;
            }
            
            // Lọc theo giá
            if (!empty($priceMin)) {
                $sql .= " AND p.price >= :price_min";
                $params[':price_min'] = $priceMin;
            }
            if (!empty($priceMax)) {
                $sql .= " AND p.price <= :price_max";
                $params[':price_max'] = $priceMax;
            }
            
            // Thêm sắp xếp
            switch ($sort) {
                case 'name_desc':
                    $sql .= " ORDER BY p.name DESC";
                    break;
                case 'price_asc':
                    $sql .= " ORDER BY p.price ASC";
                    break;
                case 'price_desc':
                    $sql .= " ORDER BY p.price DESC";
                    break;
                default: // name_asc
                    $sql .= " ORDER BY p.name ASC";
            }
            
            // Thêm phân trang
            if ($limit !== null) {
                $sql .= " LIMIT :offset, :limit";
                $params[':offset'] = $offset;
                $params[':limit'] = $limit;
            }
            
            $stmt = $this->conn->prepare($sql);
            
            // Bind các tham số
            foreach ($params as $key => $value) {
                if ($key == ':offset' || $key == ':limit') {
                    $stmt->bindValue($key, $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue($key, $value);
                }
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
            
        } catch(PDOException $e) {
            error_log("Error fetching products: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Đếm tổng số sản phẩm theo điều kiện lọc
     */
    public function countProducts($search = '', $categoryId = '', $priceMin = '', $priceMax = '') {
        try {
            $sql = "SELECT COUNT(*) FROM products p WHERE 1=1";
            $params = [];
            
            if (!empty($search)) {
                $sql .= " AND (p.name LIKE :search OR p.description LIKE :search)";
                $params[':search'] = "%{$search}%";
            }
            
            if (!empty($categoryId)) {
                $sql .= " AND p.category_id = :category_id";
                $params[':category_id'] = $categoryId;
            }
            
            if (!empty($priceMin)) {
                $sql .= " AND p.price >= :price_min";
                $params[':price_min'] = $priceMin;
            }
            
            if (!empty($priceMax)) {
                $sql .= " AND p.price <= :price_max";
                $params[':price_max'] = $priceMax;
            }
            
            $stmt = $this->conn->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            $stmt->execute();
            return $stmt->fetchColumn();
            
        } catch(PDOException $e) {
            error_log("Error counting products: " . $e->getMessage());
            return 0;
        }
    }
    
    public function getProductById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }
    
    public function addProduct($name, $description, $price, $category_id, $image = null, $stock_quantity = 0)
    {
        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Tên sản phẩm không được để trống';
        }
        if (empty($description)) {
            $errors['description'] = 'Mô tả không được để trống';
        }
        if (!is_numeric($price) || $price < 0) {
            $errors['price'] = 'Giá sản phẩm không hợp lệ';
        }
        if (!is_numeric($stock_quantity) || $stock_quantity < 0) {
            $errors['stock_quantity'] = 'Số lượng tồn kho không hợp lệ';
        }
        if (count($errors) > 0) {
            return $errors;
        }
        
        $query = "INSERT INTO " . $this->table_name . " (name, description, price, category_id, image, stock_quantity) 
                  VALUES (:name, :description, :price, :category_id, :image, :stock_quantity)";
        $stmt = $this->conn->prepare($query);
        $name = htmlspecialchars(strip_tags($name));
        // Don't strip HTML tags from description to preserve formatting
        $description = htmlspecialchars_decode($description);
        $price = htmlspecialchars(strip_tags($price));
        $stock_quantity = (int)$stock_quantity;
        
        // Check if category_id is null before applying string functions
        if ($category_id !== null && $category_id !== '') {
            $category_id = htmlspecialchars(strip_tags($category_id));
        } else {
            $category_id = null; // Make sure it's explicitly null for database
        }
        
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT); // Use PDO::PARAM_INT to handle NULL properly
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':stock_quantity', $stock_quantity, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    public function updateProduct($id, $name, $description, $price, $category_id, $image = null, $stock_quantity = 0)
    {
        if ($image !== null) {
            $query = "UPDATE " . $this->table_name . " 
                      SET name=:name, description=:description, price=:price, category_id=:category_id, image=:image, stock_quantity=:stock_quantity 
                      WHERE id=:id";
        } else {
            $query = "UPDATE " . $this->table_name . " 
                      SET name=:name, description=:description, price=:price, category_id=:category_id, stock_quantity=:stock_quantity 
                      WHERE id=:id";
        }
        
        $stmt = $this->conn->prepare($query);
        $name = htmlspecialchars(strip_tags($name));
        // Don't strip HTML tags from description to preserve formatting
        $description = htmlspecialchars_decode($description);
        $price = htmlspecialchars(strip_tags($price));
        $stock_quantity = (int)$stock_quantity;
        
        if ($category_id !== null) {
            $category_id = htmlspecialchars(strip_tags($category_id));
        }
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        
        if ($image !== null) {
            $stmt->bindParam(':image', $image);
        }
        
        $stmt->bindParam(':stock_quantity', $stock_quantity, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    public function deleteProduct($id)
    {
        $currentProduct = $this->getProductById($id);
        
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            if ($currentProduct && !empty($currentProduct->image) && file_exists('public/uploads/' . $currentProduct->image)) {
                unlink('public/uploads/' . $currentProduct->image);
            }
            return true;
        }
        return false;
    }
}
?>