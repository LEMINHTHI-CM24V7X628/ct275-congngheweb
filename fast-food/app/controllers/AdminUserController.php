<?php
namespace App\Controllers;

use PDO;

class AdminUserController
{
    private $pdo;

    public function __construct()
    {
        require __DIR__ . '/../../config.php';
        $this->pdo = $pdo;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // ✅ Kiểm tra quyền admin
        if (($_SESSION['role'] ?? '') !== 'admin') {
            echo "<script>alert('Bạn không có quyền truy cập khu vực này!'); window.location='/?page=home';</script>";
            exit;
        }
    }

    /** ✅ Hiển thị danh sách người dùng */
    public function index(): void
    {
        $stmt = $this->pdo->query("SELECT * FROM users ORDER BY id ASC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/../views/admin/admin_user_list.php';
    }

    /** ✅ Xóa tài khoản người dùng */
    public function delete(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            echo "<script>alert('ID người dùng không hợp lệ!'); history.back();</script>";
            exit;
        }

        // Không cho phép tự xóa chính mình
        if (!empty($_SESSION['user_id']) && $_SESSION['user_id'] == $id) {
            echo "<script>alert('Bạn không thể tự xóa tài khoản đang đăng nhập!'); history.back();</script>";
            exit;
        }

        try {
            $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute(['id' => $id]);

            echo "<script>alert('Đã xóa tài khoản thành công!'); window.location='/?page=admin-user-list';</script>";
        } catch (\PDOException $e) {
            echo "<script>alert('Lỗi khi xóa: " . htmlspecialchars($e->getMessage()) . "'); history.back();</script>";
        }
    }
}
