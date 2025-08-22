<?php
include('../conn.php');

// بداية جلسة عادية
session_start();

//بدايه الجلسه 
//admin_formمن قاعدة البيانات حيث الكود الحالي يمنع اي مستخدم من الدخول الى الصفحات من دون ايميل و ليس من الجدول  admin_formالكود الحالي يتاكد ان الجلسه تحتوي على ايميل وان الايميل الموجود في الجلسه من الجدول 

if(!isset($_SESSION['usermail'])){
    $_SESSION['login_befor']="قم بعمل تسجيل دخول بالبداية ";
    header('location:../login.php');//
    exit();
 } 
 /*طبعا الكود الي فوق بشيك اذا في حال كان ايميل معين مش عامل تسجيل خروج
  والسشن للايميل لسا موجودة فالبتالي الشرط غير صحيح لانو الايميل موجود فهل راح يفوتو
   على صفحة ال الادمن ؟ اكيد لا لانو الكودالي تحت بشيك اذا الايميل
    الي مخزن على السشن هو فعلا ايميل الادمن ولا لا من خلال انو يتاكد 
    انو مخزن في جدول الادمن*/
 
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

// نظام تسجيل الأحداث الأمنية
function logSecurityEvent($eventType, $details) {
    $logFile = __DIR__ . '/security_logs.log';
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'];
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    
    $logMessage = "[$timestamp] [$ip] [$userAgent] [$eventType] $details\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

// تعريف متغير الفلتر مع حماية متقدمة
$filter = isset($_GET['filter']) ? preg_replace('/[^a-zA-Z_]/', '', $_GET['filter']) : 'all';

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="لوحة تحكم مسؤول النظام - نظام مراقبة الأمان">
    <meta name="robots" content="noindex, nofollow"> <!-- منع الفهرسة -->
    <link rel="preload" href="../img/background6.jpg" as="image">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>نظام مراقبة الأمان | لوحة التحكم</title>
    <style>
        :root {
            --primary-dark: #052b53;
            --primary-color: #07427d;
            --secondary-color: #336eab;
            --accent-color: #3d88d9;
            --light-color: #f8f9fa;
            --dark-color: #2c3e50;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --info-color: #3498db;
            --border-radius: 10px;
            --box-shadow: 0 5px 20px rgba(0, 0, 0, 0.12);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Tajawal', sans-serif;
        }
        
        body {
            background-image: url('../img/background6.jpg');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            color: var(--dark-color);
            line-height: 1.7;
            overflow-x: hidden;
            direction: ltr;
        }
        
        .security-overlay {
            background-color: rgba(245, 248, 250, 0.97);
            min-height: 100vh;
            padding: 10px 0;
            position: relative;
        }
        
        .security-container {
            width: 90%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
            position: relative;
            direction: rtl;
        }
        
        .security-header {
            text-align: center;
            margin-bottom: 8px;
            position: relative;
        }
        
        .security-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
            padding: 0 20px;
        }
        
        .security-title::before,
        .security-title::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 50px;
            height: 3px;
            background: linear-gradient(to right, var(--accent-color), var(--primary-color));
        }
        
        .security-title::before {
            right: 100%;
        }
        
        .security-title::after {
            left: 100%;
        }
        
        .security-description {
            color: #555;
            font-size: 1.15rem;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.8;
        }
        
        .filter-section {
            background: linear-gradient(135deg, white, #f5f9ff);
            padding: 14px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 10px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 20px;
            border: 1px solid rgba(7, 66, 125, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .filter-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary-color), var(--accent-color));
        }
        
        .filter-label {
            font-weight: 700;
            color: var(--primary-dark);
            margin-left: 10px;
            font-size: 1.1rem;
        }
        
        .filter-select {
            padding: 12px 20px;
            border: 1px solid #d1e3f8;
            border-radius: var(--border-radius);
            font-family: 'Tajawal', sans-serif;
            font-size: 1rem;
            min-width: 280px;
            background-color: white;
            transition: var(--transition);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
        
        .filter-select:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(61, 136, 217, 0.2);
        }
        
        .security-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-family: 'Tajawal', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 10px rgba(7, 66, 125, 0.2);
        }
        
        .security-btn:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(7, 66, 125, 0.3);
        }
        
        .security-btn:active {
            transform: translateY(0);
        }
        
        .logs-container {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            position: relative;
            margin-bottom: 50px;
        }
        
        .logs-header {
            background: linear-gradient(to right, var(--primary-dark), var(--primary-color));
            color: white;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logs-title {
            font-size: 1.3rem;
            font-weight: 600;
        }
        
        .logs-count {
            background-color: rgba(255, 255, 255, 0.2);
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
        }
        
        .logs-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }
        
        .logs-table th {
            background-color: #f1f7ff;
            color: var(--primary-dark);
            padding: 16px;
            text-align: center;
            font-weight: 700;
            border-bottom: 2px solid #e0e9f5;
        }
        
        .logs-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #f0f4f9;
            text-align: center;
            transition: var(--transition);
        }
        
        .logs-table tr:not(:first-child):hover td {
            background-color: #f8fbff;
        }
        
        .no-data {
            text-align: center;
            padding: 50px;
            color: var(--danger-color);
            font-size: 1.3rem;
            font-weight: 600;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }
        
        .no-data i {
            font-size: 2.5rem;
        }
        
        .operation-badge {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            white-space: nowrap;
        }
        
        .badge-success {
            background-color: rgba(39, 174, 96, 0.12);
            color: var(--success-color);
            border: 1px solid rgba(39, 174, 96, 0.3);
        }
        
        .badge-danger {
            background-color: rgba(231, 76, 60, 0.12);
            color: var(--danger-color);
            border: 1px solid rgba(231, 76, 60, 0.3);
        }
        
        .badge-warning {
            background-color: rgba(243, 156, 18, 0.12);
            color: var(--warning-color);
            border: 1px solid rgba(243, 156, 18, 0.3);
        }
        
        .badge-info {
            background-color: rgba(52, 152, 219, 0.12);
            color: var(--info-color);
            border: 1px solid rgba(52, 152, 219, 0.3);
        }
        
        .badge-primary {
            background-color: rgba(7, 66, 125, 0.12);
            color: var(--primary-color);
            border: 1px solid rgba(7, 66, 125, 0.3);
        }
        
        @media (max-width: 992px) {
            .security-title {
                font-size: 2rem;
            }
            
            .security-title::before,
            .security-title::after {
                width: 30px;
            }
            
            .filter-section {
                flex-direction: column;
                align-items: stretch;
                padding: 25px;
            }
            
            .filter-select {
                width: 100%;
            }
            
            .security-btn {
                width: 100%;
                justify-content: center;
            }
        }
        
        @media (max-width: 768px) {
            .security-container {
                width: 95%;
                padding: 15px;
            }
            
            .security-title {
                font-size: 1.8rem;
                padding: 0;
            }
            
            .security-title::before,
            .security-title::after {
                display: none;
            }
        }
    </style>
