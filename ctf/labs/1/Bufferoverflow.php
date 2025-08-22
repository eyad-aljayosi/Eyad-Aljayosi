<?php
/**
 * تحدّي استغلال ثغرة Buffer Overflow (مبسّط)
 * 
 * الهدف: إدخال نص يتجاوز طوله السعة المحددة للحصول على الفلاغ
 */

// إعدادات التحدي
define('BUFFER_SIZE', 120);
define('EXPLOIT_THRESHOLD', 150);
define('FLAG', 'CTF{Simple_Buffer_Overflow_Success}');
define('MAX_ATTEMPTS', 10); // الحد الأقصى للمحاولات

// بدء الجلسة وتأمينها
session_start([
    'cookie_httponly' => true,
    'cookie_secure' => true,
    'use_strict_mode' => true
]);

if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
    $_SESSION['last_attempt'] = time();
}

// التحقق من معدل المحاولات (Rate Limiting)
$current_time = time();
if (isset($_SESSION['last_attempt']) && ($current_time - $_SESSION['last_attempt']) < 5) {
    die("<div class='error-message'>الرجاء الانتظار 5 ثواني بين المحاولات</div>");
}

$_SESSION['attempts']++;
$_SESSION['last_attempt'] = $current_time;

// التحقق من تجاوز الحد الأقصى للمحاولات
if ($_SESSION['attempts'] > MAX_ATTEMPTS) {
    die("<div class='error-message'>تجاوزت الحد الأقصى للمحاولات (" . MAX_ATTEMPTS . ")</div>");
}

// معالجة الإدخال مع التحقق من الأمان
if (isset($_GET['input'])) {
    $input = substr($_GET['input'], 0, 500); // تحديد طول الإدخال الأقصى
    $inputLength = strlen($input);
    
    // إنشاء المخزن المؤقت
    $buffer = str_repeat("X", BUFFER_SIZE);
    
    // التحقق من استغلال الثغرة
    if ($inputLength > EXPLOIT_THRESHOLD) {
        $success = true;
        $message = "<div class='success-message'>
                        <h3>نجحت في الاستغلال! 🎉</h3>
                        <p>الفلاغ: <strong>" . htmlspecialchars(FLAG, ENT_QUOTES, 'UTF-8') . "</strong></p>
                    </div>";
        
        // إعادة تعيين المحاولات بعد النجاح
        $_SESSION['attempts'] = 0;
    } else {
        $success = false;
        $remaining = EXPLOIT_THRESHOLD - $inputLength;
        $message = "<div class='error-message'>
                        <h3>لم تنجح المحاولة</h3>
                        <p>طول إدخالك: " . htmlspecialchars($inputLength, ENT_QUOTES, 'UTF-8') . " أحرف</p>
                        <p>تحتاج إلى " . max(0, $remaining) . " أحرف إضافية</p>
                    </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تحدي الأمان | Buffer Overflow</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
/* Base Styles */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f5f7fa;
    color: #333;
    line-height: 1.6;
    margin: 0;
    padding: 0;
    direction: rtl;
}

.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

header {
    text-align: center;
    margin-bottom: 30px;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

header h1 {
    color: #2c3e50;
    margin-bottom: 10px;
}

header p {
    color: #7f8c8d;
    margin: 0;
}

/* Challenge Card */
.challenge-card {
    background-color: #fff;
    border-radius: 8px;
    padding: 25px;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.challenge-card h2 {
    color: #3498db;
    margin-top: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Form Styles */
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #2c3e50;
}

.form-group input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    box-sizing: border-box;
}

button {
    background-color: #3498db;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #2980b9;
}

/* Message Styles */
.success-message {
    background-color: #d4edda;
    color: #155724;
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
    border-left: 5px solid #28a745;
}

.error-message {
    background-color: #f8d7da;
    color: #721c24;
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
    border-left: 5px solid #dc3545;
}

/* Progress Bar */
.progress-container {
    margin: 25px 0;
}

.progress-bar {
    height: 20px;
    background-color: #e9ecef;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 10px;
}

.progress-fill {
    height: 100%;
    background-color: #3498db;
    width: 0%;
    transition: width 0.5s ease;
}

.progress-text {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    color: #7f8c8d;
}

/* Hint Box */
.hint-box {
    background-color: #e3f2fd;
    border-radius: 8px;
    padding: 15px;
    margin-top: 25px;
    border-left: 4px solid #2196f3;
}

.hint-box h3 {
    margin-top: 0;
    color: #0d47a1;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Attempts Counter */
.attempts-counter {
    background-color: #fff;
    padding: 10px 15px;
    border-radius: 4px;
    text-align: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        padding: 10px;
    }
    
    .challenge-card {
        padding: 15px;
    }
}

/* Animation for success */
@keyframes celebrate {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.success-message {
    animation: celebrate 0.5s ease;
}        
        .security-features {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            border-left: 4px solid #f39c12;
        }
        
        .security-features h3 {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #f39c12;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>تحدي أمان المعلومات</h1>
            <p>اختبار مهاراتك في اكتشاف الثغرات الأمنية</p>
        </header>
        
        <div class="challenge-card">
            <h2><i class="fas fa-shield-alt"></i> تحدّي Buffer Overflow</h2>
            <p>حاول استغلال ثغرة Buffer Overflow عن طريق إدخال نص يتجاوز السعة المخصصة للمخزن المؤقت.</p>
            
            <?php if(isset($message)) echo $message; ?>
            
            <div class="progress-container">
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
                <div class="progress-text">
                    <span>طول الإدخال الحالي: <?php echo isset($inputLength) ? htmlspecialchars($inputLength, ENT_QUOTES, 'UTF-8') : '0'; ?></span>
                    <span>الحد المطلوب: <?php echo EXPLOIT_THRESHOLD; ?></span>
                </div>
            </div>
            
            <form method="GET" onsubmit="return validateInput()">
                <div class="form-group">
                    <label for="input">أدخل النص الخاص بك:</label>
                    <input type="text" id="input" name="input" placeholder="حاول إدخال نص طويل..." required maxlength="500">
                </div>
                <button type="submit">
                    <i class="fas fa-paper-plane"></i> إرسال
                </button>
            </form>
            
            <div class="hint-box">
                <h3><i class="fas fa-lightbulb"></i> تلميح</h3>
                <p>لحل هذا التحدي، تحتاج إلى إدخال نص يتجاوز طوله <?php echo EXPLOIT_THRESHOLD; ?> حرف.</p>
                <p>جرب استخدام نص طويل جداً أو إنشاء نمط متكرر.</p>
            </div>
            
            <div class="security-features">
                <h3><i class="fas fa-lock"></i> ميزات الأمان المضافة</h3>
                <ul>
                    <li>حد أقصى للمحاولات: <?php echo MAX_ATTEMPTS; ?></li>
                    <li>تأخير بين المحاولات: 5 ثواني</li>
                    <li>تشفير الجلسة (HTTPS فقط)</li>
                    <li>حماية ضد هجمات XSS</li>
                </ul>
            </div>
        </div>
        
        <div class="attempts-counter">
            <i class="fas fa-history"></i> عدد المحاولات: <?php echo $_SESSION['attempts']; ?> / <?php echo MAX_ATTEMPTS; ?>
        </div>
    </div>

    <script>
        function validateInput() {
            const input = document.getElementById('input').value;
            if (input.length > 500) {
                alert('الحد الأقصى لطول الإدخال هو 500 حرف');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>