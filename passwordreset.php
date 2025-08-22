
<?php
                include 'conn.php'; // الاتصال بقاعدة البيانات
                $_SESSION['otp_verified_reset'] = false;
                
                session_start(); // بدء الجلسة
                
                
                if (!isset($_SESSION['email']) || !isset($_SESSION['otp_verified_reset']) || $_SESSION['otp_verified_reset'] !== true) {
                    header('Location: forgot.php'); // إعادة التوجيه إلى صفحة إعادة التهيئة إذا لم تتحقق الشروط.
                    $_SESSION['otp_verified_resets'] = false; // إعداد قيمة لتوضيح أن التحقق غير ناجح.
                    exit(); // إنهاء التنفيذ لمنع الوصول غير المصرح به.
                }
                
                $email = $_SESSION['email']; // استرجاع البريد الإلكتروني من الجلسة
                
                if (isset($_POST['submit'])) { // إذا تم الضغط على زر "إرسال"
                    if (isset($_SESSION['email'])) { // إذا كانت الجلسة تحتوي على البريد الإلكتروني
                        $email = $_SESSION['email'];
                        $password = mysqli_real_escape_string($conn, $_POST['password']); // تعقيم كلمة المرور المدخلة
                        $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']); // تعقيم كلمة المرور المدخلة
                
                        if ($password === $cpassword) { // إذا كانت كلمة المرور متطابقة مع إعادة كلمة المرور
                            if (isPasswordComplex($password)) { // التحقق من تعقيد كلمة المرور
                                $name = $_SESSION['name'];
                                $hash_password = md5($password);//md5 شفر كلمة المرور باستخدام
                
                                // استخدام Prepared Statement لتحديث كلمة المرور في قاعدة البيانات
                                $update_query = $conn->prepare("UPDATE user_form SET password=? WHERE email=?");
                                $update_query->bind_param("ss", $hash_password, $email);
                                $update_query->execute();
                
                                // تخزين نص العملية في متغير
                                $operation = 'تغيير كلمة المرور';
                
                                // تسجيل العملية في جدول السجلات باستخدام متغير للعملية
                                $nowFormatted = (new DateTime('now', new DateTimeZone('Asia/Amman')))->format('Y-m-d h:i:s');
                                $insert_logging = $conn->prepare("INSERT INTO logging(name, email, my_datetime, operation) VALUES (?, ?, ?, ?)");
                                $insert_logging->bind_param("ssss", $name, $email, $nowFormatted, $operation);
                                $insert_logging->execute();
                
                                if ($update_query) { // إذا تم تحديث كلمة المرور
                                    unset($_SESSION['email']); // حذف الجلسة الخاصة بالبريد الإلكتروني
                                    $_SESSION['the_password_is_reset'] = "تم تغيير كلمة المرور بنجاح";
                                    $success = "تم تحديث كلمة المرور بنجاح. الرجاء تسجيل الدخول باستخدام كلمة المرور الجديدة";
                                    header('location: login.php');
                                    exit();
                                } else {
                                    $error = "حدث خطأ أثناء تحديث كلمة المرور"; // في حال حدوث خطأ
                                }
                            } else {
                                $error = "كلمة المرور يجب أن تحتوي على حروف كبيرة وصغيرة وأرقام وأحرف خاصة، وتكون طولها على الأقل 8 أحرف."; // إذا كانت كلمة المرور غير معقدة
                            }
                        } else {
                            $error = "كلمتي المرور غير متطابقتين"; // إذا كانت كلمتي المرور غير متطابقتين
                        }
                    }
                }
                
                // دالة للتحقق من تعقيد كلمة المرور
                function isPasswordComplex($password) {
                    // التحقق باستخدام تعبير منتظم للتأكد من تعقيد كلمة المرور
                    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
                    return preg_match($pattern, $password);
                }
                ?>
                
                <!DOCTYPE html>
                <html lang="ar">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <link rel="stylesheet" href="css/style-passwordreset.css?v=1.3">
                    <title>إعادة تعيين كلمة المرور</title>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
                    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
                
                </head>
                <body>
                <?php
                if (isset($_SESSION['otp_verified_message_to_rest'])) {
                        echo '<div class="alert">' . $_SESSION['otp_verified_message_to_rest'] . '</div>';
                        unset($_SESSION['otp_verified_message_to_rest']);
                    }
                    ?>
                    <div class="form-container">
                        <form action="" method="post">
                            <h3 class="title">استرجاع كلمة المرور</h3>
                            <?php
                                if(isset($error)){
                                    echo '<div class="error-msg">'.$error.'</div>';
                                }
                                if(isset($success)){
                                    echo '<div class="success-msg">'.$success.'</div>';
                                }
                            ?>
                            <div class="box-wrapper">
                                <input type="password" name="password" id="password" placeholder="ادخل كلمة المرور الجديدة" class="box" required>
                                <span class="eye-icon" onclick="togglePasswordVisibility()"><i class="fas fa-eye"></i></span>
                            </div>
                            <div class="box-wrapper">
                                <input type="password" name="cpassword" id="cpassword" placeholder="اعد ادخال كلمة المرور الجديدة" class="box" required>
                                <span class="eye-icon" onclick="togglePasswordVisibility()"><i class="fas fa-eye"></i></span>
                            </div>
                            <input type="submit" value="استرجاع كلمة المرور" class="form-btn" name="submit">
                            <p>تذكرت كلمة المرور؟ <a href="login.php">سجل دخولك</a></p>
                        </form>
                    </div>
                    <script>
                        // دالة لإظهار/إخفاء كلمة المرور
                        function togglePasswordVisibility() {
                            const passwordInput = document.getElementById('password');
                            const confirmPasswordInput = document.getElementById('cpassword');
                            const passwordIcon = passwordInput.nextElementSibling.querySelector('i');
                            const confirmPasswordIcon = confirmPasswordInput.nextElementSibling.querySelector('i');
                
                            if (passwordInput.type === 'password' || confirmPasswordInput.type === 'password') {
                                passwordInput.type = 'text';
                                confirmPasswordInput.type = 'text';
                                passwordIcon.classList.remove('fa-eye');
                                passwordIcon.classList.add('fa-eye-slash');
                                confirmPasswordIcon.classList.remove('fa-eye');
                                confirmPasswordIcon.classList.add('fa-eye-slash');
                            } else {
                                passwordInput.type = 'password';
                                confirmPasswordInput.type = 'password';
                                passwordIcon.classList.remove('fa-eye-slash');
                                passwordIcon.classList.add('fa-eye');
                                confirmPasswordIcon.classList.remove('fa-eye-slash');
                                confirmPasswordIcon.classList.add('fa-eye');
                            }
                        }
                    </script>
                </body>
                </html>