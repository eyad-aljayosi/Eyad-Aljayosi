<?php
include('../conn.php');
session_start();

if (!isset($_SESSION['usermail'])) {
    $_SESSION['login_befor'] = "قم بعمل تسجيل دخول بالبداية ";
    header('location:../login.php'); // تحويل إلى صفحة تسجيل الدخول
    exit();
}

$email = $_SESSION['usermail'];//تعريف متغيير يحمل الايميل
 $name = $_SESSION['username'];

 
// التحقق من صلاحيات الأدمن
$stmt = $conn->prepare("SELECT * FROM admin_form WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    $_SESSION['not_admin'] = "ليس لديك صلاحيات مسؤول";
    header('location:../login.php');
    exit();
}
include '../session_check.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $points = intval($_POST['points']);
    $flag = trim($_POST['flag']);
    $category = trim($_POST['category']);
    $difficulty = trim($_POST['difficulty']);

    if (empty($title) || empty($description) || empty($points) || empty($flag) || empty($category) || empty($difficulty)) {
        $message = "<div class='alert alert-error'><i class='fas fa-exclamation-circle'></i> جميع الحقول مطلوبة!</div>";
    } else {
        $stmt = $conn->prepare("INSERT INTO challenges (title, description, points, flag, category, difficulty) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisss", $title, $description, $points, $flag, $category, $difficulty);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "تم إضافة التحدي بنجاح";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $_SESSION['error_message'] = "حدث خطأ أثناء الإضافة";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة تحدي جديد</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --danger-color: #e74c3c;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --light-color: #ecf0f1;
            --dark-color: #34495e;
            --white: #ffffff;
            --gray: #95a5a6;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Tajawal', sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
            background-image: url('../img/background6.jpg');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            direction: ltr;
        }
        
        .main-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            direction: rtl;
        }
        
        .main-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        
        .page-title {
            color: var(--primary-color);
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
        }
        
        .page-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--primary-color), transparent);
        }
        
        .page-title i {
            margin-left: 10px;
            color: var(--secondary-color);
        }
        
        .challenge-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .form-label {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .form-label i {
            margin-left: 8px;
            color: var(--secondary-color);
        }
        
        .form-input {
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
            background-color: #f9f9f9;
        }
        
        .form-input:focus {
            border-color: var(--secondary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        
        textarea.form-input {
            min-height: 150px;
            resize: vertical;
        }
        
        .submit-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }
        
        .submit-btn i {
            margin-left: 10px;
        }
        
        /* Alert Styles */
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
            display: flex;
            align-items: center;
            animation: fadeIn 0.5s;
        }
        
        .alert-success {
            background-color: rgba(46, 204, 113, 0.2);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }
        
        .alert-error {
            background-color: rgba(231, 76, 60, 0.2);
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }
        
        .alert i {
            margin-left: 10px;
            font-size: 20px;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Responsive Styles */
        @media (max-width: 768px) {
            .main-container {
                margin: 15px;
                padding: 20px;
            }
            
            .page-title {
                font-size: 26px;
            }
            
            .form-label {
                font-size: 16px;
            }
            
            .form-input {
                padding: 10px 12px;
                font-size: 14px;
            }
            
            .submit-btn {
                padding: 12px;
                font-size: 16px;
            }
        }
        
        /* Floating Animation */
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
            100% { transform: translateY(0px); }
        }
        
        .floating-icon {
            animation: floating 3s ease-in-out infinite;
        }
    </style>
</head>
<body>
    <?php include 'header_Admin.php'; ?>
    
    <div class="main-container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-flag-checkered floating-icon"></i> إضافة تحدي جديد
            </h1>
        </div>
        
        <?php 
        if (!empty($message)) echo $message;
        if (isset($_SESSION['success_message'])) {
            echo '<div class="alert alert-success"><i class="fas fa-check-circle"></i> '.$_SESSION['success_message'].'</div>';
            unset($_SESSION['success_message']);
        }
        if (isset($_SESSION['error_message'])) {
            echo '<div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> '.$_SESSION['error_message'].'</div>';
            unset($_SESSION['error_message']);
        }
        ?>
        
        <form class="challenge-form" method="POST">
            <div class="form-group">
                <label class="form-label" for="title"><i class="fas fa-heading"></i> عنوان التحدي</label>
                <input type="text" class="form-input" name="title" required placeholder="أدخل عنوان التحدي">
            </div>
            
            <div class="form-group">
                <label class="form-label" for="description"><i class="fas fa-align-right"></i> وصف التحدي</label>
                <textarea class="form-input" name="description" required placeholder="أدخل وصفاً مفصلاً للتحدي"></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="points"><i class="fas fa-star"></i> النقاط</label>
                <input type="number" class="form-input" name="points" required placeholder="أدخل عدد النقاط">
            </div>
            
            <div class="form-group">
                <label class="form-label" for="flag"><i class="fas fa-flag"></i> العلم الصحيح (Flag)</label>
                <input type="text" class="form-input" name="flag" required placeholder="أدخل العلم الصحيح للتحدي">
            </div>
            
            <div class="form-group">
                <label class="form-label" for="category"><i class="fas fa-tags"></i> التصنيف</label>
                <input type="text" class="form-input" name="category" required placeholder="أدخل تصنيف التحدي">
            </div>
            
            <div class="form-group">
                <label class="form-label" for="difficulty"><i class="fas fa-tachometer-alt"></i> مستوى الصعوبة</label>
                <select class="form-input" name="difficulty" required>
                    <option value="" disabled selected>اختر مستوى الصعوبة</option>
                    <option value="Easy">سهل</option>
                    <option value="Medium">متوسط</option>
                    <option value="Hard">صعب</option>
                </select>
            </div>
            
            <button type="submit" class="submit-btn">
                <i class="fas fa-plus-circle"></i> إضافة التحدي
            </button>
        </form>
    </div>
    
    <script>
        // إخفاء التنبيهات تلقائياً بعد 5 ثوانٍ
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.display = 'none';
            });
        }, 5000);
        
        // إضافة تأثير عند التركيز على الحقول
        const inputs = document.querySelectorAll('.form-input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.querySelector('.form-label').style.color = 'var(--secondary-color)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.querySelector('.form-label').style.color = 'var(--dark-color)';
            });
        });
    </script>
</body>
</html>