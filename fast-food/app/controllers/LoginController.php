<?php
namespace App\Controllers;

use PDO;
use PDOException;

class LoginController
{
    /** 🟢 Hiển thị form đăng nhập */
    public function index()
    {
        include __DIR__ . '/../views/auth/dangnhap.php';
    }

    /** 🟢 Xử lý đăng nhập */
    public function processLogin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        require __DIR__ . '/../../config.php';

        $phone = trim($_POST['phone'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $redirect = $_POST['redirect'] ?? '/';

        if ($phone === '' || $password === '') {
            echo "<script>alert('Vui lòng nhập đầy đủ thông tin!'); history.back();</script>";
            exit;
        }

        try {
            // 🔹 Kiểm tra người dùng
            $stmt = $pdo->prepare("SELECT * FROM users WHERE phone = :phone LIMIT 1");
            $stmt->execute(['phone' => $phone]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                echo "<script>alert('Số điện thoại chưa được đăng ký!'); history.back();</script>";
                exit;
            }

            // 🔹 Kiểm tra mật khẩu
            $isValid = password_verify($password, $user['password']) || $password === $user['password'];
            if (!$isValid) {
                echo "<script>alert('Sai mật khẩu!'); history.back();</script>";
                exit;
            }

            // ✅ Lưu session đầy đủ
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['full_name'] = $user['full_name'] ?? '';
            $_SESSION['phone']     = $user['phone'] ?? '';
            $_SESSION['role']      = $user['role'] ?? 'user';

            // ✅ Thêm biến $_SESSION['user'] cho các controller khác (OrderController dùng)
            $_SESSION['user'] = [
                'id'        => $user['id'],
                'full_name' => $user['full_name'] ?? '',
                'phone'     => $user['phone'] ?? '',
                'role'      => $user['role'] ?? 'user'
            ];

            // ✅ Điều hướng sau đăng nhập
            if ($user['role'] === 'admin') {
                header("Location: /?page=admin-dashboard");
            } else {
                // nếu có redirect (từ trang checkout hoặc orders), quay lại đúng trang đó
                header("Location: " . $redirect);
            }
            exit;

        } catch (PDOException $e) {
            echo "<script>alert('Lỗi CSDL: " . htmlspecialchars($e->getMessage()) . "');</script>";
        }
    }

    /** 🟢 Đăng xuất */
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header("Location: /index.php");
        exit;
    }
}
