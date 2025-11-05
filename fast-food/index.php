<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Tự động nạp class theo PSR-4
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

// ✅ Import các controller bạn có
use App\Controllers\{
    LoginController,
    RegisterController,
    ProductController,
    OrderController,
    CartController,
    AdminOrderController
};

// ✅ Xác định route từ URL (?page=...)
$page = $_GET['page'] ?? 'home';

switch ($page) {

    // 🏠 Trang chủ
    case 'home':
        (new ProductController())->index();
        break;

    // 📦 Chi tiết sản phẩm
    case 'product':
        (new ProductController())->detail();
        break;

    // 🛒 Giỏ hàng
    case 'cart':
        (new CartController())->index();
        break;

    // ➕ Thêm vào giỏ hàng
    case 'cart/add':
        (new CartController())->add();
        break;

    // 🧾 Thanh toán
    case 'checkout':
        (new OrderController())->checkout();
        break;
// 🧾 Xem chi tiết đơn hàng
case 'order-detail':
    (new \App\Controllers\OrderController())->detail();
    break;
// ❌ Hủy đơn hàng
case 'order-cancel':
    (new \App\Controllers\OrderController())->cancel();
    break;

    // 👤 Đăng nhập
    case 'login':
        (new LoginController())->index();
        break;
    case 'login/process':
        (new LoginController())->processLogin();
        break;
    case 'logout':
        (new LoginController())->logout();
        break;

   // 📝 Hiển thị form đăng ký
case 'register':
    (new RegisterController())->index();
    break;

// 🧩 Xử lý đăng ký
case 'register/process':
    (new RegisterController())->processRegister();
    break;
    // ⚙️ ADMIN – Quản lý đơn hàng
    case 'admin-orders':
        (new AdminOrderController())->index();
        break;
    case 'admin-order-detail':
        (new AdminOrderController())->detail();
        break;
    // 🔄 Cập nhật giỏ hàng
    case 'cart/update':
        (new CartController())->update();
        break;
    case 'login/process':
        (new LoginController())->processLogin();
        break;
    case 'orders':
        (new OrderController())->list();
        break;
    // ❌ Xóa 1 sản phẩm
    case 'cart/remove':
        (new CartController())->remove();
        break;
    // 🧹 Xóa toàn bộ giỏ hàng
    case 'cart/clear':
        (new CartController())->clear();
        break;
    // 👥 Quản lý người dùng (admin)
    case 'admin-user-list':
        (new \App\Controllers\AdminUserController())->index();
        break;

    case 'admin-delete-user':
        (new \App\Controllers\AdminUserController())->delete();
        break;
    case 'admin-dashboard':
            include __DIR__ . '/app/views/admin/dashboard.php';
            break;
            // 🧾 Trang quản lý sản phẩm (Admin)
    case 'admin-product-list':
        include __DIR__ . '/app/views/admin/edit_home.php';
        break;
    // ✏️ Trang chỉnh sửa sản phẩm
    case 'admin-product-edit':
        include __DIR__ . '/app/views/admin/product_edit.php';
        break;
    // 🧾 Quản lý đơn hàng (Admin)
    case 'admin-orders-manage':
        include __DIR__ . '/app/views/admin/orders_manage.php';
        break;
        // ❌ Trang không tồn tại
        $page = $_GET['page'] ?? 'home';
        switch ($page) {
        case 'home':
            include 'app/views/pages/home.php';
            break;

        case 'cart':
            include 'app/views/pages/cart.php';
            break;
        }
          case 'cart-remove':
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $id = (int)($_GET['id'] ?? 0);

    // Nếu có trong giỏ thì xóa
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }

    // Nếu giỏ hàng trống, dọn luôn biến
    if (empty($_SESSION['cart'])) {
        unset($_SESSION['cart']);
    }

    // ✅ Quay về trang giỏ hàng (cart.php)
    header("Location: /?page=cart");
    exit;

    default:
        http_response_code(404);
        include __DIR__ . '/app/views/errors/404.php';
        break;
}
