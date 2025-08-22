
<?php
include '../conn.php'; // الاتصال بقاعدة البيانات
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

 include '../session_check.php'; // يضمن هذا الكود أن أي تفاعل مع الموقع سيجدد الجلسة، وإذا لم يتم التفاعل خلال 30 دقيقة، سيتم تسجيل الخروج تلقائيًا.

 $currentPage = basename($_SERVER['PHP_SELF']); // الحصول على اسم الصفحة الحالية

?>


<!DOCTYPE html>
<html lang="ar" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">


    <style>
        /* التصميم العام للصفحة */
        body {
            margin: 0;
            padding-top: 90px; /* مساحة للهيدر الثابت */
            background-color: #f1f3f5;
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

        header.scrolled .profile-button img {
            width: 46px;
            height: 46px;
        }

        header.scrolled .header-links a {
            font-size: 19px;
        }

        .profile-button {
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



        /* صورة البروفايل */
        .profile-button img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            pointer-events: none;
            transition: box-shadow 0.3s ease-in-out;
        }
        
        .profile-button {
            border-radius: 50%;
        }

        .profile-button:hover {
            transform: scale(1.08);
            box-shadow: inset 0 8px 18px -4px rgba(128, 132, 158, 0.7), inset 0 -8px 18px -4px rgba(185, 192, 210, 0.4);
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



        /* تصميم القائمة الجانبية الخاصة بالبروفايل */
        .profile-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            display: none;
            z-index: 1103;
            transition: all 0.3s ease-in-out;
        }

        .profile-overlay.active {
            display: block;
            opacity: 1;
        }

        .profile-menu {
            position: fixed;
            top: 80px;
            left: 10px;
            width: auto;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            padding: 20px;
            transform: translateY(-20px);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            opacity: 0;
            display: none;
            z-index: 1104;
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
        }

        .profile-menu.active {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        /* رأس القائمة */
        .profile-header {
            display: flex;
            align-items: center;
            direction: rtl;
            gap: 15px;
            border-bottom: 2px solid #a3bbd2;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }

        .profile-header i {
            font-size: 28px;
            color: #0073e6;
        }

        .profile-header h3 {
            font-size: 20px;
            margin: 0;
            color: #333;
        }

        /* بيانات المستخدم */
        .profile-info p {
            font-size: 16px;
            margin: 21px 0;
            color: #444;
            display: flex;
            align-items: center;
            gap: 6px;
            direction: rtl;
        }

        .profile-info p span {
            font-weight: bold;
            font-size: 17.4px;
            color: #0c437a;
        }

        .profile-info i {
            color: #0073e6;
            font-size: 18px;
        }
        
        .email {
            text-transform: lowercase;
        }

        /* زر تسجيل الخروج */
        .logout-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #ff4b5c, #d32f2f);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 15px;
            transition: 0.3s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, #d32f2f, #b71c1c);
            transform: scale(1.05);
        }

        /* في حال قام اليوزر بالدخول كضيف تظهر له ازرار تسجيل الدخول وانشاء الحساب */
        .auth-buttons {
            display: flex;
            gap: 14px;
            direction: ltr;
        }

        .btnn {
            background: linear-gradient(135deg, #ffd700, #e6b800);
            padding: 12px 24px;
            border-radius: 30px;
            color: #052d5f !important;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            font-size: 16px;
        }


        /* إضافة تأثيرات عامة على الصفحة */
        h1, h2, h3 {
            margin: 0;
            padding: 10px 0;
        }
        
        .info_account {
            margin: 0;
            padding: 5px 0;
        }

        /* الشعار */
        .logo {
            display: inline-block;
            padding: 0;
            margin: 0;
            border-right: 2px solid #ccc;
            padding-right: 15px;
            transform: translateX(-10px);
        }

        .logo img {
            width: auto;
            height: 44px;
            border: none;
            margin: 0;
            margin-left: -16px;
        }

        .header-links a.active::after {
            transform: scaleX(1);
        }

        .header-links a.active {
            color: rgb(232, 187, 8);
            font-weight: bold;
        }

        @media (max-width: 768px) {

            
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
                font-size: 14px;
            }
            
            .logo img {
                height: 36px;
            }
            
            header.scrolled .logo img {
                height: 30px;
            }
            
            .profile-button img {
                width: 40px;
                height: 40px;
            }
            
            header.scrolled .profile-button img {
                width: 35px;
                height: 35px;
            }
        }

    </style>
</head>


    <header>
        <!-- نقل صورة البروفايل إلى الجهة اليسرى -->
        <button class="profile-button" onclick="toggleProfileMenu()">
            <img src="../img/Profile-logo.png" alt="Profile Picture"> <!-- صورة البروفايل -->
        </button>
        <div class="header-links">
            <a href="admin_page.php" class="<?php echo ($currentPage == 'admin_page.php') ? 'active' : ''; ?>">الرئيسة</a>
            <a href="logging.php" class="<?php echo ($currentPage == 'logging.php') ? 'active' : ''; ?>">سلوك المستخدم</a>
            
            <a href="add_challenge.php" class="<?php echo ($currentPage == 'add_challenge.php') ? 'active' : ''; ?>">إضافة تحدي جديد</a>
            <a href="manage_challenges.php" class="<?php echo ($currentPage == 'manage_challenges.php') ? 'active' : ''; ?>">ادارة التحديات</a>

            <a href="add_user.php" class="<?php echo ($currentPage == 'add_user.php') ? 'active' : ''; ?>">اضافه مستخدم جديد</a>

            <a href="add_admin.php" class="<?php echo ($currentPage == 'add_admin.php') ? 'active' : ''; ?>">اضافه مستخدم رئيسي جديد</a>

        
        </div>
    </header>
    
    <div class="profile-overlay" onclick="toggleProfileMenu()"></div>

<!-- القائمة الشخصية -->
<div class="profile-menu" id="profileMenu">
    <div class="profile-header">
        <i class="fas fa-user-circle"></i>
        <h3 class="info_account">معلومات حساب الادمن</h3>
    </div>

    <div class="profile-info">
        <p><i class="fas fa-user"></i> الاسم: <span><?php echo htmlspecialchars($_SESSION['username'] ?? 'غير معروف'); ?></span></p>
        <p><i class="fas fa-envelope"></i> الايميل: <span><?php echo htmlspecialchars($_SESSION['usermail'] ?? 'غير معروف'); ?></span></p>
    </div>

    <button class="logout-btn" onclick="window.location.href='../logout.php'">
        <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
    </button>
</div>

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
