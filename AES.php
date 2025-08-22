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
    <link rel="stylesheet" href="css/style-AES.css?v=1.7">
    <link rel="preload" href="img/background6.jpg" as="image">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>تشفير وفك تشفير AES</title>

</head>

<body>
<?php include 'header/header_encryption.php' ?>

    <div class="container">
        <h1 class="title">تشفير وفك تشفير AES</h1>

        <label for="inputText">أدخل النص:</label>
        <textarea id="inputText" class="form-control" placeholder="أدخل النص هنا..." spellcheck="false"></textarea>

        <label for="password">أدخل كلمة المرور:</label>
        <input type="password" id="password" class="form-control" placeholder="أدخل كلمة المرور هنا..." />

        <div class="button-container">
            <button class="btn btn-encrypt" onclick="encryptText()"> تشفير <i class="fas fa-lock"></i></button>
            <button class="btn btn-decrypt" onclick="decryptText()"> فك التشفير <i class="fas fa-unlock"></i></button>
        </div>

        <div id="loading" style="display: none;">جاري المعالجة...</div>

        <div class="result-container">
            <label for="outputText">النص المشفر أو المفكوك:</label>
            <textarea id="outputText" class="form-control" readonly placeholder="سيتم عرض النص هنا" spellcheck="false"></textarea>
        </div>
        <div class="message" id="message"></div></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script>
    <script src="javascript/AES.js?v=1.7"></script>

</body>
</html>
