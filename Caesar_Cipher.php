<?php
include 'conn.php';
session_start();
if (!isset($_SESSION['usermail'])) {
    $_SESSION['login_befor'] = "قم بعمل تسجيل دخول بالبداية";
    header("Location: login.php");
    exit();
}
include 'session_check.php';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style-Caesar_Cipher.css?v=2.6">
    <link rel="preload" href="img/background6.jpg" as="image">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>تشفير Caesar</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

</head>
<body>
    <?php include 'header/header_encryption.php' ?>

    <div class="container">
        <h1 class="title-h1">تشفير وفك تشفير Caesar</h1>

        <label for="message">ادخل النص:</label>
        <textarea id="message" class="form-control" placeholder="أدخل النص هنا..." spellcheck="false"></textarea>

        <div class="shift-container">
            <label for="shift">مقدار الإزاحة:</label>
            <input type="number" id="shift" class="shift-input" min="1" max="30" value="3">
        </div>

        <div class="button-container">
            <button class="btn btn-encrypt" onclick="encrypt()">تشفير <i class="fas fa-lock"></i></button>
            <button class="btn btn-decrypt" onclick="decrypt()">فك التشفير <i class="fas fa-unlock"></i></button>
        </div>

        <div class="result-container">
            <label for="output">الناتج:</label>
            <textarea id="output" class="form-control" readonly placeholder="سيظهر الناتج هنا..." spellcheck="false"></textarea>
        </div>
        
        <div class="message-2" id="message-2"></div>
    </div>

    <script src="javascript/caesar_cipher.js?v=3.0"></script>
    </body>
</html>


