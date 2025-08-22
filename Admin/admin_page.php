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

include '../session_check.php';

// حذف المستخدم
if (isset($_POST['delete'])) {
    $emailToDelete = $_POST['email'];
    $nameToDelete = $_POST['name'];
    $nowFormatted = (new DateTime('now', new DateTimeZone('Asia/Amman')))->format('Y-m-d h:i:s');

    // جلب ID المستخدم
    $stmt = $conn->prepare("SELECT id FROM user_form WHERE email = ?");
    $stmt->bind_param("s", $emailToDelete);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $userId = $user['id'];

    // حذف البيانات المرتبطة
    $stmt1 = $conn->prepare("DELETE FROM user_scores WHERE user_id = ?");
    $stmt1->bind_param("i", $userId);
    $stmt1->execute();

    $stmt2 = $conn->prepare("DELETE FROM activities WHERE user_id = ?");
    $stmt2->bind_param("i", $userId);
    $stmt2->execute();

    // تسجيل العملية
    $stmt3 = $conn->prepare("INSERT INTO logging(name, email, my_datetime, operation) VALUES (?, ?, ?, ?)");
    $operation = 'حذف المستخدم من قبل مستخدم رئيسي';
    $stmt3->bind_param("ssss", $nameToDelete, $emailToDelete, $nowFormatted, $operation);
    $stmt3->execute();

    // حذف المستخدم من جدول user_form
    $stmt4 = $conn->prepare("DELETE FROM user_form WHERE email = ?");
    $stmt4->bind_param("s", $emailToDelete);
    $stmt4->execute();

    if ($stmt1 && $stmt2 && $stmt3 && $stmt4) {
        $_SESSION['success_message'] = "تم حذف المستخدم بنجاح";
    } else {
        $_SESSION['error_message'] = "حدث خطأ أثناء حذف المستخدم";
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if(isset($_POST['change_password'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);

    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    if(empty($new_password)){
        $_SESSION['error_message'] = "لا يجب ترك كلمة المرور فارغة";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $hashed_password = md5($new_password);
        $update_query = mysqli_query($conn, "UPDATE user_form SET password='$hashed_password' WHERE email='$email'");
        $nowFormatted = (new DateTime('now', new DateTimeZone('Asia/Amman')))->format('Y-m-d h:i:s');
        $insert_logging = "INSERT INTO logging(name,email,my_datetime,operation) VALUES('$name','$email','$nowFormatted','تغيير كلمة المرور من قبل مستخدم رئيسي')";
        mysqli_query($conn, $insert_logging);
        
        if($update_query) {
            $_SESSION['success_message'] = "تم تغيير كلمة المرور بنجاح";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $_SESSION['error_message'] = "حدث خطأ أثناء تحديث كلمة المرور";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المستخدمين</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --danger-color: #e74c3c;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --light-color: #ecf0f1;
            --dark-color: #34495e;
            --white: #ffffff;
            --gray: #95a5a6;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Tajawal', sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
            background-image: url('../img/background6.jpg');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            direction: ltr;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            direction: rtl;
        }
        
        .page-header {
            text-align: center;
            margin: 8px 0;
            position: relative;
        }
        
        .page-title {
            display: inline-block;
            background: linear-gradient(135deg, #053063, #095299);
            color: var(--white);
            padding: 15px 30px;
            border-radius: 30px;
            font-size: 28px;
            font-weight: 700;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .page-title::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                to bottom right,
                rgba(255, 255, 255, 0.3),
                rgba(255, 255, 255, 0)
            );
            transform: rotate(30deg);
            animation: shine 3s infinite;
        }
        
        @keyframes shine {
            0% { transform: rotate(30deg) translate(-30%, -30%); }
            100% { transform: rotate(30deg) translate(30%, 30%); }
        }
        
        .users-table {
            width: 100%;
            background-color: var(--white);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .users-table th {
            background-color: #09437c;
            color: var(--white);
            padding: 15px;
            font-weight: 700;
            text-align: center;
            font-size: 18px;
        }
        
        .users-table td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
            font-size: 17px;
            font-weight: 500;
        }
        
        .users-table tr:last-child td {
            border-bottom: none;
        }
        
        .users-table tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }
        
        .action-form {
            display: inline-block;
            margin: 0 5px;
        }
        
        .form-input {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: all 0.3s;
            width: 180px;
        }
        
        .form-input:focus {
            border-color: var(--secondary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            text-align: center;
        }
        
        .btn-primary {
            background-color: var(--secondary-color);
            color: var(--white);
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            color: var(--white);
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }
        
        .btn-success {
            background-color: var(--success-color);
            color: var(--white);
        }
        
        .btn-success:hover {
            background-color: #27ae60;
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }
        
        .btn i {
            margin-left: 5px;
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            backdrop-filter: blur(5px);
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            width: 90%;
            max-width: 500px;
            overflow: hidden;
            animation: modalFadeIn 0.3s;
        }
        
        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-50px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .modal-header {
            background-color: var(--danger-color);
            color: var(--white);
            padding: 15px 20px;
            font-size: 18px;
            font-weight: 700;
        }
        
        .modal-body {
            padding: 20px;
            text-align: center;
        }
        
        .modal-body p {
            margin-bottom: 20px;
            font-size: 16px;
            color: var(--dark-color);
        }
        
        .modal-footer {
            padding: 15px 20px;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        /* Alert Styles */
        .alert {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 15px 25px;
            border-radius: 5px;
            color: white;
            font-weight: 500;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            z-index: 1100;
            display: flex;
            align-items: center;
            animation: alertSlideIn 0.5s, alertFadeOut 0.5s 2.5s forwards;
        }
        
        .alert-success {
            background-color: var(--success-color);
        }
        
        .alert-error {
            background-color: var(--danger-color);
        }
        
        .alert i {
            margin-left: 10px;
            font-size: 18px;
        }
        
        @keyframes alertSlideIn {
            from { top: -100px; opacity: 0; }
            to { top: 20px; opacity: 1; }
        }
        
        @keyframes alertFadeOut {
            from { top: 20px; opacity: 1; }
            to { top: -100px; opacity: 0; }
        }
        
        /* Responsive Styles */
        @media (max-width: 768px) {
            .users-table {
                display: block;
                overflow-x: auto;
            }
            
            .page-title {
                font-size: 22px;
                padding: 12px 20px;
            }
            
            .form-input {
                width: 100%;
                margin-bottom: 10px;
            }
            
            .action-form {
                display: block;
                margin: 10px 0;
            }
            
            .btn {
                width: 100%;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <?php include 'header_Admin.php'; ?>
    
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-users-cog"></i> إدارة المستخدمين
            </h1>
        </div>
        
        <div class="table-responsive">
            <table class="users-table">
                <thead>
                    <tr>
                        <th><i class="fas fa-user"></i> الاسم</th>
                        <th><i class="fas fa-envelope"></i> البريد الإلكتروني</th>
                        <th><i class="fas fa-cogs"></i> الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $select_query = mysqli_query($conn, "SELECT name, email FROM user_form");
                    while ($row = mysqli_fetch_array($select_query)) {
                        $name = $row['name'];
                        $email = $row['email'];
                    ?>
                    <tr>
                        <td><?php echo $name; ?></td>
                        <td><?php echo $email; ?></td>
                        <td>
                            <form class="action-form" action="" method="post">
                                <input type="hidden" name="email" value="<?php echo $email; ?>">
                                <input type="hidden" name="name" value="<?php echo $name; ?>">
                                <input type="text" class="form-input" name="new_password" placeholder="كلمة المرور الجديدة" required>
                                <button type="submit" name="change_password" class="btn btn-success">
                                    <i class="fas fa-key"></i> تغيير كلمة المرور
                                </button>
                            </form>
                            
                            <form class="action-form" action="" method="post" onsubmit="event.preventDefault(); showDeleteModal('<?php echo $email; ?>', '<?php echo $name; ?>')">
                                <input type="hidden" name="email" value="<?php echo $email; ?>">
                                <input type="hidden" name="name" value="<?php echo $name; ?>">
                                <button type="submit" name="delete" class="btn btn-danger">
                                    <i class="fas fa-trash-alt"></i> حذف
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fas fa-exclamation-triangle"></i> تأكيد الحذف
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من رغبتك في حذف هذا المستخدم؟</p>
                <p style="color: var(--danger-color); font-weight: bold;">سيتم حذف جميع بيانات المستخدم بشكل نهائي ولا يمكن استرجاعها.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" onclick="deleteUser()">
                    <i class="fas fa-check"></i> تأكيد الحذف
                </button>
                <button class="btn btn-primary" onclick="closeDeleteModal()">
                    <i class="fas fa-times"></i> إلغاء
                </button>
            </div>
        </div>
    </div>
    
    <!-- Alert Messages -->
    <?php if (isset($_SESSION['success_message'])) { ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success_message']; ?>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php } ?>
    
    <?php if (isset($_SESSION['error_message'])) { ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error_message']; ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php } ?>
    
    <script>
        // عرض نافذة التأكيد
        function showDeleteModal(email, name) {
            document.getElementById('deleteModal').style.display = 'flex';
            window.emailToDelete = email;
            window.nameToDelete = name;
        }
        
        // إغلاق نافذة التأكيد
        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
        
        // تنفيذ الحذف
        function deleteUser() {
            const form = document.createElement('form');
            form.method = 'post';
            form.action = '';
            
            const emailInput = document.createElement('input');
            emailInput.type = 'hidden';
            emailInput.name = 'email';
            emailInput.value = window.emailToDelete;
            
            const nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.name = 'name';
            nameInput.value = window.nameToDelete;
            
            const deleteInput = document.createElement('input');
            deleteInput.type = 'hidden';
            deleteInput.name = 'delete';
            deleteInput.value = '1';
            
            form.appendChild(emailInput);
            form.appendChild(nameInput);
            form.appendChild(deleteInput);
            
            document.body.appendChild(form);
            form.submit();
        }
        
        // إخفاء التنبيهات تلقائياً بعد 3 ثوانٍ
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.display = 'none';
            });
        }, 3000);
    </script>
</body>
</html>