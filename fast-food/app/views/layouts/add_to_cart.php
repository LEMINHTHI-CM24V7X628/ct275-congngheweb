<?php
session_start();

try {
    $pdo = new PDO("pgsql:host=localhost;port=5432;dbname=db_fastfood;", "postgres", "12345", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("<div style='padding:30px;text-align:center;color:red;'>Lỗi kết nối CSDL: " . htmlspecialchars($e->getMessage()) . "</div>");
}

// ✅ Nhận dữ liệu từ form
$product_id = (int)($_POST['product_id'] ?? 0);
$quantity   = max(1, (int)($_POST['quantity'] ?? 1));
$extras     = isset($_POST['extras']) ? array_map('trim', $_POST['extras']) : [];

// ❌ Nếu không có sản phẩm → quay về trang chủ
if ($product_id <= 0) {
    header("Location: /app/views/pages/home.php");
    exit;
}

// ✅ Nếu chưa đăng nhập → yêu cầu login, lưu lại sản phẩm đang chọn
if (empty($_SESSION['user_id'])) {
    $_SESSION['pending_product'] = [
        'id' => $product_id,
        'quantity' => $quantity,
        'extras' => $extras
    ];
    header("Location: /app/views/auth/dangnhap.php?redirect=/app/views/pages/checkout.php");
    exit;
}

// ✅ Lấy sản phẩm từ DB
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute([':id' => $product_id]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: /app/views/pages/home.php");
    exit;
}

// ✅ Tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ✅ Kiểm tra trùng
$found = false;
foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] === $product['id']) {
        $item['quantity'] += $quantity;
        $found = true;
        break;
    }
}

// ✅ Thêm mới nếu chưa có
if (!$found) {
    $_SESSION['cart'][] = [
        'id'       => (int)$product['id'],
        'name'     => htmlspecialchars($product['name']),
        'price'    => (float)($product['price'] ?? 0),
        'image'    => $product['image'] ?: 'noimg.png',
        'quantity' => $quantity,
        'extras'   => $extras
    ];
}

// ✅ Chuyển tới checkout nếu đăng nhập, ngược lại thì login sẽ xử lý tiếp
header("Location: /app/views/pages/checkout.php");
exit;
