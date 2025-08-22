<?php
include 'conn.php'; // الاتصال بقواعد البيانات

// التحقق من وجود الجلسة وبيانات المستخدم
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/*تجنب الخطأ الناتج عن محاولة تشغيل الجلسة أكثر من مرة في نفس السكربت.
ضمان تشغيل الجلسة فقط عند الحاجة، مما يحسن الأداء ويمنع مشاكل التكرار.
متى نستخدمه؟
عند كتابة ملفات مشتركة (shared files) */

if (!isset($_SESSION['usermail'])) {
    // إذا لم يكن المستخدم مسجلاً الدخول، يتم توجيههم إلى صفحة تسجيل الدخول
    $_SESSION['login_befor']="قم بعمل تسجيل دخول بالبداية ";
    header("Location: ../login.php");
    exit();
}

include 'session_check.php'; // يضمن هذا الكود أن أي تفاعل مع الموقع سيجدد الجلسة، وإذا لم يتم التفاعل خلال 30 دقيقة، سيتم تسجيل الخروج تلقائيًا.

$currentPage = basename($_SERVER['PHP_SELF']); // الحصول على اسم الصفحة الحالية
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css-header/style-header_pass.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        
    /* التصميم العام للصفحة */
    body {
            margin: 0;
            padding-top: 90px; /* مساحة للهيدر الثابت */
            background-color: #f1f3f5;
            color: #333;
        }

        /* الهيدر الثابت */
        * {
            font-family: 'Tajawal', 'Cairo', sans-serif;
        }

        header {
            background: linear-gradient(135deg, #052d5f 0%, #0a5aa6 100%);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            font-family: 'Tajawal', 'Cairo', sans-serif;
            text-transform: capitalize;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        /* تصغير الهيدر عند التمرير */
        header.scrolled {
            padding: 10px 40px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        header.scrolled .logo img {
            height: 45px;
        }



        header.scrolled .header-links a {
            font-size: 19.4px;
        }

        .menu-button, .profile-button {
            background: none;
            border: none;
            cursor: pointer;
            color: white;
            font-size: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .menu-button:hover {
            color: rgb(232, 187, 8);
            transform: scale(1.1);
        }

         /* تنسيق روابط الهيدر */
         .header-links {
            display: flex;
            gap: 50px;
            justify-content: flex-start;
            align-items: center;
            flex: 1;
            direction: rtl;
            margin-right: 60px;
            transition: all 0.3s ease;
        }

        .header-links a {
            color: white;
            text-decoration: none;
            font-size: 19.4px;
            font-weight: 600; 
            padding: 8px 0;
            position: relative;
            transition: color 0.3s, transform 0.3s, letter-spacing 0.3s;
        }

        .header-links a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 2px;
            background-color: #e6b800;
            transform: scaleX(0);
            transform-origin: bottom right;
            transition: transform 0.25s ease-out;
        }

        .header-links a:hover {
            color: #e6b800;
            transform: translateY(-2px);
            letter-spacing: 1px;
        }

        .header-links a:hover::after {
            transform: scaleX(1);
            transform-origin: bottom left;
        }

        /* إعدادات القائمة الجانبية */
        .menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: none;
            z-index: 1101;
        }

        .menu-overlay.active {
            display: block;
        }

        /* تصميم القائمة الجانبية */
        .menu {
            position: fixed;
            top: 0;
            right: 0;
            width: 350px;
            height: 100%;
            background: linear-gradient(135deg, #1D2B64, #2a436f, #4e73b4);
            color: #ecf0f1;
            box-shadow: -8px 0 35px rgba(0, 0, 0, 0.4);
            z-index: 1102;
            transform: translateX(100%);
            transition: transform 0.6s ease, background-color 0.3s ease;
            border-radius: 20px 0 0 20px;
            overflow-y: auto;
        }

        .menu.active {
            transform: translateX(0);
        }

        .menu-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px;
            font-size: 26px;
            color: #ffffff;
            background: linear-gradient(135deg, #2a436f, #4e73b4);
            border-bottom: 2px solid #ecf0f1;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            transition: background-color 0.3s ease;
        }

        .menu-header button {
            background: none;
            border: none;
            font-size: 30px;
            color: #ecf0f1;
            cursor: pointer;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .menu-header button:hover {
            color: rgb(232, 8, 8);
            transform: rotate(360deg);
        }

        .menu ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .menu ul li {
            border-bottom: 1px solid #7f8c8d;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .menu ul li a {
            display: flex;
            align-items: center;
            padding: 27px 24px;
            text-decoration: none;
            color: #ecf0f1;
            font-size: 21px;
            font-weight: 600;
            transition: background 0.3s ease, transform 0.3s ease, padding-left 0.3s ease;
            border-left: 4px solid transparent;
            gap: 15px;
        }

        .menu ul li a i {
            width: 25px;
            text-align: center;
            font-size: 20px;
            transition: transform 0.3s ease;
        }

        .menu ul li a:hover {
            background: #345a7f;
            color: #ecf0f1;
            padding-left: 32px;
            transform: translateX(5px);
            border-left-color: #81c1d7;
            box-shadow: inset 10px 0px 20px rgba(129, 193, 215, 0.4);
        }

        .menu ul li a:hover i {
            transform: scale(1.2);
            color: #81c1d7;
        }

        .menu ul li a:active {
            background: #2a436f;
            color: #ecf0f1;
            padding-left: 32px;
            transform: translateX(3px);
        }

    /* تكبير حجم الصورة */
    .logo img {
        width: auto; /* عرض الصورة يتكيف مع حجمها الطبيعي */
        height: 48px; /* تم تكبير الحجم إلى 60px (يمكنك تعديله حسب الحجم المطلوب) */
        border: none; /* إزالة الحدود */
        margin: 0; /* إزالة الهوامش */
    }


.header-links a.active::after {
            transform: scaleX(1);
        }

        .header-links a.active {
            color: rgb(232, 187, 8);
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .menu {
                width: 270px;
            }

            .menu-header {
                font-size: 24px;
                padding: 16px;
            }

            .menu ul li a {
                font-size: 18px;
                padding: 16px 20px;
                gap: 10px;
            }
            
            .menu ul li a i {
                font-size: 18px;
            }

            
            /* تعديلات للهاتف */
            header {
                padding: 15px 20px;
            }
            
            header.scrolled {
                padding: 8px 20px;
            }
            
            .header-links {
                gap: 20px;
                margin-right: 30px;
            }
            
            .header-links a {
                font-size: 16px;
            }
            
            header.scrolled .header-links a {
                font-size: 77px;
            }
            
            .logo img {
                height: 36px;
            }
            
            header.scrolled .logo img {
                height: 30px;
            }

        
        }
    </style>
<header class="header_pass">
    <button class="menu-button" onclick="toggleMenu()">☰</button>

    <div class="header-links">
        <a href="Password_Generator.php" class="<?php echo ($currentPage == 'Password_Generator.php') ? 'active' : ''; ?>">توليد كلمة سر</a>
        <a href="password_analysis.php" class="<?php echo ($currentPage == 'password_analysis.php') ? 'active' : ''; ?>">اختبار كلمة سر</a>
    </div>

    <div class="logo">
            <a href="home.php">
                <img src="./img/logo_cyberbox_gate.png" alt="CyberBox Gate">
                
            </a>
        </div>

        <div class="menu-overlay" onclick="toggleMenu()"></div>
<div class="menu">
    <div class="menu-header">
        <span>قائمة الخدمات</span>
        <button class="menu-button-x" onclick="toggleMenu()">X</button>
    </div>
    <ul>
        <li><a href="home.php"><i class="fas fa-home"></i> الرئيسية</a></li>
        <li><a href="subbstitution.php"><i class="fas fa-lock"></i> خدمات التشفير</a></li>
        <li><a href="Password_Generator.php"><i class="fas fa-key"></i>كلمات السر</a></li>
        <li><a href="steganography.php"><i class="fas fa-image"></i> steganography</a></li><!--fas fa-eye-slash-->
        <li><a href="multi_system_converter.php"><i class="fas fa-exchange-alt"></i> محول الأنظمة الرقمية</a></li>
        <li><a href="md5.php"><i class="fas fa-hashtag"></i> Hash</a></li>
    </ul>
</div>
</header>
<script>
        // كود تصغير الهيدر عند التمرير
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // وظائف القوائم
        function toggleMenu() {
            const menu = document.querySelector('.menu');
            const overlay = document.querySelector('.menu-overlay');
            menu.classList.toggle('active');
            overlay.classList.toggle('active');
            
            // منع التمرير عند فتح القائمة
            document.body.style.overflow = menu.classList.contains('active') ? 'hidden' : '';
        }

        function toggleProfileMenu() {
            const profileMenu = document.querySelector('.profile-menu');
            const overlay = document.querySelector('.profile-overlay');
            
            if (profileMenu.classList.contains('active')) {
                profileMenu.classList.remove('active');
                overlay.classList.remove('active');
            } else {
                profileMenu.classList.add('active');
                overlay.classList.add('active');
            }
        }
        
        // إغلاق القوائم عند النقر خارجها
        document.addEventListener('click', function(event) {
            const profileButton = document.querySelector('.profile-button');
            const profileMenu = document.querySelector('.profile-menu');
            const profileOverlay = document.querySelector('.profile-overlay');
            
            if (profileButton && !profileButton.contains(event.target) && 
                profileMenu && !profileMenu.contains(event.target) && 
                profileOverlay.classList.contains('active')) {
                profileMenu.classList.remove('active');
                profileOverlay.classList.remove('active');
            }
        });
    </script>