<?php
// filepath: c:\laragon\www\Project_4\app\models\UserModel.php

class UserModel {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Tạo user mới
     */
    public function createUser($userData) {
        try {
            $sql = "INSERT INTO users (username, email, password, full_name, phone, address) 
                    VALUES (:username, :email, :password, :full_name, :phone, :address)";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $userData['username']);
            $stmt->bindParam(':email', $userData['email']);
            $stmt->bindParam(':password', $userData['password']);
            $stmt->bindParam(':full_name', $userData['full_name']);
            $stmt->bindParam(':phone', $userData['phone']);
            $stmt->bindParam(':address', $userData['address']);
            
            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            }
            return false;
            
        } catch(PDOException $e) {
            error_log("Error creating user: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Tìm user theo email
     */
    public function getUserByEmail($email) {
        try {
            $sql = "SELECT * FROM users WHERE email = :email AND is_active = 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch(PDOException $e) {
            error_log("Error getting user by email: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Tìm user theo username
     */
    public function getUserByUsername($username) {
        try {
            $sql = "SELECT * FROM users WHERE username = :username AND is_active = 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch(PDOException $e) {
            error_log("Error getting user by username: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Tìm user theo ID
     */
    public function getUserById($id) {
        try {
            $sql = "SELECT * FROM users WHERE id = :id AND is_active = 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch(PDOException $e) {
            error_log("Error getting user by ID: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Kiểm tra email đã tồn tại chưa
     */
    public function emailExists($email) {
        try {
            $sql = "SELECT id FROM users WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            return $stmt->rowCount() > 0;
            
        } catch(PDOException $e) {
            error_log("Error checking email exists: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Kiểm tra username đã tồn tại chưa
     */
    public function usernameExists($username) {
        try {
            $sql = "SELECT id FROM users WHERE username = :username";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            
            return $stmt->rowCount() > 0;
            
        } catch(PDOException $e) {
            error_log("Error checking username exists: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Cập nhật last login
     */
    public function updateLastLogin($userId) {
        try {
            $sql = "UPDATE users SET last_login = CURRENT_TIMESTAMP, failed_login_attempts = 0 WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            
            return $stmt->execute();
            
        } catch(PDOException $e) {
            error_log("Error updating last login: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Tăng số lần đăng nhập thất bại
     */
    public function incrementFailedLoginAttempts($email) {
        try {
            $sql = "UPDATE users SET failed_login_attempts = failed_login_attempts + 1 WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            
            return $stmt->execute();
            
        } catch(PDOException $e) {
            error_log("Error incrementing failed login attempts: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Khóa tài khoản tạm thời
     */
    public function lockAccount($email, $lockDuration = 15) {
        try {
            $lockUntil = date('Y-m-d H:i:s', time() + ($lockDuration * 60));
            $sql = "UPDATE users SET locked_until = :locked_until WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':locked_until', $lockUntil);
            $stmt->bindParam(':email', $email);
            
            return $stmt->execute();
            
        } catch(PDOException $e) {
            error_log("Error locking account: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Kiểm tra tài khoản có bị khóa không
     */
    public function isAccountLocked($email) {
        try {
            $sql = "SELECT locked_until FROM users WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user && $user['locked_until']) {
                return strtotime($user['locked_until']) > time();
            }
            
            return false;
            
        } catch(PDOException $e) {
            error_log("Error checking account lock: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Cập nhật thông tin user
     */
    public function updateUser($userId, $userData) {
        try {
            $sql = "UPDATE users SET full_name = :full_name, phone = :phone, address = :address, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':full_name', $userData['full_name']);
            $stmt->bindParam(':phone', $userData['phone']);
            $stmt->bindParam(':address', $userData['address']);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            
            return $stmt->execute();
            
        } catch(PDOException $e) {
            error_log("Error updating user: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Đổi mật khẩu
     */
    public function changePassword($userId, $newPassword) {
        try {
            $sql = "UPDATE users SET password = :password, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':password', $newPassword);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            
            return $stmt->execute();
            
        } catch(PDOException $e) {
            error_log("Error changing password: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Hash password
     */
    public function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    /**
     * Verify password
     */
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    /**
     * Lấy danh sách tất cả người dùng
     */
    public function getAllUsers() {
        try {
            $sql = "SELECT * FROM users ORDER BY id ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch(PDOException $e) {
            error_log("Error getting all users: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Xóa người dùng
     */
    public function deleteUser($userId) {
        try {
            // Use soft delete by setting is_active = 0
            $sql = "UPDATE users SET is_active = 0, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            
            return $stmt->execute();
            
        } catch(PDOException $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Cập nhật vai trò người dùng
     */
    public function updateUserRole($userId, $role) {
        try {
            $sql = "UPDATE users SET role = :role, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            
            return $stmt->execute();
            
        } catch(PDOException $e) {
            error_log("Error updating user role: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật avatar của người dùng
     */
    public function updateAvatar($userId, $avatarPath) {
        try {
            $sql = "UPDATE users SET avatar = :avatar WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':avatar', $avatarPath);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error updating avatar: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật email người dùng
     */
    public function updateEmail($userId, $newEmail) {
        try {
            // Kiểm tra email mới có trùng với người dùng khác không
            $sql = "SELECT id FROM users WHERE email = :email AND id != :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $newEmail);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                error_log("Email already exists: " . $newEmail);
                return false; // Email đã tồn tại
            }

            // Cập nhật email mới
            $sql = "UPDATE users SET email = :email, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $newEmail);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            
            $result = $stmt->execute();
            if ($result) {
                error_log("Email updated successfully for user ID: " . $userId);
            } else {
                error_log("Failed to update email for user ID: " . $userId);
            }
            return $result;
            
        } catch(PDOException $e) {
            error_log("Error updating email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy avatar của người dùng
     */
    public function getAvatar($userId) {
        try {
            $sql = "SELECT avatar FROM users WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['avatar'] : 'default-avatar.jpg';
        } catch(PDOException $e) {
            error_log("Error getting avatar: " . $e->getMessage());
            return 'default-avatar.jpg';
        }
    }
}
