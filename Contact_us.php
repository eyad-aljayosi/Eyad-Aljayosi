

<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'mailer/autoload.php'; // تأكد من المسار الصحيح لمكتبة Composer

$message_sent = false;
$error_message = "";

// التحقق مما إذا كانت الجلسة تحتوي على رسالة ناجحة
if (isset($_SESSION['message_sent']) && $_SESSION['message_sent'] === true) {
    $message_sent = true;
    unset($_SESSION['message_sent']); // مسح الجلسة بعد عرض الرسالة
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim(htmlspecialchars($_POST['name']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = trim(htmlspecialchars($_POST['message']));

    // التحقق من صحة المدخلات
    if (empty($name) || empty($email) || empty($message)) {
        $error_message = "جميع الحقول مطلوبة";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "يرجى إدخال بريد إلكتروني صالح";
    } else {
        $mail = new PHPMailer(true);

        try {
            // إعدادات SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'cyberboxgate@gmail.com'; // حساب Gmail الذي سيرسل الرسائل
            $mail->Password = 'jxkb qapy mndw dzcg'; // استخدم كلمة مرور التطبيق من Gmail
            $mail->SMTPSecure = 'ssl'; // استخدم SSL بدلاً من STARTTLS
            $mail->Port = 465; // استخدم المنفذ 465
            $mail->Subject = "رسالة جديدة من $name عبر نموذج التواصل"; // هنا التعديل

            // إعداد البريد
            $mail->setFrom($email, $name);
            $mail->addAddress('cyberboxgate@gmail.com', 'CyberBox Gate'); // بريدك لاستقبال الرسائل
            $mail->addReplyTo($email, $name);
            $mail->CharSet = 'UTF-8';
$message = 
'<body style="margin: 1px; padding: 2px; background-color: #eef2f7; font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif; direction: rtl; text-align: right;">
    <div class="container" style="width: 100%; max-width: 600px; margin: 50px auto; background-color: #ffffff; border-radius: 16px; box-shadow: 0 12px 30px rgba(0,0,0,0.08); overflow: hidden;">
        
        <!-- Header -->
        <div class="header" style="background: linear-gradient(to left, #003366, #0a3d62); padding: 30px; text-align: center;">
            <h1 style="color: #ffffff; font-size: 33px; margin: 0;">CyberBox Gate</h1>
        </div>

        <!-- Intro Text (New message notification) -->
        <div class="content" style="padding: 5px 30px 6px 30px; text-align: right;">
            <p class="intro-text" style="font-size: 16px; color: #333; margin: 12px 0 1px 0; text-align: right;">لقد تلقينا رسالة جديدة من الزائر على نموذج التواصل في موقع <strong>CyberBox Gate</strong>. التفاصيل هي كما يلي:</p>
        </div>

        <!-- Content -->
        <div class="content" style="padding: 5px 30px 6px 30px; text-align: right;">

            <!-- اسم المستخدم -->
            <div class="info-box" style="background-color: #e8f0fe; padding: 15px; margin-top: 15px; border-radius: 8px; border: 2px solid #1e88e5; text-align: right;">
                <p style="margin: 0; font-size: 16px;"><span style="font-weight: bold; color: #283593;">الاسم:</span>  '. $name .' </p>
            </div>

            <!-- البريد الإلكتروني -->
            <div class="info-box" style="background-color: #e8f0fe; padding: 15px; margin-top: 15px; border-radius: 8px; border: 2px solid #1e88e5; text-align: right;">
                <p style="margin: 0; font-size: 16px;"><span style="font-weight: bold; color: #283593;">البريد الإلكتروني:</span>  '. $email .' </p>
            </div>


            <div class="info-box" style="background-color: #e8f0fe; padding: 15px; margin-top: 15px; border-radius: 8px; border: 2px solid #1e88e5; text-align: right;">
              <p style="margin: 0; font-size: 16px;">
              <span style="font-weight: bold; color: #283593;">التاريخ:</span> '.date('Y-m-d h:i A') .'</p>
            </div>

            <!-- الرسالة -->
            <div class="message-box" style="background-color: #e8f0fe; padding: 15px; margin-top: 15px; border-radius: 8px; border: 2px solid #1e88e5; text-align: right;">
                <p style="margin: 0; font-size: 16px;"><span class="massage" style="color: #283593; font-weight: bold;">الرسالة:</span></p>
                <p style="margin: 0; font-size: 16px;"> '. nl2br($message) .' </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer" style="padding: 25px 30px; text-align: center; font-size: 13px; color: #aaa;">
            <p>جميع الحقوق محفوظة © CyberBox Gate 2025</p>
        </div>
        
    </div>
</body>';

            $mail->Body = $message; 
            $mail->isHTML( TRUE );
            $mail->send();
            
            // تعيين الجلسة لتأكيد الإرسال الناجح
            $_SESSION['message_sent'] = true;

            // إعادة توجيه إلى نفس الصفحة بعد الإرسال الناجح لتجنب إعادة إرسال البيانات
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
          
        } catch (Exception $e) {
            $error_message = "لم يتم إرسال الرسالة: {$mail->ErrorInfo}";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preload" href="img/background7.jpg" as="image">
    <link rel="stylesheet" href="css/style-Contact_us.css?v=1.1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <title>تواصل معنا - CyberBox Gate</title>

</head>
<body>
    <?php include 'header/header_home.php' ?>
    
    <div class="contact-wrapper">
        <div class="contact-container">
            <div class="contact-header">
                <h1>تواصل معنا</h1>
                <p>نرحب بتلقي استفساراتكم في <span>CyberBox Gate</span></p>
            </div>
            
            <?php if ($message_sent): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> تم إرسال رسالتك بنجاح، شكراً لتواصلك معنا.
                </div>
            <?php elseif (!empty($error_message)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?= $error_message ?>
                </div>
            <?php endif; ?>
            
            <form id="contact-form" method="post" dir="ltr">
                <div class="form-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="name" class="form-control" placeholder="الاسم " required>
                </div>
                
                <div class="form-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" class="form-control" placeholder="البريد الإلكتروني" required>
                </div>
                
                <div class="form-group">
                    <i class="fas fa-comment-alt"></i>
                    <textarea name="message" class="form-control" placeholder="نص الرسالة..." required></textarea>
                </div>
                
                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> إرسال الرسالة
                </button>
            </form>
            
            <div class="contact-footer">
                <p>للتواصل الفوري: <a href="mailto:cyberboxgate@gmail.com">cyberboxgate@gmail.com</a></p>
            </div>
        </div>
    </div>
        
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // إخفاء رسائل التنبيه بعد 4 ثواني
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 300);
                }, 4000);
            });
            
            // إضافة تأثير تحميل عند الإرسال
            const form = document.getElementById('contact-form');
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإرسال...';
                submitBtn.disabled = true;
            });
        });
    </script>
</body>
</html>