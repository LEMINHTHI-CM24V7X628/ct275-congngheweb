<?php
namespace App\Controllers;

use PDO;
use PDOException;

class RegisterController
{
    // 📌 Hiển thị form đăng ký
    public function index()
    {
        include __DIR__ . '/../views/auth/dangky.php';
    }

    // 📌 Xử lý đăng ký
    public function processRegister()
    {
        session_start();
        require __DIR__ . '/../../config.php';

        $full_name = $_POST['full_name'] ?? '';
        $email     = $_POST['email'] ?? '';
        $phone     = $_POST['phone'] ?? '';
        $address   = $_POST['address'] ?? '';
        $password  = $_POST['password'] ?? '';
        $confirm   = $_POST['confirm_password'] ?? '';

        if (empty($full_name) || empty($phone) || empty($password) || empty($confirm)) {
            echo "<script>alert('Vui lòng nhập đầy đủ thông tin bắt buộc!'); history.back();</script>";
            exit;
        }

        if ($password !== $confirm) {
            echo "<script>alert('Mật khẩu nhập lại không khớp!'); history.back();</script>";
            exit;
        }

        try {
            $check = $pdo->prepare("SELECT 1 FROM users WHERE phone = :phone OR email = :email");
            $check->execute(['phone' => $phone, 'email' => $email]);
            if ($check->rowCount() > 0) {
                echo "<script>alert('Số điện thoại hoặc email đã được đăng ký!'); history.back();</script>";
                exit;
            }

            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $role   = 'user';
            $status = true;

            $stmt = $pdo->prepare("
                INSERT INTO users (full_name, email, password, phone, address, role, status, created_at)
                VALUES (:full_name, :email, :password, :phone, :address, :role, :status, NOW())
            ");
            $ok = $stmt->execute([
                'full_name' => $full_name,
                'email'     => $email,
                'password'  => $hashed,
                'phone'     => $phone,
                'address'   => $address,
                'role'      => $role,
                'status'    => $status
            ]);

            if ($ok) {
                echo "<script>alert('Đăng ký thành công! Vui lòng đăng nhập.'); window.location='/?page=login';</script>";
            } else {
                echo "<script>alert('Không thể đăng ký!'); history.back();</script>";
            }
        } catch (PDOException $e) {
            echo "<pre style='color:red'><b>LỖI SQL:</b> " . $e->getMessage() . "</pre>";
        }
    }
}
