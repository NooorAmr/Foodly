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
    // الحصول على بيانات الكارت (في الحقيقة لا نحفظها لأسباب أمنية)
    $card_number = clean_input($_POST['card_number']);
    $card_holder = clean_input($_POST['card_holder']);
    $expiry = clean_input($_POST['expiry']);
    $cvv = clean_input($_POST['cvv']);
    
    // التحقق من صحة البيانات الأساسية
    $card_number_clean = str_replace(' ', '', $card_number);
    
    if (strlen($card_number_clean) < 13 || strlen($card_number_clean) > 19) {
        header("Location: ../PHP/payment_visa.php?error=invalid_card");
        exit();
    }
    
    if (strlen($cvv) != 3) {
        header("Location: ../PHP/payment_visa.php?error=invalid_cvv");
        exit();
    }
    
    // هنا في الواقع يتم الاتصال ببوابة الدفع الحقيقية
    // لكن للمشروع التعليمي سنفترض أن الدفع نجح
    
    $user_id = $_SESSION['user_id'];
    $order_data = $_SESSION['current_order_data'];
    $total_price = $_SESSION['current_order_total'];
    
    // إدراج الطلب مع payment_method = visa
    $insert_order = "INSERT INTO orders (user_id, total_price, payment_method, order_status) VALUES (?, ?, 'visa', 'pending')";
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
        
        // التوجيه لصفحة النجاح
        header("Location: ../PHP/order_success.php?order_id=" . $order_id . "&payment=visa");
        exit();
    } else {
        header("Location: ../PHP/payment_visa.php?error=payment_failed");
        exit();
    }
    
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>