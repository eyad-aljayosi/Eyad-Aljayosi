<?php
// الاتصال بقاعدة البيانات
$conn = mysqli_connect('localhost', 'eyad', 'Eyad@2003', 'database');

// تحقق من الاتصال
if (!$conn) {
    die("فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error());
}
?>
