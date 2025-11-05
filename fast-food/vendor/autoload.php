<?php
/**
 * autoload.php – PSR-4 autoload đơn giản cho dự án FastFood
 */

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';

    // Nếu không thuộc namespace App thì bỏ qua
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Lấy đường dẫn file
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // Nạp file nếu tồn tại
    if (is_file($file)) {
        require_once $file;
    } else {
        // Hiển thị lỗi dễ debug (chỉ bật khi dev)
        error_log("⚠️ Autoload: Không tìm thấy file cho class: $class ($file)");
    }
});
