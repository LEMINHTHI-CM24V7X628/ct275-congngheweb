<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../../config.php';

// ✅ Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: /?page=login');
    exit;
}

// ✅ Lấy danh sách đơn hàng
try {
    $sql = "
        SELECT o.id, o.total, o.created_at, o.status
        FROM orders o
        ORDER BY o.created_at DESC
    ";
    $stmt = $pdo->query($sql);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("<div class='alert alert-danger text-center mt-5'>
        ⚠️ Lỗi truy vấn: " . htmlspecialchars($e->getMessage()) . "
    </div>");
}

include __DIR__ . '/../layouts/header.php';
?>

<div class="container py-5">
  <h3 class="text-center fw-bold text-white bg-danger p-3 rounded shadow">
    <i class="bi bi-cart4"></i> Quản lý giỏ hàng (Đơn hàng)
  </h3>

  <?php if (empty($orders)): ?>
    <div class="alert alert-info text-center mt-4">
      Chưa có đơn hàng nào trong hệ thống.
    </div>
  <?php else: ?>
    <table class="table table-hover align-middle mt-4 shadow-sm bg-white rounded">
      <thead class="table-danger">
        <tr class="text-center">
          <th>ID</th>
          <th>Ngày tạo</th>
          <th>Tổng tiền</th>
          <th>Trạng thái</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $o): ?>
          <tr class="text-center">
            <td><?= htmlspecialchars($o['id']) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></td>
            <td class="fw-bold text-danger"><?= number_format($o['total'], 0, ',', '.') ?> đ</td>
            <td>
              <span class="badge 
                <?= $o['status'] === 'Hoàn tất' ? 'bg-success' : 
                   ($o['status'] === 'Đang xử lý' ? 'bg-warning text-dark' : 'bg-secondary') ?>">
                <?= htmlspecialchars($o['status'] ?? 'Chưa xác định') ?>
              </span>
            </td>
            <td>
              <a href="/?page=admin-order-detail&id=<?= $o['id'] ?>" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-eye"></i> Xem
              </a>
              <a href="/?page=admin-delete-order&id=<?= $o['id'] ?>"
                 class="btn btn-sm btn-outline-danger"
                 onclick="return confirm('Bạn chắc chắn muốn xóa đơn hàng này?')">
                <i class="bi bi-trash"></i> Xóa
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
