<?php
// filepath: c:\laragon\www\Project_4\app\helpers\SessionHelper.php

class SessionHelper {
    
    /**
     * Khởi tạo session
     */
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Đăng nhập user
     */
    public static function login($user) {
        self::start();
        
        // Regenerate session ID để bảo mật
        session_regenerate_id(true);
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['is_logged_in'] = true;
        $_SESSION['login_time'] = time();
        
        // Tạo CSRF token
        self::generateCSRFToken();
    }
    
    /**
     * Đăng xuất user
     */
    public static function logout() {
        self::start();
        
        // Xóa tất cả session variables
        $_SESSION = array();
        
        // Xóa session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Hủy session
        session_destroy();
    }
    
    /**
     * Kiểm tra user đã đăng nhập chưa
     */
    public static function isLoggedIn() {
        self::start();
        return isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
    }
    
    /**
     * Lấy user ID hiện tại
     */
    public static function getUserId() {
        self::start();
        return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    }
    
    /**
     * Lấy username hiện tại
     */
    public static function getUsername() {
        self::start();
        return isset($_SESSION['username']) ? $_SESSION['username'] : null;
    }
    
    /**
     * Lấy email hiện tại
     */
    public static function getEmail() {
        self::start();
        return isset($_SESSION['email']) ? $_SESSION['email'] : null;
    }
    
    /**
     * Lấy full name hiện tại
     */
    public static function getFullName() {
        self::start();
        return isset($_SESSION['full_name']) ? $_SESSION['full_name'] : null;
    }
    
    /**
     * Lấy role hiện tại
     */
    public static function getRole() {
        self::start();
        return isset($_SESSION['role']) ? $_SESSION['role'] : null;
    }
    
    /**
     * Kiểm tra có phải admin không
     */
    public static function isAdmin() {
        return self::getRole() === 'admin';
    }
    
    /**
     * Kiểm tra có phải user thường không
     */
    public static function isUser() {
        return self::getRole() === 'user';
    }
    
    /**
     * Lấy thông tin user hiện tại
     */
    public static function getCurrentUser() {
        if (!self::isLoggedIn()) {
            return null;
        }
        
        return [
            'id' => self::getUserId(),
            'username' => self::getUsername(),
            'email' => self::getEmail(),
            'full_name' => self::getFullName(),
            'role' => self::getRole(),
            'login_time' => isset($_SESSION['login_time']) ? $_SESSION['login_time'] : null
        ];
    }
    
    /**
     * Tạo CSRF token
     */
    public static function generateCSRFToken() {
        self::start();
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Lấy CSRF token
     */
    public static function getCSRFToken() {
        self::start();
        return isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : self::generateCSRFToken();
    }
    
    /**
     * Verify CSRF token
     */
    public static function verifyCSRFToken($token) {
        self::start();
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Set flash message
     */
    public static function setFlashMessage($type, $message) {
        self::start();
        $_SESSION['flash_messages'][] = [
            'type' => $type,
            'message' => $message
        ];
    }
    
    /**
     * Get và xóa flash messages
     */
    public static function getFlashMessages() {
        self::start();
        $messages = isset($_SESSION['flash_messages']) ? $_SESSION['flash_messages'] : [];
        unset($_SESSION['flash_messages']);
        return $messages;
    }
    
    /**
     * Set success message
     */
    public static function setSuccess($message) {
        self::setFlashMessage('success', $message);
    }
    
    /**
     * Set error message
     */
    public static function setError($message) {
        self::setFlashMessage('error', $message);
    }
    
    /**
     * Set warning message
     */
    public static function setWarning($message) {
        self::setFlashMessage('warning', $message);
    }
    
    /**
     * Set info message
     */
    public static function setInfo($message) {
        self::setFlashMessage('info', $message);
    }
    
    /**
     * Middleware để bảo vệ các trang yêu cầu đăng nhập
     */
    public static function requireLogin($redirectUrl = '/Project_4/User/showLogin') {
        if (!self::isLoggedIn()) {
            header("Location: $redirectUrl");
            exit();
        }
    }
    
    /**
     * Middleware để bảo vệ các trang chỉ dành cho admin
     */
    public static function requireAdmin($redirectUrl = '/Project_4/') {
        self::requireLogin();
        if (!self::isAdmin()) {
            self::setError('Bạn không có quyền truy cập trang này!');
            header("Location: $redirectUrl");
            exit();
        }
    }
    
    /**
     * Middleware để redirect user đã đăng nhập khỏi trang login/register
     */
    public static function redirectIfLoggedIn($redirectUrl = '/Project_4/') {
        if (self::isLoggedIn()) {
            header("Location: $redirectUrl");
            exit();
        }
    }
    
    /**
     * Kiểm tra session timeout (optional)
     */
    public static function checkSessionTimeout($timeoutMinutes = 120) {
        self::start();
        
        if (isset($_SESSION['login_time'])) {
            $timeoutSeconds = $timeoutMinutes * 60;
            if (time() - $_SESSION['login_time'] > $timeoutSeconds) {
                self::setWarning('Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.');
                self::logout();
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Update session activity time
     */
    public static function updateActivity() {
        self::start();
        $_SESSION['last_activity'] = time();
    }
    
    /**
     * Lấy avatar hiện tại
     */
    public static function getAvatar() {
        self::start();
        
        // Nếu đã có avatar trong session
        if (isset($_SESSION['avatar'])) {
            return $_SESSION['avatar'];
        }
        
        // Nếu chưa có, lấy từ database
        if (self::isLoggedIn()) {
            require_once 'app/models/UserModel.php';
            require_once 'app/config/database.php';
            
            $database = new Database();
            $conn = $database->getConnection();
            $userModel = new UserModel($conn);
            
            $avatar = $userModel->getAvatar(self::getUserId());
            $_SESSION['avatar'] = $avatar; // Cache lại trong session
            
            return $avatar;
        }
        
        return 'default-avatar.jpg';
    }
}
