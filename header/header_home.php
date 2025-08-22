<?php
include 'conn.php'; // الاتصال بقاعدة البيانات
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'session_check.php'; // يضمن هذا الكود أن أي تفاعل مع الموقع سيجدد الجلسة، وإذا لم يتم التفاعل خلال 30 دقيقة، سيتم تسجيل الخروج تلقائيًا.

$currentPage = basename($_SERVER['PHP_SELF']); // الحصول على اسم الصفحة الحالية
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css-header/style-header_home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">

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

        header.scrolled .profile-button img {
            width: 46px;
            height: 46px;
        }

        header.scrolled .header-links a {
            font-size: 19px;
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

        /* Dropdown Styles */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-btn {
            color: white;
            text-decoration: none;
            font-size: 19.4px;
            font-weight: 600;
            padding: 8px 0;
            position: relative;
            transition: color 0.3s, transform 0.3s, letter-spacing 0.3s;
            cursor: pointer;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background: linear-gradient(135deg, #052d5f 0%, #0a5aa6 100%);
            min-width: 200px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 8px;
            right: 0;
            top: 100%;
            padding: 10px 0;
        }

        .dropdown-content a {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            font-size: 16px;
            transition: all 0.3s;
            text-align: right;
        }

        .dropdown-content a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            padding-right: 25px;
            color: #e6b800;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown:hover .dropdown-btn {
            color: #e6b800;
            transform: translateY(-2px);
            letter-spacing: 1px;
        }

        .dropdown-btn::after {
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

        .dropdown:hover .dropdown-btn::after {
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

        .btn {
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

        .btn:hover {
            background: linear-gradient(135deg, #e6b800, #cfa700);
            transform: scale(1.1);
        }

        .auth-buttons a:first-child {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .auth-buttons a:last-child {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
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

            .profile-menu {
                width: 80%;
                left: 10%;
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
<body>
    <header>
        <button class="menu-button" onclick="toggleMenu()">☰</button>
        
        <div class="header-links">
            <a href="home.php" class="<?php echo ($currentPage == 'home.php') ? 'active' : ''; ?>">الرئيسية</a>
            
            <!-- قسم التعليمي مع القائمة المنسدلة -->
            <div class="dropdown">
                <a href="education/cybersecurity.php" class="dropdown-btn">القسم التعليمي <i class="fas fa-caret-down"></i></a>
                <div class="dropdown-content">
                    <a href="education/cybersecurity.php">مقدمة عن الامن السبراني</a>
                    <a href="education/crypto.php">التشفير</a>
                    <a href="education/vulnerability.php">الثغرات</a>
                    <a href="education/exploittools.php">أدوات الاختراق</a>
                    <a href="education/MITRE_ATT&CK.php">MITRE ATT&CK</a>
                    <a href="education/NetworkS.php">Network Security</a>
                    <a href="education/CTF_learn.php">ما هو الـ CTF</a>
                </div>
            </div>  
            
            <a href="ctf/challenges.php" class="<?php echo ($currentPage == 'ctf/challenges.php') ? 'active' : ''; ?>">تحديات CTF</a>
            <a href="comman_questions.php" class="<?php echo ($currentPage == 'comman_questions.php') ? 'active' : ''; ?>">الأسئلة الشائعة</a>
            <a href="about.php" class="<?php echo ($currentPage == 'about.php') ? 'active' : ''; ?>">عن الموقع</a>
            <a href="Contact_us.php" class="<?php echo ($currentPage == 'Contact_us.php') ? 'active' : ''; ?>">تواصل معنا</a>
        </div>
        
        <?php
        if (isset($_SESSION['usermail']) && isset($_SESSION['username'])) {
            echo '<button class="profile-button" onclick="toggleProfileMenu()">
                    <img src="./img/Profile-logo.png" alt="Profile Picture"> 
                  </button>';
        } else {
            echo '<div class="auth-buttons">
                  <a href="login.php" class="btn"><i class="fas fa-sign-in-alt"></i> تسجيل الدخول</a>
                  <a href="register.php" class="btn"><i class="fas fa-user-plus"></i> إنشاء حساب</a>
                  </div>';
        }
        ?>
        
        <div class="menu-overlay" onclick="toggleMenu()"></div>
        <div class="menu">
            <div class="menu-header">
                <span>قائمة الخدمات</span>
                <button class="menu-button-x" onclick="toggleMenu()">X</button>
            </div>
            <ul>
                <li><a href="home.php"><i class="fas fa-home"></i> الرئيسية</a></li>
                <li><a href="subbstitution.php"><i class="fas fa-lock"></i> خدمات التشفير</a></li>
                <li><a href="Password_Generator.php"><i class="fas fa-key"></i> كلمات السر</a></li>
                <li><a href="steganography.php"><i class="fas fa-image"></i> steganography</a></li>
                <li><a href="multi_system_converter.php"><i class="fas fa-exchange-alt"></i> محول الأنظمة الرقمية</a></li>
                <li><a href="md5.php"><i class="fas fa-hashtag"></i> Hash</a></li>
            </ul>
        </div>
        
        <?php
        if (isset($_SESSION['usermail']) && isset($_SESSION['username'])) {
            echo '<div class="logo">
                    <a href="home.php">
                        <img src="img/logo_cyberbox_gate.png" alt="CyberBox Gate">
                    </a>
                  </div>
                  <div class="profile-overlay" onclick="toggleProfileMenu()"></div>
                  <div class="profile-menu" id="profileMenu">
                      <div class="profile-header">
                          <i class="fas fa-user-circle"></i>
                          <h3 class="info_account">معلومات الحساب</h3>
                      </div>
                      <div class="profile-info">
                          <p><i class="fas fa-user"></i> الاسم: <span>' . htmlspecialchars($_SESSION['username'] ?? 'غير معروف') . '</span></p>
                          <p><i class="fas fa-envelope"></i> الايميل: <span class="email">' . htmlspecialchars($_SESSION['usermail'] ?? 'غير معروف') . '</span></p>
                      </div>
                      <button class="logout-btn" onclick="window.location.href=\'logout.php\'">
                          <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                      </button>
                  </div>';
        }
        ?>
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
</body>
</html>