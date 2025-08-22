<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// توليد كود التحقق العشوائي
$otp = rand(10000, 99999);

// تحميل مكتبة PHPMailer
require '../mailer/autoload.php';

// إنشاء محتوى رسالة HTML مع استخدام Inline CSS

$message = '
<body style="font-family: sans-serif; direction: rtl; background-color: #f4f7fc; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; height: 100vh; width: 100%;">

    <div style="max-width: 600px; width: 100%; background-color: #ffffff; padding: 40px; border-radius: 12px; box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1); text-align: center; overflow: hidden;">
        <div style="background-color: #007bff; color: #fff; font-size: 30px; font-weight: 600; padding: 20px 0; border-radius: 10px; margin-bottom: 30px; letter-spacing: 1px;">
            CyberBox Gate
        </div>

        <h2 style="color: #333; font-size: 26px; font-weight: 600; margin-top: 0;">أهلاً بك في <span style="color: #007bff;">CyberBox Gate</span></h2>

        <p style="color: #555; font-size: 18px; line-height: 1.6; margin-top: 15px;">
            لتأكيد هويتك، يرجى استخدام رمز التحقق التالي:
        </p>

        <div style="background-color: #f1f8ff; border-radius: 12px; padding: 30px; font-size: 36px; font-weight: 600; color: #007bff; margin-top: 20px; border: 2px solid #007bff;">
            ' . $otp . '
        </div>

        <p style="color: #555; font-size: 18px; line-height: 1.6; margin-top: 15px;">
            استخدم هذا الرمز لإتمام عملية التحقق في التطبيق أو الموقع الإلكتروني.
        </p>

        <p style="font-size: 18px; color: #ff4d4d; font-weight: 600; margin-top: 30px;">
            ملاحظة: صلاحية الرمز تنتهي بعد 120 ثانية.
        </p>

        <p style="color: #555; font-size: 18px; line-height: 1.6; margin-top: 15px;">
            إذا لم تطلب هذه العملية، يمكنك تجاهل هذه الرسالة.
        </p>

        <div style="font-size: 14px; color: #aaa; margin-top: 40px; font-style: italic;">
            <p>© 2025 <span style="color: #007bff;">CyberBox Gate</span>. جميع الحقوق محفوظة.</p>
        </div>
    </div>

</body>
';

// إعدادات إرسال البريد
$mail = new PHPMailer;
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->Host = 'smtp.gmail.com';
$mail->Username = 'cyberboxgate@gmail.com';
$mail->Password = 'jxkb qapy mndw dzcg'; // استخدم كلمة مرور التطبيق هنا
$mail->SMTPSecure = 'ssl';
$mail->Port = 465;
$mail->FromName = "CyberBox Gate";//الاسم الذي يظهر كمرسل للمستخدم 
$mail->AddAddress($email);// ايميل المرسل الية 
$mail->Subject = "OTP Verification";
$mail->isHTML( TRUE );
$mail->CharSet = 'UTF-8';
$mail->Body = $message;//الرساله
?>
