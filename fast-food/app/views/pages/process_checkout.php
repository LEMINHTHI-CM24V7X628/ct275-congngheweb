<?php
session_start();
require_once __DIR__ . '/../../../config.php';

// Nếu giỏ hàng rỗng → quay lại
if (empty($_SESSION['cart'])) {
  header("Location: /?page=cart");
  exit;
}

// Lấy thông tin người dùng nhập từ form checkout
$fullname = trim($_POST['fullname'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$address  = trim($_POST['address'] ?? '');
$note     = trim($_POST['note'] ?? '');
$total    = 0;

// Tính tổng tiền
foreach ($_SESSION['cart'] as $item) {
  $price = (float)($item['price'] ?? 0);
  $qty   = (int)($item['quantity'] ?? 1);
  $total += $price * $qty;
}

// Kiểm tra dữ liệu
if ($fullname === '' || $phone === '' || $address === '' || $total <= 0) {
  echo "<script>alert('Vui lòng nhập đầy đủ thông tin giao hàng!'); history.back();</script>";
  exit;
}

try {
  // Bắt đầu transaction
  $pdo->beginTransaction();

  // ✅ Lưu đơn hàng (thêm fullname, phone, address, total)
  $stmt = $pdo->prepare("
    INSERT INTO orders (fullname, phone, address, total, created_at, status)
    VALUES (:fullname, :phone, :address, :total, NOW(), 'Đang xử lý')
    RETURNING id
  ");
  $stmt->execute([
    ':fullname' => $fullname,
    ':phone'    => $phone,
    ':address'  => $address,
    ':total'    => $total
  ]);

  $order_id = $stmt->fetchColumn();

  // ✅ Lưu từng món hàng vào order_items
  $sql_item = $pdo->prepare("
    INSERT INTO order_items (order_id, product_id, quantity, price)
    VALUES (:oid, :pid, :qty, :price)
  ");

  foreach ($_SESSION['cart'] as $item) {
    $sql_item->execute([
      ':oid'   => $order_id,
      ':pid'   => $item['id'],
      ':qty'   => $item['quantity'],
      ':price' => $item['price']
    ]);
  }

  $pdo->commit();
  unset($_SESSION['cart']); // Xóa giỏ hàng sau khi đặt

  // ✅ Chuyển hướng đến trang cảm ơn
  header("Location: /app/views/pages/thankyou.php");
  exit;

} catch (PDOException $e) {
  $pdo->rollBack();
  echo "<div style='color:red;text-align:center;padding:40px;'>
          ❌ Lỗi khi lưu đơn hàng: " . htmlspecialchars($e->getMessage()) . "
        </div>";
}
?>
