<?php
namespace App\Models;

class Category
{
    private array $list = [
        ["name" => "Bestseller",    "img" => "best.png"],
        ["name" => "Khuyến mãi",    "img" => "khuyenmai.png"],
        ["name" => "Thức Uống",     "img" => "thucuong.png"],
        ["name" => "Thức Ăn Nhẹ",   "img" => "thucannhe.png"],
        ["name" => "Cơm - Mì Ý",    "img" => "commi.png"],
        ["name" => "Phần Ăn Nhóm",  "img" => "phanan.png"],
        ["name" => "Combo",         "img" => "combo.png"],
        ["name" => "Gà Rán Phần",   "img" => "garan.png"],
        ["name" => "Burger",        "img" => "burger.png"]
    ];

    public function getAll(): array
    {
        return $this->list;
    }
}
?>
