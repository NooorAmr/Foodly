<?php
session_start();
require_once 'config.php';

// التحقق من تسجيل الدخول
if (!is_logged_in()) {
    header("Location: ../PHP/Login.php?error=not_logged_in");
    exit();
}

// التحقق من وجود بيانات الطلب
if (!isset($_SESSION['current_order_data']) || !isset($_SESSION['current_order_total'])) {
    header("Location: ../HTML/ordering.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_method = $_POST['payment_method'];
    $user_id = $_SESSION['user_id'];
    $order_data = $_SESSION['current_order_data'];
    $total_price = $_SESSION['current_order_total'];
    
    // إذا اختار Cash - حفظ الطلب مباشرة
    if ($payment_method == 'cash') {
        // إدراج الطلب
        $insert_order = "INSERT INTO orders (user_id, total_price, payment_method, order_status) VALUES (?, ?, 'cash', 'pending')";
        $stmt = mysqli_prepare($conn, $insert_order);
        mysqli_stmt_bind_param($stmt, "id", $user_id, $total_price);
        
        if (mysqli_stmt_execute($stmt)) {
            $order_id = mysqli_insert_id($conn);
            
            // إدراج تفاصيل الطلب
            $insert_item = "INSERT INTO order_items (order_id, product_name, product_price, quantity) VALUES (?, ?, ?, 1)";
            $stmt_item = mysqli_prepare($conn, $insert_item);
            
            foreach ($order_data as $item) {
                mysqli_stmt_bind_param($stmt_item, "isd", $order_id, $item['name'], $item['price']);
                mysqli_stmt_execute($stmt_item);
            }
            
            mysqli_stmt_close($stmt_item);
            
            // مسح بيانات الطلب من الجلسة
            unset($_SESSION['current_order_data']);
            unset($_SESSION['current_order_total']);
            
            // التوجيه مع رسالة نجاح
            header("Location: ../PHP/order_success.php?order_id=" . $order_id);
            exit();
        }
        mysqli_stmt_close($stmt);
    }
    // إذا اختار Visa - التوجيه لصفحة الدفع
    elseif ($payment_method == 'visa') {
        header("Location: ../PHP/payment_visa.php");
        exit();
    }
}

mysqli_close($conn);
?>