<?php
include_once('conn.php'); // الاتصال بقاعدة البيانات
session_start(); // بدء الجلسة

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['usermail']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);
    $cpass = mysqli_real_escape_string($conn, $_POST['cpassword']);

    $error = [];

    // التحقق من وجود البريد في قاعدة البيانات باستخدام Prepared Statements
    $select = $conn->prepare("SELECT * FROM user_form WHERE email = ?");
    $select->bind_param("s", $email); // ربط المتغير
    $select->execute();
    $result = $select->get_result();

    if (mysqli_num_rows($result) > 0) {
        $error[] = 'تم تسجيل الايميل من قبل';
    } else {
        if ($pass != $cpass) {
            $error[] = 'كلمة المرور غير متطابقة';
        } else {
            // التحقق من تعقيد كلمة المرور باستخدام دالة isPasswordComplex
            if (!isPasswordComplex($pass)) {
                $error[] = 'كلمة المرور يجب أن تحتوي على حروف كبيرة وصغيرة وأرقام وأحرف خاصة، وتكون طولها على الأقل 8 أحرف.';
            }

            // إذا كانت كل الأمور سليمة، إرسال OTP
            if (count($error) === 0) {
                include 'send_otp.php'; 

                if ($mail->send()) {
                    // إدخال OTP في قاعدة البيانات باستخدام Prepared Statement
                    $insert_query = $conn->prepare("INSERT INTO tbl_otp_check SET otp=?, is_expired='0', email=?");
                    $insert_query->bind_param("ss", $otp, $email);
                    $insert_query->execute();

                    // تخزين معلومات المستخدم في الجلسة
                    $_SESSION['email'] = $email;
                    $_SESSION['name'] = $name;
                    $_SESSION['register_forwarding'] = true; // شرط أنه تم إرسال OTP
                    $_SESSION['pass'] = $pass;

                    // التوجيه إلى صفحة التحقق
                    header('location: verification.php');
                    exit();
                } else {
                    $error[] = "لم يتم ارسال الايميل";
                }
            }
        }
    }
}

// دالة للتحقق من تعقيد كلمة المرور
function isPasswordComplex($password) {
    // يجب أن تحتوي كلمة المرور على حرف كبير، حرف صغير، رقم، وحرف خاص، وطولها على الأقل 8 أحرف
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!#_%*?&])[A-Za-z\d@$!#_%*?&]{8,}$/';
    return preg_match($pattern, $password);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style-register.css?v=1.3">
    <title>انشاء الحساب</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="preload" href="img/Background_login_register.jpg" as="image">

</head>
<body>
    <div class="login-container">
        <div class="login-box">
        <a href="index.php" class="back-button">
    <i class="fas fa-arrow-left"></i>
</a>
            <div class="avatar">
                <i class="fas fa-user"></i>
            </div>
            <h2>انشاء الحساب</h2>
            
            <form action="" method="POST">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" id="name" name="username" autocomplete="off" placeholder="ادخل اسم المستخدم" required>

                </div>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="usermail" autocomplete="off" placeholder="ادخل ايميل المستخدم" required>

                </div>

                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="ادخل كلمة مرور جديدة" required>
                    <span class="eye-icon" id="togglePassword"><i class="fas fa-eye"></i></span>
                </div>

                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="confirm_password" name="cpassword" placeholder="اعد كتابة كلمة المرور" required>
                    <span class="eye-icon" id="toggleConfirmPassword"><i class="fas fa-eye"></i></span>
                </div>
                <?php
if (!empty($error)) {
    echo '<div class="error-container">';
    foreach ($error as $err) {
        echo '<div class="error-message">' . htmlspecialchars($err) . '</div>';
    }
    echo '</div>';
}
?>

                <button type="submit" name="submit" class="login-btn">انشاء الحساب</button>
                
            </form>
            <p class="register-text">هل لديك حساب ؟ <a href="login.php">تسجيل الدخول</a></p>
        </div>
    </div>

    <script>
    // دالة لتغيير حالة زر الإرسال
    function setSubmitButtonState(isLoading) {
        const submitBtn = document.querySelector('button[name="submit"]');
        if (submitBtn) {
            if (isLoading) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جار التسجيل...';
                submitBtn.disabled = true;
            } else {
                submitBtn.innerHTML = 'إنشاء الحساب';
                submitBtn.disabled = false;
            }
        }
    }

    // إضافة حدث submit للنموذج
    document.querySelector('form')?.addEventListener('submit', function(e) {
        // تأخير تغيير حالة الزر لضمان تنفيذ PHP أولاً
        setTimeout(() => {
            if (this.checkValidity()) {
                setSubmitButtonState(true);
            }
        }, 100);
    });

    // إعادة تعيين الزر عند تحميل الصفحة
    window.addEventListener('load', function() {
        setSubmitButtonState(false);
        
        // إخفاء رسائل الخطأ بعد 5 ثوان
        const errorMessages = document.querySelectorAll('.error-message');
        if (errorMessages.length > 0) {
            setTimeout(() => {
                errorMessages.forEach(msg => {
                    msg.style.opacity = '0';
                    setTimeout(() => msg.remove(), 500);
                });
            }, 5000);
        }
    });

    // بقية الكود الخاص بإظهار/إخفاء كلمة المرور
    const togglePassword = document.querySelector('#togglePassword');
    const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');

    const toggleVisibility = (input, icon) => {
        if (input && icon) {
            const isPasswordVisible = input.type === 'text';
            input.type = isPasswordVisible ? 'password' : 'text';
            
            const iconElement = icon.querySelector('i');
            if (iconElement) {
                iconElement.classList.toggle('fa-eye');
                iconElement.classList.toggle('fa-eye-slash');
            }
        }
    };

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', () => toggleVisibility(passwordInput, togglePassword));
    }

    if (toggleConfirmPassword && confirmPasswordInput) {
        toggleConfirmPassword.addEventListener('click', () => toggleVisibility(confirmPasswordInput, toggleConfirmPassword));
    }
</script>
        

</body>
</html>
