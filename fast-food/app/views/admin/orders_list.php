<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container py-5">
  <h3 class="fw-bold text-danger mb-4"><i class="bi bi-list-ul"></i> Danh sách đơn hàng</h3>

  <?php if (empty($orders)): ?>
    <div class="alert alert-info text-center">Chưa có đơn hàng nào.</div>
  <?php else: ?>
    <table class="table table-hover text-center align-middle">
      <thead class="table-danger">
        <tr>
          <th>ID</th>
          <th>Khách hàng</th>
          <th>Số điện thoại</th>
          <th>Địa chỉ</th>
          <th>Tổng tiền</th>
          <th>Thời gian</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $order): ?>
          <tr>
            <td><?= (int)$order['id'] ?></td>
            <td><?= htmlspecialchars($order['fullname']) ?></td>
            <td><?= htmlspecialchars($order['phone']) ?></td>
            <td><?= htmlspecialchars($order['address']) ?></td>
            <td class="fw-bold text-danger"><?= number_format($order['total'], 0, ',', '.') ?> đ</td>
            <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
            <td>
              <a href="/?page=admin-order-detail&id=<?= (int)$order['id'] ?>" 
                 class="btn btn-sm btn-outline-primary">
                Xem chi tiết
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
