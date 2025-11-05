<?php
session_start();
include __DIR__ . '/header.php';

// 🔹 Dọn lỗi tạm nếu giỏ hàng bị sai
foreach ($_SESSION['cart'] ?? [] as $i => $item) {
  if (!isset($item['id'], $item['name'])) unset($_SESSION['cart'][$i]);
}

// Xác định URL thanh toán phù hợp
if (!empty($_SESSION['user_id'])) {
  $checkoutUrl = "/app/views/pages/checkout.php";
} else {
  $checkoutUrl = "/app/views/auth/dangnhap.php?redirect=/app/views/pages/checkout.php";
}
?>

<style>
body {
  background: url("/public/images/bg_sky.png") no-repeat center top fixed;
  background-size: cover;
}
.cart-container {
  background: rgba(255, 255, 255, 0.92);
  backdrop-filter: blur(6px);
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}
.table img {
  width: 70px;
  border-radius: 10px;
}
.btn-danger {
  background-color: #e63946;
  border: none;
}
.btn-danger:hover {
  background-color: #c42e3b;
}
</style>

<div class="container py-5">
  <div class="cart-container">
    <h3 class="fw-bold mb-4 text-danger"><i class="bi bi-cart4"></i> Giỏ hàng của bạn</h3>

    <?php if (empty($_SESSION['cart'])): ?>
      <div class="alert alert-info text-center fs-6 py-3">
        🛒 Chưa có sản phẩm nào trong giỏ hàng!
      </div>
      <div class="text-center mt-4">
        <a href="/app/views/pages/home.php" class="btn btn-primary px-4">⬅ Tiếp tục mua hàng</a>
      </div>
    <?php else: ?>
      <table class="table align-middle table-hover text-center">
        <thead class="table-danger text-dark">
          <tr>
            <th>Hình</th>
            <th>Tên món</th>
            <th>Số lượng</th>
            <th>Giá</th>
            <th>Tạm tính</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $total = 0;
            foreach ($_SESSION['cart'] as $item): 
              $price = isset($item['price']) ? (float)$item['price'] : 0;
              $qty = isset($item['quantity']) ? (int)$item['quantity'] : 1;
              $subtotal = $price * $qty;
              $total += $subtotal;
          ?>
          <tr>
            <td><img src="/public/images/products/<?= htmlspecialchars($item['image'] ?? 'noimg.png') ?>" alt=""></td>
            <td class="fw-semibold"><?= htmlspecialchars($item['name'] ?? 'Không tên') ?></td>
            <td><?= $qty ?></td>
            <td><?= number_format($price, 0, ',', '.') ?> đ</td>
            <td><?= number_format($subtotal, 0, ',', '.') ?> đ</td>
          </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot class="table-light">
          <tr>
            <td colspan="4" class="text-end fw-bold">Tổng cộng:</td>
            <td class="fw-bold text-danger fs-5"><?= number_format($total, 0, ',', '.') ?> đ</td>
          </tr>
        </tfoot>
      </table>

      <div class="text-end mt-4">
        <a href="../../../index.php" class="btn btn-outline-secondary px-4 me-2">
          <i class="bi bi-arrow-left"></i> Tiếp tục mua hàng
        </a>
        <a href="<?= $checkoutUrl ?>" class="btn btn-danger px-4">
          <i class="bi bi-credit-card"></i> Thanh toán
        </a>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