</head>
<?php
include 'header_Admin.php';
?>
<body>
    <div class="security-overlay">
        <div class="security-container">
            <div class="security-header">
                <h1 class="security-title">نظام مراقبة الأمان</h1>
                
            </div>
            
            <!-- قسم التصفية -->
            <form action="" method="get" class="filter-section">
                <label for="filter" class="filter-label">تصفية السجلات:</label>
                <select name="filter" id="filter" class="filter-select">
                    <option value="all" <?= $filter == 'all' ? 'selected' : '' ?>>جميع الأحداث</option>
                    <option value="admin_login" <?= $filter == 'admin_login' ? 'selected' : '' ?>>تسجيلات دخول المسؤولين</option>
                    <option value="Admin_Register" <?= $filter == 'Admin_Register' ? 'selected' : '' ?>>تسجيل مسؤولين جدد</option>
                    <option value="delete" <?= $filter == 'delete' ? 'selected' : '' ?>>حذف المستخدمين</option>
                    <option value="reset_admin" <?= $filter == 'reset_admin' ? 'selected' : '' ?>>تغيير كلمات المرور</option>
                    <option value="login" <?= $filter == 'login' ? 'selected' : '' ?>>تسجيلات دخول ناجحة</option>
                    <option value="Register" <?= $filter == 'Register' ? 'selected' : '' ?>>تسجيلات مستخدمين جدد</option>
                    <option value="logout" <?= $filter == 'logout' ? 'selected' : '' ?>>تسجيلات خروج</option>
                    <option value="mistake" <?= $filter == 'mistake' ? 'selected' : '' ?>>محاولات دخول فاشلة</option>
                    <option value="reset" <?= $filter == 'reset' ? 'selected' : '' ?>>تغيير كلمات المرور</option>
                </select>
                <button type="submit" class="security-btn">
                    <i class="fas fa-filter"></i> تطبيق التصفية
                </button>
            </form>
            
            <!-- جدول السجلات -->
            <div class="logs-container">
                <div class="logs-header">
                    <div class="logs-title">سجل الأحداث الأمنية</div>
                    <div class="logs-count" id="logsCount">0 حدث</div>
                </div>
                
                <div class="logs-table-container">
                    <?php
                    include '../conn.php';
                    
                    switch ($filter) {
                        case 'admin_login':
                            $sql = "SELECT * FROM logging WHERE operation = 'تسجيل دخول مستخدم رئيسي' ORDER BY id DESC";
                            break;
                        case 'Admin_Register':
                            $sql = "SELECT * FROM logging WHERE operation = 'تسجيل مستخدم رئيسي جديد' ORDER BY id DESC";
                            break;
                        case 'delete':
                            $sql = "SELECT * FROM logging WHERE operation = 'حذف المستخدم من قبل مستخدم رئيسي' ORDER BY id DESC";
                            break;
                        case 'reset_admin':
                            $sql = "SELECT * FROM logging WHERE operation = 'تغيير كلمة المرور من قبل مستخدم رئيسي' ORDER BY id DESC";
                            break;
                        case 'Register':
                            $sql = "SELECT * FROM logging WHERE operation = 'تسجيل جديد' ORDER BY id DESC";
                            break;
                        case 'login':
                            $sql = "SELECT * FROM logging WHERE operation = 'تسجيل دخول ناجح' ORDER BY id DESC";
                            break;
                        case 'logout':
                            $sql = "SELECT * FROM logging WHERE operation = 'تسجيل الخروج' ORDER BY id DESC";
                            break;
                        case 'mistake':
                            $sql = "SELECT * FROM logging WHERE operation = 'تسجيل دخول خاطئ كلمة المرور غير صحيحة' OR operation ='تسجيل دخول خاطئ الايميل غير مسجل' ORDER BY id DESC";
                            break;
                        case 'reset':
                            $sql = "SELECT * FROM logging WHERE operation = 'تغيير كلمة المرور' ORDER BY id DESC";
                            break;
                        default:
                            $sql = "SELECT * FROM logging ORDER BY id DESC";
                            break;
                    }
                    
                    $result = mysqli_query($conn, $sql);
                    $totalLogs = mysqli_num_rows($result);
                    ?>
                    
                    <?php if ($totalLogs > 0): ?>
                        <table class="logs-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>التاريخ والوقت</th>
                                    <th>العملية</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $counter = 1;
                                while ($row = mysqli_fetch_assoc($result)): 
                                    $badge_class = '';
                                    $icon = '';
                                    
                                    if (strpos($row['operation'], 'ناجح') !== false) {
                                        $badge_class = 'badge-success';
                                        $icon = '<i class="fas fa-check-circle"></i>';
                                    } elseif (strpos($row['operation'], 'خاطئ') !== false) {
                                        $badge_class = 'badge-danger';
                                        $icon = '<i class="fas fa-exclamation-triangle"></i>';
                                    } elseif (strpos($row['operation'], 'تسجيل') !== false) {
                                        $badge_class = 'badge-primary';
                                        $icon = '<i class="fas fa-user-plus"></i>';
                                    } elseif (strpos($row['operation'], 'تغيير') !== false) {
                                        $badge_class = 'badge-info';
                                        $icon = '<i class="fas fa-key"></i>';
                                    } elseif (strpos($row['operation'], 'حذف') !== false) {
                                        $badge_class = 'badge-warning';
                                        $icon = '<i class="fas fa-user-times"></i>';
                                    }
                                ?>
                                    <tr>
                                        <td><?= $counter++ ?></td>
                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                        <td><?= htmlspecialchars($row['email']) ?></td>
                                        <td><?= htmlspecialchars($row['my_datetime']) ?></td>
                                        <td>
                                            <span class="operation-badge <?= $badge_class ?>">
                                                <?= $icon ?> <?= htmlspecialchars($row['operation']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="no-data">
                            <i class="fas fa-database"></i>
                            <p>لا توجد سجلات متاحة حسب معايير التصفية المحددة</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // تحديث عدد السجلات
        document.getElementById('logsCount').textContent = '<?= $totalLogs ?> حدث';
    </script>
</body>
</html>

<?php
mysqli_close($conn);
?>