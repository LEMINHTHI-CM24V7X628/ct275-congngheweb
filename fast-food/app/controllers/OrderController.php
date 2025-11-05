<?php
namespace App\Controllers;

use PDO;
use Exception;

class OrderController
{
    private $pdo;

    public function __construct()
    {
        // 🔹 Kết nối PDO từ config
        require __DIR__ . '/../../config.php';
        $this->pdo = $pdo;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /** ✅ Trang theo dõi đơn hàng */
    public function list(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 🔹 Lấy user_id từ session
        $userId = $_SESSION['user']['id'] ?? ($_SESSION['user_id'] ?? 0);

        if (!$userId) {
            echo "<div style='padding:40px;text-align:center;color:red;'>⚠️ Bạn chưa đăng nhập!</div>";
            exit;
        }

        try {
            // 🔹 Kiểm tra PDO có tồn tại không
            if (!$this->pdo) {
                require __DIR__ . '/../../config.php';
                $this->pdo = $pdo;
            }

            // 🔹 Truy vấn danh sách đơn hàng theo user_id
            $stmt = $this->pdo->prepare("
                SELECT id, fullname, phone, address, total, created_at, status
                FROM orders
                WHERE user_id = :uid
                ORDER BY created_at DESC
            ");
            $stmt->execute([':uid' => $userId]);
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // 🔹 Kiểm tra xem có dữ liệu không (debug tạm thời)
            // echo '<pre>'; print_r($orders); exit;

            include __DIR__ . '/../views/pages/orders.php';
        } catch (Exception $e) {
            echo "<div style='color:red;text-align:center;padding:40px;'>
                    ❌ Lỗi khi truy vấn: " . htmlspecialchars($e->getMessage()) . "
                  </div>";
        }
    }
/** ✅ Xem chi tiết đơn hàng */
public function detail(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $userId = $_SESSION['user']['id'] ?? ($_SESSION['user_id'] ?? 0);
    $orderId = (int)($_GET['id'] ?? 0);

    if (!$userId || !$orderId) {
        header("Location: /?page=orders");
        exit;
    }

    try {
        // Lấy thông tin đơn hàng
        $stmt = $this->pdo->prepare("
            SELECT * FROM orders WHERE id = :oid AND user_id = :uid
        ");
        $stmt->execute([':oid' => $orderId, ':uid' => $userId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            echo "<div style='text-align:center;color:red;padding:40px;'>Không tìm thấy đơn hàng!</div>";
            exit;
        }

        // Lấy danh sách sản phẩm trong đơn hàng
        $sql = $this->pdo->prepare("
            SELECT p.name, oi.quantity, oi.price
            FROM order_items oi
            JOIN products p ON p.id = oi.product_id
            WHERE oi.order_id = :oid
        ");
        $sql->execute([':oid' => $orderId]);
        $items = $sql->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/../views/pages/order_detail.php';
    } catch (Exception $e) {
        echo "<div style='color:red;text-align:center;padding:40px;'>Lỗi: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}
/** ✅ Hủy đơn hàng */
public function cancel(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $userId  = $_SESSION['user']['id'] ?? ($_SESSION['user_id'] ?? 0);
    $orderId = (int)($_GET['id'] ?? 0);

    if (!$userId || !$orderId) {
        header("Location: /?page=orders");
        exit;
    }

    try {
        // Kiểm tra xem đơn có thuộc về user không & còn đang xử lý
        $stmt = $this->pdo->prepare("
            SELECT * FROM orders
            WHERE id = :oid AND user_id = :uid AND status = 'Đang xử lý'
        ");
        $stmt->execute([':oid' => $orderId, ':uid' => $userId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            echo "<script>alert('Không thể hủy đơn này!'); window.location='/?page=orders';</script>";
            exit;
        }

        // Cập nhật trạng thái
        $update = $this->pdo->prepare("
            UPDATE orders SET status = 'Đã hủy' WHERE id = :oid
        ");
        $update->execute([':oid' => $orderId]);
        // ✅ Cập nhật trạng thái đơn hàng
$update = $this->pdo->prepare("
    UPDATE orders SET status = 'Đã hủy' WHERE id = :oid
");
$update->execute([':oid' => $orderId]);

// ✅ Chuyển hướng về lại trang danh sách đơn hàng (không hiện popup)
header("Location: /?page=orders");
exit;

    } catch (Exception $e) {
        echo "<div style='color:red;text-align:center;padding:40px;'>❌ Lỗi: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}

    /** ✅ Xử lý đặt hàng (checkout) */
    public function checkout(): void
    {
        if (empty($_SESSION['user_id'])) {
            header("Location: /?page=login&redirect=/?page=checkout");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            include __DIR__ . '/../views/pages/checkout.php';
            return;
        }

        if (empty($_SESSION['cart'])) {
            header("Location: /?page=cart");
            exit;
        }

        $fullname = $_SESSION['full_name'] ?? '';
        $phone    = $_SESSION['phone'] ?? '';
        $address  = trim($_POST['address'] ?? '');
        $user_id  = (int)$_SESSION['user_id'];
        $total    = 0;

        foreach ($_SESSION['cart'] as $item) {
            $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }

        if ($address === '' || $total <= 0) {
            echo "<script>alert('Vui lòng nhập địa chỉ hợp lệ!'); history.back();</script>";
            exit;
        }

        try {
            $this->pdo->beginTransaction();

            // 🔹 Thêm đơn hàng
            $stmt = $this->pdo->prepare("
                INSERT INTO orders (user_id, fullname, phone, address, total, status, created_at)
                VALUES (:uid, :name, :phone, :addr, :total, 'Đang xử lý', NOW())
                RETURNING id
            ");
            $stmt->execute([
                ':uid'   => $user_id,
                ':name'  => $fullname,
                ':phone' => $phone,
                ':addr'  => $address,
                ':total' => $total
            ]);
            $order_id = $stmt->fetchColumn();

            // 🔹 Thêm sản phẩm vào bảng order_items
            $sqlItem = $this->pdo->prepare("
                INSERT INTO order_items (order_id, product_id, quantity, price)
                VALUES (:oid, :pid, :qty, :price)
            ");
            foreach ($_SESSION['cart'] as $item) {
                $sqlItem->execute([
                    ':oid'   => $order_id,
                    ':pid'   => $item['id'],
                    ':qty'   => $item['quantity'],
                    ':price' => $item['price']
                ]);
            }

            $this->pdo->commit();
            unset($_SESSION['cart']);
            header("Location: /app/views/pages/thankyou.php");
            exit;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            echo "<div style='color:red;text-align:center;padding:40px;'>❌ Lỗi: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
}
