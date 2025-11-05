<?php
namespace App\Models;

use PDO;
use PDOException;

class Product
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // ✅ Lấy tất cả sản phẩm
    public function getAll(): array
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM products ORDER BY id ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new \Exception("Lỗi truy vấn Product::getAll(): " . $e->getMessage());
        }
    }

    // ✅ Lấy sản phẩm theo ID
    public function getById(int $id): ?array
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            throw new \Exception("Lỗi Product::getById(): " . $e->getMessage());
        }
    }

    // ✅ Thêm sản phẩm mới (Prepared Statement)
    public function create(string $name, string $desc, float $price, ?float $old_price, string $image): int
    {
        try {
            $sql = "INSERT INTO products (name, description, price, old_price, image)
                    VALUES (:name, :desc, :price, :old_price, :image)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'name' => htmlspecialchars($name),
                'desc' => htmlspecialchars($desc),
                'price' => $price,
                'old_price' => $old_price,
                'image' => htmlspecialchars($image)
            ]);
            return (int)$this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new \Exception("Lỗi Product::create(): " . $e->getMessage());
        }
    }
}
?>
