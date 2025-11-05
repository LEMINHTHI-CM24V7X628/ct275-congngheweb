<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>FAST-FOOD</title>
  <link rel="icon" type="image/png" href="/public/images/logo/fastfood_logo.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="/public/css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<style>
  body { font-family: 'Poppins', sans-serif; }
.navbar-icons {
  display: flex;
  align-items: center;
  gap: 14px;
}

.navbar-icons .btn {
  border-radius: 8px;
}

.navbar-icons .btn-light {
  padding: 6px 10px;
}
.navbar-icons .bi-bag {
  font-size: 18px;
  margin-top: 2px;
}
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg fixed-top shadow-sm bg-white">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="/?page=home">
      <img src="/public/images/logo/fastfood_logo.png" alt="FastFood T&D" style="height:40px;">
      <span class="fw-bold text-danger">FastFood T&D</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-center" id="mainNav">
      <ul class="navbar-nav mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link <?= ($_GET['page'] ?? '') === 'home' ? 'active text-danger' : '' ?>" href="/?page=home">TRANG CHỦ</a>
        </li>
        <li class="nav-item"><a class="nav-link" href="#">CHÍNH SÁCH</a></li>
        <li class="nav-item"><a class="nav-link" href="#">CỬA HÀNG</a></li>
        <li class="nav-item"><a class="nav-link" href="#">KHUYẾN MÃI</a></li>
      </ul>
    </div>
<div class="navbar-icons ms-lg-3 d-flex align-items-center gap-2">
  <?php if (!empty($_SESSION['user_id'])): ?>
    <div class="dropdown me-3">
  <button class="btn btn-outline-success dropdown-toggle" data-bs-toggle="dropdown">
    <i class="bi bi-person-circle"></i>
    <?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['phone'] ?? 'Tài khoản') ?>
  </button>
      <ul class="dropdown-menu dropdown-menu-end shadow-sm">
        <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
          <li><a class="dropdown-item" href="/?page=admin-dashboard"><i class="bi bi-speedometer2"></i> Bảng điều khiển</a></li>
          <li><a class="dropdown-item" href="/?page=admin-product-list"><i class="bi bi-box"></i> Quản lý sản phẩm</a></li>
          <li><a class="dropdown-item" href="/?page=admin-user-list"><i class="bi bi-people"></i> Quản lý tài khoản</a></li>
          <li><hr class="dropdown-divider"></li>
        <?php endif; ?>
        <li><a class="dropdown-item" href="/?page=cart"><i class="bi bi-bag"></i> Quản lý giỏ hàng</a></li>
        <li><a class="dropdown-item" href="/?page=orders"><i class="bi bi-truck"></i> Theo dõi đơn hàng</a></li>
        <li><a class="dropdown-item text-danger" href="/?page=logout"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a></li>
      </ul>
    </div>
  <?php else: ?>
    <a href="/?page=login" class="btn btn-outline-primary"><i class="bi bi-person-circle"></i> Đăng nhập</a>
    <a href="/?page=register" class="btn btn-success text-white"><i class="bi bi-person-plus"></i> Đăng ký</a>
  <?php endif; ?>
</div>
<a href="/?page=cart" class="btn btn-light border position-relative ms-2">
  <i class="bi bi-bag"></i>
  <?php
  $count = 0;
  if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
      $count += $item['quantity'];
    }
  }
  if ($count > 0): ?>
    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
      <?= $count ?>
    </span>
  <?php endif; ?>
</a>

</div>

  </div>
</nav>
<div style="height:80px"></div>
