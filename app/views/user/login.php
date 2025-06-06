<?php
// filepath: c:\laragon\www\Project_4\app\views\user\login.php

require_once 'app/helpers/SessionHelper.php';
include 'app/views/shares/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fas fa-sign-in-alt"></i> Đăng nhập</h4>
                </div>
                <div class="card-body">
                    <!-- Flash Messages -->
                    <?php
                    $flashMessages = SessionHelper::getFlashMessages();
                    foreach ($flashMessages as $message):
                    ?>
                        <div class="alert alert-<?php echo $message['type'] === 'error' ? 'danger' : $message['type']; ?> alert-dismissible fade show" role="alert">
                            <?php echo $message['message']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endforeach; ?>

                    <form method="POST" action="/Project_4/User/login" id="loginForm">
                        <!-- CSRF Token -->
                        <input type="hidden" name="csrf_token" value="<?php echo SessionHelper::getCSRFToken(); ?>">
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" required
                                       placeholder="Nhập địa chỉ email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" required
                                       placeholder="Nhập mật khẩu">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="rememberMe" name="remember_me">
                            <label class="form-check-label" for="rememberMe">
                                Ghi nhớ đăng nhập
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-sign-in-alt"></i> Đăng nhập
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <p><a href="#" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">Quên mật khẩu?</a></p>
                        <p>Chưa có tài khoản? <a href="/Project_4/User/showRegister">Đăng ký ngay</a></p>
                    </div>

                    <!-- Demo Accounts Info -->
                    <div class="alert alert-info mt-3">
                        <h6><i class="fas fa-info-circle"></i> Tài khoản demo:</h6>
                        <p class="mb-1"><strong>Admin:</strong> admin@mystore.com / password</p>
                        <p class="mb-0"><strong>User:</strong> user1@gmail.com / password</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Forgot Password Modal -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quên mật khẩu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Chức năng khôi phục mật khẩu sẽ được cập nhật trong phiên bản tiếp theo.</p>
                <p>Hiện tại, vui lòng liên hệ quản trị viên để được hỗ trợ.</p>
                
                <div class="alert alert-warning">
                    <strong>Liên hệ:</strong><br>
                    Email: admin@mystore.com<br>
                    Phone: 0901234567
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle password visibility
document.getElementById('togglePassword').addEventListener('click', function() {
    const password = document.getElementById('password');
    const icon = this.querySelector('i');
    
    if (password.type === 'password') {
        password.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        password.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});

// Form validation
document.getElementById('loginForm').addEventListener('submit', function(e) {
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    
    if (!email || !password) {
        e.preventDefault();
        alert('Vui lòng nhập đầy đủ email và mật khẩu!');
        return false;
    }
    
    // Simple email validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        e.preventDefault();
        alert('Email không hợp lệ!');
        return false;
    }
});

// Auto-fill demo account
function fillDemoAccount(type) {
    if (type === 'admin') {
        document.getElementById('email').value = 'admin@mystore.com';
        document.getElementById('password').value = 'password';
    } else if (type === 'user') {
        document.getElementById('email').value = 'user1@gmail.com';
        document.getElementById('password').value = 'password';
    }
}

// Add click handlers to demo account info
document.addEventListener('DOMContentLoaded', function() {
    const adminText = document.querySelector('.alert-info p:nth-child(2)');
    const userText = document.querySelector('.alert-info p:nth-child(3)');
    
    if (adminText) {
        adminText.style.cursor = 'pointer';
        adminText.addEventListener('click', () => fillDemoAccount('admin'));
    }
    
    if (userText) {
        userText.style.cursor = 'pointer';
        userText.addEventListener('click', () => fillDemoAccount('user'));
    }
});
</script>

<?php include 'app/views/shares/footer.php'; ?>
