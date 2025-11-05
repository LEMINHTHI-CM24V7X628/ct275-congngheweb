<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="container py-4">
  <h2 class="text-center fw-bold text-primary mb-4">
    ✏️ Sửa sản phẩm <span class="text-success">#<?= htmlspecialchars($product['id']) ?></span>
  </h2>

  <form method="post" action="/app/controllers/ProductController.php?action=update&id=<?= htmlspecialchars($product['id']) ?>"
        enctype="multipart/form-data" class="card shadow-sm border-0 p-4">

    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Tên sản phẩm</label>
        <input type="text" name="name" class="form-control"
               value="<?= htmlspecialchars($product['name'] ?? '') ?>" required>
      </div>

      <div class="col-md-3">
        <label class="form-label">Giá</label>
        <input type="number" name="price" class="form-control"
               value="<?= htmlspecialchars($product['price'] ?? 0) ?>" min="0" step="1000" required>
      </div>

      <div class="col-md-3">
        <label class="form-label">Giá cũ</label>
        <input type="number" name="old_price" class="form-control"
               value="<?= htmlspecialchars($product['old_price'] ?? '') ?>" min="0" step="1000">
      </div>

      <div class="col-12">
        <label class="form-label">Mô tả</label>
        <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
      </div>

      <div class="col-md-4">
        <label class="form-label">Danh mục</label>
        <select name="category_id" class="form-select">
          <option value="">-- Chọn danh mục --</option>
          <?php foreach ($categories as $c): ?>
            <option value="<?= $c['id'] ?>" <?= ($product['category_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($c['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label">Ảnh sản phẩm</label>
        <input type="file" name="image" class="form-control">
        <?php if (!empty($product['image'])): ?>
          <img src="/public/images/products/<?= htmlspecialchars($product['image']) ?>" alt="Ảnh" width="100" class="rounded mt-2 border">
        <?php endif; ?>
      </div>

      <div class="col-md-4 d-flex align-items-center">
        <div class="form-check mt-4">
          <input type="checkbox" name="status" id="status" class="form-check-input"
                 <?= !empty($product['status']) ? 'checked' : '' ?>>
          <label for="status" class="form-check-label">Hiển thị (✅ hiện / ❌ ẩn)</label>
        </div>
      </div>

      <div class="col-12 text-center mt-4">
        <button class="btn btn-success px-4">💾 Lưu thay đổi</button>
        <a href="/app/views/admin/edit_home.php" class="btn btn-secondary px-3">⬅ Quay lại</a>
      </div>
    </div>
  </form>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
