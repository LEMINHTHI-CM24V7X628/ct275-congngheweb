<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../layouts/header.php';

// Giỏ rỗng -> gợi ý quay lại mua hàng
if (empty($_SESSION['cart'])) {
    echo '<div class="container py-5 text-center">
            <div class="alert alert-info fs-5">🛒 Giỏ hàng của bạn đang trống!</div>
            <a href="/index.php" class="btn btn-primary mt-3">⬅ Tiếp tục mua hàng</a>
          </div>';
    include __DIR__ . '/../layouts/footer.php';
    exit;
}

$fullname = $_SESSION['full_name'] ?? '';
$phone    = $_SESSION['phone'] ?? '';
?>

<style>
  .section-title{
    width:100%;
    max-width:780px;
    margin:0 auto 24px auto;
    background:#dc3545;
    color:#fff;
    padding:10px 18px;
    border-radius:16px;
    font-weight:700;
    text-align:center;
    box-shadow:0 6px 16px rgba(220,53,69,.35);
  }
  .order-card{ background:#fff; border:0; border-radius:16px; box-shadow:0 6px 18px rgba(0,0,0,.1); }
  .summary li span:last-child{ min-width:120px; text-align:right; display:inline-block; }
  .btn-primary-outline{ border:1px solid #adb5bd; }
</style>

<div class="container py-5">
  <div class="section-title">
    <i class="bi bi-credit-card"></i> Thanh toán đơn hàng
  </div>

  <div class="row g-4">
    <!-- Thông tin giao hàng -->
    <div class="col-lg-6">
      <div class="card order-card p-4 h-100">
        <h5 class="fw-bold mb-3">Thông tin giao hàng</h5>

        <form action="/?page=checkout" method="POST" novalidate>
          <div class="mb-3">
            <label class="form-label fw-semibold">Họ và tên</label>
            <input type="text" name="fullname" value="<?= htmlspecialchars($fullname) ?>" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Số điện thoại</label>
            <input type="tel" name="phone" value="<?= htmlspecialchars($phone) ?>" class="form-control" pattern="[0-9]{9,11}" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Địa chỉ giao hàng</label>
            <textarea name="address" class="form-control" rows="3" placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành" required></textarea>
          </div>

          <div class="d-flex justify-content-between mt-3">
            <a href="/index.php" class="btn btn-light btn-primary-outline rounded-pill px-4">
              ⬅ Tiếp tục mua hàng
            </a>
            <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">
              <i class="bi bi-check2-circle"></i> Xác nhận đặt hàng
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Tóm tắt đơn hàng -->
    <div class="col-lg-6">
      <div class="card order-card p-4 h-100">
        <h5 class="fw-bold mb-3">Tóm tắt đơn hàng</h5>
        <ul class="list-group list-group-flush summary">
          <?php
            $total = 0;
            foreach ($_SESSION['cart'] as $item):
              $qty      = (int)($item['quantity'] ?? 1);
              $price    = (float)($item['price'] ?? 0);
              $name     = $item['name'] ?? '';
              $subtotal = $qty * $price;
              $total   += $subtotal;
          ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span><?= htmlspecialchars($name) ?> <small class="text-muted">(x<?= $qty ?>)</small></span>
            <span><?= number_format($subtotal, 0, ',', '.') ?> đ</span>
          </li>
          <?php endforeach; ?>
          <li class="list-group-item d-flex justify-content-between fw-bold">
            <span>Tổng cộng</span>
            <span class="text-danger"><?= number_format($total, 0, ',', '.') ?> đ</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
