<?php
// ملف الاتصال بقاعدة البيانات

// بيانات الاتصال بقاعدة البيانات
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'foodly_db');

// إنشاء الاتصال
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// فحص الاتصال
if (!$conn) {
    die("فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error());
}

// تعيين الترميز
mysqli_set_charset($conn, "utf8mb4");

// دالة مساعدة لتنظيف المدخلات
function clean_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return mysqli_real_escape_string($conn, $data);
}

// دالة للتحقق من تسجيل الدخول
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// دالة للتحقق من تسجيل دخول التاجر
function is_merchant_logged_in() {
    return isset($_SESSION['merchant_id']);
}

// دالة للحصول على بيانات المستخدم الحالي
function get_logged_user() {
    global $conn;
    if (!is_logged_in()) {
        return null;
    }
    
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM users WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) == 1) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

// دالة للحصول على طلبات المستخدم
function get_user_orders($user_id) {
    global $conn;
    $query = "SELECT o.*, 
              GROUP_CONCAT(oi.product_name SEPARATOR ', ') as items
              FROM orders o
              LEFT JOIN order_items oi ON o.order_id = oi.order_id
              WHERE o.user_id = ?
              GROUP BY o.order_id
              ORDER BY o.order_date DESC";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $orders = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }
    
    return $orders;
}
?>