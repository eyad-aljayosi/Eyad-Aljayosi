<?php
// بدء الجلسة في بداية الصفحة
session_start();
@include 'conn.php'; // الاتصال بقاعدة البيانات

if (isset($_SESSION['usermail']) && isset($_SESSION['username'])) {
    $email = $_SESSION['usermail']; // استرجاع البريد الإلكتروني من الجلسة
    $name = $_SESSION['username']; // استرجاع اسم المستخدم من الجلسة

    // الحصول على الوقت الحالي بتنسيق معين
    $nowFormatted = (new DateTime('now', new DateTimeZone('Asia/Amman')))->format('Y-m-d h:i:s');

    // تسجيل العملية في جدول السجلات باستخدام Prepared Statement
    $insert_logging = $conn->prepare("INSERT INTO logging (name, email, my_datetime, operation) VALUES (?, ?, ?, ?)");
    $operation = 'تسجيل الخروج'; // العملية التي سيتم تسجيلها في السجل
    $insert_logging->bind_param("ssss", $name, $email, $nowFormatted, $operation); // ربط المتغيرات مع الاستعلام
    $insert_logging->execute(); // تنفيذ الاستعلام

    // تدمير الجلسة
    session_unset(); // إلغاء جميع بيانات الجلسة
    session_destroy(); // تدمير الجلسة بشكل كامل

    // التوجيه إلى الصفحة المحددة بعد تدمير الجلسة
    header('location: index.php');
    exit(); // تأكد من توقف التنفيذ بعد التوجيه
} else {
    // في حال عدم وجود الجلسة
    header('location: index.php'); // إعادة التوجيه إلى الصفحة الرئيسية
    exit();
}



/*session_start();
@include 'conn.php'; // الاتصال بقاعدة البيانات

// التحقق إذا كانت الجلسة تحتوي على بيانات المستخدم
if (isset($_SESSION['usermail'])) {
    // استخراج البيانات من الجلسة
    $email = $_SESSION['usermail'];
    $name = $_SESSION['username'];

    // تسجيل عملية تسجيل الخروج في قاعدة البيانات
    $now = new DateTime('now', new DateTimeZone('Asia/Amman')); // تعيين التوقيت بتوقيت عمان
    $nowFormatted = $now->format('Y-m-d H:i:s'); // عرض التاريخ والوقت بالشكل المطلوب
    $insert_logging = "INSERT INTO logging(name,email, my_datetime, operation) VALUES ('$name','$email','$nowFormatted', 'تسجيل الخروج')";
    mysqli_query($conn, $insert_logging); // تنفيذ الاستعلام

    // مسح الجلسة
    session_unset();
    session_destroy(); // تدمير الجلسة الحالية

    // مسح الكوكيز (إذا كانت موجودة)
    setcookie('usermail', '', time() - 3600, '/'); // مسح الكوكيز الخاصة بالبريد الإلكتروني
    setcookie('username', '', time() - 3600, '/'); // مسح الكوكيز الخاصة بالاسم

    // التوجيه إلى صفحة الاندكس بعد تسجيل الخروج
    header('Location: index.php');
    exit();
} else {
    // في حال لم تكن الجلسة تحتوي على بيانات المستخدم، يمكن توجيه المستخدم إلى الاندكس مباشرة
    header('Location: index.php');
    exit();
}*/
?>