<?php
session_start(); // بدء الجلسة

if (isset($_POST['submit'])) { // إذا تم الضغط على زر "تسجيل الدخول"
    include 'conn.php'; // الاتصال بقاعدة البيانات
    $email = mysqli_real_escape_string($conn, $_POST['usermail']); // تعقيم البيانات
    $pass = md5($_POST['password']); // تشفير كلمة المرور المدخلة

    // إعادة ضبط الجلسة بالكامل عند تسجيل الدخول الجديد
    session_unset();
    session_destroy();
    session_start();
    session_regenerate_id(true);
 
    // التحقق من بيانات المستخدم في جدول الإدمن باستخدام Prepared Statements
    $admin_select = $conn->prepare("SELECT * FROM admin_form WHERE email = ? AND password = ?");
    $admin_select->bind_param("ss", $email, $pass); // ربط المتغيرات
    $admin_select->execute();
    $admin_result = $admin_select->get_result();

    if ($admin_result->num_rows > 0) { // إذا كان المستخدم في جدول الإدمن
        $admin_data = $admin_result->fetch_assoc(); // جلب البيانات
        $_SESSION['usermail'] = $email; // تعيين البريد الإلكتروني في الجلسة
        $_SESSION['username'] = $admin_data['name']; // تعيين الاسم في الجلسة
        $name = $_SESSION['username'];

        // تخزين نص العملية في متغير
        $operation = 'تسجيل دخول مستخدم رئيسي';

        // تسجيل العملية في جدول التسجيلات
        $nowFormatted = (new DateTime('now', new DateTimeZone('Asia/Amman')))->format('Y-m-d h:i:s');
        $insert_logging = $conn->prepare("INSERT INTO logging(name, email, my_datetime, operation) VALUES (?, ?, ?, ?)");
        $insert_logging->bind_param("ssss", $name, $email, $nowFormatted, $operation);
        $insert_logging->execute();

        // إرسال OTP عبر البريد الإلكتروني (إذا كنت بحاجة لها)
        include 'send_otp.php';
        if ($mail->send()) {
            $insert_query = $conn->prepare("INSERT INTO tbl_otp_check SET otp=?, is_expired='0', email=?");
            $insert_query->bind_param("ss", $otp, $email);
            $insert_query->execute();

            $_SESSION['email'] = $email;
            $_SESSION['name'] = $name;
            $_SESSION['login_admin_forwarding'] = true;
            header('Location: verification.php'); // التوجيه إلى صفحة التحقق
            exit();
        } else {
            $error[] = "لم يتم إرسال الإيميل";
        }
        exit();
    }

    // التحقق من بيانات المستخدم العادي في جدول المستخدمين باستخدام Prepared Statements
    $user_select = $conn->prepare("SELECT * FROM user_form WHERE email = ?");
    $user_select->bind_param("s", $email); // ربط المتغير
    $user_select->execute();
    $user_result = $user_select->get_result();

    if ($user_result->num_rows > 0) {
        $user_row = $user_result->fetch_assoc();
        $stored_hashed_password = $user_row['password']; // كلمة المرور المخزنة
        $name = $user_row['name']; // استخراج الاسم
        $id = $user_row['id']; // استخراج ID المستخدم

        if ($pass === $stored_hashed_password) { // إذا تطابق كلمة المرور المدخلة مع المخزنة
            // ✅ تسجيل دخول ناجح
            $_SESSION['usermail'] = $email;
            $_SESSION['username'] = $name;
            $_SESSION['id'] = $id;

            // تخزين نص العملية في متغير
            $operation = 'تسجيل دخول ناجح';

            // تسجيل العملية في جدول التسجيلات
            $nowFormatted = (new DateTime('now', new DateTimeZone('Asia/Amman')))->format('Y-m-d h:i:s');
            $insert_logging = $conn->prepare("INSERT INTO logging(name, email, my_datetime, operation) VALUES (?, ?, ?, ?)");
            $insert_logging->bind_param("ssss", $name, $email, $nowFormatted, $operation);
            $insert_logging->execute();


            // توجيه المستخدم إلى الصفحة الرئيسية
            header('Location: home.php');
            exit();
        } else {
            // ❌ كلمة المرور خاطئة
            $error[] = 'كلمة المرور خاطئة';
            $nowFormatted = (new DateTime('now', new DateTimeZone('Asia/Amman')))->format('Y-m-d h:i:s');

            // تخزين نص العملية في متغير
            $operation = 'تسجيل دخول خاطئ كلمة المرور غير صحيحة';

            // تسجيل العملية في جدول التسجيلات
            $insert_logging = $conn->prepare("INSERT INTO logging(name, email, my_datetime, operation) VALUES (?, ?, ?, ?)");
            $insert_logging->bind_param("ssss", $name, $email, $nowFormatted, $operation);
            $insert_logging->execute();
        }
    } else {
        // ❌ الإيميل غير موجود
        $error[] = 'الايميل غير مسجل';
        $nowFormatted = (new DateTime('now', new DateTimeZone('Asia/Amman')))->format('Y-m-d h:i:s');

        // تخزين نص العملية في متغير
        $name = 'Unknown'; // قم بتعيين اسم افتراضي
        $operation = 'تسجيل دخول خاطئ الايميل غير مسجل';

        // تسجيل العملية في جدول التسجيلات
        $insert_logging = $conn->prepare("INSERT INTO logging(name, email, my_datetime, operation) VALUES (?, ?, ?, ?)");
        $insert_logging->bind_param("ssss", $name, $email, $nowFormatted, $operation);
        $insert_logging->execute();
    }

    mysqli_close($conn);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style-login.css?v=1.7">
    <title>تسجيل الدخول</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="preload" href="img/Background_login_register.jpg" as="image">

</head>
<body>
    <?php
    // عرض الإشعار إذا كان موجودًا
    if (isset($_SESSION['otp_verified_message_to_register'])) {
        echo '<div class="alert">' . $_SESSION['otp_verified_message_to_register'] . '</div>';
        unset($_SESSION['otp_verified_message_to_register']); //حذف الرسالة من الجلسة بعد عرضها 
    }

    if (isset($_SESSION['login_befor'])) {
        echo '<div class="alert-log">' . $_SESSION['login_befor'] . '</div>';
        unset($_SESSION['login_befor']);
        
    }
    
    if (isset($_SESSION['session_status'])) {
        echo '<div class="alert-log">' . $_SESSION['session_status'] . '</div>';
        unset($_SESSION['session_status']);
    }
    
    if (isset($_SESSION['not_admin'])) {
        echo '<div class="alert-log">' . $_SESSION['not_admin'] . '</div>';
        unset($_SESSION['not_admin']);
    }
    
    if (isset($_SESSION['the_password_is_reset'])) {
        echo '<div class="alert">' . $_SESSION['the_password_is_reset'] . '</div>';
        unset($_SESSION['the_password_is_reset']);
    }
    if (isset($_SESSION['Login_as_an_admin'])) {
        echo '<div class="alert">' . $_SESSION['Login_as_an_admin'] . '</div>';
        unset($_SESSION['Login_as_an_admin']); //حذف الرسالة من الجلسة بعد عرضها 
    }

?>
    <div class="login-container">
        <div class="login-box">

        <a href="index.php" class="back-button">
    <i class="fas fa-arrow-left"></i>
</a>

            <div class="avatar">
                <i class="fas fa-user"></i>
            </div>
            <h2>تسجيل الدخول</h2>
            
            <form action=""  method="POST">
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email"  id="usermail" name="usermail" placeholder="ادخل ايميل المستخدم"  required>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="ادخل كلمة المرور "  required>

                    <span class="eye-icon" id="togglePassword"><i class="fas fa-eye"></i></span>
                </div>
                <?php
            if(isset($error)){
                foreach($error as $error){
                    echo '<div class="error-message">'.$error.'</div>';
                }
            }
            ?>
                <div class="forgot-password">
                    <a href="forgot.php"><i class="fas fa-key"></i>   نسيت كلمة السر   </a>
                </div>
                
                <button type="submit" name="submit" class="login-btn">  تسجيل الدخول </button>
            </form>
            
            <p class="register-text">ليس لديك حساب ؟<a href="register.php">   انشاء حساب    </a></p>
           
        </div>
    </div>

    <script>
        // جافاسكربت لإظهار/إخفاء كلمة المرور
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;

            // تبديل أيقونة العين بين مفتوحة ومغلقة
            const icon = this.querySelector('i');
            if (type === 'password') {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });

                    // تأكد من أن الصفحة قد تم تحميلها قبل تشغيل الكود
document.addEventListener("DOMContentLoaded", function() {
    const errorMessage = document.querySelector('.error-message');

    if (errorMessage) {
        // أخفِ الرسالة بعد 5 ثوانٍ
        setTimeout(() => {
            errorMessage.style.display = 'none';
        }, 5000);  // 5000 ملي ثانية = 5 ثوانٍ
    }
});

    </script>

</body>
</html>






