<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../layouts/header.php';

// 🔹 Dọn lỗi tạm nếu giỏ hàng bị sai
foreach ($_SESSION['cart'] ?? [] as $i => $item) {
  if (!isset($item['id'], $item['name'])) unset($_SESSION['cart'][$i]);
}

// 🔹 Xác định URL thanh toán
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
    <h3 class="fw-bold mb-4 text-danger text-center">
      <i class="bi bi-cart4"></i> Giỏ hàng của bạn
    </h3>
<?php if (empty($_SESSION['cart'])): ?>
  <div class="text-center py-5">
    <div class="display-1 text-danger mb-3">
  <i class="bi bi-cart-x"></i>
</div>
    <h4 class="mt-4 fw-semibold text-secondary">Giỏ hàng của bạn đang trống</h4>
    <p class="text-muted mb-4">Vui lòng chọn món khác để đặt hàng nhé!</p>
    <a href="/index.php" class="btn btn-primary px-4 rounded-pill">
      <i class="bi bi-arrow-left-circle"></i> Đặt món ngay
    </a>
  </div>
<?php else: ?>
      <form action="/?page=cart/update" method="POST">
        <table class="table align-middle table-hover text-center">
          <thead class="table-danger text-dark">
            <tr>
              <th>Hình</th>
              <th>Tên món</th>
              <th>Số lượng</th>
              <th>Giá</th>
              <th>Tạm tính</th>
              <th>Xóa</th>
            </tr>
          </thead>
          <tbody>
            <?php 
              $total = 0;
              foreach ($_SESSION['cart'] as $item): 
                $id = $item['id'];
                $price = isset($item['price']) ? (float)$item['price'] : 0;
                $qty = isset($item['quantity']) ? (int)$item['quantity'] : 1;
                $subtotal = $price * $qty;
                $total += $subtotal;
            ?>
            <tr>
              <td><img src="/public/images/products/<?= htmlspecialchars($item['image'] ?? 'noimg.png') ?>" alt=""></td>
              <td class="fw-semibold"><?= htmlspecialchars($item['name'] ?? 'Không tên') ?></td>
              <td style="width:130px;">
                <div class="input-group input-group-sm justify-content-center">
                  <button type="button" class="btn btn-outline-secondary" onclick="changeQty(<?= $id ?>, -1)">−</button>
                  <input type="number" name="quantity[<?= $id ?>]" id="qty-<?= $id ?>" class="form-control text-center" value="<?= $qty ?>" min="1" style="width:60px;">
                  <button type="button" class="btn btn-outline-secondary" onclick="changeQty(<?= $id ?>, 1)">+</button>
                </div>
              </td>
              <td><?= number_format($price, 0, ',', '.') ?> đ</td>
              <td><?= number_format($subtotal, 0, ',', '.') ?> đ</td>
              <td>
                <a href="/?page=cart-remove&id=<?= $id ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Xóa sản phẩm này?');">
                  <i class="bi bi-trash"></i>
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot class="table-light">
            <tr>
              <td colspan="4" class="text-end fw-bold">Tổng cộng:</td>
              <td class="fw-bold text-danger fs-5"><?= number_format($total, 0, ',', '.') ?> đ</td>
              <td></td>
            </tr>
          </tfoot>
        </table>

        <!-- Nút hành động -->
        <div class="d-flex justify-content-between mt-4">
          <a href="/index.php" class="btn btn-outline-secondary px-4">
            <i class="bi bi-arrow-left"></i> Tiếp tục mua hàng
          </a>

          <div>
            <button type="submit" class="btn btn-success me-2">
              <i class="bi bi-arrow-repeat"></i> Cập nhật giỏ hàng
            </button>
            <a href="<?= $checkoutUrl ?>" class="btn btn-danger">
              <i class="bi bi-credit-card"></i> Thanh toán
            </a>
          </div>
        </div>
      </form>
    <?php endif; ?>
  </div>
</div>

<script>
function changeQty(id, delta) {
  let input = document.getElementById('qty-' + id);
  let value = parseInt(input.value);
  if (value + delta >= 1) {
    input.value = value + delta;
  }
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
