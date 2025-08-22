<?php
include 'conn.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usermail'])) {
    $_SESSION['login_befor'] = "قم بعمل تسجيل دخول بالبداية";
    header("Location: ../login.php");
    exit();
}

include 'session_check.php';

$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Tajawal', sans-serif;
            background-color: #f1f3f5;
            color: #333;
        }
        
        header {
            background: linear-gradient(135deg, #052d5f 0%, #0a5aa6 100%);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            position: relative;
            text-transform: capitalize; /* لجعل النص يبدو أكثر ترتيبًا */
            z-index: 1000;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2); /* حد سفلي خفيف */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .menu-button {
            background: none;
            border: none;
            cursor: pointer;
            color: white;
            font-size: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .menu-button:hover {
            color: rgb(232, 187, 8);
            transform: scale(1.1);
        }

        .header-links {
            display: flex;
            gap: 50px;
            justify-content: flex-start;
            align-items: center;
            flex: 1;
            direction: rtl;
            margin-right: 60px;
        }

        .header-links a {
            color: white;
            text-decoration: none;
            font-size: 22px;
            font-weight: 800;
            padding: 8px 0;
            position: relative;
            transition: color 0.3s;
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
            transition: transform 0.25s ease-out;
        }

        .header-links a:hover::after {
            transform: scaleX(1);
        }

        .header-links a.active {
            color: rgb(232, 187, 8);
            font-weight: bold;
        }

        .header-links a.active::after {
            transform: scaleX(1);
        }

        .menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: none;
            z-index: 900;
        }
        
        .menu-overlay.active {
            display: block;
        }

        .menu {
            position: fixed;
            top: 0;
            right: 0;
            width: 350px;
            height: 100%;
            background: linear-gradient(135deg, #1D2B64, #2a436f);
            color: #ecf0f1;
            box-shadow: -8px 0 35px rgba(0, 0, 0, 0.4);
            z-index: 1000;
            transform: translateX(100%);
            transition: transform 0.6s ease, background-color 0.3s ease;
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
            transform: rotate(360deg);        }

        .menu ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .menu ul li {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            transition: background-color 0.3s ease, transform 0.2s ease;

        }

        .menu ul li a {
            display: block;
            padding: 20px 24px;
            text-decoration: none;
            color: #ecf0f1;
            font-size: 19px;
            font-weight: 600;
            transition: background 0.3s ease, transform 0.3s ease, padding-left 0.3s ease;
            gap: 15px;
            border-left: 4px solid transparent;

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

        /* القوائم المنسدلة */
        .submenu {
            display: none;
            background: rgba(0, 0, 0, 0.2);
            padding-left: 20px;
        }

        .submenu.active {
            display: block;
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .submenu li {
            border-bottom: none;
        }

        .submenu li a {
            padding: 15px 20px;
            font-size: 16px;
        }

        .has-submenu > a::after {
            content: '\f078';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            left: 20px;
            transition: transform 0.3s;
        }

        .has-submenu.active > a::after {
            transform: rotate(180deg);
        }

        .logo img {
            height: 48px;
        }

        @media (max-width: 768px) {
            .menu {
                width: 280px;
            }
            
            .menu-header {
                padding: 16px;
                font-size: 22px;
            }
            
            .menu ul li a {
                padding: 16px 20px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <header>
        <button class="menu-button" onclick="toggleMenu()">☰</button>

        <div class="header-links">
            <a href="md5.php" class="<?php echo ($currentPage == 'md5.php') ? 'active' : ''; ?>">MD5</a>
            <a href="SHA-1.php" class="<?php echo ($currentPage == 'SHA-1.php') ? 'active' : ''; ?>">SHA-1</a>
            <a href="SHA-256.php" class="<?php echo ($currentPage == 'SHA-256.php') ? 'active' : ''; ?>">SHA-256</a>
        </div>

        <div class="logo">
            <a href="home.php">
                <img src="./img/logo-cyberbox gate2.png" alt="CyberBox Gate">
            </a>
        </div>

        <div class="menu-overlay" onclick="toggleMenu()"></div>
        <div class="menu">
            <div class="menu-header">
                <span>قائمة الخدمات</span>
                <button class="menu-button-x" onclick="toggleMenu()">✕</button>
            </div>
            <ul>
                <li><a href="home.php"><i class="fas fa-home"></i> الرئيسية</a></li>
                
                <li class="has-submenu">
                    <a href="#"><i class="fas fa-lock"></i> خدمات التشفير</a>
                    <ul class="submenu">
                        <li><a href="subbstitution.php"><i class="fas fa-exchange-alt"></i> التشفير الاستبدالي</a></li>
                        <li><a href="caesar.php"><i class="fas fa-shield-alt"></i> تشفير قيصر</a></li>
                        <li><a href="vigenere.php"><i class="fas fa-key"></i> تشفير فيجينير</a></li>
                    </ul>
                </li>
                
                <li><a href="Password_Generator.php"><i class="fas fa-key"></i> كلمات السر</a></li>
                
                <li class="has-submenu">
                    <a href="#"><i class="fas fa-image"></i> steganography</a>
                    <ul class="submenu">
                        <li><a href="steganography_image.php"><i class="fas fa-image"></i> في الصور</a></li>
                        <li><a href="steganography_audio.php"><i class="fas fa-music"></i> في الصوت</a></li>
                    </ul>
                </li>
                
                <li><a href="multi_system_converter.php"><i class="fas fa-exchange-alt"></i> محول الأنظمة الرقمية</a></li>
                
                <li class="has-submenu">
                    <a href="#"><i class="fas fa-hashtag"></i> Hash</a>
                    <ul class="submenu">
                        <li><a href="md5.php"><i class="fas fa-fingerprint"></i> MD5</a></li>
                        <li><a href="SHA-1.php"><i class="fas fa-hashtag"></i> SHA-1</a></li>
                        <li><a href="SHA-256.php"><i class="fas fa-barcode"></i> SHA-256</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </header>

    <script>
        function toggleMenu() {
            const menu = document.querySelector('.menu');
            const overlay = document.querySelector('.menu-overlay');
            menu.classList.toggle('active');
            overlay.classList.toggle('active');
            
            // إغلاق جميع القوائم المنسدلة عند فتح القائمة الرئيسية
            if (!menu.classList.contains('active')) {
                document.querySelectorAll('.submenu').forEach(sub => {
                    sub.classList.remove('active');
                });
                document.querySelectorAll('.has-submenu').forEach(item => {
                    item.classList.remove('active');
                });
            }
        }
        
        // إدارة القوائم المنسدلة
        document.querySelectorAll('.has-submenu > a').forEach(link => {
            link.addEventListener('click', function(e) {
                if (window.innerWidth > 768) { // فقط على أجهزة الكمبيوتر
                    e.preventDefault();
                    const submenu = this.nextElementSibling;
                    const parentItem = this.parentElement;
                    
                    // إغلاق القوائم الأخرى
                    document.querySelectorAll('.submenu').forEach(sub => {
                        if (sub !== submenu) sub.classList.remove('active');
                    });
                    document.querySelectorAll('.has-submenu').forEach(item => {
                        if (item !== parentItem) item.classList.remove('active');
                    });
                    
                    // تبديل القائمة الحالية
                    submenu.classList.toggle('active');
                    parentItem.classList.toggle('active');
                }
            });
        });
        
        // إغلاق القائمة عند النقر خارجها
        document.addEventListener('click', function(e) {
            const menu = document.querySelector('.menu');
            const menuButton = document.querySelector('.menu-button');
            
            if (!menu.contains(e.target) && e.target !== menuButton) {
                menu.classList.remove('active');
                document.querySelector('.menu-overlay').classList.remove('active');
                
                // إغلاق جميع القوائم المنسدلة
                document.querySelectorAll('.submenu').forEach(sub => {
                    sub.classList.remove('active');
                });
                document.querySelectorAll('.has-submenu').forEach(item => {
                    item.classList.remove('active');
                });
            }
        });
        
        // إغلاق القوائم المنسدلة عند تغيير حجم الشاشة
        window.addEventListener('resize', function() {
            if (window.innerWidth <= 768) {
                document.querySelectorAll('.submenu').forEach(sub => {
                    sub.classList.remove('active');
                });
                document.querySelectorAll('.has-submenu').forEach(item => {
                    item.classList.remove('active');
                });
            }
        });
    </script>
</body>
</html>