<?php
session_start();
require_once 'config.php';

// معالجة التسجيل
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = clean_input($_POST['username']);
    $email = clean_input($_POST['email']);
    $password = $_POST['password'];
    
    // التحقق من البيانات
    if (empty($username) || empty($email) || empty($password)) {
        header("Location: ../HTML/Login.html?error=empty_signup");
        exit();
    }
    
    // التحقق من صحة البريد الإلكتروني
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../HTML/Login.html?error=invalid_email");
        exit();
    }
    
    // التحقق من طول كلمة المرور
    if (strlen($password) < 6) {
        header("Location: ../HTML/Login.html?error=weak_password");
        exit();
    }
    
    // التحقق من أن اسم المستخدم غير موجود مسبقاً
    $check_query = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        header("Location: ../HTML/Login.html?error=user_exists");
        exit();
    }
    
    // تشفير كلمة المرور
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // إدراج المستخدم الجديد
    $insert_query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);
    
    if (mysqli_stmt_execute($stmt)) {
        // تسجيل الدخول تلقائياً بعد التسجيل
        $user_id = mysqli_insert_id($conn);
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        
        header("Location: ../index.php");
        exit();
    } else {
        header("Location: ../HTML/Login.php?error=registration_failed");
        exit();
    }
    
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>