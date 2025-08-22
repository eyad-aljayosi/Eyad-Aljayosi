<?php
include '../conn.php';
session_start();

if (!isset($_SESSION['usermail'])) {
    $_SESSION['login_befor'] = "قم بعمل تسجيل دخول بالبداية";
    header('location:../login.php');
    exit();
}

$email = $_SESSION['usermail'];
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

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['usermail']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);
    $cpass = mysqli_real_escape_string($conn, $_POST['cpassword']);

    $select = "SELECT * FROM user_form WHERE email = '$email'";
    $result = mysqli_query($conn, $select);

    $hashed_pass = md5($pass);

    $error = [];
    if (mysqli_num_rows($result) > 0) {
        $error[] = 'تم تسجيل الايميل من قبل';
    } else {
        if ($pass != $cpass) {
            $error[] = 'كلمة المرور غير متطابقة';
        } else {
            if (!isPasswordComplex($pass)) {
                $error[] = 'كلمة المرور يجب أن تحتوي على حروف كبيرة وصغيرة وأرقام وأحرف خاصة، وتكون طولها على الأقل 8 أحرف.';
            }

            if (count($error) === 0) {
                $insert_query = mysqli_query($conn, "INSERT INTO user_form (name, email, password) VALUES ('$name', '$email', '$hashed_pass')");
                
                if ($insert_query) {
                    $_SESSION['success_message'] = 'تم إنشاء الحساب بنجاح';
                    header('Location: ' . $_SERVER['PHP_SELF']);
                    exit();
                } else {
                    $error[] = "حدث خطأ أثناء إنشاء الحساب";
                }
            }
        }
    }
}

function isPasswordComplex($password) {
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!#%*?&])[A-Za-z\d@$!#%*?&]{8,}$/';
    return preg_match($pattern, $password);
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب مستخدم</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --danger-color: #e74c3c;
            --success-color: #2ecc71;
            --light-color: #ecf0f1;
            --dark-color: #34495e;
            --white: #ffffff;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Tajawal', sans-serif;
        }
        
        body {
            background-image: url('../img/background6.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            direction: ltr;
        }
        
        .admin-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex: 1;
            padding: 20px;
            direction: rtl;
        }
        
        .admin-form {
            background: rgb(40 90 138);
            border-radius: 20px;
            padding: 30px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .form-title {
            color: var(--white);
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 700;
            position: relative;
        }
        
        .form-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: var(--white);
            border-radius: 3px;
        }
        
        .input-group {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 30px;
            padding: 5px 10px;            
            margin-bottom: 20px;
            direction: ltr;
            transition: all 0.3s;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .input-group:focus-within {
            background: rgba(255, 255, 255, 0.3);
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
        }
        
        .input-group i {
            color: var(--white);
            font-size: 18px;
            margin-left: 10px;
        }
        
        .input-group input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            padding: 15px 15px;
            color: var(--white);
            font-size: 16px;
        }
        
        .input-group input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        
        .eye-icon {
            cursor: pointer;
            color: var(--white);
            font-size: 18px;
            margin-right: 10px;
        }
        
        .submit-btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 30px;
            background: var(--white);
            color: var(--primary-color);
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .submit-btn:hover {
            background: rgba(255, 255, 255, 0.9);
            transform: translateY(-2px);
            box-shadow: 0 7px 20px rgba(0, 0, 0, 0.15);
        }
        
        .error-message {
            color: var(--white);
            background-color: rgba(231, 76, 60, 0.7);
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
            font-size: 16px;
            animation: shake 0.5s;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }
        
        .alert {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 15px 25px;
            border-radius: 8px;
            background: var(--success-color);
            color: var(--white);
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            display: flex;
            align-items: center;
            animation: slideIn 0.5s, fadeOut 0.5s 3s forwards;
        }
        
        .alert i {
            margin-left: 10px;
        }
        
        @keyframes slideIn {
            from { top: -100px; opacity: 0; }
            to { top: 20px; opacity: 1; }
        }
        
        @keyframes fadeOut {
            from { top: 20px; opacity: 1; }
            to { top: -100px; opacity: 0; }
        }
        
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .admin-form {
                padding: 25px;
                margin: 20px;
            }
            
            .form-title {
                font-size: 24px;
            }
            
            .input-group {
                padding: 0 15px;
            }
            
            .input-group input {
                padding: 12px 0;
                font-size: 15px;
            }
            
            .submit-btn {
                padding: 12px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <?php include 'header_Admin.php'; ?>
    
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert">
            <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success_message']; ?>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    
    <div class="admin-container">
        <div class="admin-form">
            <h2 class="form-title">
                <i class="fas fa-user-plus"></i> إنشاء حساب مستخدم
            </h2>
            
            <form action="" method="POST">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" autocomplete="off" placeholder="اسم المستخدم" required>
                </div>
                
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="usermail" autocomplete="off" placeholder="البريد الإلكتروني" required>
                </div>
                
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="كلمة المرور" required>
                    <span class="eye-icon" id="togglePassword"><i class="fas fa-eye"></i></span>
                </div>

                
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="confirm_password" name="cpassword" placeholder="تأكيد كلمة المرور" required>
                    <span class="eye-icon" id="toggleConfirmPassword"><i class="fas fa-eye"></i></span>
                </div>
                
                <?php if (!empty($error)): ?>
                    <?php foreach ($error as $err): ?>
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $err; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <button type="submit" name="submit" class="submit-btn">
                    <i class="fas fa-user-plus"></i> إنشاء الحساب
                </button>
            </form>
        </div>
    </div>

    <script>
        // إظهار/إخفاء كلمة المرور
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
        
        // إخفاء رسائل الخطأ بعد 5 ثوان
        setTimeout(() => {
            const errorMessages = document.querySelectorAll('.error-message');
            errorMessages.forEach(msg => {
                msg.style.display = 'none';
            });
        }, 5000);
    </script>
</body>
</html>