<?php
require_once 'app/helpers/SessionHelper.php';
include 'app/views/shares/header.php';
?>
<div class="container mt-5">
    <h1 class="mb-4">Danh sách người dùng</h1>
    <div class="mb-3">
        <a href="/Project_4/User/add" class="btn btn-success"><i class="bi bi-plus-circle"></i> Thêm người dùng mới</a>
    </div>
    <?php if (!empty($users)): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Tên đăng nhập</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Điện thoại</th>
                    <th>Địa chỉ</th>
                    <th>Vai trò</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['phone']); ?></td>
                    <td><?php echo htmlspecialchars($user['address']); ?></td>
                    <td><span class="badge bg-<?php echo $user['role'] === 'admin' ? 'danger' : 'primary'; ?>"><?php echo $user['role'] === 'admin' ? 'Quản trị viên' : 'Người dùng'; ?></span></td>
                    <td><span class="badge bg-<?php echo $user['is_active'] ? 'success' : 'secondary'; ?>"><?php echo $user['is_active'] ? 'Kích hoạt' : 'Vô hiệu'; ?></span></td>
                    <td>
                        <a href="/Project_4/User/edit/<?php echo $user['id']; ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i> Sửa</a>
                        <a href="/Project_4/User/delete/<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');"><i class="bi bi-trash"></i> Xóa</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <div class="alert alert-info">Chưa có người dùng nào.</div>
    <?php endif; ?>
</div>
<?php include 'app/views/shares/footer.php'; ?>
