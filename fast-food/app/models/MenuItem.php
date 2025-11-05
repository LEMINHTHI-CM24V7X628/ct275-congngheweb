<?php
namespace App\Models;

use PDO;
use PDOException;

class MenuItem
{
    private $conn;

    public function __construct($pdo = null)
    {
        try {
            if ($pdo) {
                $this->conn = $pdo;
            } else {
                $this->conn = new PDO(
                    "pgsql:host=localhost;port=5432;dbname=db_fastfood;",
                    "postgres",
                    "12345" // thay bằng mật khẩu PostgreSQL thật của bạn
                );
            }
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Lỗi kết nối CSDL trong MenuItem: " . $e->getMessage());
        }
    }

    /** ✅ Lấy toàn bộ danh mục menu */
    public function getAll()
    {
        $sql = "SELECT * FROM menu_items WHERE status = true ORDER BY id ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
