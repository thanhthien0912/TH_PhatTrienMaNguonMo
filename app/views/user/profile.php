<?php
// filepath: c:\laragon\www\Project_4\app\views\user\profile.php

require_once 'app/helpers/SessionHelper.php';
include 'app/views/shares/header.php';
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">
            <!-- User Info Sidebar -->
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <?php if (!empty($user['avatar']) && file_exists('public/uploads/avatars/' . $user['avatar'])): ?>
                            <img src="/Project_4/public/uploads/avatars/<?php echo htmlspecialchars($user['avatar']); ?>" 
                                 alt="Avatar" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        <?php else: ?>
                            <i class="fas fa-user-circle fa-5x text-secondary"></i>
                        <?php endif; ?>
                    </div>
                    <form action="/Project_4/User/profile" method="POST" enctype="multipart/form-data" class="mb-3">
                        <input type="hidden" name="csrf_token" value="<?php echo SessionHelper::getCSRFToken(); ?>">
                        <input type="hidden" name="action" value="update_avatar">
                        <div class="mb-2">
                            <input type="file" class="form-control form-control-sm" name="avatar" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-upload"></i> Cập nhật ảnh đại diện
                        </button>
                    </form>
                    <h5><?php echo htmlspecialchars($user['full_name']); ?></h5>
                    <p class="text-muted">@<?php echo htmlspecialchars($user['username']); ?></p>
                    <span class="badge bg-<?php echo $user['role'] === 'admin' ? 'danger' : 'primary'; ?>">
                        <?php echo $user['role'] === 'admin' ? 'Quản trị viên' : 'Người dùng'; ?>
                    </span>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Thống kê</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Ngày tham gia:</span>
                        <small><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></small>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Lần cuối đăng nhập:</span>
                        <small><?php echo $user['last_login'] ? date('d/m/Y H:i', strtotime($user['last_login'])) : 'Chưa có'; ?></small>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Trạng thái:</span>
                        <span class="badge bg-success">Hoạt động</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
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

            <!-- Profile Tabs -->
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="profileTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">
                                <i class="fas fa-user"></i> Thông tin cá nhân
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab">
                                <i class="fas fa-lock"></i> Đổi mật khẩu
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">
                                <i class="fas fa-shield-alt"></i> Bảo mật
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="profileTabsContent">
                        <!-- Personal Information Tab -->
                        <div class="tab-pane fade show active" id="info" role="tabpanel">
                            <form method="POST" action="/Project_4/User/profile">
                                <input type="hidden" name="csrf_token" value="<?php echo SessionHelper::getCSRFToken(); ?>">
                                <input type="hidden" name="action" value="update_info">
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Tên đăng nhập</label>
                                            <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                                            <div class="form-text">Tên đăng nhập không thể thay đổi</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                                            <div class="form-text">Email không thể thay đổi</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" required
                                           value="<?php echo htmlspecialchars($user['full_name']); ?>">
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Số điện thoại</label>
                                            <input type="tel" class="form-control" id="phone" name="phone"
                                                   value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="role" class="form-label">Vai trò</label>
                                            <input type="text" class="form-control" id="role" 
                                                   value="<?php echo $user['role'] === 'admin' ? 'Quản trị viên' : 'Người dùng'; ?>" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Địa chỉ</label>
                                    <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Cập nhật thông tin
                                </button>
                            </form>
                        </div>

                        <!-- Password Tab -->
                        <div class="tab-pane fade" id="password" role="tabpanel">
                            <form method="POST" action="/Project_4/User/profile" id="changePasswordForm">
                                <input type="hidden" name="csrf_token" value="<?php echo SessionHelper::getCSRFToken(); ?>">
                                <input type="hidden" name="action" value="change_password">
                                
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('current_password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Mật khẩu mới <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('new_password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Tối thiểu 6 ký tự</div>
                                </div>

                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('confirm_password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <strong>Lưu ý:</strong> Sau khi đổi mật khẩu, bạn sẽ cần đăng nhập lại với mật khẩu mới.
                                </div>

                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-key"></i> Đổi mật khẩu
                                </button>
                            </form>
                        </div>

                        <!-- Security Tab -->
                        <div class="tab-pane fade" id="security" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Thông tin bảo mật</h6>
                                    <div class="list-group list-group-flush">
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-envelope text-primary"></i>
                                                <span class="ms-2">Email đã xác thực</span>
                                            </div>
                                            <span class="badge bg-<?php echo $user['email_verified'] ? 'success' : 'warning'; ?>">
                                                <?php echo $user['email_verified'] ? 'Đã xác thực' : 'Chưa xác thực'; ?>
                                            </span>
                                        </div>
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-shield-alt text-success"></i>
                                                <span class="ms-2">Tài khoản hoạt động</span>
                                            </div>
                                            <span class="badge bg-success">Kích hoạt</span>
                                        </div>
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-clock text-info"></i>
                                                <span class="ms-2">Lần cuối đăng nhập</span>
                                            </div>
                                            <small><?php echo $user['last_login'] ? date('d/m/Y H:i', strtotime($user['last_login'])) : 'Chưa có'; ?></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>Các thiết lập bảo mật</h6>
                                    <div class="alert alert-info">
                                        <h6><i class="fas fa-info-circle"></i> Các tính năng bảo mật:</h6>
                                        <ul class="mb-0">
                                            <li>Mã hóa mật khẩu bằng bcrypt</li>
                                            <li>CSRF protection cho form</li>
                                            <li>Khóa tài khoản khi đăng nhập sai nhiều lần</li>
                                            <li>Session security với regenerate ID</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle password visibility
function togglePasswordVisibility(fieldId) {
    const field = document.getElementById(fieldId);
    const button = field.nextElementSibling;
    const icon = button.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Xử lý tab
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo tabs
    var triggerTabList = [].slice.call(document.querySelectorAll('#profileTabs button'));
    triggerTabList.forEach(function(triggerEl) {
        triggerEl.addEventListener('click', function(event) {
            event.preventDefault();
            var tabTrigger = new bootstrap.Tab(triggerEl);
            tabTrigger.show();
        });
    });

    // Xử lý khi có hash trong URL
    if (window.location.hash) {
        const hash = window.location.hash.substring(1);
        const tab = document.querySelector(`#profileTabs button[data-bs-target="#${hash}"]`);
        if (tab) {
            var tabTrigger = new bootstrap.Tab(tab);
            tabTrigger.show();
        }
    }

    // Form validation for email change
    const emailForm = document.getElementById('emailChangeForm');
    if (emailForm) {
        emailForm.addEventListener('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            this.classList.add('was-validated');

            const newEmail = document.getElementById('new_email').value.trim();
            const currentEmail = document.getElementById('current_email_display').value.trim();

            if (newEmail === currentEmail) {
                e.preventDefault();
                alert('Email mới không được trùng với email hiện tại!');
                return false;
            }

            if (!confirm('Bạn có chắc chắn muốn đổi email? Bạn sẽ cần đăng nhập lại.')) {
                e.preventDefault();
                return false;
            }
        });
    }
});

// Form validation for password change
document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (newPassword !== confirmPassword) {
        e.preventDefault();
        alert('Mật khẩu xác nhận không khớp!');
        return false;
    }
    
    if (newPassword.length < 6) {
        e.preventDefault();
        alert('Mật khẩu mới phải có ít nhất 6 ký tự!');
        return false;
    }
    
    if (confirm('Bạn có chắc chắn muốn đổi mật khẩu?')) {
        return true;
    } else {
        e.preventDefault();
        return false;
    }
});
</script>

<?php include 'app/views/shares/footer.php'; ?>
