<?php
// filepath: c:\laragon\www\Project_4\app\controllers\UserController.php

require_once 'app/models/UserModel.php';
require_once 'app/helpers/SessionHelper.php';

class UserController {
    private $userModel;
    
    public function __construct($db) {
        $this->userModel = new UserModel($db);
    }
    
    /**
     * Hiển thị form đăng ký
     */
    public function showRegister() {
        // Redirect nếu đã đăng nhập
        SessionHelper::redirectIfLoggedIn();
        
        $pageTitle = 'Đăng ký tài khoản';
        include 'app/views/user/register.php';
    }
    
    /**
     * Xử lý đăng ký
     */
    public function register() {
        // Redirect nếu đã đăng nhập
        SessionHelper::redirectIfLoggedIn();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Project_4/User/showRegister');
            exit();
        }
        
        // Verify CSRF token
        if (!SessionHelper::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            SessionHelper::setError('Token bảo mật không hợp lệ!');
            header('Location: /Project_4/User/showRegister');
            exit();
        }
        
        // Validate input
        $errors = $this->validateRegisterData($_POST);
        
        if (!empty($errors)) {
            SessionHelper::setError(implode('<br>', $errors));
            header('Location: /Project_4/User/showRegister');
            exit();
        }
        
        // Kiểm tra email và username đã tồn tại
        if ($this->userModel->emailExists($_POST['email'])) {
            SessionHelper::setError('Email đã được sử dụng!');
            header('Location: /Project_4/User/showRegister');
            exit();
        }
        
        if ($this->userModel->usernameExists($_POST['username'])) {
            SessionHelper::setError('Tên đăng nhập đã được sử dụng!');
            header('Location: /Project_4/User/showRegister');
            exit();
        }
        
        // Tạo user mới
        $userData = [
            'username' => trim($_POST['username']),
            'email' => trim($_POST['email']),
            'password' => $this->userModel->hashPassword($_POST['password']),
            'full_name' => trim($_POST['full_name']),
            'phone' => trim($_POST['phone'] ?? ''),
            'address' => trim($_POST['address'] ?? '')
        ];
        
        $userId = $this->userModel->createUser($userData);
        
        if ($userId) {
            SessionHelper::setSuccess('Đăng ký thành công! Bạn có thể đăng nhập ngay bây giờ.');
            header('Location: /Project_4/User/showLogin');
        } else {
            SessionHelper::setError('Có lỗi xảy ra khi đăng ký. Vui lòng thử lại!');
            header('Location: /Project_4/User/showRegister');
        }
        
