<?php
include 'conn.php'; // الاتصال بقواعد البيانات
session_start(); // بداية الجلسة

if (!isset($_SESSION['usermail'])) {
    // إذا لم يكن المستخدم مسجلاً الدخول، يتم توجيههم إلى صفحة تسجيل الدخول
    $_SESSION['login_befor']="قم بعمل تسجيل دخول بالبداية ";
    header("Location: login.php");
    exit();
} 
include 'session_check.php'; // يضمن هذا الكود أن أي تفاعل مع الموقع سيجدد الجلسة، وإذا لم يتم التفاعل خلال 30 دقيقة، سيتم تسجيل الخروج تلقائيًا.


?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style-Password_Generator.css?v=1.3">
    <link rel="preload" href="img/background6.jpg" as="image">

    <title>توليد كلمات مرور </title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">

</head>
<?php
include 'header/header_pass.php'
?>
<body>
<div class="container">
    <h1 class="title">توليد كلمات السر </h1>
<div class="section">
    <label for="password-length">طول كلمة السر:</label>
    <input type="number" id="password-length" class="shift-input" min="8" max="64" value="16">
    
    <!-- خيارات تخصيص كلمة المرور -->
    <div class="checkbox-container">
        <div>
            <label>تشمل الأحرف الكبيرة؟</label>
            <input type="checkbox" id="include-uppercase" checked>
        </div>
        <div>
            <label>تشمل الأرقام؟</label>
            <input type="checkbox" id="include-numbers" checked>
        </div>
        <div>
            <label>تشمل الرموز الخاصة؟</label>
            <input type="checkbox" id="include-symbols" checked>
        </div>
    </div>
</div>

<div class="button-container">
        <button class="btn-generate"  onclick="generatePassword()">توليد كلمة السر</button>
    </div>

    <div class="result-container">
        <label for="generated-password">كلمة السر:</label>
        <input type="text" id="passwordField" readonly>
    </div>

    <div class="strength-bar-container">
        <p id="strength-label" style="display: none;"><strong>قوة كلمة السر:</strong></p>
        <div class="strength-bar" id="strength-bar" style="display: none;">
            <span></span>
        </div>
        <p id="strength-text" style="display: none;"></p>
    </div>

    
    <div class="password-info" id="password-info" style="display: none;">
        <p><strong>معلومات عن كلمة السر:</strong></p>
        <ul id="password-details"></ul>
    </div>
    
</div>

<script src="javascript/Password_Generator.js"></script>

</body>
</html>

