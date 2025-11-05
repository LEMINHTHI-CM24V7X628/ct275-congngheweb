<footer class="footer-sky mt-2 bg-light text-dark pt-5 border-top">
  <div class="container">
    <div class="row gy-4">
      <!-- Cột 1: Logo + form đăng ký -->
      <div class="col-md-3 mx-auto text-center">
  <!-- 🔹 Logo canh giữa & phóng to -->
  <div class="mb-3">
    <img src="/public/images/logo/fastfood_logo.png" alt="FastFood T&D"
         style="height:100px; max-width:100%; object-fit:contain;">
  </div>

  <!-- 🔹 Tiêu đề -->
  <h6 class="fw-bold text-danger mb-3">ĐĂNG KÝ NHẬN THÔNG TIN KHUYẾN MÃI</h6>

  <!-- 🔹 Form nhập email -->
  <form class="footer-form">
    <div class="input-group justify-content-center" style="max-width:280px;margin:auto;">
      <span class="input-group-text bg-white border-end-0">
        <i class="bi bi-envelope-fill text-danger"></i>
      </span>
      <input type="email" class="form-control border-start-0" placeholder="Nhập email của bạn" required>
    </div>
  </form>
</div>


      <!-- Cột 2 -->
      <div class="col-md-2">
        <h6 class="fw-bold text-danger mb-3">THÔNG TIN CỬA HÀNG</h6>
        <ul class="list-unstyled">
          <li><a href="#" class="text-decoration-none text-dark">Tin tức mới nhất</a></li>
          <li><a href="#" class="text-decoration-none text-dark">Khuyến mãi</a></li>
          <li><a href="#" class="text-decoration-none text-dark">Tuyển dụng</a></li>
          <li><a href="#" class="text-decoration-none text-dark">Khách hàng VIP</a></li>
          <li><a href="#" class="text-decoration-none text-dark">Đăng ký sản phẩm</a></li>
        </ul>
      </div>

      <!-- Cột 3 -->
      <div class="col-md-3">
        <h6 class="fw-bold text-danger mb-3">HỖ TRỢ KHÁCH HÀNG</h6>
        <ul class="list-unstyled">
          <li><a href="#" class="text-decoration-none text-dark">Điều khoản dịch vụ</a></li>
          <li><a href="#" class="text-decoration-none text-dark">Chính sách bảo mật</a></li>
          <li><a href="#" class="text-decoration-none text-dark">Chính sách giao hàng</a></li>
          <li><a href="#" class="text-decoration-none text-dark">Chăm sóc khách hàng</a></li>
          <li><a href="#" class="text-decoration-none text-dark">Thành viên công ty</a></li>
        </ul>
      </div>

      <!-- Cột 4 -->
      <div class="col-md-3">
        <h6 class="fw-bold text-danger mb-3">THEO DÕI CHÚNG TÔI</h6>
        <ul class="list-unstyled">
          <li><a href="#" class="text-decoration-none text-dark"><i class="bi bi-facebook text-primary"></i> Facebook</a></li>
          <li><a href="#" class="text-decoration-none text-dark"><i class="bi bi-instagram text-danger"></i> Instagram</a></li>
          <li><a href="#" class="text-decoration-none text-dark"><i class="bi bi-chat-dots text-success"></i> Zalo</a></li>
          <li><a href="#" class="text-decoration-none text-dark"><i class="bi bi-exclamation-circle-fill text-danger"></i> Cảnh báo lừa đảo</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Dòng bản quyền -->
  <div class="footer-copy text-center py-3 mt-4 bg-danger text-white">
    © 2025 <b>FastFood T&D</b> — Website mô phỏng cửa hàng bán thức ăn nhanh.<br>
    Được thực hiện trong học phần <b>Công Nghệ Web - CT275</b> bởi <b>Lê Minh Thi & Lê Khánh Duy</b>.
  </div>

  <!-- Script -->
  <script>
    // 🔹 Tự dịch cảnh báo required sang tiếng Việt
    document.addEventListener("invalid", function(e) {
      e.target.setCustomValidity("Vui lòng nhập vào trường này.");
    }, true);

    document.addEventListener("input", function(e) {
      e.target.setCustomValidity("");
    }, true);
  </script>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</footer>

</body>
</html>
