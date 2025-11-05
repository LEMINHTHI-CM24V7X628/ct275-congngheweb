<?php
include __DIR__ . '/../layouts/header.php';
error_reporting(E_ALL & ~E_DEPRECATED);

// 🔹 Kết nối PostgreSQL
try {
    $pdo = new PDO("pgsql:host=localhost;port=5432;dbname=db_fastfood;", "postgres", "12345");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("<div class='alert alert-danger text-center'>Kết nối thất bại: " . htmlspecialchars($e->getMessage()) . "</div>");
}

// =========================
// 🔸 Xử lý thêm / sửa / xóa
// =========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 🟡 Thêm danh mục
    if (isset($_POST['add_category'])) {
        $name = trim($_POST['cat_name'] ?? '');
        $desc = trim($_POST['cat_desc'] ?? '');
        $status = isset($_POST['cat_status']) ? 1 : 0;
        $img = null;

        if (!empty($_FILES['cat_image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['cat_image']['name'], PATHINFO_EXTENSION));
            $img = 'cat_' . time() . '.' . $ext;
            $path = __DIR__ . '/../../../public/images/menu/' . $img;
            @move_uploaded_file($_FILES['cat_image']['tmp_name'], $path);
        }

        if ($name !== '') {
            $sql = "INSERT INTO categories(name, description, image, status)
                    VALUES(:n, :d, :i, :s)";
            $st = $pdo->prepare($sql);
            $st->execute([':n'=>$name, ':d'=>$desc, ':i'=>$img, ':s'=>$status]);
            echo "<script>alert('✅ Thêm danh mục thành công!');</script>";
        }
    }

    // 🟡 Sửa danh mục
    if (isset($_POST['edit_category'])) {
        $id = (int)$_POST['cat_id'];
        $name = trim($_POST['cat_name'] ?? '');
        $desc = trim($_POST['cat_desc'] ?? '');
        $status = isset($_POST['cat_status']) ? 1 : 0;
        $img = $_POST['cat_image_old'] ?? null;

        if (!empty($_FILES['cat_image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['cat_image']['name'], PATHINFO_EXTENSION));
            $img = 'cat_' . $id . '_' . time() . '.' . $ext;
            $path = __DIR__ . '/../../../public/images/menu/' . $img;
            @move_uploaded_file($_FILES['cat_image']['tmp_name'], $path);
        }

        $sql = "UPDATE categories SET name=:n, description=:d, image=:i, status=:s WHERE id=:id";
        $st = $pdo->prepare($sql);
        $st->execute([':n'=>$name, ':d'=>$desc, ':i'=>$img, ':s'=>$status, ':id'=>$id]);
        echo "<script>alert('✅ Cập nhật danh mục thành công!');</script>";
    }

    // 🗑️ Xóa danh mục (chặn nếu còn sản phẩm)
    if (isset($_POST['delete_category'])) {
        $id = (int)$_POST['cat_id'];

        // kiểm tra có sản phẩm thuộc danh mục không
        $chk = $pdo->prepare("SELECT COUNT(*) FROM products WHERE category_id = :id");
        $chk->execute([':id'=>$id]);
        $cnt = (int)$chk->fetchColumn();

        if ($cnt > 0) {
            echo "<script>alert('⚠️ Không thể xóa: Danh mục còn $cnt sản phẩm. Hãy chuyển sản phẩm sang danh mục khác hoặc xóa sản phẩm trước.');</script>";
        } else {
            // lấy ảnh cũ để xóa file
            $cur = $pdo->prepare("SELECT image FROM categories WHERE id=:id");
            $cur->execute([':id'=>$id]);
            $row = $cur->fetch(PDO::FETCH_ASSOC);
            $img = $row['image'] ?? null;

            $del = $pdo->prepare("DELETE FROM categories WHERE id=:id");
            $del->execute([':id'=>$id]);

            if ($img) {
                $f = __DIR__ . '/../../../public/images/menu/' . $img;
                if (is_file($f)) @unlink($f);
            }
            echo "<script>alert('✅ Đã xóa danh mục!');</script>";
        }
    }

    // 🟢 Thêm sản phẩm
    if (isset($_POST['add_product'])) {
        $name = trim($_POST['p_name'] ?? '');
        $desc = trim($_POST['p_desc'] ?? '');
        $price = (float)($_POST['p_price'] ?? 0);
        $cat   = ($_POST['p_category'] ?? '') !== '' ? (int)$_POST['p_category'] : null;
        $status= isset($_POST['p_status']) ? 1 : 0;
        $img = null;

        if (!empty($_FILES['p_image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['p_image']['name'], PATHINFO_EXTENSION));
            $img = 'prod_' . time() . '.' . $ext;
            $path = __DIR__ . '/../../../public/images/products/' . $img;
            @move_uploaded_file($_FILES['p_image']['tmp_name'], $path);
        }

        if ($name !== '' && $price > 0) {
            $sql = "INSERT INTO products(name, description, price, image, category_id, status)
                    VALUES(:n,:d,:p,:i,:c,:s)";
            $st = $pdo->prepare($sql);
            $st->bindValue(':n',$name);
            $st->bindValue(':d',$desc);
            $st->bindValue(':p',$price);
            $st->bindValue(':i',$img);
            $cat === null ? $st->bindValue(':c', null, PDO::PARAM_NULL) : $st->bindValue(':c', $cat, PDO::PARAM_INT);
            $st->bindValue(':s',$status, PDO::PARAM_INT);
            $st->execute();
            echo "<script>alert('✅ Thêm sản phẩm thành công!');</script>";
        }
    }

    // 🟢 Sửa sản phẩm
    if (isset($_POST['edit_product'])) {
        $id = (int)$_POST['p_id'];
        $name = trim($_POST['p_name'] ?? '');
        $desc = trim($_POST['p_desc'] ?? '');
        $price = (float)($_POST['p_price'] ?? 0);
        $cat   = ($_POST['p_category'] ?? '') !== '' ? (int)$_POST['p_category'] : null;
        $status= isset($_POST['p_status']) ? 1 : 0;
        $img = $_POST['p_image_old'] ?? null;

        if (!empty($_FILES['p_image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['p_image']['name'], PATHINFO_EXTENSION));
            $img = 'prod_' . $id . '_' . time() . '.' . $ext;
            $path = __DIR__ . '/../../../public/images/products/' . $img;
            @move_uploaded_file($_FILES['p_image']['tmp_name'], $path);
        }

        $sql = "UPDATE products 
                SET name=:n, description=:d, price=:p, image=:i, category_id=:c, status=:s 
                WHERE id=:id";
        $st = $pdo->prepare($sql);
        $st->bindValue(':n',$name);
        $st->bindValue(':d',$desc);
        $st->bindValue(':p',$price);
        $st->bindValue(':i',$img);
        $cat === null ? $st->bindValue(':c', null, PDO::PARAM_NULL) : $st->bindValue(':c', $cat, PDO::PARAM_INT);
        $st->bindValue(':s',$status, PDO::PARAM_INT);
        $st->bindValue(':id',$id, PDO::PARAM_INT);
        $st->execute();

        echo "<script>alert('✅ Cập nhật sản phẩm thành công!');</script>";
    }

    // 🗑️ Xóa sản phẩm
    if (isset($_POST['delete_product'])) {
        $id = (int)$_POST['p_id'];

        // lấy ảnh để xóa file
        $cur = $pdo->prepare("SELECT image FROM products WHERE id=:id");
        $cur->execute([':id'=>$id]);
        $row = $cur->fetch(PDO::FETCH_ASSOC);
        $img = $row['image'] ?? null;

        $del = $pdo->prepare("DELETE FROM products WHERE id=:id");
        $del->execute([':id'=>$id]);

        if ($img) {
            $f = __DIR__ . '/../../../public/images/products/' . $img;
            if (is_file($f)) @unlink($f);
        }
        echo "<script>alert('✅ Đã xóa sản phẩm!');</script>";
    }
}

// 🔹 Lấy dữ liệu danh mục & sản phẩm
$categories = $pdo->query("SELECT * FROM categories ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
$products   = $pdo->query("SELECT * FROM products ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-4">
  <h2 class="text-center fw-bold text-primary mb-4">
    🧩 Quản lý Danh mục & Sản phẩm <span class="text-warning">FastFood T&D</span>
  </h2>

  <!-- 🟡 DANH MỤC -->
  <div class="card shadow-lg border-0 mb-4">
    <div class="card-header bg-warning fw-bold text-dark">DANH MỤC SẢN PHẨM</div>
    <div class="card-body bg-white rounded-bottom">
      <form method="post" enctype="multipart/form-data" class="mb-4">
        <div class="row g-3">
          <div class="col-md-3"><input type="text" name="cat_name" class="form-control" placeholder="Tên danh mục" required></div>
          <div class="col-md-3"><input type="text" name="cat_desc" class="form-control" placeholder="Mô tả"></div>
          <div class="col-md-3"><input type="file" name="cat_image" class="form-control" accept="image/*"></div>
          <div class="col-md-2 d-flex align-items-center">
            <div class="form-check m-0">
              <input class="form-check-input me-2" type="checkbox" name="cat_status" value="1" id="cat_status" checked>
              <label class="form-check-label" for="cat_status">Hiển thị</label>
            </div>
          </div>
          <div class="col-md-1"><button type="submit" name="add_category" class="btn btn-success w-100">Thêm</button></div>
        </div>
      </form>

      <table class="table table-bordered text-center align-middle">
        <thead class="table-light">
          <tr>
            <th>STT</th><th>Tên</th><th>Mô tả</th><th>Ảnh</th><th>Trạng thái</th><th>Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1; foreach ($categories as $cat): ?>
          <tr>
            <form method="post" enctype="multipart/form-data">
              <td><span class="badge bg-secondary"><?= $i++ ?></span>
                  <input type="hidden" name="cat_id" value="<?= $cat['id'] ?>">
              </td>
              <td><input type="text" name="cat_name" class="form-control" value="<?= htmlspecialchars($cat['name']) ?>"></td>
              <td><input type="text" name="cat_desc" class="form-control" value="<?= htmlspecialchars($cat['description'] ?? '') ?>"></td>
              <td>
                <img src="/public/images/menu/<?= htmlspecialchars($cat['image'] ?? 'noimg.png') ?>" width="60">
                <input type="hidden" name="cat_image_old" value="<?= htmlspecialchars($cat['image'] ?? '') ?>">
                <input type="file" name="cat_image" class="form-control mt-1" accept="image/*">
              </td>
              <td class="text-center">
                <div class="form-check d-flex justify-content-center align-items-center m-0">
                  <input class="form-check-input me-1" type="checkbox" name="cat_status" value="1" <?= $cat['status'] ? 'checked' : '' ?>>
                  <label class="form-check-label small">Hiện</label>
                </div>
              </td>
              <td class="text-nowrap">
                <button type="submit" name="edit_category" class="btn btn-primary btn-sm">Lưu</button>
                <button type="submit" name="delete_category" class="btn btn-danger btn-sm" onclick="return confirm('Xóa danh mục này? Nếu còn sản phẩm sẽ không xóa được.')">Xóa</button>
              </td>
            </form>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- 🟢 SẢN PHẨM -->
  <div class="card shadow-lg border-0">
    <div class="card-header bg-success text-white fw-bold">DANH SÁCH SẢN PHẨM</div>
    <div class="card-body bg-white rounded-bottom">
      <form method="post" enctype="multipart/form-data" class="mb-4">
        <div class="row g-2 align-items-center">
          <div class="col-md-3"><input type="text" name="p_name" class="form-control" placeholder="Tên sản phẩm" required></div>
          <div class="col-md-2"><input type="number" name="p_price" class="form-control" min="0" step="1000" placeholder="Giá" required></div>
          <div class="col-md-2">
            <select name="p_category" class="form-select">
              <option value="">--Danh mục--</option>
              <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-2"><input type="file" name="p_image" class="form-control" accept="image/*"></div>
          <div class="col-md-1 d-flex align-items-center">
            <div class="form-check m-0">
              <input class="form-check-input me-1" type="checkbox" name="p_status" value="1" id="p_status" checked>
              <label class="form-check-label small" for="p_status">Hiện</label>
            </div>
          </div>
          <div class="col-md-2"><button type="submit" name="add_product" class="btn btn-success w-100"><i class="bi bi-plus-circle"></i> Thêm</button></div>
        </div>
        <div class="row mt-2"><div class="col"><input type="text" name="p_desc" class="form-control" placeholder="Mô tả sản phẩm"></div></div>
      </form>

      <table class="table table-striped align-middle text-center">
        <thead class="table-success">
          <tr>
            <th>STT</th><th>Tên</th><th>Mô tả</th><th>Giá</th><th>Ảnh</th><th>Danh mục</th><th>Hiện</th><th>Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <?php $stt = 1; foreach ($products as $p): ?>
          <tr>
            <form method="post" enctype="multipart/form-data">
              <td><span class="badge bg-secondary"><?= $stt++ ?></span>
                  <input type="hidden" name="p_id" value="<?= $p['id'] ?>">
              </td>
              <td><input type="text" name="p_name" class="form-control" value="<?= htmlspecialchars($p['name']) ?>"></td>
              <td><input type="text" name="p_desc" class="form-control" value="<?= htmlspecialchars($p['description']) ?>"></td>
              <td><input type="number" name="p_price" class="form-control" value="<?= (float)$p['price'] ?>"></td>
              <td>
                <img src="/public/images/products/<?= htmlspecialchars($p['image'] ?? 'noimg.png') ?>" width="60">
                <input type="hidden" name="p_image_old" value="<?= htmlspecialchars($p['image'] ?? '') ?>">
                <input type="file" name="p_image" class="form-control mt-1" accept="image/*">
              </td>
              <td>
                <select name="p_category" class="form-select">
                  <?php foreach ($categories as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $p['category_id'] == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                  <?php endforeach; ?>
                </select>
              </td>
              <td class="text-center">
                <div class="form-check d-flex justify-content-center align-items-center m-0">
                  <input class="form-check-input me-1" type="checkbox" name="p_status" value="1" <?= $p['status'] ? 'checked' : '' ?>>
                  <label class="form-check-label small">Hiện</label>
                </div>
              </td>
              <td class="text-nowrap">
                <button type="submit" name="edit_product" class="btn btn-primary btn-sm">Lưu</button>
                <button type="submit" name="delete_product" class="btn btn-danger btn-sm" onclick="return confirm('Xóa sản phẩm này?')">Xóa</button>
              </td>
            </form>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
