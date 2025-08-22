<?php
@include 'conn.php';// الاتصال بقاعدة البيانات 

session_start();// بدايه الجلسة 

if(isset($_POST['submit'])){//اذا تم الضغط على ارسال 
   $email = mysqli_real_escape_string($conn,$_REQUEST['usermail']);//لتعقيم المدخلات mysqli_real_escape_string يتم اخذ الايميل وحفظه في المتغيير مع استخدام 
   
   $check_email = mysqli_query($conn, "SELECT name, email FROM user_form WHERE email = '$email'"); // يتم التأكد اذا كان الايميل موجود في قاعدة البيانات
   $res = mysqli_num_rows($check_email);
   
   if ($res > 0) { // اذا نعم
       // استخراج البيانات من الاستعلام
       $row = mysqli_fetch_assoc($check_email);
   
       // تخزين الايميل في الجلسة
       $_SESSION['usermail'] = $email;
   
       // تخزين الاسم في الجلسة
       $_SESSION['name'] = $row['name']; 
       $name = $_SESSION['name'];
       
       include 'send_otp.php';  // otp الاتصال بالصفحة الخاصه بارسال رمز           

        
     if($mail->send()){// اذا تم ارسال الايميل 
        $insert_query = mysqli_query($conn,"insert into tbl_otp_check set otp='$otp', is_expired='0', email='$email'"); // مع اضافه صفر لحالته بحيث صفر لم يتم استخدمه و 1 تم استخدامه  tbl_otp_check اضق الرقم الؤقت مع الايميل في الجدول 
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $name;
        $_SESSION['forgot_forwarding'] = true;//وادخله المستخدم بشكل صحيح  OTP يتم تخزين هذه القيمه كشرط انه تم ارسال رمز  
        header('location: verification.php');// اذا تم يتم التوجه الى الصفحة التالية
        exit();
        
     }else{
        $error[] = "لم يتم ارسال الايميل ";//يتم عرض الخطا التالي اذا حدثت مشكله اثناء الارسال 
     }
     
  }else{
     $error[] = 'الايميل غير موجود';//user يتم عرض الخطا التالي اذا كانالايميل الخاص بالمستخدم غير مسجل في الجدول 
  }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style-Forgot.css?v=1.3">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">

    <title>استرجاع كلمة المرور</title>
</head>
<body>
    <div class="form-container">
        <form action="" method="post">
            <h3 class="title">استرجاع كلمة المرور</h3>
            <?php
                if(isset($error)){
                    foreach($error as $error){
                        echo '<span class="error-msg">'.$error.'</span>';
                    }
                }
            ?>
            <input type="email" name="usermail" placeholder="ادخل الايميل الخاص بك" class="box" required>
            <input type="submit" value="استرجاع كلمة المرور" class="form-btn" name="submit">

            <p>تذكرت كلمة المرور؟ <a href="login.php">سجل دخولك</a></p>
        </form>
    </div>
</body>
</html>