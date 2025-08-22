<?php
include 'conn.php'; // الاتصال بقاعدة البيانات
session_start();

// التحقق من وجود الجلسة وبيانات المستخدم
if (!isset($_SESSION['usermail'])) {
    // إذا لم يكن المستخدم مسجلاً الدخول، يتم توجيههم إلى صفحة تسجيل الدخول
    $_SESSION['login_befor'] = "الرجاء عمل تسجيل دخول";
    header("Location: login.php");
    exit();
}

include 'session_check.php'; // يضمن هذا الكود أن أي تفاعل مع الموقع سيجدد الجلسة
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style-File_encryption.css?v=2.4">
    <link rel="preload" href="img/background6.jpg" as="image">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <title>File Encryption</title>

</head>
<body>
    <?php include 'header/header_encryption.php' ?>

    <div class="container">
        <h1 class="title">تشفير الملفات</h1>
        <label for="fileInput">اختر ملفًا للتشفير أو فك التشفير:</label>
        
        <div class="file-input-container" id="dropArea">
            <i class="fas fa-cloud-upload-alt"></i>
            <span>اسحب وأفلت الملف هنا أو انقر للاختيار</span>
            <input type="file" id="fileInput">
            <div class="file-info" id="fileInfo">
                <div class="file-name" id="fileName"></div>
                <div class="file-size" id="fileSize"></div>
                <div class="file-type" id="fileType"></div>
            </div>
        </div>

        <label for="password">كلمة المرور:</label>
        <input type="password" id="password" placeholder="أدخل كلمة المرور هنا" autocomplete="off">

        <div class="button-container">
            <button class="btn-encrypt" onclick="encryptFile()">
                <i class="fas fa-lock"></i> تشفير الملف
            </button>
            <button class="btn-decrypt" onclick="decryptFile()">
                <i class="fas fa-unlock"></i> فك التشفير
            </button>
        </div>
        
        <div id="loading">
            <div class="loading-spinner"></div>
            <p>جاري المعالجة...</p>
        </div>
        
        <div class="message" id="message"></div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script>
    <script src="javascript/File_encryption.js"></script>
    </body>
</html>