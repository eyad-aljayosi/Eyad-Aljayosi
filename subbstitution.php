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
    <link rel="stylesheet" href="css/style-subbstitution.css?v=1.9">
    <link rel="preload" href="img/background6.jpg" as="image">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>تشفير subbstitution</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">

</head>

<?php
include 'header/header_encryption.php'
?>
<body>

    <div class="container">
        <h1 class="title">تشفير Subbstitution</h1>

        <label for="text-input">ادخل النص:</label>
        <textarea id="text-input" class="form-control" placeholder="أدخل النص هنا..." spellcheck="false"></textarea>

        <div class="button-container">
            <button class="btn btn-encrypt" onclick="encrypt()"> تشفير <i class="fas fa-lock"></i></button>
            <button class="btn btn-decrypt" onclick="decrypt()"> فك التشفير <i class="fas fa-unlock"></i></button>
        </div>



        <div class="result-container">
            <label for="output">النتيجة:</label>
            <textarea id="output" class="form-control" readonly placeholder="ستظهر النتيجة هنا..." spellcheck="false"></textarea>
        </div>
        <div class="message" id="message"></div> 

    </div>


<script src="javascript/Subbstitution.js?v=1.8"></script><!-- الاتصال بالكود الخاص بجافا سكريبت -->



