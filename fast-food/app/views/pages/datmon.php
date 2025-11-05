<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/header.php';

try {
    $pdo = new PDO("pgsql:host=localhost;port=5432;dbname=db_fastfood;", "postgres", "12345", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    echo "<div class='alert alert-danger text-center mt-5'>Lỗi kết nối CSDL: " . htmlspecialchars($e->getMessage()) . "</div>";
    include __DIR__ . '/footer.php';
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute([':id' => $id]);
$product = $stmt->fetch();

if (!$product) {
    echo "<div class='alert alert-warning text-center mt-5'>Không tìm thấy sản phẩm!</div>";
    include __DIR__ . '/footer.php';
    exit;
}

$image = !empty($product['image']) ? $product['image'] : 'noimg.png';
?>
<style>
/* ✅ KHÔNG đổi nền toàn trang — giữ banner */
.body {
background: url("../images/bg_sky.png") no-repeat center top fixed;
background-attachment: fixed;
background-size: cover;
}
/* ====== KHU VỰC ĐẶT MÓN - CHỈ TRANG DATMON ====== */

/* Giữ nền banner mờ nhẹ */
body.page-datmon {
  background: url("/public/images/banner.jpg") center/cover fixed no-repeat;
  background-attachment: fixed;
  background-size: cover;
}
.product-section {
  display: flex;
  justify-content: center;
  align-items: flex-start; /* tránh form bị cao giữa */
  padding: 5px 0;         /* giảm chiều cao tổng thể */
}
/* Khung chính */
.product-box {
  width: 100%;
  max-width: 1100px;       /* giới hạn vừa phải, không tràn */
  margin: 0 auto;          /* Căn giữa tuyệt đối */
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(8px);
  border-radius: 20px;
  box-shadow: 0 6px 25px rgba(0, 0, 0, 0.12);
  padding: 10px 20px;      /* giảm padding -> thấp hơn */
  transition: all 0.3s ease;
}
.product-box:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
}
.product-box .row {
  align-items: center; /* căn giữa hàng ngang */
}
/* Ảnh sản phẩm lớn hơn */
.product-box img {
  max-width: 100%;
  height: auto;
  object-fit: contain;
  border-radius: 20px;
}

/* Tên sản phẩm */
.product-box h3 {
  font-size: 2rem;
  font-weight: 700;
}

/* Giá sản phẩm */
.price-now {
  font-size: 1.8rem;
  color: #e63946;
  font-weight: 700;
}

/* Danh sách topping */
.list-group-item {
  font-size: 1rem;
  padding: 10px 14px;
  border-radius: 10px;
  margin-bottom: 5px;
  background-color: rgba(255,255,255,0.8);
  border: 1px solid rgba(0, 0, 0, 0.05);
}

/* Số lượng */
.input-group {
  width: 150px !important;
}
input[type=number] {
  text-align: center;
  font-size: 1.1rem;
}

/* Nút thêm giỏ hàng */
.btn-add {
  background-color: #e63946;
  border: none;
  font-size: 1.1rem;
  padding: 12px 0;
  transition: all 0.25s;
}
.btn-add:hover {
  background-color: #c52835;
  transform: translateY(-1px);
}

/* Breadcrumb */
.breadcrumb {
  font-size: 0.95rem;
  margin-bottom: 1.5rem;
}

/* Giãn dòng chữ mô tả món */
.product-box p {
  font-size: 1rem;
  line-height: 1.5;
  color: #555;
}

@media (max-width: 992px) {
  .product-box {
    padding: 30px 25px;
  }
}
</style>

<!-- ======= KHU VỰC ĐẶT MÓN ======= -->
<section class="product-section">
  <div class="container" style="max-width: 1100px;">
    <div class="product-box">
      <!-- Nội dung sản phẩm -->
      <div class="row align-items-center g-5">
        <!-- Ảnh trái -->
        <div class="col-lg-6 text-center">
          <img src="/public/images/products/<?= htmlspecialchars($image) ?>"
               alt="<?= htmlspecialchars($product['name']) ?>"
               class="img-fluid rounded-4 shadow-sm"
               style="max-height:400px;object-fit:contain;">
          <p class="text-muted mt-3 small"><?= nl2br(htmlspecialchars($product['description'] ?? '')) ?></p>
        </div>
        <!-- Nội dung phải -->
        <div class="col-lg-6">
          <h3 class="fw-bold mb-2"><?= htmlspecialchars($product['name']) ?></h3>
          <div class="price-now mb-3"><?= number_format($product['price'], 0, ',', '.') ?> đ</div>

          <!-- Form giỏ hàng -->
         <form action="/app/views/layouts/add_to_cart.php" method="POST">
  <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
  <input type="hidden" name="redirect" value="giohang">

  <!-- Phần chọn topping -->
  <div class="mb-4">
    <div class="fw-semibold mb-2">Ngon hơn khi ăn kèm Burger</div>
    <div class="list-group small">
      <label class="list-group-item d-flex justify-content-between align-items-center">
        <span><input class="form-check-input me-2" type="checkbox" name="extras[]" value="pho_mai_1_mieng">Phô Mai 1 Miếng</span>
        <span class="text-muted">+7.000 đ</span>
      </label>
      <label class="list-group-item d-flex justify-content-between align-items-center">
        <span><input class="form-check-input me-2" type="checkbox" name="extras[]" value="trung">Trứng</span>
        <span class="text-muted">+7.000 đ</span>
      </label>
    </div>
  </div>

  <div class="mb-4">
    <div class="fw-semibold mb-2">Ngon hơn khi ăn kèm Khoai Tây</div>
    <div class="list-group small">
      <label class="list-group-item d-flex justify-content-between align-items-center">
        <span><input class="form-check-input me-2" type="checkbox" name="extras[]" value="vi_pho_mai">Vị Phô Mai</span>
        <span class="text-muted">+5.000 đ</span>
      </label>
      <label class="list-group-item d-flex justify-content-between align-items-center">
        <span><input class="form-check-input me-2" type="checkbox" name="extras[]" value="vi_tuyet_xanh">Vị Tuyết Xanh</span>
        <span class="text-muted">+5.000 đ</span>
      </label>
    </div>
  </div>

  <!-- Số lượng -->
  <div class="d-flex align-items-center gap-2 mb-4">
    <span class="fw-semibold">Số lượng:</span>
    <div class="input-group" style="width:130px;">
      <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1"> 
    </div>
  </div>

  <!-- Nút thêm -->
  <button type="submit" class="btn btn-add w-100 rounded-pill fw-bold text-white py-2">
    <i class="bi bi-cart-plus"></i> Thêm vào giỏ hàng
  </button>
</form>

        </div>
      </div>
    </div>
  </div>
</section>

<script>
function changeQty(delta) {
  const input = document.getElementById('quantity');
  let val = parseInt(input.value) || 1;
  val += delta;
  if (val < 1) val = 1;
  input.value = val;
}
</script>

<?php include __DIR__ . '/footer.php'; ?>
