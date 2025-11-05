<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
} ?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng ký</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body style="background-color: #f3faf6;">

  <div class="container" style="margin-top: 60px;">
    <div class="panel panel-info" style="max-width: 450px; margin: 0 auto; border-radius: 6px;">
      <div class="panel-heading text-center">
        <strong>Đăng Ký Tài Khoản</strong>
      </div>
      <div class="panel-body">
        <form id="registerForm" action="/?page=register/process" method="POST">
          <div class="form-group">
            <label>Họ và tên:</label>
            <input type="text" name="full_name" class="form-control" placeholder="Nhập họ và tên" required>
          </div>

          <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" placeholder="Nhập email" required>
          </div>

          <div class="form-group">
            <label>Số điện thoại:</label>
            <input type="text" name="phone" class="form-control" placeholder="Nhập số điện thoại" required>
          </div>

          <div class="form-group">
            <label>Địa chỉ:</label>
            <input type="text" name="address" class="form-control" placeholder="Nhập địa chỉ" required>
          </div>

          <div class="form-group">
            <label>Mật khẩu:</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
          </div>

          <div class="form-group">
            <label>Nhập lại mật khẩu:</label>
            <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Nhập lại mật khẩu" required>
            <span id="error_message" class="text-danger" style="display:none;">⚠️ Mật khẩu không khớp!</span>
          </div>

          <button type="submit" class="btn btn-success btn-block">
            <i class="fa fa-user-plus"></i> Đăng ký
          </button>
          <a href="/?page=login" class="btn btn-primary btn-block" style="color: #fff;">
            <i class="fa fa-sign-in"></i> Quay lại đăng nhập
          </a>
        </form>
      </div>
    </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#registerForm').on('submit', function(e) {
        var pass = $('#password').val().trim();
        var confirm = $('#confirm_password').val().trim();
        if (pass !== confirm) {
          e.preventDefault();
          $('#error_message').show();
          $('#confirm_password').focus();
        } else {
          $('#error_message').hide();
        }
      });
    });
  </script>
</body>
</html>
