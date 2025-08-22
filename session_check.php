<?php


// التحقق من مدة الجلسة
/*if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > 1800) { // 1800 ثانية = 30 دقيقة
    session_unset();     // إلغاء الجلسة
    session_destroy();   // تدمير الجلسة
    session_start();     // بدء جلسة جديدة لإضافة رسالة الجلسة

    $_SESSION['session_status'] = "تم إنهاء الجلسة بسبب عدم النشاط";

    // 🔹 حساب عدد المجلدات في المسار الحالي للوصول إلى جذر المشروع g-enc
    $path_to_root = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/") - 2);

    // 🔹 توجيه المستخدم إلى صفحة تسجيل الدخول داخل مجلد g-enc/
    header("Location: " . $path_to_root . "login.php");
    exit();
}

// تحديث وقت آخر نشاط
$_SESSION['last_activity'] = time();*/


// التحقق من مدة الجلسة فقط إذا كان المستخدم ليس في صفحة index.php
if (basename($_SERVER['PHP_SELF']) !== 'index.php' && isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > 1800) {
    session_unset();     // إلغاء الجلسة
    session_destroy();   // تدمير الجلسة
    session_start();     // بدء جلسة جديدة لإضافة رسالة الجلسة

    $_SESSION['session_status'] = "تم إنهاء الجلسة بسبب عدم النشاط";

    // 🔹 حساب عدد المجلدات في المسار الحالي للوصول إلى جذر المشروع g-enc
    $path_to_root = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/") - 2);

    // 🔹 توجيه المستخدم إلى صفحة تسجيل الدخول داخل مجلد g-enc/
    header("Location: " . $path_to_root . "login.php");
    exit();
}

// تحديث وقت آخر نشاط
$_SESSION['last_activity'] = time();
?>