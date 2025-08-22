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

include '../session_check.php'; // تجديد الجلسة بشكل تلقائي بعد فترة معينة

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['usermail']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);
    $cpass = mysqli_real_escape_string($conn, $_POST['cpassword']);

    $select = "SELECT * FROM admin_form WHERE email = '$email'"; // استعلام للتحقق من وجود الإيميل
    $result = mysqli_query($conn, $select);
    $error = [];
    if (mysqli_num_rows($result) > 0) {
        $error[] = 'تم تسجيل الإيميل من قبل';
    } else {
        if ($pass != $cpass) { // التحقق من تطابق كلمات المرور
            $error[] = 'كلمة المرور غير متطابقة';
        } else {
            if (!isPasswordComplex($pass)) { // التحقق من تعقيد كلمة المرور
                $error[] = 'كلمة المرور يجب أن تحتوي على حروف كبيرة وصغيرة وأرقام وأحرف خاصة، وتكون طولها على الأقل 8 أحرف.';
            }

            if (count($error) === 0) { // التحقق من عدم وجود أخطاء
                include_once 'send_otp.php'; // إرسال رمز التحقق عبر البريد الإلكتروني

                if ($mail->send()) {
                    $insert_query = mysqli_query($conn, "INSERT INTO tbl_otp_check SET otp='$otp', is_expired='0', email='$email'");
                    $_SESSION['email'] = $email;
                    $_SESSION['name'] = $name;
                    $_SESSION['pass'] = $pass;
                    $_SESSION['register_admin_forwarding'] = true; // تخزين قيمة تم إرسال الرمز
                    header('location:../verification.php'); // الانتقال إلى صفحة التحقق
                    exit();
                } else {
                    $error[] = "لم يتم إرسال الإيميل";
                }
            }
        }
    }
}

function isPasswordComplex($password) {
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'; // التحقق من تعقيد كلمة المرور
    return preg_match($pattern, $password);
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل مستخدم رئيسي</title>
    <link rel="preload" href="../img/background6.jpg" as="image">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
         @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Tajawal', 'Cairo', sans-serif;
        }
        body {
            background-image: url('../img/background6.jpg'); /* وضع رابط الصورة هنا */
            background-size: cover;
            height: 100vh;
        }
        .login-container {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

        .login-box {
            background: rgb(40 90 138);
            border-radius: 20px;
            padding: 30px 35px;
            width: 470px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.25);
            text-align: center;
            position: relative;
        }
        h2 {
            color: white;
            margin-bottom: 28px;
            font-size: 30px;
            font-weight: 600;
        }
        
.register-text {
    color: white;
    margin-top: 16px; /* تقليص المسافة */
    font-size: 17px;
}

.register-text a {
    color:  rgb(43 43 43);
    text-decoration: none;
    font-weight: bold;
    transition: 0.3s;
}

.register-text a:hover {
    text-decoration: underline;
    color: #0073e6;
}

        .input-group {
            display: flex;
            align-items: center;
            background: rgba(254, 253, 253, 0.2);
            border-radius: 30px;
            padding: 10px 16px 10px 21px;
            margin-bottom: 15px;
            transition: 0.3s;
            justify-content: space-between;
        }
        .input-group input {
            border: none;
            background: none;
            outline: none;
            flex: 1;
            color: white;
            font-size: 16px;
            padding: 10px;
            text-align: left;
        }
        .input-group i {
            color: white;
            margin-right: 12px;
            font-size: 18px;
        }
        input::placeholder {
            color: white;
        }
        .input-group .eye-icon {
            cursor: pointer;
            margin-left: 12px;
            font-size: 18px;
        }
        .input-group:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        .error-message {
            color: #ff4d4d;
            background-color: #ffd1d1;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 25px;
            text-align: center;
            font-size: 1.1rem;
        }
        .login-btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 30px;
            background: white;
            color: #2c3e50;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 4px 8px rgba(255, 255, 255, 0.2);
        }
        .login-btn:hover {
            background: rgba(255, 255, 255, 0.9);
            transform: scale(1.05);
        }

        
          
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active {
            background-color: #7095e2 !important;
            -webkit-box-shadow: 0 0 0px 1000px #7095e2 inset !important;
            -webkit-text-fill-color: white !important;
            border-radius: 55px !important;
            color: white !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
    </style>
</head>
<?php
include 'header_Admin.php'; 
?>

<body>
    

               <div class="login-container">
        <div class="login-box">
            <h2>انشاء حساب مستخدم رئيسي</h2><br>


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
                foreach ($error as $err) {
                    echo '<div class="error-message">' . $err . '</div>';
                }
            }
            ?>
                <button type="submit" name="submit" class="login-btn"><i class="fas fa-user-plus"></i> إنشاء حساب مستخدم</button>
                
            </form>
            <p class="register-text">هل لديك حساب ؟ <a href="../login.php">تسجيل الدخول</a></p>

        </div>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirm_password');
    
        const toggleVisibility = (input, icon) => {
            const isPasswordVisible = input.type === 'text';
            input.type = isPasswordVisible ? 'password' : 'text';
            
            const iconElement = icon.querySelector('i');
            iconElement.classList.toggle('fa-eye');
            iconElement.classList.toggle('fa-eye-slash');
        };
    
        togglePassword.addEventListener('click', () => toggleVisibility(passwordInput, togglePassword));
        toggleConfirmPassword.addEventListener('click', () => toggleVisibility(confirmPasswordInput, toggleConfirmPassword));

        document.addEventListener("DOMContentLoaded", function() {
            const errorMessage = document.querySelector('.error-message');

            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.style.display = 'none';
                }, 5000);  
            }
        });
    </script>
</body>
</html>

   