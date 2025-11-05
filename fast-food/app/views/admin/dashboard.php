<?php
// ✅ Bắt đầu session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: /?page=login');
    exit;
}

// ✅ Kết nối database (config nằm cùng cấp index.php)
require_once dirname(__DIR__, 3) . '/config.php';

// ✅ Lấy thống kê
try {
    $totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $totalOrders   = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    $totalRevenue  = $pdo->query("SELECT COALESCE(SUM(total), 0) FROM orders")->fetchColumn();
    $totalUsers    = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
} catch (PDOException $e) {
    echo "<div class='alert alert-danger text-center mt-4'>
            ⚠️ Lỗi truy vấn: " . htmlspecialchars($e->getMessage()) . "
          </div>";
    exit;
}

// ✅ Dữ liệu biểu đồ doanh thu
$revenueData = [];
$sql = "
  SELECT TO_CHAR(created_at, 'MM') AS month, SUM(total) AS revenue
  FROM orders
  WHERE EXTRACT(YEAR FROM created_at) = EXTRACT(YEAR FROM CURRENT_DATE)
  GROUP BY TO_CHAR(created_at, 'MM')
  ORDER BY month ASC";
$stmt = $pdo->query($sql);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $revenueData[$row['month']] = (float)$row['revenue'];
}

// ✅ Mảng 12 tháng
$months = [];
$revenues = [];
for ($i = 1; $i <= 12; $i++) {
    $key = str_pad($i, 2, '0', STR_PAD_LEFT);
    $months[] = "Tháng $i";
    $revenues[] = $revenueData[$key] ?? 0;
}

// ✅ Gọi header
include __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-danger">
      <i class="bi bi-speedometer2"></i> Bảng điều khiển
    </h3>
  </div>

  <!-- Thống kê -->
  <div class="row g-4">
    <div class="col-md-3">
      <div class="card text-center shadow-sm border-0 p-3">
        <i class="bi bi-box-seam fs-1 text-primary mb-2"></i>
        <h6 class="fw-bold text-secondary">SẢN PHẨM</h6>
        <h4 class="fw-bold text-dark"><?= number_format($totalProducts) ?></h4>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center shadow-sm border-0 p-3">
        <i class="bi bi-receipt fs-1 text-success mb-2"></i>
        <h6 class="fw-bold text-secondary">ĐƠN HÀNG</h6>
        <h4 class="fw-bold text-dark"><?= number_format($totalOrders) ?></h4>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center shadow-sm border-0 p-3">
        <i class="bi bi-cash-stack fs-1 text-warning mb-2"></i>
        <h6 class="fw-bold text-secondary">DOANH THU</h6>
        <h4 class="fw-bold text-danger"><?= number_format($totalRevenue, 0, ',', '.') ?> đ</h4>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center shadow-sm border-0 p-3">
        <i class="bi bi-people fs-1 text-info mb-2"></i>
        <h6 class="fw-bold text-secondary">KHÁCH HÀNG</h6>
        <h4 class="fw-bold text-dark"><?= number_format($totalUsers) ?></h4>
      </div>
    </div>
  </div>

  <hr class="my-4">

  <!-- Biểu đồ -->
  <div class="row">
    <div class="col-lg-8">
      <div class="card shadow-sm border-0 p-4">
        <h5 class="fw-bold text-secondary mb-3">
          <i class="bi bi-bar-chart"></i> Doanh thu theo tháng (<?= date('Y') ?>)
        </h5>
        <canvas id="revenueChart" height="120"></canvas>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card shadow-sm border-0 p-4">
        <h5 class="fw-bold text-secondary mb-3">
          <i class="bi bi-clock-history"></i> Đơn hàng mới nhất
        </h5>
        <?php
        $recent = $pdo->query("SELECT id, fullname, total, created_at FROM orders ORDER BY created_at DESC LIMIT 5");
        $orders = $recent->fetchAll(PDO::FETCH_ASSOC);
        if (empty($orders)) {
            echo "<div class='alert alert-info text-center'>Chưa có đơn hàng.</div>";
        } else {
            echo "<ul class='list-group'>";
            foreach ($orders as $o) {
                echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                        <span>" . htmlspecialchars($o['fullname']) . "</span>
                        <span class='text-danger fw-bold'>" . number_format($o['total'], 0, ',', '.') . " đ</span>
                      </li>";
            }
            echo "</ul>";
        }
        ?>
      </div>
    </div>
  </div>
</div>

<!-- Biểu đồ doanh thu -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('revenueChart');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: <?= json_encode($months, JSON_UNESCAPED_UNICODE) ?>,
    datasets: [{
      label: 'Doanh thu (VNĐ)',
      data: <?= json_encode($revenues) ?>,
      borderWidth: 1,
      backgroundColor: 'rgba(255, 99, 132, 0.5)',
      borderColor: 'rgba(255, 99, 132, 1)'
    }]
  },
  options: {
    scales: { y: { beginAtZero: true } }
  }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
