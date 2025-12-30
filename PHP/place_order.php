<?php
session_start();
require_once 'config.php';

// التحقق من تسجيل الدخول
if (!is_logged_in()) {
    header("Location: ../PHP/Login.php?error=not_logged_in");
    exit();
}

// معالجة الطلب
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_data = json_decode($_POST['order_data'], true);
    $total_price = floatval($_POST['total_price']);
    $user_id = $_SESSION['user_id'];
    
    // التحقق من صحة البيانات
    if (empty($order_data) || $total_price <= 0) {
        header("Location: ../HTML/ordering.html?error=empty_cart");
        exit();
    }
    
    // حفظ بيانات الطلب في الجلسة لاستخدامها في الفاتورة
    $_SESSION['current_order_data'] = $order_data;
    $_SESSION['current_order_total'] = $total_price;
    
    // التوجيه إلى صفحة الفاتورة
    header("Location: ../PHP/invoice.php");
    exit();
}

mysqli_close($conn);
?>