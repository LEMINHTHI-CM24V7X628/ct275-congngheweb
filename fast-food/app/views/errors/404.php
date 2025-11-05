<?php include __DIR__ . '/../layouts/header.php'; ?>

<style>
body {
  background-color: #f9fafb;
}
.error-container {
  text-align: center;
  padding: 80px 20px;
}
.error-code {
  font-size: 120px;
  font-weight: 700;
  color: #e63946;
  line-height: 1;
}
.error-message {
  font-size: 22px;
  color: #444;
  margin-bottom: 25px;
}
.error-actions a {
  margin: 5px;
  font-weight: 500;
}
</style>

<div class="container error-container">
  <div class="error-code">404</div>
  <div class="error-message">Trang bạn yêu cầu không tồn tại hoặc đã bị xóa.</div>
  <div class="error-actions">
    <a href="/?page=home" class="btn btn-primary">
      <i class="bi bi-house-door"></i> Quay lại trang chủ
    </a>
    <a href="javascript:history.back()" class="btn btn-outline-secondary">
      <i class="bi bi-arrow-left"></i> Quay lại trước đó
    </a>
  </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
