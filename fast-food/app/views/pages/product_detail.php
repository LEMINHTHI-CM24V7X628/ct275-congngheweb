<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="container py-5">
  <?php if (!empty($product)): ?>
    <?php
      $name  = htmlspecialchars($product['name'] ?? '');
      $desc  = htmlspecialchars($product['description'] ?? '');
      $price = (float)($product['price'] ?? 0);
      $old   = $product['old_price'] ?? null;
      $img   = htmlspecialchars($product['image'] ?? 'noimg.png');
    ?>
    <div class="bg-white shadow rounded-4 p-4 p-md-5" style="max-width: 1000px; margin: 0 auto;">
      <div class="row g-5 align-items-center">
        <!-- Ảnh sản phẩm -->
        <div class="col-md-6 text-center">
          <div class="border rounded-4 p-3 shadow-sm" style="background:#fff;">
            <img src="/public/images/products/<?= $img ?>" 
                 alt="<?= $name ?>" 
                 class="img-fluid rounded-3" 
                 style="max-height: 350px; object-fit: contain;" 
                 onerror="this.src='/public/images/noimg.png'">
          </div>
        </div>

        <!-- Thông tin sản phẩm -->
        <div class="col-md-6">
          <h2 class="fw-bold mb-2"><?= $name ?></h2>
          <p class="text-muted mb-3"><?= $desc ?></p>

          <div class="d-flex align-items-baseline gap-3 mb-4">
            <span class="text-danger fw-bold fs-3"><?= number_format($price, 0, ',', '.') ?> đ</span>
            <?php if ($old): ?>
              <span class="text-muted text-decoration-line-through fs-6"><?= number_format((float)$old, 0, ',', '.') ?> đ</span>
            <?php endif; ?>
          </div>

          <form action="/cart/add.php" method="POST" class="d-flex align-items-center gap-3 mb-4">
            <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
            <input type="number" name="quantity" value="1" min="1" class="form-control w-auto" style="max-width:90px;">
            <button type="submit" class="btn btn-primary px-4 fw-semibold">
              <i class="bi bi-cart-plus"></i> Thêm vào giỏ
            </button>
          </form>

          <a href="/" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
          </a>
        </div>
      </div>
    </div>

  <?php else: ?>
    <div class="alert alert-warning text-center">Không có dữ liệu sản phẩm.</div>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
