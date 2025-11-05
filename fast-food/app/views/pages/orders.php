<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-5">
  <div class="bg-white p-4 rounded-4 shadow-sm">
    <h3 class="fw-bold text-danger mb-4">
      <i class="bi bi-truck"></i> Theo dõi đơn hàng của bạn
    </h3>

    <?php if (empty($orders)): ?>
      <div class="text-center py-4 text-muted">Bạn chưa có đơn hàng nào.</div>
    <?php else: ?>
      <table class="table table-bordered text-center align-middle">
        <thead class="table-danger">
          <tr>
            <th>Mã đơn</th>
            <th>Ngày đặt</th>
            <th>Tổng tiền</th>
            <th>Tình trạng</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $order): ?>
            <tr>
              <td>#<?= htmlspecialchars($order['id']) ?></td>
              <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
              <td><?= number_format($order['total'], 0, ',', '.') ?> đ</td>
              <td>
                <span class="badge bg-<?=
                $order['status'] === 'Hoàn tất' ? 'success' :
                ($order['status'] === 'Đang giao' ? 'info' :
                ($order['status'] === 'Đã hủy' ? 'secondary' : 'warning'))
                ?>">
                  <?= htmlspecialchars($order['status']) ?>
                </span>
              </td>
              <td>
                <a href="/?page=order-detail&id=<?= $order['id'] ?>" class="btn btn-outline-primary btn-sm">
                  <i class="bi bi-eye"></i> Chi tiết
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
