<?php
namespace App\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\MenuItem;

class ProductController
{
    private $pdo;

    public function __construct()
    {
        // Kết nối CSDL (PostgreSQL)
        require __DIR__ . '/../../config.php';
        $this->pdo = $pdo;
    }

    /** 🏠 Trang chủ - hiển thị danh sách sản phẩm */
    public function index(): void
    {
        try {
            $productModel  = new Product($this->pdo);
            $categoryModel = new Category($this->pdo);
            $menuModel     = new MenuItem($this->pdo);

            $products   = $productModel->getAll();
            $categories = $categoryModel->getAll();
            $menus      = $menuModel->getAll();

            // Gọi view chính xác
            include __DIR__ . '/../views/pages/home.php';

        } catch (\Throwable $e) {
            $this->renderError("Lỗi tải dữ liệu", $e);
        }
    }

    /** 📦 Trang chi tiết sản phẩm */
    public function detail(): void
    {
        try {
            $productModel = new Product($this->pdo);
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

            if ($id <= 0) {
                throw new \Exception("ID sản phẩm không hợp lệ.");
            }

            $product = $productModel->getById($id);
            if (!$product) {
                throw new \Exception("Không tìm thấy sản phẩm có ID = $id.");
            }

            include __DIR__ . '/../views/pages/product_detail.php';

        } catch (\Throwable $e) {
            $this->renderError("Lỗi tải chi tiết sản phẩm", $e);
        }
    }

    /** ⚠️ Hiển thị lỗi chung */
    private function renderError(string $title, \Throwable $e): void
    {
        echo "<div style='color:red;text-align:center;margin-top:50px;'>
                <h4>{$title}</h4>
                <p>" . htmlspecialchars($e->getMessage()) . "</p>
              </div>";
    }
}
