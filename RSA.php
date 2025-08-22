


<?php
include 'conn.php'; // الاتصال بقواعد البيانات
session_start(); // بداية الجلسة

if (!isset($_SESSION['usermail'])) {
    $_SESSION['login_befor'] = "قم بعمل تسجيل دخول بالبداية ";
    header("Location: login.php");
    exit();
}

include 'session_check.php'; // يضمن تجديد الجلسة تلقائيًا بعد التفاعل
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style-RSA.css?v=2.4">
    <link rel="preload" href="img/background6.jpg" as="image">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>RSA</title>

</head>

<?php
include 'header/header_encryption.php';
?>

<body>
    <div class="container">
        <h2 class="title">تشفير وفك تشفير RSA</h2>

        <!-- زر توليد المفاتيح -->
        <button class="btn-generate" onclick="generateKeys()"> توليد مفاتيح جديدة</button>

        <div class="content">
            <!-- قسم المفتاح العام على اليمين -->
            <div class="key-section">
                <h4>المفتاح العام:</h4>
                <textarea id="publicKey" placeholder="أدخل المفتاح العام هنا..." ></textarea>
                <button class="btn-copy" onclick="copyKey('publicKey')">نسخ المفتاح العام</button>

                <h4>أدخل النص للتشفير:</h4>
                <textarea id="plainText" placeholder="أدخل النص هنا..." spellcheck="false"></textarea>
                <button onclick="encryptText()"> تشفير النص <i class="fas fa-lock"></i></button>
                <h4>النص المشفر:</h4>
                <textarea id="encryptedText" placeholder="سوف يظهر النص هنا..." spellcheck="false" readonly></textarea>

            </div>

            <!-- قسم المفتاح الخاص وفك التشفير على اليسار -->
            <div class="encryption-section">
                <h4>المفتاح الخاص:</h4>
                <textarea id="privateKey" placeholder="أدخل المفتاح الخاص هنا..."></textarea>
                <button class="btn-copy" onclick="copyKey('privateKey')">نسخ المفتاح الخاص</button>

                <h4>أدخل النص المشفر لفك التشفير:</h4>
                <textarea id="cipherText" placeholder="أدخل النص المشفر هنا..." spellcheck="false"></textarea>
                <button onclick="decryptText()"> فك التشفير <i class="fas fa-unlock"></i></button>
                <h4>النص بعد فك التشفير:</h4>
                <textarea id="decryptedText" placeholder="سوف يظهر النص هنا..." spellcheck="false" readonly></textarea>
            </div>
        </div>
        <div class="message" id="message"></div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsencrypt/3.2.1/jsencrypt.min.js"></script>
    <script src="javascript/RSA.js?v=1.9"></script>

</body>
</html>


