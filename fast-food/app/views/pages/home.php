<?php include __DIR__ . '/../layouts/header.php'; ?>

<!-- Dải danh mục -->
<div class="container mt-3">
  <div class="category-strip d-flex flex-wrap gap-3 justify-content-start justify-content-md-center">
    <?php if (!empty($menus)): ?>
      <?php foreach ($menus as $menu): ?>
        <?php if (!empty($menu['status'])): ?>
          <div class="menu-item p-2 bg-white shadow-sm text-center rounded-4">
            <img
              src="/public/images/menu/<?= htmlspecialchars($menu['image'] ?? 'noimg.png') ?>"
              class="menu-img rounded"
              alt="<?= htmlspecialchars($menu['name'] ?? '') ?>"
              onerror="this.src='/public/images/noimg.png'">
            <div class="menu-label fw-semibold mt-2 small">
              <?= htmlspecialchars($menu['name'] ?? '') ?>
            </div>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="alert alert-info w-100 text-center">Chưa có danh mục.</div>
    <?php endif; ?>
  </div>
</div>

<!-- Lưới sản phẩm -->
<div class="container py-4">
  <div class="row g-4 justify-content-center">
    <?php if (!empty($products)): ?>
      <?php foreach ($products as $item): ?>
        <?php
          $id       = (int)($item['id'] ?? 0);
          $name     = htmlspecialchars($item['name'] ?? 'Sản phẩm chưa có tên');
          $desc     = htmlspecialchars($item['description'] ?? '');
          $price    = (float)($item['price'] ?? 0);
          $oldPrice = $item['old_price'] ?? null;
          $image    = !empty($item['image']) ? htmlspecialchars($item['image']) : 'noimg.png';
        ?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
          <div class="card product-card border-0 shadow-sm h-100">
            <a href="/?page=product&id=<?= urlencode($id) ?>" class="text-decoration-none text-dark">
              <div class="p-3 pb-0">
                <img
                  src="/public/images/products/<?= $image ?>"
                  class="card-img-top rounded-4"
                  alt="<?= $name ?>"
                  style="height: 200px; object-fit: cover;"
                  onerror="this.src='/public/images/noimg.png'">
              </div>
            </a>
            <div class="card-body text-center">
              <h6 class="card-title fw-bold mb-2">
                <a href="/?page=product&id=<?= urlencode($id) ?>" class="text-decoration-none text-dark">
                  <?= $name ?>
                </a>
              </h6>
              <p class="text-muted small mb-2"><?= $desc ?></p>
              <div class="price-line mb-3">
                <span class="price-now text-danger fw-bold"><?= number_format($price, 0, ',', '.') ?> đ</span>
                <?php if ($oldPrice !== null && $oldPrice !== ''): ?>
                  <span class="price-old text-muted text-decoration-line-through ms-2">
                    <?= number_format((float)$oldPrice, 0, ',', '.') ?> đ
                  </span>
                <?php endif; ?>
              </div>
              <a href="/?page=cart/add&id=<?= urlencode($id) ?>" class="btn btn-primary w-100 rounded-pill fw-bold">
                <i class="bi bi-cart-plus"></i> Đặt món
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12">
        <div class="alert alert-info text-center">Chưa có sản phẩm để hiển thị.</div>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
