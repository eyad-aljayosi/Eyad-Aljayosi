<?php
include 'conn.php'; // الاتصال بقاعدة البيانات
session_start(); // بدء الجلسة

// التحقق إذا كان المستخدم مسجلاً للدخول
if (!isset($_SESSION['usermail']) && !isset($_SESSION['id'])) {
    $_SESSION['login_befor'] = "قم بعمل تسجيل دخول بالبداية ";
    header("Location: login.php");
    exit();
}

include 'session_check.php'; // يضمن هذا الكود أن أي تفاعل مع الموقع سيجدد الجلسة، وإذا لم يتم التفاعل خلال 30 دقيقة، سيتم تسجيل الخروج تلقائيًا.


$user_id = $_SESSION['id']; // أخذ id المستخدم من الجلسة

// التحقق إذا تم طلب حذف نشاط معين
if (isset($_POST['delete_id']) && is_numeric($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    // حذف النشاط المحدد من قاعدة البيانات
    $query_delete = $conn->prepare("DELETE FROM activities WHERE id = ? AND user_id = ?");
    $query_delete->bind_param('ii', $delete_id, $user_id);
    $query_delete->execute();

    if ($query_delete->affected_rows > 0) {
        // إعادة توجيه لتجنب إعادة إرسال الطلب عند تحديث الصفحة
        header("Location: view_activities.php");
        exit();
    } else {
        echo "<script>alert('حدث خطأ أثناء حذف النشاط، أعد المحاولة');</script>";
    }
}


// حذف الأنشطة التي مر عليها أكثر من 12 ساعة
$delete_query = "DELETE FROM activities WHERE timestamp < NOW() - INTERVAL 12 HOUR";
mysqli_query($conn, $delete_query);

$query = "SELECT * FROM activities WHERE user_id = $user_id ORDER BY timestamp DESC";
$result = mysqli_query($conn, $query);

// استعلام لجلب الأنشطة الخاصة بالمستخدم
/*$query = "SELECT * FROM activities WHERE user_id = ? ORDER BY timestamp DESC";   الطريقة الاكثر حماية لكن لم يتم وضعها الان لغايات تسهيل تتبع البرنامج وعند الانتهاء سوف يتم وضع كامل العمليات بهذه الطريقة 
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();*/
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style-view_activities.css?v=1.4">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>عرض الأنشطة</title>

</head>

<?php
    include 'header/header_encryption.php'; 
?>

<body>

<h1>الأنشطة الخاصة بك</h1>

<!-- مفتاح الألوان -->
<div class="legend">
    <div><span class="color-box" style="background-color: #4CAF50;"></span> قيصر (تشفير/فك تشفير)</div>
    <div><span class="color-box" style="background-color: #2196F3;"></span> التباديل (تشفير/فك تشفير)</div>
    <div><span class="color-box" style="background-color: #FF9800;"></span> AES (تشفير/فك تشفير)</div>
    <div><span class="color-box" style="background-color: #5a79b0;"></span> RSA (تشفير/فك تشفير)</div>
</div>

<?php if ($result->num_rows == 0): ?>
    
    <div class="no-activities">لا توجد نشاطات حاليا <i class="far fa-folder-open" style="font-size: 24px; margin-bottom: 10px;"></i></div>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>النص المدخل</th>
                <th>الناتج</th>
                <th>نوع العملية</th>
                <th>التوقيت</th>
                <th>حذف</th>

            </tr>
        </thead>
        <tbody>
            <?php
            $counter = 1;
            while ($row = $result->fetch_assoc()) {
                $row_color = " #FF9800"; // افتراضي

                if ($row['operation_type'] == 'تشفير باستخدام قاعدة قيصر' || $row['operation_type'] == 'فك تشفير باستخدام قاعدة قيصر') {
                    $row_color = " #4CAF50"; // أخضر لقيصر
                } elseif ($row['operation_type'] == 'تشفير باستخدام قاعدة التباديل' || $row['operation_type'] == 'فك تشفير باستخدام قاعدة التباديل') {
                    $row_color = " #2196F3"; // أزرق للتباديل
                }
                elseif ($row['operation_type'] == 'تشفير باستخدام RSA' || $row['operation_type'] == 'فك تشفير باستخدام RSA') {
                    $row_color = " #5a79b0;"; // 
                }

                echo "<tr style='background-color: $row_color; color: white;'>";
                echo "<td>" . $counter++ . "</td>";

                echo "<td>";
                $text = htmlspecialchars($row['message']);
                if (mb_strlen($text, 'UTF-8') > 13) {
                    $shortText = htmlspecialchars(mb_substr($text, 0, 13, 'UTF-8')) . "";
                    echo "<div class='text-container has-more'>
                            <span class='short-text'>$shortText</span>
                            <span class='full-text'>$text</span>
                            <button class='toggle-btn' onclick='toggleText(this)'>عرض المزيد</button>
                          </div>";
                } else {
                    echo $text; // عرض النص كما هو إذا كان قصيرًا
                }
                echo "</td>";
                
                echo "<td>";
                $encryptedText = htmlspecialchars($row['encrypted_message']);
                if (mb_strlen($encryptedText, 'UTF-8') > 13) {
                    $shortEncryptedText = htmlspecialchars(mb_substr($encryptedText, 0, 13, 'UTF-8')) . "";
                    echo "<div class='text-container has-more'>
                            <span class='short-text'>$shortEncryptedText</span>
                            <span class='full-text'>$encryptedText</span>
                            <button class='toggle-btn' onclick='toggleText(this)'>عرض المزيد</button>
                          </div>";
                } else {
                    echo $encryptedText; // عرض النص كما هو إذا كان قصيرًا
                }
                echo "</td>";
                


                /*echo "<td>" . htmlspecialchars($row['message']) . "</td>";        
                echo "<td>" . htmlspecialchars($row['encrypted_message']) . "</td>";*/

                echo "<td>" . htmlspecialchars($row['operation_type']) . "</td>";

                echo "<td class='timestamp'><i class='far fa-clock'></i> " . htmlspecialchars(date('Y-m-d H:i', strtotime($row['timestamp']))) . "</td>";
                // زر الحذف
                echo "<td>
                        <form action='view_activities.php' method='POST'>
                            <input type='hidden' name='delete_id' value='" . $row['id'] . "'>
                            <button type='submit' class='btn-delete'><i class='fas fa-trash'></i> حذف</button>
                        </form>
                      </td>";
    
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
<?php endif; ?>

</body>
</html>
<script>


    function toggleText(button) {
        var container = button.parentElement;
        if (container.classList.contains('expanded')) {
            container.classList.remove('expanded');
            button.textContent = 'عرض المزيد';
        } else {
            container.classList.add('expanded');
            button.textContent = 'إخفاء';
        }
    }


</script>