<?php
include 'conn.php'; // الاتصال بقواعد البيانات
session_start();

if (!isset($_SESSION['usermail'])) {
    // إذا لم يكن المستخدم مسجلاً الدخول، يتم توجيههم إلى صفحة تسجيل الدخول
    $_SESSION['login_befor']="قم بعمل تسجيل دخول بالبداية ";
    header("Location: login.php");
    exit();
}

include 'session_check.php'; // يضمن هذا الكود أن أي تفاعل مع الموقع سيجدد الجلسة، وإذا لم يتم التفاعل خلال 30 دقيقة، سيتم تسجيل الخروج تلقائيًا.

$currentPage = basename($_SERVER['PHP_SELF']); // الحصول على اسم الصفحة الحالية
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="preload" href="img/background6.jpg" as="image">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <title>مولد SHA-256</title>
    <style>
*{
    font-family: 'Tajawal', 'Cairo', sans-serif;
}
body {
    background-color: #f7f8fa;
    margin: 0;
    padding: 0;
    direction: rtl;
    color: #34495e;

    background-image: url('img/background6.jpg');
    background-size: cover; 
    background-position: center center; 
    background-attachment: fixed;
}

.container {
    width: 100%;
    max-width: 720px;
    margin: 20px auto;
    background-color: #ffffff;
    border-radius: 12px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    padding: 15px 40px 40px 40px;
    text-align: center;
    box-sizing: border-box;

    overflow: hidden;
    border-radius: 12px;
    position: relative;
}


.container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 7px;
    background: linear-gradient(90deg, #063064, #3e87cc);        }
.title {
    font-size: 36px;
    color: #07427d;
    font-weight: 700;
    margin-bottom: 30px;
}

textarea {
    width: 100%;
    padding: 14px;
    margin-bottom: 20px;
    border-radius: 8px;
    border: 1px solid #ddd;
    font-size: 16px;
    background-color: #f9f9f9;
    color: #333;
    min-height: 149px;
    resize: none;
}

textarea:focus {
    outline: none;
    border-color: #2980b9;
    background-color: #ffffff;
    box-shadow: 0 0 8px rgba(41, 128, 185, 0.2);
}

.btn-generate {
    background: linear-gradient(to right, #2980b9, #3498db);
    color: white;
    padding: 15px 30px;
    font-size: 16px;
    font-weight: 600;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 48%;
    margin-bottom: 15px;
}

.btn-generate:hover {
    background: linear-gradient(to right, #3498db, #2980b9);
    transform: scale(1.05);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.output-container {
            margin-top: 20px;
            padding: 11px;
            background: #f8f9fa;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: none;
        }
        .output-container span {
            font-size: 15.5px;
            word-wrap: break-word;
            font-weight: bold;
            color: #495057;
        }

.btn-copy {
    background: #00aaff;
    color: white;
    padding: 9px 10px;
    font-size: 12px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-copy:hover {
    background: #2980b9;
    transform: scale(1.05);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.input-error {
    border: 1px solid #e74c3c;
}

.message-2 {
            padding: 12px 20px;
            font-size: 15px;
            font-weight: 500;
            border-radius: 8px;
            width: fit-content;
            text-align: center;
            margin: 10px auto;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: none;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }
        
        .message-2.show {
            display: block;
            opacity: 1;
            transform: translateY(0);
            animation: fadeOut 0.5s ease 3s forwards;
        }
        
        .message-2.success {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            color: #ffffff;
            border-left: 4px solid rgba(0, 0, 0, 0.2);
        }
        
        .message-2.error {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: #ffffff;
            border-left: 4px solid rgba(0, 0, 0, 0.2);
        }
        
        .message-2 i {
            margin-left: 8px;
        }
        
        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateY(-10px);
                display: none;
            }
        }

    </style>
</head>

<?php
include 'header/header_hash.php'
?>
<body>
    <div class="container">
        <h2 class="title">مولد SHA-256</h2>
        <textarea id="inputText" placeholder="أدخل النص هنا..." spellcheck="false"></textarea>
        <button class="btn-generate" onclick="generateSHA256()">توليد SHA-256</button>
        <div id="outputContainer" class="output-container">
            <span id="outputHash"></span>
            <button class="btn-copy" onclick="copyToClipboard()">نسخ</button>
        </div>
        <br>
        <div class="message-2" id="message-2"></div> 

    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
    <script src="javascript/SHA-256.js?v=1.3"></script>
</body>
</html>
