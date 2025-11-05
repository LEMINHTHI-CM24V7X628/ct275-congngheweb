<?php
// app/controllers/edit.php
include __DIR__ . '/../config.php'; // 🔹 Dùng kết nối từ config.php
include __DIR__ . '/../views/layouts/header.php';
error_reporting(E_ALL & ~E_DEPRECATED);

$type = $_GET['type'] ?? 'product'; // product hoặc category
$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("<div class='alert alert-danger text-center mt-5'>❌ Thiếu ID hợp lệ</div>");
}

try {
    // Kết nối từ config
    $conn = $pdo ?? null;
    if (!$conn) throw new Exception("Không tìm thấy kết nối CSDL trong config.php");

    if ($type === 'product') {
        // 🔹 Lấy dữ liệu sản phẩm
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();

        // 🔹 Lấy danh mục
        $categories = $conn->query("SELECT id, name FROM categories ORDER BY id ASC")->fetchAll();
    } else {
        // 🔹 Lấy dữ liệu danh mục
        $stmt = $conn->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
    }

    if (!$data) {
        die("<div class='alert alert-danger text-center mt-5'>Không tìm thấy dữ liệu!</div>");
    }

    // 🔹 Khi submit form
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($type === 'product') {
            $name = trim($_POST['name'] ?? '');
            $desc = trim($_POST['description'] ?? '');
            $price = (float)($_POST['price'] ?? 0);
            $old_price = strlen($_POST['old_price'] ?? '') ? (float)$_POST['old_price'] : null;
            $category_id = (int)($_POST['category_id'] ?? 0);
            $status = isset($_POST['status']) ? 1 : 0;

            // Upload ảnh
            $image = $data['image'] ?? null;
            if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $newName = 'prod_' . $id . '_' . time() . '.' . strtolower($ext);
                $dest = __DIR__ . '/../../public/images/products/' . $newName;
                if (!is_dir(__DIR__ . '/../../public/images/products')) {
                    mkdir(__DIR__ . '/../../public/images/products', 0775, true);
                }
                move_uploaded_file($_FILES['image']['tmp_name'], $dest);
                $image = $newName;
            }

            $sql = "UPDATE products 
                    SET name=:name, description=:desc, price=:price, old_price=:old_price,
                        image=:image, category_id=:category_id, status=:status
                    WHERE id=:id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':desc' => $desc,
                ':price' => $price,
                ':old_price' => $old_price,
                ':image' => $image,
                ':category_id' => $category_id,
                ':status' => $status,
                ':id' => $id
            ]);

            echo "<div class='alert alert-success text-center mt-3'>✅ Đã cập nhật sản phẩm thành công!</div>";
            $data = array_merge($data, $_POST);
            $data['image'] = $image;

        } else { // category
            $name = trim($_POST['name'] ?? '');
            $desc = trim($_POST['description'] ?? '');
            $status = isset($_POST['status']) ? 1 : 0;

            $image = $data['image'] ?? null;
            if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $newName = 'cat_' . $id . '_' . time() . '.' . strtolower($ext);
                $dest = __DIR__ . '/../../public/images/menu/' . $newName;
                if (!is_dir(__DIR__ . '/../../public/images/menu')) {
                    mkdir(__DIR__ . '/../../public/images/menu', 0775, true);
                }
                move_uploaded_file($_FILES['image']['tmp_name'], $dest);
                $image = $newName;
            }

            $sql = "UPDATE categories 
                    SET name=:name, description=:desc, image=:image, status=:status
                    WHERE id=:id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':desc' => $desc,
                ':image' => $image,
                ':status' => $status,
                ':id' => $id
            ]);

            echo "<div class='alert alert-success text-center mt-3'>✅ Đã cập nhật danh mục thành công!</div>";
            $data = array_merge($data, $_POST);
            $data['image'] = $image;
        }
    }

} catch (Throwable $e) {
    die("<div class='alert alert-danger text-center p-3'>Lỗi: " . htmlspecialchars($e->getMessage() ?? '') . "</div>");
}
?>

<div class="container py-4">
  <h2 class="text-center fw-bold mb-4 text-primary">
    <?= $type === 'product' ? '🛠️ Sửa sản phẩm' : '📂 Sửa danh mục' ?> 
    <span class="text-success">#<?= htmlspecialchars($id) ?></span>
  </h2>

  <form method="post" enctype="multipart/form-data" class="card shadow-lg border-0 p-4">
    <div class="row g-3">

      <div class="col-md-6">
        <label class="form-label">Tên <?= $type === 'product' ? 'sản phẩm' : 'danh mục' ?> *</label>
        <input type="text" name="name" class="form-control" required
               value="<?= htmlspecialchars($data['name'] ?? '') ?>">
      </div>

      <div class="col-md-6">
        <label class="form-label">Ảnh</label><br>
        <input type="file" name="image" class="form-control mb-2">
        <?php if (!empty($data['image'])): ?>
          <img src="/public/images/<?= $type === 'product' ? 'products' : 'menu' ?>/<?= htmlspecialchars($data['image']) ?>"
               alt="Ảnh" width="100" class="rounded border">
        <?php endif; ?>
      </div>

      <div class="col-12">
        <label class="form-label">Mô tả</label>
        <textarea name="description" rows="3" class="form-control"><?= htmlspecialchars($data['description'] ?? '') ?></textarea>
      </div>

      <?php if ($type === 'product'): ?>
        <div class="col-md-4">
          <label class="form-label">Giá *</label>
          <input type="number" name="price" class="form-control" min="0" required
                 value="<?= htmlspecialchars($data['price'] ?? 0) ?>">
        </div>

        <div class="col-md-4">
          <label class="form-label">Giá cũ</label>
          <input type="number" name="old_price" class="form-control" min="0"
                 value="<?= htmlspecialchars($data['old_price'] ?? '') ?>">
        </div>

        <div class="col-md-4">
          <label class="form-label">Danh mục</label>
          <select name="category_id" class="form-select">
            <option value="">-- Chọn danh mục --</option>
            <?php foreach ($categories as $c): ?>
              <option value="<?= $c['id'] ?>" <?= ($data['category_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      <?php endif; ?>

      <div class="col-md-3 form-check ms-3">
        <input type="checkbox" name="status" class="form-check-input" id="status" <?= !empty($data['status']) ? 'checked' : '' ?>>
        <label class="form-check-label" for="status">Hiển thị (✅ hiện / ❌ ẩn)</label>
      </div>

      <div class="col-12 text-center mt-3">
        <button type="submit" class="btn btn-success px-4 fw-bold">💾 Lưu thay đổi</button>
        <a href="/app/views/admin/edit_home.php" class="btn btn-secondary px-3">⬅ Quay lại</a>
      </div>

    </div>
  </form>
</div>

<?php include __DIR__ . '/../views/layouts/footer.php'; ?>
