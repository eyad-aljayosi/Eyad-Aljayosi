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
    <link rel="stylesheet" href="css/style-password_analysis.css?v=1.3">
    <link rel="preload" href="img/background6.jpg" as="image">
    <title>اختبار قوة كلمة السر</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">

</head>
<?php
include 'header/header_pass.php'
?>
<body>
<div class="container">
    <h1 class="title">اختبار قوة كلمة السر</h1>

    <div class="section">
        <label for="password">أدخل كلمة السر:</label>
        <input type="text" id="password" class="shift-input" placeholder="ادخل كلمة مرورك هنا">
        <div id="error-message" class="error-message">الرجاء كتابة كلمة السر</div>
    </div>

    <div class="button-container">
        <button class="btn-generate" onclick="evaluatePassword()">تحليل كلمة السر</button>
    </div>

    <div class="strength-bar-container">
        <div class="strength-bar" id="strength-bar">
            <span></span>
        </div>
    </div>

    <div class="result-container">
        <p id="result-text" class="result-text"></p>
    </div>

    <div class="password-info" id="password-info" style="display: none;">
        <p><strong>نصائح لتقوية كلمة السر:</strong></p>
        <ul id="password-details"></ul>
    </div>
</div>

<script src="javascript/password_analysis.js"></script>

</body>
</html>

