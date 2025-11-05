<?php include __DIR__ . '/../../layouts/header.php'; ?>

<style>
  body {
    background: url('/public/images/bg-fastfood.jpg') no-repeat center center fixed;
    background-size: cover;
  }
  .table thead {
    background-color: #ffc107;
    color: #000;
    font-weight: bold;
  }
  .card-header {
    background-color: #ffc107;
    color: #000;
    font-weight: bold;
  }
  .title-bar {
    color: #007bff;
    font-weight: 700;
    font-size: 1.8rem;
    text-align: center;
    margin-bottom: 20px;
  }
  .btn-edit {
    background-color: #007bff;
    color: #fff;
  }
  .btn-delete {
    background-color: #dc3545;
    color: #fff;
  }
  .btn-edit:hover {
    background-color: #0069d9;
  }
  .btn-delete:hover {
    background-color: #c82333;
  }
</style>

<div class="container py-4">
  <div class="title-bar">
    <i class="bi bi-tools"></i> Quản lý trang chủ FastFood T&D
  </div>

  <div class="card shadow-lg border-0">
    <div class="card-header">
      Danh mục sản phẩm
      <button class="btn btn-success btn-sm float-end">
        <i class="bi bi-plus-circle"></i> Thêm danh mục
      </button>
    </div>

    <div class="card-body bg-white rounded-bottom">
      <table class="table table-bordered text-center align-middle shadow-sm">
        <thead>
          <tr>
            <th>ID</th>
            <th>Tên danh mục</th>
            <th>Mô tả</th>
            <th>Ảnh</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($categories as $cat): ?>
            <tr>
              <td><?= htmlspecialchars($cat['id']) ?></td>
              <td><?= htmlspecialchars($cat['name']) ?></td>
              <td><?= htmlspecialchars($cat['description'] ?? '') ?></td>
              <td>
                <img src="/public/images/menu/<?= htmlspecialchars($cat['image'] ?? 'noimg.png') ?>"
                     alt="Ảnh danh mục" width="35"
                     onerror="this.src='/public/images/noimg.png'">
              </td>
              <td>
                <?php if (!empty($cat['status'])): ?>
                  <i class="bi bi-check-square-fill text-success"></i> Hiện
                <?php else: ?>
                  <i class="bi bi-x-square-fill text-danger"></i> Ẩn
                <?php endif; ?>
              </td>
              <td>
                <a href="#" class="btn btn-sm btn-edit"><i class="bi bi-pencil"></i> Sửa</a>
                <a href="#" class="btn btn-sm btn-delete"><i class="bi bi-trash"></i> Xóa</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
