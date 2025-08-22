<?php

session_start();

include_once('conn.php');

if (isset($_REQUEST['otp_verify'])) {
    $otp = $_REQUEST['otp'];
    $email = $_SESSION['email'];
    $name = $_SESSION['name'];



    /*$select_query = mysqli_query($conn, "SELECT * FROM tbl_otp_check WHERE otp='$otp' AND email='$email' AND is_expired!=1 AND NOW() <= DATE_ADD(create_at, INTERVAL 2 MINUTE)");
    $count = mysqli_num_rows($select_query);*/

$select_query = $conn->prepare("SELECT * FROM tbl_otp_check WHERE otp=? AND email=? 
AND is_expired!=1 AND NOW() <= DATE_ADD(create_at, INTERVAL 2 MINUTE)");
$select_query->bind_param("ss", $otp, $email);
$select_query->execute();
$result = $select_query->get_result();
$count = $result->num_rows;


if ($count > 0) {
    $update_query = $conn->prepare("UPDATE tbl_otp_check SET is_expired=1 WHERE otp=?");
    $update_query->bind_param("s", $otp);
    $update_query->execute();
    /* $select_query = mysqli_query($conn, "UPDATE tbl_otp_check SET is_expired=1 WHERE otp='$otp'");//حدث القيمه على انه تم استعمال رمز التحقق */
        
      
      if (isset($_SESSION['forgot_forwarding']) && $_SESSION['forgot_forwarding'] == true) {
          $_SESSION['otp_verified_reset'] = true;
          $_SESSION['otp_verified_message_to_rest'] = "تم التحقق من الكود بنجاح";
          header('location: passwordreset.php');
          $_SESSION['forgot_forwarding'] = false;
          exit();
      }
        if (isset($_SESSION['pass'])) {
            $pass = $_SESSION['pass'];
            if (isset($_SESSION['register_forwarding']) && $_SESSION['register_forwarding'] == true) {
                $_SESSION['otp_verified_register'] = true;
                $hashed_pass = md5(string: $pass);//تشفير كلمة المرور
                $name = $_SESSION['name'];
                $insert = "INSERT INTO user_form (name, email, password) VALUES ('$name', '$email', '$hashed_pass')";//اضفت البريد الالكتروني لقاعدة البيانات مع الرمز بشكل مشفر 
                mysqli_query($conn, $insert);
                $nowFormatted = (new DateTime('now', new DateTimeZone('Asia/Amman')))->format('Y-m-d h:i:s');
                $insert_logging = "INSERT INTO logging (name,email, my_datetime, operation) VALUES ('$name','$email', '$nowFormatted', 'تسجيل جديد')";// اضافه انه تم تسجيل مستخدم جديد للجدول الخاص بتجميع اللوقز
                mysqli_query($conn, $insert_logging);
                unset($_SESSION['pass']);
                $_SESSION['otp_verified_message_to_register'] = "تم التحقق من الكود بنجاح";
                header('location: login.php');
                exit();
            }
            
            if (isset($_SESSION['register_admin_forwarding']) && $_SESSION['register_admin_forwarding'] == true) {
                $_SESSION['otp_verified_register_admin'] = true;
                $hashed_pass = md5($pass);
                $name = $_SESSION['name'];
                $insert = "INSERT INTO admin_form (name,email, password) VALUES ('$name','$email', '$hashed_pass')";
                mysqli_query($conn, $insert);
                $nowFormatted = (new DateTime('now', new DateTimeZone('Asia/Amman')))->format('Y-m-d h:i:s');
                $insert_logging = "INSERT INTO logging (name,email, my_datetime, operation) VALUES ('$name','$email', '$nowFormatted', 'تسجيل مستخدم رئيسي جديد')";
                mysqli_query($conn, $insert_logging);
                unset($_SESSION['pass']);
              //  $_SESSION['Add_admin_success'] = "تمت اضافة الادمن بنجاح";

                header('location: add_admin_ok.php');
                exit();
            }
          }
            
              if (isset($_SESSION['login_admin_forwarding']) && $_SESSION['login_admin_forwarding'] == true) {
                  $_SESSION['otp_verified_admin'] = true;
                  header('location: Admin/admin_page.php');
                  exit();
              }

    } else {
        $msg = "رمز التحقق المدخل خاطئ";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style-verification.css?v=1.1">
    <link rel="preload" href="img/Background-verification.jpg" as="image">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">



    <title>OTP Verify</title>
    <style>
        *{
            font-family: 'Tajawal', 'Cairo', sans-serif; /* خط عربي أنيق */
        }
        </style>
</head>
<body>

    <div class="circle circle1"></div>
    <div class="circle circle2"></div>
    <div class="circle circle3"></div>

    <div class="form-container">
    <a href="javascript:history.back()" class="back-button">
                <i class="fas fa-arrow-left"></i>
            </a>
        <form action="" method="post">
            <h3 class="title">ادخل رمز التحقق</h3>

            <input type="text" name="otp" id="otp" placeholder="رمز التحقق" required data-parsley-type="otp" data-parsley-trigger="keyup" class="box" />
            <input type="submit" id="submit" name="otp_verify" value="تحقق" class="form-btn" />
            <p class="error <?php echo !empty($msg) ? 'show' : ''; ?>">
                <?php if (!empty($msg)) { echo $msg; } ?>
            </p><br><br>

            <div>
                <span id="expiry-text">تنتهي صلاحية رمز التحقق بعد :</span>
                <span id="timer">120</span> 
            </div>
        </form>
    </div>

</body>
</html>

<script>
        let timeLeft = 120; // وقت العد التنازلي بالثواني
        const timerElement = document.getElementById('timer');
        const expiryTextElement = document.getElementById('expiry-text');

        const countdown = setInterval(function() {
            if (timeLeft <= 0) {
                clearInterval(countdown); // إيقاف العداد
                timerElement.innerHTML = "انتهت صلاحية رمز التحقق"; // عرض الرسالة بعد انتهاء الوقت
                expiryTextElement.style.display = "none"; // إخفاء النص "تنتهي الصلاحية بعد"
            } else {
                timerElement.innerHTML = timeLeft; // تحديث العداد
                timeLeft--;
            }
        }, 1000); // تحديث كل ثانية
    </script>
</body>
</html>
