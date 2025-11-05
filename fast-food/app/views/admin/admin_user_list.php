<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-danger fw-bold m-0">
      <i class="bi bi-people"></i> Danh sách người dùng
    </h3>
    <a href="/?page=admin-orders" class="btn btn-outline-secondary">
      <i class="bi bi-arrow-left"></i> Trở lại Bảng điều khiển
    </a>
  </div>

  <?php if (!empty($users)): ?>
    <div class="table-responsive shadow-sm rounded-3">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-danger text-center">
          <tr>
            <th>ID</th>
            <th>Họ tên</th>
            <th>Số điện thoại</th>
            <th>Email</th>
            <th>Quyền</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
          </tr>
        </thead>
        <tbody class="text-center">
          <?php foreach ($users as $u): ?>
            <tr>
              <td><?= htmlspecialchars($u['id']) ?></td>
              <td><?= htmlspecialchars($u['full_name']) ?></td>
              <td><?= htmlspecialchars($u['phone']) ?></td>
              <td><?= htmlspecialchars($u['email']) ?></td>
              <td>
                <?= $u['role'] === 'admin' 
                    ? '<span class="badge bg-danger">Admin</span>' 
                    : '<span class="badge bg-secondary">User</span>' ?>
              </td>
              <td>
                <?= $u['status'] 
                    ? '<span class="text-success fw-semibold">Hoạt động</span>' 
                    : '<span class="text-muted">Khoá</span>' ?>
              </td>
              <td>
                <?php if ($u['role'] !== 'admin' && $u['phone'] !== '0999999999'): ?>
                  <a href="/?page=admin-delete-user&id=<?= $u['id'] ?>" 
                     class="btn btn-sm btn-outline-danger"
                     onclick="return confirm('Bạn có chắc muốn xoá người dùng này?')">
                     <i class="bi bi-trash"></i> Xoá
                  </a>
                <?php else: ?>
                  <span class="text-muted small">Không thể xoá</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-info text-center mt-4">
      <i class="bi bi-exclamation-circle"></i> Chưa có người dùng nào trong hệ thống.
    </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
