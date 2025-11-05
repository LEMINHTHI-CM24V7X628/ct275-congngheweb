<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-5">
  <div class="bg-white p-4 rounded-4 shadow-sm">
    <h3 class="fw-bold text-danger mb-4">
      <i class="bi bi-receipt-cutoff"></i> Chi tiết đơn hàng #<?= htmlspecialchars($order['id']) ?>
    </h3>

    <p><strong>Khách hàng:</strong> <?= htmlspecialchars($order['fullname']) ?></p>
    <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($order['phone']) ?></p>
    <p><strong>Địa chỉ giao hàng:</strong> <?= htmlspecialchars($order['address']) ?></p>
    <p><strong>Ngày đặt:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
    <p><strong>Trạng thái:</strong>
      <span class="badge bg-warning text-dark"><?= htmlspecialchars($order['status']) ?></span>
    </p>

    <hr>

    <h5 class="fw-bold mb-3">Danh sách sản phẩm</h5>
    <table class="table table-bordered text-center align-middle">
      <thead class="table-danger">
        <tr>
          <th>Tên món</th>
          <th>Số lượng</th>
          <th>Giá</th>
          <th>Tạm tính</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $sum = 0;
          foreach ($items as $item):
            $subtotal = $item['price'] * $item['quantity'];
            $sum += $subtotal;
        ?>
        <tr>
          <td><?= htmlspecialchars($item['name']) ?></td>
          <td><?= $item['quantity'] ?></td>
          <td><?= number_format($item['price'], 0, ',', '.') ?> đ</td>
          <td><?= number_format($subtotal, 0, ',', '.') ?> đ</td>
        </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot class="table-light">
        <tr>
          <td colspan="3" class="fw-bold text-end">Tổng cộng:</td>
          <td class="fw-bold text-danger"><?= number_format($sum, 0, ',', '.') ?> đ</td>
        </tr>
      </tfoot>
    </table>

    <div class="text-center mt-4">
      <a href="/?page=orders" class="btn btn-outline-danger rounded-pill px-4 me-2">
        <i class="bi bi-arrow-left"></i> Quay lại danh sách
      </a>
       <?php if ($order['status'] === 'Đang xử lý'): ?>
  <a href="/?page=order-cancel&id=<?= $order['id'] ?>"
     class="btn btn-outline-danger rounded-pill px-4 me-2"
     onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này không?');">
     <i class="bi bi-x-circle"></i> Hủy đơn hàng
  </a>
<?php endif; ?>
    </div>
   

  </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
