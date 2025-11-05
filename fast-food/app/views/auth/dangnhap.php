<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng nhập</title>
  <!-- Bootstrap 3 -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    .password-wrapper { position: relative; }
    .password-wrapper input { padding-right: 40px; }
    .password-wrapper .toggle-password {
      position: absolute; top: 70%; right: 12px;
      transform: translateY(-48%);
      cursor: pointer; color: #888;
      font-size: 18px; transition: color 0.2s ease;
    }
    .password-wrapper .toggle-password:hover { color: #333; }
  </style>
</head>
<body style="background-color: #f3faf6;">

  <?php
    // ✅ Lấy redirect nếu có (từ link thanh toán hoặc trang trước)
    $redirect = $_GET['redirect'] ?? '/';
  ?>

  <div class="container" style="margin-top: 80px;">
    <div class="panel panel-info" style="max-width: 400px; margin: 0 auto; border-radius: 6px;">
      <div class="panel-heading text-center">
        <strong>Đăng Nhập</strong>
      </div>

      <div class="panel-body">
<form action="/?page=login/process" method="POST">
          <!-- 🔹 Giữ lại đường dẫn chuyển hướng sau khi login -->
          <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">

          <div class="form-group">
            <label for="phone">Số điện thoại:</label>
            <input type="text" name="phone" class="form-control"
              placeholder="Nhập vào số điện thoại"
              required
              oninvalid="this.setCustomValidity('Vui lòng nhập số điện thoại!')"
              oninput="setCustomValidity('')">
          </div>

          <div class="form-group password-wrapper">
            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" class="form-control"
              placeholder="Nhập vào mật khẩu"
              required
              oninvalid="this.setCustomValidity('Vui lòng nhập mật khẩu!')"
              oninput="setCustomValidity('')">
            <span class="fa fa-eye toggle-password" id="togglePassword"></span>
          </div>

          <div class="checkbox">
            <label><input type="checkbox" name="remember"> Ghi nhớ tôi</label>
          </div>

          <!-- Nút login -->
          <button type="submit" class="btn btn-primary btn-block">
            <i class="fa fa-sign-in"></i> LOGIN
          </button>

          <!-- Nút đăng ký mới -->
          <a href="/?page=register" class="btn btn-success btn-block" style="color: #fff;">
            <i class="fa fa-user-plus"></i> Đăng ký mới
          </a>

          <!-- Quên mật khẩu -->
          <div class="text-center" style="margin-top: 10px;">
            <a href="#" style="text-decoration: none;">
              <i class="fa fa-unlock-alt"></i> Quên mật khẩu?
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- JS -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

  <script>
    // Toggle hiện / ẩn mật khẩu
    document.getElementById("togglePassword").addEventListener("click", function () {
      const passwordField = document.getElementById("password");
      const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
      passwordField.setAttribute("type", type);
      this.classList.toggle("fa-eye");
      this.classList.toggle("fa-eye-slash");
    });
  </script>
</body>
</html>
