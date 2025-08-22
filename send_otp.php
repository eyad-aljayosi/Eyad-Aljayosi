<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// توليد كود التحقق العشوائي
$otp = rand(10000, 99999);

// تحميل مكتبة PHPMailer
require 'mailer/autoload.php';

// إنشاء محتوى رسالة HTML مع استخدام Inline CSS

$message = '
<body style="margin:1px; padding:2px; background-color:#eef2f7; font-family: Tahoma, Geneva, Verdana, sans-serif;direction: rtl; text-align: right;">

  <table align="center" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px; margin:50px auto; background:#ffffff; border-radius:16px; box-shadow:0 12px 30px rgba(0,0,0,0.08); overflow:hidden;">
    
    <!-- Header -->
    <tr>
      <td align="center" style="background: linear-gradient(to left, #003366, #0a3d62); padding:30px;">
        <h1 style="color:#ffffff; font-size:33px; margin:0;">CyberBox Gate</h1>
      </td>
    </tr>
    
    <!-- OTP Section -->
    <tr>
      <td style="padding:40px 30px 20px 30px; text-align:center;">

        <!-- Stylized Message -->
        <p style="font-size:17px; color:#555; margin-bottom:22px;">
            لتأكيد هويتك، يرجى استخدام رمز التحقق التالي:
        </p>

        <p style="font-size:20px; margin-bottom:20px; color:#333; font-weight:500;">
          رمز التحقق الخاص بك هو:
        </p>

        <!-- OTP Code -->
        <div style="background: linear-gradient(to right, #b6e0fe, #82c0f6); display:inline-block; padding:20px 50px; border-radius:12px; font-size:34px; font-weight:bold; color:#003366; letter-spacing:10px; box-shadow:0 4px 8px rgba(0,0,0,0.12);">
          '.$otp.'
        </div>

        <!-- Copy Note -->
        <div style="margin-top:12px;">
          <span style="font-size:13px; color:#999;">يرجى نسخ الرمز يدويًا لاستخدامه في تسجيل الدخول</span>
        </div>

        <!-- Expiry Warning -->
        <p style="color:#d63031; font-size:16px; margin-top:30px; line-height:1.8;">
           صلاحية هذا الرمز 120 ثانية فقط. لا تشاركه مع أي شخص.
        </p>
      </td>
    </tr>

    <!-- Divider -->
    <tr>
      <td>
        <hr style="border:none; border-top:1px solid #eee; margin:0 30px;">
      </td>
    </tr>

    <!-- Footer -->
    <tr>
      <td style="padding:25px 30px; text-align:center; font-size:13px; color:#aaa;">
        جميع الحقوق محفوظة © CyberBox Gate 2025
      </td>
    </tr>
  </table>

</body>
</html>
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
