<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Chỉ cho phép admin truy cập
if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: /?page=login');
    exit;
}
?>
