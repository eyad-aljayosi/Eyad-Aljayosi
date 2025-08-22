
<?php
include('conn.php');// الاتصال بفاعدة البيانات
session_start();// بدايه الجلسه

if(!isset($_SESSION['usermail'])){
    $_SESSION['login_befor']="قم بعمل تسجيل دخول بالبداية ";
    header('location:login.php');//
    exit();
 } 
    /*طبعا الكود الي فوق بشيك اذا في حال كان ايميل معين مش عامل تسجيل خروج
  والسشن للايميل لسا موجودة فالبتالي الشرط غير صحيح لانو الايميل موجود فهل راح يفوتو
   على صفحة ال الادمن ؟ اكيد لا لانو الكودالي تحت بشيك اذا الايميل
    الي مخزن على السشن هو فعلا ايميل الادمن ولا لا من خلال انو يتاكد 
    انو مخزن في جدول الادمن*/
 
 $email = $_SESSION['usermail'];//تعريف متغيير يحمل الايميل
 $name = $_SESSION['username'];
 $sql = "SELECT * FROM admin_form WHERE email = '$email'";//تعريف متغيير يحمل الاتصال بفاعدة البيانات 
 $result = mysqli_query($conn, $sql);
 
 if(mysqli_num_rows($result) != 1){
   $_SESSION['not_admin']="ليس لديك صلاحيات مسؤول ";
     header('location:login.php'); //حوله الى الصفحة  admin_form   الشرط التالي اذا لم يكن الايميل من الجدول
 }

 include 'session_check.php'; // يضمن هذا الكود أن أي تفاعل مع الموقع سيجدد الجلسة، وإذا لم يتم التفاعل خلال 30 دقيقة، سيتم تسجيل الخروج تلقائيًا.
 
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style-add_admin_ok.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <title>تم تسجيل المستخدم الرئيسي بنجاح</title>
    <style>

    </style>
</head>
<body>
    <div class="form-container">
        <form action="" method="post">
            <h3 class="title">تم تسجيل المستخدم الرئيسي بنجاح</h3>
            <p>التوجه الى <a href="Admin/admin_page.php">صفحه المستخدم الرئيسي</a></p>
        </form>
    </div>
</body>
</html>
