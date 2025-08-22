<?php
include 'conn.php'; // الاتصال بقاعدة البيانات
session_start(); // بدء الجلسة

// التحقق من تسجيل الدخول
if (!isset($_SESSION['usermail']) || !isset($_SESSION['id'])) {
    $_SESSION['login_befor'] = "قم بعمل تسجيل دخول بالبداية";
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id']; // الحصول على ID المستخدم من الجلسة

// التحقق من إرسال البيانات عبر POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $message = $_POST['message'];
    $result = $_POST['result'];
    $operation = $_POST['operation'];

    // حذف الأنشطة التي مر عليها أكثر من 12 ساعة
    $delete_query = "DELETE FROM activities WHERE timestamp < NOW() - INTERVAL 12 HOUR";
    mysqli_query($conn, $delete_query);

    // التحقق من نوع العملية: تشفير أو فك تشفير لأي نوع من الخوارزميات
    if (strpos($operation, 'تشفير باستخدام') !== false) {
        // تحقق مما إذا كانت عملية التشفير هذه قد تم تسجيلها من قبل
        $check_query = "SELECT * FROM activities WHERE user_id = ? AND message = ? AND encrypted_message = ? AND operation_type = ?";
        $stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($stmt, "ssss", $user_id, $message, $result, $operation);
        mysqli_stmt_execute($stmt);
        $check_result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($check_result) > 0) {
            echo json_encode(["error" => "هذه العملية موجودة بالفعل في السجل."]);
        } else {
            // تسجيل عملية التشفير الجديدة
            $query = "INSERT INTO activities (user_id, message, encrypted_message, operation_type) 
                      VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ssss", $user_id, $message, $result, $operation);
            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(["success" => "تم حفظ عملية التشفير بنجاح."]);
            } else {
                echo json_encode(["error" => "خطأ أثناء حفظ عملية التشفير: " . mysqli_error($conn)]);
            }
        }

    } elseif (strpos($operation, 'فك تشفير باستخدام') !== false) {
        // استخراج نوع التشفير من اسم العملية
        $encryption_type = str_replace('فك تشفير باستخدام', 'تشفير باستخدام', $operation);

        // تحقق مما إذا كان النص المشفر قد تم تسجيله مسبقًا بنفس نوع التشفير
        $check_query = "SELECT * FROM activities WHERE user_id = ? AND encrypted_message = ? AND operation_type = ?";
        $stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($stmt, "sss", $user_id, $message, $encryption_type);
        mysqli_stmt_execute($stmt);
        $check_result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($check_result) > 0) {
            // تسجيل عملية فك التشفير
            $query = "INSERT INTO activities (user_id, message, encrypted_message, operation_type) 
                      VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ssss", $user_id, $message, $result, $operation);
            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(["success" => "تم حفظ عملية فك التشفير بنجاح."]);
            } else {
                echo json_encode(["error" => "خطأ أثناء حفظ عملية فك التشفير: " . mysqli_error($conn)]);
            }
        } else {
            echo json_encode(["error" => "لا يمكن فك التشفير، لأن النص المشفر غير مسجل كعملية تشفير باستخدام نفس الطريقة."]);
        }
    } else {
        echo json_encode(["error" => "عملية غير معروفة."]);
    }
}
?>


