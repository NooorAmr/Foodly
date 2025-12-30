<?php
session_start();

// مسح جميع متغيرات الجلسة
$_SESSION = array();

// حذف ملف الجلسة
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// إنهاء الجلسة
session_destroy();

// التوجيه لصفحة تسجيل الدخول
header("Location: ../PHP/Login.php");
exit();
?>