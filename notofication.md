          $_SESSION['otp_verified_message_to_rest'] = "تم التحقق من الكود بنجاح "
           header('location: passwordreset.php');
                  موجود في صفحة التحقق 
              - - - - - - - - 
          if (isset($_SESSION['otp_verified_message_to_rest'])) //
          { 
             echo '<div class="alert">' . $_SESSION['otp_verified_message_to_rest'] . '</div>';
            unset($_SESSION['otp_verified_message_to_rest']);
          }
              بطبع الاشعار في صفحة الباسوردريست اذا كان مخزن بالسشن وجاي من صفحة التحقق 


      **************************************************


         $_SESSION['otp_verified_message_to_register'] = "تم التحقق من الكود بنجاح";
          header('location: login.php');
          exit();
                  موجود في صفحة التحقق 
              - - - - - - - - 
          if (isset($_SESSION['otp_verified_message_to_register'])) {
        echo '<div class="alert">' . $_SESSION['otp_verified_message_to_register'] . '</div>';
        unset($_SESSION['otp_verified_message_to_register']);
    }
              بطبع الاشعار في صفحة اللوغان اذا كان مخزن بالسشن وجاي من صفحة التحقق 

      **************************************************


        if (!isset($_SESSION['usermail'])) {
          $_SESSION['login_befor']="الرجاء عمل تسجيل دخول";
          header("Location: login.php");
          exit();
        }

        هذا الكود موزع على كل صفحات الموقع وهو في حال عدم وجود الايميل بالسشن خزن بسشن الرسالة ورجعه على صفحة اللوغان 
     
        if (isset($_SESSION['login_befor'])) {
          echo '<div class="alert-log">' . $_SESSION['login_befor'] . '</div>';
          unset($_SESSION['login_befor']);
        }

       بطبع الاشعار في صفحة اللوغان اذا كان مخزن بالسشن وجاي من من كل الصفحات الي فيها هاض الكود 


      **************************************************

        $_SESSION['session_status']="تم إنهاء الجلسة بسبب عدم النشاط";
         header("Location: login.php"); // إعادة التوجيه إلى صفحة تسجيل الدخول
         exit();

         موجود في صفحة التحقق من السشن وبحكي في حال تعدى المستخدة مدة معينة خزن الرسالة بسشن ورجعو على صفحة تسجيل الدخول 

        if (isset($_SESSION['session_status'])) {
        echo '<div class="alert-log">' . $_SESSION['session_status'] . '</div>';
        unset($_SESSION['session_status']);
        }

        بطبع الاشعار في صفحة اللوغان اذا كان مخزن بالسشن وجاي من من صفحة التحقق من السشن 

      **************************************************

       if(mysqli_num_rows($result) != 1){
        $_SESSION['not_admin']="ليس لديك صلاحيات مسؤول ";
        header('location:login.php'); 
        }

        هاظ الكود موزع في صفحات الادمن وبحكي في حال طان الشرط صح يعني ببسطاطة انو الشخص مش حساب ادمن يعني الايميل مش موجود بجدول الادمن خزن الرسالة بالسشن ورجعو على صفحة اللوغان 

       if (isset($_SESSION['not_admin'])) {
        echo '<div class="alert-log">' . $_SESSION['not_admin'] . '</div>';
        unset($_SESSION['not_admin']);
        }

        بطبع الاشعار في صفحة اللوغان اذا كان مخزن بالسشن وجاي من من صفحات الادمن



        *********************************
الفكرة هي إنه بدل ما تستخدم نفس الأنماط العامة لكل العناصر في الصفحة (زي h1, a, form)، تقدر تخصص CSS منفصل لكل جزء من الصفحة. مثلاً، تعطي الهيدر أنماط خاصة به، وبهذا الشكل تمنع تأثير أي تغيير في الأنماط على باقي العناصر. ده بيحافظ على التنظيم داخل المشروع ويسهل تعديل الأنماط مستقبلاً بدون ما يحصل تعارض بين الأجزاء المختلفة للصفحة.

 يحافظ على التنظيم، مما يسهل تطوير المشروع لاحقًا.
✅ تجنب استخدام أسماء عناصر HTML العامة، مما يقلل من التعارضات في الأنماط (CSS Conflicts)

        *********************************

//بالنسبة للسشن هيك بعطيني لو اخلي include '../session_check.php'; بدون تعديل على المسار اللوغان فببحث عن اللوغان بعد ما تخلص الجلسة بنقس فولدر ctf فيك بعطيني ال url http://localhost/g-enc/ctf/login.php

