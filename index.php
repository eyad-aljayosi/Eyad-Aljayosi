
<?php
session_start();
include 'session_check.php'; 
if (isset($_SESSION['usermail'])) {
    header("Location: home.php");
    exit();
}


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
            $mail->Subject = "New message from CyberBox Gate";

            // إعداد البريد
            $mail->setFrom($email, $name);
            $mail->addAddress('cyberboxgate@gmail.com', 'CyberBox Gate'); // بريدك لاستقبال الرسائل
            $mail->addReplyTo($email, $name);
           
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
            
            // تعيين الجلسة لتأكيد الإرسال الناجح
            $_SESSION['message_sent'] = true;
          
        } catch (Exception $e) {
            $error_message = "❌ لم يتم إرسال الرسالة: {$mail->ErrorInfo}";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="preload" href="img/background6.jpg" as="image">
    
    <title>CyberBox Gate</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            scroll-behavior: smooth;
            font-family: 'Tajawal', sans-serif;

        }

        body {
            background: linear-gradient(180deg, #eef2f7, #cfd9e7);
            background: url('img/background6.jpg') no-repeat center center/cover fixed;
            text-align: center;
            overflow-x: hidden;
            padding-top: 80px;
            line-height: 1.6;
        }

        header {
        background: linear-gradient(135deg, #052d5f 0%, #0a5aa6 100%);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 40px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        font-weight: 700;
        width: 100%;
        z-index: 1000;
        position: fixed; /* تغيير من relative إلى fixed */
        top: 0;
        left: 0;
        transition: all 0.3s ease; /* إضافة تأثير انتقالي */
    }

    /* كلاس الهيدر المخفض */
    header.shrink {
        padding: 8px 40px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .logo {
        display: inline-block;
        padding: 0;
        margin: 0;
        transition: all 0.3s ease;
    }

    /* تكبير حجم الصورة */
    .logo img {
        width: auto;
        height: 48px;
        border: none;
        margin: 0;
        transition: all 0.3s ease;
    }

    /* تصغير الصورة عند التقلص */
    header.shrink .logo img {
        height: 36px;
    }

    nav {
        flex: 1;
        text-align: center;
    }

    nav ul {
        list-style: none;
        display: flex;
        margin-left:130px ;
        justify-content: center;
    }

    nav ul li {
        margin: 0 20px;
    }

    nav ul li a {
        color: white;
        font-size: 18px;
        text-decoration: none;
        font-weight: bold;
        transition: color 0.3s ease;
    }

    nav ul li a:hover {
        color: #ffd700;
    }

    .auth-buttons {
        display: flex;
        gap: 15px;
    }

    .auth-buttons {
            display: flex;
            gap: 15px;
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



        main {
            padding: 140px 40px 40px;
        }

   
        .description-text {
            font-size: 20px;
            color: #555;
            line-height: 1.8;
            margin-top: 10px;
            font-weight: bold;
            animation: fadeIn 1s ease-in-out;

        }

.hero {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #0a5aa6;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    font-size: 34px;
    font-weight: bold;
    margin-top: 50px; /* To avoid the header overlapping the hero section */
    animation: fadeIn 1s ease-in-out;

}

  .guest-btn {
        display: inline-block;
        text-decoration: none;
        margin-top: 40px; /* زيادة المسافة بين الـ hero والزر */
        text-align: center;
        animation: fadeIn 1s ease-in-out;
    }

    .guest-btn  {
        background: linear-gradient(135deg, #ffd700, #e6b800);
        color: rgb(8 73 140);
        padding: 18px 40px;
        border-radius: 30px;
        font-family: 'Tajawal', sans-serif;
        font-size: 20px;
        font-weight: bold;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        text-transform: uppercase;
        margin-bottom: 65px;
    }

    .guest-btn:hover {
        background: linear-gradient(135deg, #cfa700, #ffd700);
        transform: scale(1.1);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
    }

    .guest-btn:focus {
        outline: none;
    }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .section-container {
            background: white;
            padding: 30px;
            margin: 20px auto;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            max-width: 900px;
            transition: transform 0.3s ease;
        }

        .section-container:hover {
            transform: scale(1.02);
        }

        .contact-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .contact-form input, .contact-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .contact-form button {
            background: #0a5aa6;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        /*************************************************************************************************/

        /*قسم تصميم الاسئلة الشائعة */

        .section-title {
            direction: rtl;
            text-align: center;
            font-size: 36px;
            color: #007bff;
            border-bottom: 3px solid #007bff;
            padding-bottom: 15px;
            margin-bottom: 40px;
            margin-top: 30px;
        }

        .container-qus {
            padding: 16px 40px 10px 40px;
            max-width: 1200px;
            margin: 10px auto;
            background-color: #f4f4f4;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 75px;

        }
        .accordion-container {
            width: 90%;
            direction: rtl;
            margin: 0 auto;
            margin-bottom: 70px;
        }

        .accordion {
            background-color: #f2f2f2;
            color: #333;
            cursor: pointer;
            padding: 20px;
            width: 100%;
            border: 2px solid #007bff;
            border-radius: 8px;
            text-align: right;
            outline: none;
            font-size: 20px;
            margin-top: 10px;
            transition: background-color 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .accordion:hover {
            background-color: #007bff;
            color: #fff;
        }

        .accordion:after {
            content: '\002B';
            font-size: 25px;
            color: #007bff;
            float: left;
            margin-left: 10px;
        }

        .accordion.active:after {
            content: "\2212";
        }

        .panel {
            padding: 20px;
            display: none;
            overflow: hidden;
            background-color: #f9f9f9;
            margin-bottom: 12px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .panel p {
            font-size: 18px;
            color: #555;
            line-height: 1.6;
            margin: 0;
        }

/*************************************************************************************************/


        /*حول*/

        .container {
            padding: 40px;
            max-width: 1200px;
            margin: 10px auto;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 75px;
        }

        .title {
            font-size: 32px;
            color: #2980b9;
            margin-bottom: 30px;
            text-align: center;
            border-bottom: 3px solid #3498DB; /* خط أسفل العنوان لإضافة تأثير مرئي بسيط */
            padding-bottom: 10px;
        }

        .section-title {
            font-size: 28px;
            color: #2980b9;
            margin-bottom: 20px;
            text-align: right;
        }

        .card {
            padding: 25px;
            margin: 20px 0;
            border-radius: 10px;
            background-color: #f9fafb;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border-left: 5px solid #2980b9;
            text-align: right;
        }

        .card-header {
            font-size: 24px;
            font-weight: 600;
            color: #34495e;
            margin-bottom: 15px;
        }

        .paragraph {
            font-size: 20px;
            color: #555;
            line-height: 1.7;
        }

        .list {
            font-size: 20px;
            color: #555;
            line-height: 1.7;
            padding-right: 20px;
        }

        .nnn {
            font-size: 36px;
            font-weight: 600;
            color: #34495E;
            margin-bottom: 20px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 15px;
            border-bottom: 3px solid #3498DB;
        }

        .advantages ul {
            list-style-type: disc;
            padding-left: 30px;
        }

        .advantages li {
            font-size: 18px;
            color: #555;
        }

        .note {
            background-color: #fff3cd;
            padding: 20px;
            border-left: 5px solid #f1c40f;
            margin-bottom: 25px;
            font-size: 18px;
        }

        /* Separate class for right-to-left alignment */
        .rtl-align {
            direction: rtl; /* محاذاة النصوص من اليمين لليسار */
        }

        /* Responsive design improvements */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            .title {
                font-size: 28px;
                margin-bottom: 20px;
            }
            .section-title {
                font-size: 24px;
            }
            .paragraph, .list {
                font-size: 18px;
            }
        }

/* تنسيق العنوان */
/*.main-title {
  text-align: center;
  color: #ffffff;
  background: linear-gradient(135deg, #1d3c8b, #4e73df); /* تدرج لوني أزرق متناسق */
 /* padding: 30px 20px; /* تقليل المسافة حول النص */
 /* font-size: 2.8em; /* تقليص حجم الخط قليلاً ليصبح أكثر تناسقًا */
 /* font-family: 'Arial', sans-serif;
 # font-weight: bold;
  letter-spacing: 2px; /* تقليل التباعد بين الحروف */
 /* text-transform: uppercase; /* الحفاظ على النص بالحروف الكبيرة */
  /*box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1); /* إضافة ظل خفيف لإبراز العنوان */
  /*border-radius: 8px; /* زوايا دائرية متوسطة */
  /*animation: fadeInFromTop 1s ease-out; /* تأثير حركة ظهور من الأعلى */
  /*margin-bottom: 20px; /* تقليل التباعد أسفل العنوان */
/*}

/* حركة العنوان */
/*@keyframes fadeInFromTop {
  from {
    transform: translateY(-50px); /* يظهر من الأعلى */
  /*  opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}*/

/* تصميم الشريط */

.title-container {
  width: auto;
  margin: 0 auto;
  padding: 5px 30px;
  text-align: center;
  position: relative;
}

.main-title {
  font-size: 38px;
  color: #ffffff;
  background-color: #3b7bbf; /* Blue background color */
  display: inline-block;
  padding: 10px 30px;
  border-radius: 20px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  text-transform: uppercase;
  position: relative;
  width: 500px;
}

.main-title::before,
.main-title::after {
  content: '';
  position: absolute;
  top: 50%;
  width: 20px;
  height: 20px;
  background-color:rgb(235 191 0); /* Accent color */
  border-radius: 50%;
  transform: translateY(-50%);
}

.main-title::before {
  left: -30px;
}

.main-title::after {
  right: -30px;
}


/* تواصل معنا */
       

        .contact-container {
            width: 100%;
            max-width: 720px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 25px;
            text-align: center;
            box-sizing: border-box;
            height: auto;
            margin-bottom: 65px;
        }

        .title {
            font-size: 30px;
            color: #07427d;
            font-weight: 700;
            margin-bottom: 25px;
            letter-spacing: 1px;
        }

        .input-container {
            position: relative;
            margin-bottom: 20px;
        }

        .input-container-Message {
            position: relative;
            margin-bottom: 20px;
        }

        .input-field, .textarea-field {
            width: 100%;
            padding: 14px;
            padding-left: 40px;
            border: 2px solid #07427d;
            border-radius: 12px;
            font-size: 16px;
            color: #333;
            background-color: #f9f9f9;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        .textarea-field {
            min-height: 100px;
            resize: none;
        }

        .input-field:focus, .textarea-field:focus {
            outline: none;
            border-color: #2980b9;
            background-color: #ffffff;
            box-shadow: 0 0 8px rgba(41, 128, 185, 0.2);
        }

        .input-container i {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            color: #07427d;
            font-size: 18px;
        }

        .input-container-Message i {
            position: absolute;
            top: 19%;
            left: 12px;
            transform: translateY(-50%);
            color: #07427d;
            font-size: 18px;
        }

        .btn-submit {
            background: linear-gradient(to right, #2980b9, #3498db);
            color: white;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            text-transform: uppercase;
        }

        .btn-submit:hover {
            background: linear-gradient(to right, #3498db, #2980b9);
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 15px;
        }

        /*#contact {
            text-align: center;
            padding: 30px 28px 40px 28px;
        }*/

        .message.success {
            background-color: #4CAF50;
        color: white;
        padding: 10px;
        text-align: center;
        margin-bottom: 20px;
        border-radius: 5px;
        }

        .message.error {
            background-color: #f44336;
        color: white;
        padding: 10px;
        text-align: center;
        margin-bottom: 20px;
        border-radius: 5px;
        }


        @media (max-width: 768px) {
            .container {
                width: 90%;
                padding: 20px;
            }

            .button-container button {
                width: 100%;
                margin-bottom: 15px;
            }

            .title {
                font-size: 26px;
            }
        }
/* خدماتنا */

/* تنسيق قسم الخدمات */
.services {
  direction: rtl;
  text-align: center;
  padding: 60px 20px;
  max-width: 1200px;
  margin: 20px auto;
  background-color: #f7f7f7;
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  margin-bottom: 75px;
  position: relative;
}

.services h2 {
  font-size: 36px;
  color: #2d3e50;
  margin-bottom: 50px;
  font-weight: 800;
  letter-spacing: 2px;
  text-transform: uppercase;
  position: relative;
}

.services h2::before {
  content: '';
  position: absolute;
  width: 50px;
  height: 4px;
  background-color: #4c8cff;
  bottom: -10px;
  left: 50%;
  transform: translateX(-50%);
}

/* تنسيق الصناديق داخل الخدمات */
.service-boxes {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 30px;
  padding: 0 20px;
}

.service-box {
  background-color: #ffffff;
  padding: 30px;
  border-radius: 15px;
  border: 2px solid #e1e8f0;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  text-align: center;
  transition: all 0.3s ease;
  max-width: 280px;
  margin: 0 auto;
  position: relative;
  overflow: hidden;
}

.service-box:hover {
  border-color: #4c8cff;
  transform: translateY(-15px);
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
}

.service-icon {
  width: 90px;
  height: 90px;
  margin-bottom: 20px;
  object-fit: cover;
  background-color: #e1effd;
  border-radius: 50%;
  padding: 5px;
  box-shadow: 0 8px 18px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}

.service-box:hover .service-icon {
  transform: rotate(360deg);
}

.service-box h3 {
  font-size: 24px;
  color: #2d3e50;
  margin-bottom: 15px;
  font-weight: 600;
  letter-spacing: 1px;
}

.service-box p {
  font-size: 16px;
  color: #7a7a7a;
  margin-bottom: 20px;
  line-height: 1.6;
}

.service-link {
  display: inline-block;
  background-color: #4c8cff;
  color: #ffffff;
  padding: 12px 25px;
  border-radius: 30px;
  text-decoration: none;
  font-weight: bold;
  font-size: 16px;
  transition: background-color 0.3s ease, transform 0.3s ease;
}

.service-link:hover {
  background-color: #3a74c4;
  transform: scale(1.1);
}

/* تنسيق قسم "اكتشاف المزيد من الخدمات" */
.more-services {
  margin-top: 50px;
  background-color: #ffffff;
  padding: 30px;
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  text-align: center;
}

.more-services p {
  font-size: 18px;
  color: #333;
  margin-bottom: 15px;
  font-weight: 600;
}

.more-services a {
  color: #4c8cff;
  text-decoration: none;
  font-weight: bold;
  font-size: 16px;
}

.more-services a:hover {
  text-decoration: underline;
}

/* استجابة لتغيير العرض (Responsive Design) */
@media (max-width: 1024px) {
  .service-boxes {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .service-boxes {
    grid-template-columns: 1fr;
  }
}

/* تصميم الفوتر */
footer {
            background-color: #2b6daf;
            color: #fff;
            padding: 8px;
            text-align: center;
            font-size: 1rem;
            margin-top: auto; /* هذا سيجعل الفوتر يبقى في الأسفل */
        }
        
        /***/

        .alert-log {
    color: rgb(209 23 23);
    text-align: center;
    padding: 12px;
    background-color: rgb(218 176 176);
    border: 1px solid rgb(230, 203, 195);
    border-radius: 12px;
    position: fixed;
    top: 0; 
    left: 50%;
    transform: translateX(-50%);
    width: 80%;
    max-width: 200px;
    z-index: 1050; 
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    opacity: 1;
    animation: fadeInOut 3.8s ease forwards;
    font-size: 18px;
    font-weight: bold;
    max-width: 350px;
}

@keyframes fadeInOut {
    0% {
        opacity: 0;
        top: -45px;
    }
    30% {
        opacity: 1;
        top: 20px;
    }
    70% {
        opacity: 1;
        top: 20px;
    }
    100% {
        opacity: 0;
        top: -50px;
    }
}



    </style>
</head>
<body>
<header>
    <div class="logo">
        <a href="index.php">
            <img src="./img/logo_cyberbox_gate.png" alt="CyberBox Gate">
        </a>
    </div>
    <nav>
        <ul>
            <li><a href="#about">حول</a></li>
            <li><a href="#services">خدماتنا</a></li>
            <li><a href="#faq">الأسئلة الشائعة</a></li>
            <li><a href="#contact">اتصل بنا</a></li>
        </ul>
    </nav>
    <div class="auth-buttons">
        <a href="login.php" class="btn"><i class="fas fa-sign-in-alt"></i> تسجيل الدخول</a>
        <a href="register.php" class="btn"><i class="fas fa-user-plus"></i> إنشاء حساب</a>
    </div>
</header>
   
    <div class="hero">
        <h1>CyberBox Gate مرحبا بك في </h1>
    </div>
    
    <p class="description-text">استمتع باستخدام أدوات فعّالة لتشفير النصوص والملفات وتحويل الأرقام بين الأنظمة المختلفة بسهولة وبدون تعقيد</p>
    

    <a href="home.php" class="guest-btn">
    دخول كضيف
    </a>



    <!-- خدماتنا  -->
    <div class="title-container">
  <h1 id="services" class="main-title">خدماتنا</h1>
</div>
<section class="services">
  <div class="service-boxes">
    <div class="service-box">
      <img src="https://www.oolom.com/wp-content/uploads/2015/03/encryption_11.jpg" alt="Encryption Service" class="service-icon">
      <h3>التشفير</h3>
      <p>احصل على أعلى مستوى من الأمان مع تقنيات التشفير المتقدمة.</p>
      <a href="login.php" class="service-link">سجل الآن</a>
    </div>
    <div class="service-box">
      <img src="img/Steganography Service.png" alt="Steganography Service" class="service-icon">
      <h3>إخفاء البيانات</h3>
      <p>اخفي بياناتك بشكل آمن داخل الوسائط المتعددة باستخدام تقنيات حديثة.</p>
      <a href="login.php" class="service-link">سجل الآن</a>
    </div>
    <div class="service-box">
      <img src="https://sectigostore.com/blog/wp-content/uploads/2019/09/HashingvsEncryption-lr-940x529.jpg" alt="Hashing Service" class="service-icon">
      <h3>الهاش</h3>
      <p>احصل على أمان بياناتك باستخدام تقنيات الهاش القوية.</p>
      <a href="login.php" class="service-link">سجل الآن</a>
    </div>
    <div class="service-box">
      <img src="https://www.forenova.com/wp-content/uploads/2024/10/CTF-Blog.webp" alt="CTF Challenge" class="service-icon">
      <h3>CTF</h3>
      <p>انضم إلى تحديات CTF لحل المشكلات الأمنية واختبار مهاراتك.</p>
      <a href="login.php" class="service-link">سجل الآن</a>
    </div>
  </div>
  <!-- قسم يوضح أن هناك المزيد من الخدمات -->
  <div class="more-services">
    <p>هل ترغب في استكشاف المزيد من الخدمات؟ <a href="login.php">قم بعمل تسجيل دخول او انشاء حساب</a></p>
  </div>
</section>

<div class="separator"></div>


    <!--****************************************-->

    <div class="title-container">
  <h1 id="faq" class="main-title">الاسئلة الشائعة</h1>
</div>
<div class="container-qus">
    <div class="accordion-container">
        <button class="accordion">ما هو مشروع Cyberbox Gate؟</button>
        <div class="panel">
            <p>Cyberbox Gate هو منصة ويب تفاعلية متخصصة في الأمن السيبراني والتشفير، توفر مجموعة من الأدوات والخدمات التي تساعد المستخدمين على تشفير البيانات، إخفاء المعلومات، وتطوير مهاراتهم في المجال.</p>
        </div>

        <button class="accordion">ما هي الخدمات الأساسية التي يقدمها المشروع؟</button>
        <div class="panel">
            <p>توفر المنصة خدمات مثل التشفير، إخفاء البيانات، توليد كلمات المرور، تحويل الأرقام بين الأنظمة العددية، التحديات العملية (CTF Challenges)، بالإضافة إلى قسم تعليمي.</p>
        </div>

        <button class="accordion">هل يمكنني استخدام الأدوات المتاحة على المنصة بدون تسجيل؟</button>
        <div class="panel">
            <p>لا، يجب عليك تسجيل الدخول لتتمكن من الوصول إلى الأدوات والميزات المتاحة على المنصة.</p>
        </div>

        <button class="accordion">هل المنصة تدعم التشفير الحديث فقط؟</button>
        <div class="panel">
            <p>لا، المنصة تدعم التشفير الكلاسيكي مثل قيصر وفيجينير، بالإضافة إلى التشفير الحديث مثل RSA وAES.</p>
        </div>

        <button class="accordion">ما هي آلية عمل أدوات إخفاء البيانات (Steganography)؟</button>
        <div class="panel">
            <p>تسمح لك الأدوات بإخفاء النصوص داخل الصور الرقمية بحيث لا تتغير الصورة بشكل ملحوظ ويمكنك استرجاع النصوص لاحقًا.</p>
        </div>

        <button class="accordion">هل يمكنني اختبار قوة كلمات المرور على المنصة؟</button>
        <div class="panel">
            <p>نعم، يمكنك توليد كلمات مرور عشوائية واختبار مدى قوتها باستخدام الأدوات المتاحة في المنصة.</p>
        </div>

        <button class="accordion">ما هي التحديات الأمنية (CTF Challenges) التي تقدمها المنصة؟</button>
        <div class="panel">
            <p>توفر المنصة مجموعة من التحديات الأمنية التفاعلية التي تساعد المستخدمين على اختبار مهاراتهم في فك التشفير وحل الألغاز الأمنية.</p>
        </div>

        <button class="accordion">هل يحتوي الموقع على دروس تعليمية حول الأمن السيبراني؟</button>
        <div class="panel">
            <p>نعم، المنصة تحتوي على قسم تعليمي يقدم شروحات تفصيلية حول خوارزميات التشفير وتقنيات الأمن السيبراني وكيفية استخدام الأدوات المتاحة.</p>
        </div>

        <button class="accordion">هل يمكنني استخدام المنصة كمبتدئ في مجال الأمن السيبراني؟</button>
        <div class="panel">
            <p>نعم، المنصة مصممة لتناسب جميع مستويات المهارة، سواء كنت مبتدئًا أو محترفًا في مجال الأمن السيبراني.</p>
        </div>

        <button class="accordion">هل تتطلب المنصة معرفة مسبقة بتقنيات التشفير؟</button>
        <div class="panel">
            <p>لا، يمكنك البدء في استخدام المنصة دون معرفة مسبقة بتقنيات التشفير، حيث يحتوي القسم التعليمي على شروحات للمبتدئين.</p>
        </div>

        <button class="accordion">هل يمكنني استرجاع النصوص المخفية في الصور بسهولة؟</button>
        <div class="panel">
            <p>نعم، يمكنك استرجاع النصوص المخفية في الصور باستخدام الأدوات الخاصة بذلك على المنصة.</p>
        </div>

        <button class="accordion">هل المنصة مجانية؟</button>
        <div class="panel">
            <p>تقدم المنصة خدمات أساسية مجانية، ولكن بعض الأدوات المتقدمة قد تتطلب اشتراكًا أو تسجيل دخول.</p>
        </div>

        <button class="accordion">كيف أبدأ في استخدام المنصة؟</button>
        <div class="panel">
            <p>يمكنك البدء بتسجيل حساب جديد على المنصة، ومن ثم يمكنك الوصول إلى الأدوات والخدمات المتاحة.</p>
        </div>

        <button class="accordion">هل يمكنني استخدام المنصة على الهاتف المحمول؟</button>
        <div class="panel">
            <p>نعم، المنصة متوافقة مع الأجهزة المحمولة ويمكنك الوصول إليها عبر متصفح الهاتف.</p>
        </div>

        <button class="accordion">هل يوجد دعم فني في حال واجهت مشكلة؟</button>
        <div class="panel">
            <p>نعم، يمكنك التواصل مع فريق الدعم الفني عبر البريد الإلكتروني أو من خلال صفحة "اتصل بنا" على الموقع.</p>
        </div>
    </div>
</div>


    <!--**************************************************-->
    <div class="title-container">
  <h1 id="about" class="main-title">نبذة عن الموقع</h1>
</div>
    <div class="container rtl-align"> <!-- Apply RTL here -->
       <!-- <h2 class="title">نبذة عن الموقع</h2>-->
        <div class="card">
            <p class="paragraph">يهدف هذا الموقع إلى توفير أدوات بسيطة وفعّالة في مجال التشفير وحماية البيانات، مما يتيح للمستخدمين القيام بعمليات تشفير النصوص والملفات، وتوليد كلمات مرور قوية، وتحويل الأرقام بين الأنظمة العددية المختلفة مثل الثنائية والعشرية والـHexadecimal. بالإضافة إلى ذلك، يوفر الموقع أدوات لحماية البيانات من خلال تقنيات مثل "Steganography" لإخفاء النصوص داخل الصور.</p>
        </div>

        <h2 class="title">لماذا هذا المشروع؟</h2>
        <div class="card">
            <p class="paragraph">الكثير من المستخدمين يعانون من تعقيد الأدوات المتوفرة للتشفير، مما يعيق قدرتهم على حماية بياناتهم الشخصية. لذلك، جاء هذا المشروع ليحل هذه المشكلة من خلال تقديم أدوات سهلة الاستخدام ومباشرة تضمن أمان البيانات دون الحاجة لمهارات متقدمة.</p>
        </div>

        <h2 class="title">الخدمات التي نقدمها</h2>
        <div class="card">
            <ul class="list">
                <li>تشفير النصوص والملفات باستخدام خوارزميات قوية مثل AES و RSA.</li>
                <li>إنشاء كلمات مرور قوية وموثوقة.</li>
                <li>تحويل الأرقام بين الأنظمة العددية المختلفة.</li>
                <li>إخفاء البيانات داخل الصور باستخدام تقنية "Steganography".</li>
                <li>محتوى تعليمي شامل يشرح مفاهيم التشفير والأمن السيبراني.</li>
                <li>تحديات عملية (CTF) لتحسين مهارات الأمان السيبراني.</li>
            </ul>
        </div>

    </div>

    <!--****************************************-->

<div class="separator"></div>

<div class="title-container">
  <h1 id="contact" class="main-title">تواصل معنا</h1>
</div>
    <div class="contact-container">
        <h2 class="title">للتواصل</h2>
        <!-- Success or Error message -->
        <?php if ($message_sent): ?>
            <div class="message success">
                ✅ تم إرسال رسالتك بنجاح
            </div>
        <?php elseif (!empty($error_message)): ?>
            <div class="message error">
                <?= $error_message ?>
            </div>
        <?php endif; ?>
        <div class="form-container">
            <form id="contact-form" action="" method="post" onsubmit="showLoader()">
                
                <!-- Name input -->
                <div class="input-container">
                    <i class="fas fa-user icon"></i>
                    <input type="text" name="name" placeholder="اسمك" required class="input-field">
                </div>

                <!-- Email input -->
                <div class="input-container">
                    <i class="fas fa-envelope icon"></i>
                    <input type="email" name="email" placeholder="بريدك الإلكتروني" required class="input-field">
                </div>

                <!-- Message input -->
                <div class="input-container-Message">
                    <i class="fas fa-comment icon"></i>
                    <textarea name="message" placeholder="الموضوع" rows="4" required class="textarea-field"></textarea>
                </div>

                <!-- Submit button -->
                <div class="button-container">
                    <button type="submit" class="btn-submit">إرسال</button>
                </div>
            </form>
        </div>
    </div>

    

<!-- محتوى الصفحة -->
<div style="flex: 1;"></div> <!-- عنصر افتراضي لدفع الفوتر للأسفل -->

<!-- الفوتر -->
<footer>
    <div class="footer-content">
    <p>&copy; 2025 جامعة آل البيت (AABU). جميع الحقوق محفوظة. موقع: CyberBox Gate</p>

    </div>
</footer>

</body>
</html>

<script>
      // تأكد من أن الصفحة قد تم تحميلها قبل تشغيل الكود
           document.addEventListener("DOMContentLoaded", function() {
    const errorMessage = document.querySelector('.message.success');

    if (errorMessage) {
        // أوخفِ الرسالة بعد 5 ثانٍ
        setTimeout(() => {
            errorMessage.style.display = 'none';
        }, 5000);  // 5000 ملي ثانية = 5 ثوانٍ
    }
});

var acc = document.getElementsByClassName("accordion");
        var i;
        for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var panel = this.nextElementSibling;
                if (panel.style.display === "block") {
                    panel.style.display = "none";
                } else {
                    panel.style.display = "block";
                }
            });
        }

        window.addEventListener('scroll', function() {
        const header = document.querySelector('header');
        if (window.scrollY > 50) {
            header.classList.add('shrink');
        } else {
            header.classList.remove('shrink');
        }
    });
</script>






















