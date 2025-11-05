<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container py-5">
  <a href="/?page=admin-orders" class="btn btn-outline-secondary mb-3">
    <i class="bi bi-arrow-left"></i> Quay lại danh sách
  </a>

  <div class="card shadow-sm p-4">
    <h4 class="fw-bold text-danger mb-3">
      <i class="bi bi-receipt"></i> Đơn hàng #<?= (int)$order['id'] ?>
    </h4>

    <p><strong>Khách hàng:</strong> <?= htmlspecialchars($order['fullname']) ?></p>
    <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($order['phone']) ?></p>
    <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['address']) ?></p>
    <p><strong>Ngày đặt:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>

    <h5 class="fw-bold mt-4 mb-3">Chi tiết sản phẩm</h5>

    <table class="table table-striped text-center align-middle">
      <thead class="table-danger">
        <tr>
          <th>Tên sản phẩm</th>
          <th>Giá</th>
          <th>Số lượng</th>
          <th>Thành tiền</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $i): ?>
          <tr>
            <td><?= htmlspecialchars($i['product_name']) ?></td>
            <td><?= number_format($i['price'], 0, ',', '.') ?> đ</td>
            <td><?= (int)$i['quantity'] ?></td>
            <td><?= number_format($i['subtotal'], 0, ',', '.') ?> đ</td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3" class="text-end fw-bold">Tổng cộng:</td>
          <td class="fw-bold text-danger"><?= number_format($order['total'], 0, ',', '.') ?> đ</td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