        exit();
    }
    
    /**
     * Hiển thị form đăng nhập
     */
    public function showLogin() {
        // Redirect nếu đã đăng nhập
        SessionHelper::redirectIfLoggedIn();
        
        $pageTitle = 'Đăng nhập';
        include 'app/views/user/login.php';
    }
    
    /**
     * Xử lý đăng nhập
     */
    public function login() {
        // Redirect nếu đã đăng nhập
        SessionHelper::redirectIfLoggedIn();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Project_4/User/showLogin');
            exit();
        }
        
        // Verify CSRF token
        if (!SessionHelper::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            SessionHelper::setError('Token bảo mật không hợp lệ!');
            header('Location: /Project_4/User/showLogin');
            exit();
        }
        
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // Validate input
        if (empty($email) || empty($password)) {
            SessionHelper::setError('Vui lòng nhập đầy đủ email và mật khẩu!');
            header('Location: /Project_4/User/showLogin');
            exit();
        }
        
        // Kiểm tra tài khoản có bị khóa không
        if ($this->userModel->isAccountLocked($email)) {
            SessionHelper::setError('Tài khoản đã bị khóa tạm thời do đăng nhập sai nhiều lần. Vui lòng thử lại sau!');
            header('Location: /Project_4/User/showLogin');
            exit();
        }
        
        // Lấy thông tin user
        $user = $this->userModel->getUserByEmail($email);
        
        if (!$user) {
            $this->userModel->incrementFailedLoginAttempts($email);
            SessionHelper::setError('Email hoặc mật khẩu không đúng!');
            header('Location: /Project_4/User/showLogin');
            exit();
        }
        
        // Verify password
        if (!$this->userModel->verifyPassword($password, $user['password'])) {
            $this->userModel->incrementFailedLoginAttempts($email);
            
            // Khóa tài khoản nếu đăng nhập sai quá nhiều lần
            if ($user['failed_login_attempts'] >= 4) {
                $this->userModel->lockAccount($email, 15); // Khóa 15 phút
                SessionHelper::setError('Tài khoản đã bị khóa 15 phút do đăng nhập sai quá nhiều lần!');
            } else {
                SessionHelper::setError('Email hoặc mật khẩu không đúng!');
            }
            
            header('Location: /Project_4/User/showLogin');
            exit();
        }
        
        // Đăng nhập thành công
        SessionHelper::login($user);
        $this->userModel->updateLastLogin($user['id']);
        
        SessionHelper::setSuccess('Đăng nhập thành công!');
          // Redirect based on role
        if ($user['role'] === 'admin') {
            header('Location: /Project_4/Product/');
        } else {
            header('Location: /Project_4/');
        }
        
        exit();
    }
    
    /**
     * Đăng xuất
     */
    public function logout() {
        SessionHelper::logout();
        SessionHelper::setSuccess('Đăng xuất thành công!');
        header('Location: /Project_4/');
        exit();
    }
    
    /**
     * Hiển thị trang profile
     */
    public function profile() {
        SessionHelper::requireLogin();
        
        $pageTitle = 'Thông tin tài khoản';
        $user = $this->userModel->getUserById(SessionHelper::getUserId());
        
        if (!$user) {
            SessionHelper::setError('Không tìm thấy thông tin tài khoản!');
            header('Location: /Project_4/');
            exit();
        }
        
        // Xử lý cập nhật thông tin
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleProfileUpdate($user);
            return;
        }
        
        include 'app/views/user/profile.php';
    }
    
    /**
     * Xử lý upload avatar
     */
    private function handleAvatarUpload($userId) {
        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $file = $_FILES['avatar'];
        $fileName = $file['name'];
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Kiểm tra định dạng file
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileType, $allowedTypes)) {
            SessionHelper::setError('Chỉ chấp nhận file ảnh định dạng JPG, PNG, GIF!');
            return false;
        }

        // Kiểm tra kích thước file (giới hạn 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            SessionHelper::setError('Kích thước file không được vượt quá 5MB!');
            return false;
        }

        // Tạo tên file mới để tránh trùng lặp
        $newFileName = uniqid() . '_' . $fileName;
        $uploadPath = 'public/uploads/avatars/';
        
        // Tạo thư mục nếu chưa tồn tại
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $filePath = $uploadPath . $newFileName;

        // Upload file
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            // Xóa avatar cũ nếu có
            $oldAvatar = $this->userModel->getAvatar($userId);
            if ($oldAvatar != 'default-avatar.jpg' && file_exists($uploadPath . $oldAvatar)) {
                unlink($uploadPath . $oldAvatar);
            }

            // Cập nhật đường dẫn avatar mới trong database
            if ($this->userModel->updateAvatar($userId, $newFileName)) {
                // Cập nhật avatar trong session
                $_SESSION['avatar'] = $newFileName;
                return true;
            }

            return false;
        }

        return false;
    }

    /**
     * Xử lý đổi email
     */
    private function handleEmailUpdate($userId) {
        try {
            $newEmail = trim($_POST['new_email'] ?? '');
            error_log("Attempting to update email for user $userId. New email: " . $newEmail);
            
            // Validate email
            if (empty($newEmail)) {
                SessionHelper::setError('Vui lòng nhập email mới!');
                return false;
            }
            
            if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                SessionHelper::setError('Email không đúng định dạng!');
                return false;
            }

            // Kiểm tra email mới có trùng với email hiện tại không
            $currentUser = $this->userModel->getUserById($userId);
            if ($currentUser['email'] === $newEmail) {
                SessionHelper::setError('Email mới không được trùng với email hiện tại!');
                return false;
            }

            // Kiểm tra email đã tồn tại chưa
            if ($this->userModel->emailExists($newEmail)) {
                SessionHelper::setError('Email này đã được sử dụng bởi người dùng khác!');
                return false;
            }

            // Cập nhật email
            if ($this->userModel->updateEmail($userId, $newEmail)) {
                error_log("Email updated successfully for user $userId");
                SessionHelper::setSuccess('Cập nhật email thành công! Vui lòng đăng nhập lại với email mới.');
                SessionHelper::logout();
                header('Location: /Project_4/User/showLogin');
                exit();
            } else {
                SessionHelper::setError('Có lỗi xảy ra khi cập nhật email!');
                return false;
            }
        } catch (Exception $e) {
            error_log("Error in handleEmailUpdate: " . $e->getMessage());
            SessionHelper::setError('Có lỗi xảy ra khi cập nhật email!');
            return false;
        }
    }

    /**
     * Xử lý cập nhật profile
     */
    private function handleProfileUpdate($user) {
        // Verify CSRF token
        if (!SessionHelper::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            SessionHelper::setError('Token bảo mật không hợp lệ!');
            header('Location: /Project_4/User/profile');
            exit();
        }
        
        $action = $_POST['action'] ?? '';
        
        if ($action === 'update_info') {
            $this->updateUserInfo($user);
        } elseif ($action === 'change_password') {
            $this->changeUserPassword($user);
        } elseif ($action === 'update_avatar') {
            if ($this->handleAvatarUpload($user['id'])) {
                SessionHelper::setSuccess('Cập nhật ảnh đại diện thành công!');
            } else {
                SessionHelper::setError('Có lỗi xảy ra khi cập nhật ảnh đại diện!');
            }
            header('Location: /Project_4/User/profile');
            exit();
        } elseif ($action === 'update_email') {
            $this->handleEmailUpdate($user['id']);
            header('Location: /Project_4/User/profile');
            exit();
        }
    }
    
    /**
     * Cập nhật thông tin user
     */
    private function updateUserInfo($user) {
        $errors = $this->validateProfileData($_POST);
        
        if (!empty($errors)) {
            SessionHelper::setError(implode('<br>', $errors));
            header('Location: /Project_4/User/profile');
            exit();
        }
        
        $userData = [
            'full_name' => trim($_POST['full_name']),
            'phone' => trim($_POST['phone'] ?? ''),
            'address' => trim($_POST['address'] ?? '')
        ];
        
        if ($this->userModel->updateUser($user['id'], $userData)) {
            // Cập nhật session
            $_SESSION['full_name'] = $userData['full_name'];
            SessionHelper::setSuccess('Cập nhật thông tin thành công!');
        } else {
            SessionHelper::setError('Có lỗi xảy ra khi cập nhật thông tin!');
        }
        
        header('Location: /Project_4/User/profile');
        exit();
    }
    
    /**
     * Đổi mật khẩu
     */
    private function changeUserPassword($user) {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validate input
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            SessionHelper::setError('Vui lòng nhập đầy đủ thông tin mật khẩu!');
            header('Location: /Project_4/User/profile');
            exit();
        }
        
        // Verify current password
        if (!$this->userModel->verifyPassword($currentPassword, $user['password'])) {
            SessionHelper::setError('Mật khẩu hiện tại không đúng!');
            header('Location: /Project_4/User/profile');
            exit();
        }
        
        // Validate new password
        if (strlen($newPassword) < 6) {
            SessionHelper::setError('Mật khẩu mới phải có ít nhất 6 ký tự!');
            header('Location: /Project_4/User/profile');
            exit();
        }
        
        if ($newPassword !== $confirmPassword) {
            SessionHelper::setError('Mật khẩu xác nhận không khớp!');
            header('Location: /Project_4/User/profile');
            exit();
        }
        
        // Update password
        $hashedPassword = $this->userModel->hashPassword($newPassword);
        
        if ($this->userModel->changePassword($user['id'], $hashedPassword)) {
            SessionHelper::setSuccess('Đổi mật khẩu thành công!');
        } else {
            SessionHelper::setError('Có lỗi xảy ra khi đổi mật khẩu!');
        }
        
        header('Location: /Project_4/User/profile');
        exit();
    }
    
    /**
     * Validate dữ liệu đăng ký
     */
    private function validateRegisterData($data) {
        $errors = [];
        
        // Username
        if (empty(trim($data['username'] ?? ''))) {
            $errors[] = 'Tên đăng nhập không được để trống';
        } elseif (strlen(trim($data['username'])) < 3) {
            $errors[] = 'Tên đăng nhập phải có ít nhất 3 ký tự';
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($data['username']))) {
            $errors[] = 'Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới';
        }
        
        // Email
        if (empty(trim($data['email'] ?? ''))) {
            $errors[] = 'Email không được để trống';
        } elseif (!filter_var(trim($data['email']), FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email không hợp lệ';
        }
        
        // Password
        if (empty($data['password'] ?? '')) {
            $errors[] = 'Mật khẩu không được để trống';
        } elseif (strlen($data['password']) < 6) {
            $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
        }
        
        // Confirm password
        if (empty($data['confirm_password'] ?? '')) {
            $errors[] = 'Vui lòng xác nhận mật khẩu';
        } elseif ($data['password'] !== $data['confirm_password']) {
            $errors[] = 'Mật khẩu xác nhận không khớp';
        }
        
        // Full name
        if (empty(trim($data['full_name'] ?? ''))) {
            $errors[] = 'Họ tên không được để trống';
        } elseif (strlen(trim($data['full_name'])) < 2) {
            $errors[] = 'Họ tên phải có ít nhất 2 ký tự';
        }
        
        return $errors;
    }
    
    /**
     * Validate dữ liệu profile
     */
    private function validateProfileData($data) {
        $errors = [];
        
        // Full name
        if (empty(trim($data['full_name'] ?? ''))) {
            $errors[] = 'Họ tên không được để trống';
        } elseif (strlen(trim($data['full_name'])) < 2) {
            $errors[] = 'Họ tên phải có ít nhất 2 ký tự';
        }
        
        // Phone (optional)
        if (!empty(trim($data['phone'] ?? ''))) {
            if (!preg_match('/^[0-9+\-\s()]+$/', trim($data['phone']))) {
                $errors[] = 'Số điện thoại không hợp lệ';
            }
        }
        
        return $errors;
    }
    
    /**
     * Hiển thị danh sách người dùng (Admin)
     */
    public function list() {
        // Kiểm tra quyền admin
        SessionHelper::requireLogin();
        if (!SessionHelper::isAdmin()) {
            SessionHelper::setError('Bạn không có quyền truy cập trang này!');
            header('Location: /Project_4/');
            exit();
        }
        
        $pageTitle = 'Quản lý người dùng';
        $users = $this->userModel->getAllUsers();
        include 'app/views/user/list.php';
    }
    
    /**
     * Hiển thị form thêm người dùng mới (Admin)
     */
    public function add() {
        // Kiểm tra quyền admin
        SessionHelper::requireLogin();
        if (!SessionHelper::isAdmin()) {
            SessionHelper::setError('Bạn không có quyền truy cập trang này!');
            header('Location: /Project_4/');
            exit();
        }
        
        $pageTitle = 'Thêm người dùng mới';
        include 'app/views/user/add.php';
    }
    
    /**
     * Xử lý thêm người dùng mới (Admin)
     */
    public function create() {
        // Kiểm tra quyền admin
        SessionHelper::requireLogin();
        if (!SessionHelper::isAdmin()) {
            SessionHelper::setError('Bạn không có quyền thực hiện thao tác này!');
            header('Location: /Project_4/');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Project_4/User/add');
            exit();
        }
        
        // Verify CSRF token
        if (!SessionHelper::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            SessionHelper::setError('Token bảo mật không hợp lệ!');
            header('Location: /Project_4/User/add');
            exit();
        }
        
        // Validate input
        $errors = $this->validateRegisterData($_POST);
        
        if (!empty($errors)) {
            SessionHelper::setError(implode('<br>', $errors));
            header('Location: /Project_4/User/add');
            exit();
        }
        
        // Kiểm tra email và username đã tồn tại
        if ($this->userModel->emailExists($_POST['email'])) {
            SessionHelper::setError('Email đã được sử dụng!');
            header('Location: /Project_4/User/add');
            exit();
        }
        
        if ($this->userModel->usernameExists($_POST['username'])) {
            SessionHelper::setError('Tên đăng nhập đã được sử dụng!');
            header('Location: /Project_4/User/add');
            exit();
        }
        
        // Tạo user mới
        $userData = [
            'username' => trim($_POST['username']),
            'email' => trim($_POST['email']),
            'password' => $this->userModel->hashPassword($_POST['password']),
            'full_name' => trim($_POST['full_name']),
            'phone' => trim($_POST['phone'] ?? ''),
            'address' => trim($_POST['address'] ?? '')
        ];
        
        $userId = $this->userModel->createUser($userData);
        
        if ($userId) {
            // Cập nhật vai trò nếu được chọn
            if (isset($_POST['role']) && $_POST['role'] === 'admin') {
                $this->userModel->updateUserRole($userId, 'admin');
            }
            
            SessionHelper::setSuccess('Thêm người dùng mới thành công!');
            header('Location: /Project_4/User/list');
        } else {
            SessionHelper::setError('Có lỗi xảy ra khi thêm người dùng. Vui lòng thử lại!');
            header('Location: /Project_4/User/add');
        }
        
        exit();
    }
    
    /**
     * Hiển thị form chỉnh sửa người dùng (Admin)
     */
    public function edit($id) {
        // Kiểm tra quyền admin
        SessionHelper::requireLogin();
        if (!SessionHelper::isAdmin()) {
            SessionHelper::setError('Bạn không có quyền truy cập trang này!');
            header('Location: /Project_4/');
            exit();
        }
        
        $user = $this->userModel->getUserById($id);
        
        if (!$user) {
            SessionHelper::setError('Không tìm thấy người dùng!');
            header('Location: /Project_4/User/list');
            exit();
        }
        
        $pageTitle = 'Chỉnh sửa người dùng';
        include 'app/views/user/edit.php';
    }
    
    /**
     * Cập nhật thông tin người dùng (Admin)
     */
    public function update($id) {
        // Kiểm tra quyền admin
        SessionHelper::requireLogin();
        if (!SessionHelper::isAdmin()) {
            SessionHelper::setError('Bạn không có quyền thực hiện thao tác này!');
            header('Location: /Project_4/');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Project_4/User/edit/' . $id);
            exit();
        }
        
        // Verify CSRF token
        if (!SessionHelper::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            SessionHelper::setError('Token bảo mật không hợp lệ!');
            header('Location: /Project_4/User/edit/' . $id);
            exit();
        }
        
        // Validate input
        $errors = $this->validateProfileData($_POST);
        
        if (!empty($errors)) {
            SessionHelper::setError(implode('<br>', $errors));
            header('Location: /Project_4/User/edit/' . $id);
            exit();
        }
        
        $userData = [
            'full_name' => trim($_POST['full_name']),
            'phone' => trim($_POST['phone'] ?? ''),
            'address' => trim($_POST['address'] ?? '')
        ];
        
        // Lấy thông tin người dùng hiện tại
        $currentUser = $this->userModel->getUserById($id);
        
        // Kiểm tra nếu email đã thay đổi
        if (isset($_POST['email']) && $_POST['email'] !== $currentUser['email']) {
            // Kiểm tra email mới có trùng với người dùng khác không
            if ($this->userModel->emailExists($_POST['email'])) {
                SessionHelper::setError('Email đã được sử dụng bởi tài khoản khác!');
                header('Location: /Project_4/User/edit/' . $id);
                exit();
            }
            
            // Cập nhật email
            if (!$this->userModel->updateEmail($id, trim($_POST['email']))) {
                SessionHelper::setError('Có lỗi xảy ra khi cập nhật email!');
                header('Location: /Project_4/User/edit/' . $id);
                exit();
            }
        }
        
        if ($this->userModel->updateUser($id, $userData)) {
            // Cập nhật vai trò nếu được chỉ định
            if (isset($_POST['role'])) {
                $this->userModel->updateUserRole($id, $_POST['role']);
            }
            
            SessionHelper::setSuccess('Cập nhật thông tin người dùng thành công!');
        } else {
            SessionHelper::setError('Có lỗi xảy ra khi cập nhật thông tin!');
        }
        
        header('Location: /Project_4/User/list');
        exit();
    }
    
    /**
     * Xóa người dùng (Admin)
     */
    public function delete($id) {
        // Kiểm tra quyền admin
        SessionHelper::requireLogin();
        if (!SessionHelper::isAdmin()) {
            SessionHelper::setError('Bạn không có quyền thực hiện thao tác này!');
            header('Location: /Project_4/');
            exit();
        }
        
        // Không cho phép xóa tài khoản admin đang đăng nhập
        if ($id == SessionHelper::getUserId()) {
            SessionHelper::setError('Không thể xóa tài khoản của chính mình!');
            header('Location: /Project_4/User/list');
            exit();
        }
        
        $user = $this->userModel->getUserById($id);
        
        if (!$user) {
            SessionHelper::setError('Không tìm thấy người dùng!');
            header('Location: /Project_4/User/list');
            exit();
        }
        
        if ($this->userModel->deleteUser($id)) {
            SessionHelper::setSuccess('Xóa người dùng thành công!');
        } else {
            SessionHelper::setError('Có lỗi xảy ra khi xóa người dùng!');
        }
        
        header('Location: /Project_4/User/list');
        exit();
    }
}
