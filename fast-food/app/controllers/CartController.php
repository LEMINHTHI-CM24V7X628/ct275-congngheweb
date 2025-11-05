<?php
namespace App\Controllers;

use App\Models\Product;
use PDO;

class CartController
{
    private $pdo;

    public function __construct()
    {
        require __DIR__ . '/../../config.php';
        $this->pdo = $pdo;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /** ✅ Hiển thị giỏ hàng */
    public function index(): void
    {
        include __DIR__ . '/../views/pages/cart.php';
    }

    /** ✅ Thêm sản phẩm vào giỏ hàng (Không alert, chỉ redirect) */
    public function add(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header("Location: /?page=home");
            exit;
        }

        // Lấy thông tin sản phẩm từ DB
        $productModel = new Product($this->pdo);
        $product = $productModel->getById($id);

        if (!$product) {
            header("Location: /?page=home");
            exit;
        }

        // Tạo giỏ hàng nếu chưa có
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Nếu sản phẩm đã tồn tại → tăng số lượng
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = [
                'id'       => $product['id'],
                'name'     => $product['name'],
                'price'    => $product['price'],
                'quantity' => 1,
                'image'    => $product['image'] ?? 'noimg.png'
            ];
        }

        // ✅ Không alert, chỉ chuyển hướng về giỏ hàng
        header("Location: /?page=cart");
        exit;
    }

    /** ✅ Cập nhật số lượng */
    public function update(): void
    {
        if (!empty($_POST['quantity'])) {
            foreach ($_POST['quantity'] as $id => $qty) {
                $id  = (int)$id;
                $qty = (int)$qty;
                if ($qty <= 0) {
                    unset($_SESSION['cart'][$id]);
                } else {
                    $_SESSION['cart'][$id]['quantity'] = $qty;
                }
            }
        }

        // ✅ Chuyển hướng về giỏ hàng, không alert
        header("Location: /?page=cart");
        exit;
    }

    /** ✅ Xóa 1 sản phẩm */
    public function remove(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0 && isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }

        // Nếu giỏ hàng trống → dọn session
        if (empty($_SESSION['cart'])) {
            unset($_SESSION['cart']);
        }

        // ✅ Redirect
        header("Location: /?page=cart");
        exit;
    }

    /** ✅ Xóa toàn bộ giỏ hàng */
    public function clear(): void
    {
        unset($_SESSION['cart']);

        // ✅ Redirect
        header("Location: /?page=cart");
        exit;
    }
}
