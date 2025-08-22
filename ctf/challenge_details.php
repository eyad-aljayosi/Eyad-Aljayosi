<?php
session_start();
include '../conn.php';

// التحقق من وجود الـ usermail أو id في الجلسة
if (!isset($_SESSION['usermail']) || !isset($_SESSION['id'])) {
    $_SESSION['login_befor'] = "قم بعمل تسجيل دخول بالبداية";
    header("Location: ../login.php");
    exit();
}

include '../session_check.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("التحدي غير موجود.");
}

$challenge_id = intval(value: $_GET['id']);


$query = "SELECT * FROM challenges WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $challenge_id);
$stmt->execute();
$result = $stmt->get_result();
$challenge = $result->fetch_assoc();


if (!$challenge) {
    die("التحدي غير موجود.");
}

// التحقق مما إذا كان المستخدم قد حل التحدي مسبقاً
$user_id = $_SESSION['id'];
$solved_before = false;

$check_solved = $conn->prepare("SELECT id FROM user_scores WHERE user_id = ? AND challenge_id = ?");
$check_solved->bind_param("ii", $user_id, $challenge_id);
$check_solved->execute();
$check_solved->store_result();

if ($check_solved->num_rows > 0) {
    $solved_before = true;
}

$message = "";
$notification = ""; // متغير جديد للرسالة المنبثقة


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submitted_flag = mysqli_real_escape_string($conn, $_POST['flag']);

    if ($submitted_flag == $challenge['flag']) {
        if (!$solved_before) {
            $insert_stmt = $conn->prepare("INSERT INTO user_scores (user_id, challenge_id, points) VALUES (?, ?, ?)");
            $insert_stmt->bind_param("iii", $user_id, $challenge_id, $challenge['points']);
            $insert_stmt->execute();
            $_SESSION['success_message'] = "<div class='message-card success'><i class='fas fa-trophy'></i><span>إجابة صحيحة! لقد حصلت على " . $challenge['points'] . " نقطة</span></div>";
            $notification = "<div class='notification success'><i class='fas fa-trophy'></i><span>إجابة صحيحة! لقد حصلت على " . $challenge['points'] . " نقطة</span></div>";
            $solved_before = true;
        } else {
            $_SESSION['warning_message'] = "<div class='message-card warning'><i class='fas fa-info-circle'></i><span>الإجابة صحيحة، ولقد بحل هذا التحدي مسبقًا.</span></div>";
            /*$_SESSION['warning_message'] = "<div class='message-card warning'><i class='fas fa-info-circle'></i><span>لقد قمت بحل هذا التحدي مسبقًا</span></div>";*/
            /*$notification = "<div class='notification warning'><i class='fas fa-info-circle'></i><span>لقد قمت بحل هذا التحدي مسبقًا</span></div>";*/
        }
    } else {
        $_SESSION['error_message'] = "<div class='message-card error'><i class='fas fa-exclamation-triangle'></i><span>الإجابة خاطئة! حاول مرة أخرى</span></div>";
        $notification = "<div class='notification error'><i class='fas fa-exclamation-triangle'></i><span>الإجابة خاطئة! حاول مرة أخرى</span></div>";
    }
    
    // إعادة التوجيه لمنع إعادة إرسال النموذج
    header("Location: ../ctf/".$challenge_id);
    exit();
}

// عرض الرسائل من الجلسة إذا وجدت
if (isset($_SESSION['success_message'])) {
    $message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
} elseif (isset($_SESSION['warning_message'])) {
    $message = $_SESSION['warning_message'];
    unset($_SESSION['warning_message']);
} elseif (isset($_SESSION['error_message'])) {
    $message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
} elseif ($solved_before && empty($message)) {
    $notification = "<div class='notification info'><i class='fas fa-info-circle'></i><span>لقد قمت بحل هذا التحدي مسبقاً</span></div>";
}

// استعلام لمعرفة عدد الأشخاص الذين حلوا التحدي
$solved_count_query = $conn->prepare("SELECT COUNT(*) as count FROM user_scores WHERE challenge_id = ?");
$solved_count_query->bind_param("i", $challenge_id);
$solved_count_query->execute();
$solved_count_result = $solved_count_query->get_result();
$solved_count = $solved_count_result->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($challenge['title']); ?> | تحدي CTF</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&family=Cairo:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="css/design-scrollbar.css">
    <style>
        :root {
            --primary-color: #3498db;
            --primary-dark: #2980b9;
            --success-color: #2ecc71;
            --error-color: #e74c3c;
            --warning-color: #f39c12;
            --info-color: #3498db;
            --text-color: #333;
            --light-bg: #f7f9fb;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Tajawal', 'Cairo', sans-serif;
        }

        body {
            background-color: #f7f9fb;
            background-image: url('../img/background6.jpg');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
        }

        .container {
            width: 90%;
            max-width: 1000px;
            margin: 30px auto;
            animation: fadeIn 0.5s ease;
        }

        .challenge-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 20px 30px 30px 30px;
            box-shadow: var(--card-shadow);
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease;
            backdrop-filter: blur(5px);
            border: #07427d 6px outset ;
            width: 860px;
            margin: 0px auto;
        }

        .challenge-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            direction: ltr;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .challenge-title {
            font-size: 28px;
            color: var(--primary-dark);
            font-weight: 700;
        }

        .points-badge {
            background: linear-gradient(45deg, #f39c12, #e67e22);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 18px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
        }

        .points-badge i {
            margin-right: 5px;
        }

        .challenge-description {
            font-size: 19px;
            color: #444;
            line-height: 1.4;
            margin: 25px 0;
            text-align: right;
            padding: 0  40px;;
        }

        .flag-form {
            margin-top: 30px;
        }

        .input-group {
            display: flex;
            gap: 10px;
            max-width: 800px;
            margin: 0 auto;
            margin-bottom: 30px;
        }

        .flag-input {
            flex: 1;
            padding: 15px 25px;
            border: 2px solid #ddd;
            border-radius: 50px;
            font-size: 16px;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            text-align: center;
        }

        .flag-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.3);
        }

        .submit-btn {
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            padding: 0 45px;
            font-size: 18px;
            border-radius: 50px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.4);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            white-space: nowrap;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.6);
        }

        .message-container {
            margin-top: 25px;
            display: flex;
            justify-content: center;
        }

        .message-card {
            padding: 8px 8px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            max-width: 80%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            animation: slideIn 0.5s ease;
        }

        .message-card i {
            font-size: 22px;
            margin-left: 10px;
        }

        .message-card span {
            font-weight: 600;
        }

        .success {
            background-color: rgba(46, 204, 113, 0.2);
            border-left: 4px solid var(--success-color);
            color: var(--success-color);
        }

        .error {
            background-color: rgba(231, 76, 60, 0.2);
            border-left: 4px solid var(--error-color);
            color: var(--error-color);
        }

        .warning {
            background-color: rgba(243, 156, 18, 0.2);
            border-left: 4px solid var(--warning-color);
            color: var(--warning-color);
        }

        .info {
            background-color: rgba(52, 152, 219, 0.2);
            border-left: 4px solid var(--info-color);
            color: var(--info-color);
        }

        /* أنماط الرسائل المنبثقة */
        .notification-container {
            position: fixed;
            top: 20px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            z-index: 1000;
            pointer-events: none;
        }

        .notification {
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            animation: slideDown 0.5s ease-out, fadeOut 0.5s ease-in 3.6s;
            animation-fill-mode: forwards;
            transform: translateY(-100px);
            opacity: 0;
            background: white;
            font-size: 18px;
            font-weight: bold;
            max-width: 90%;
        }

        .notification i {
            margin-left: 10px;
            font-size: 20px;
        }

        /* أنماط عدد الحلول */
        .solved-count {
            position: absolute;
            bottom: 20px;
            right: 35px;
            background: rgba(7, 66, 125, 0.1);
            padding: 4px 7px;
            border-radius: 20px;
            font-size: 11px;
            color: #07427d;
            font-weight: 500;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(7, 66, 125, 0.2);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }


        .solved-count i {
            margin-left: 8px;
            color: #07427d;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-100px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-50px);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                width: 95%;
                margin: 20px auto;
            }
            
            .challenge-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .points-badge {
                margin-top: 10px;
            }
            
            .challenge-title {
                font-size: 24px;
            }
            
            .challenge-description {
                font-size: 16px;
            }
            
            .input-group {
                flex-direction: column;
                gap: 15px;
            }
            
            .submit-btn {
                padding: 12px;
                justify-content: center;
            }
            
            .notification {
                padding: 12px 20px;
                max-width: 95%;
            }

            .solved-count {
                position: static;
                margin-top: 20px;
                align-self: flex-start;
            }
        }
    </style>
</head>
<body>
    <?php include 'header-ctf.php'; ?>

    <?php if (!empty($notification)): ?>
        <div class="notification-container">
            <?php echo $notification; ?>
        </div>
    <?php endif; ?>

    <div class="container">
        <div class="challenge-card">
            <div class="challenge-header">
                <h1 class="challenge-title"><?php echo htmlspecialchars($challenge['title']); ?></h1>
                <div class="points-badge">
                    <i class="fas fa-star"></i> 
                    <span>
                        <?php echo htmlspecialchars($challenge['points']); ?> نقاط </span> 
                </div>
            </div>
            
            <div class="challenge-description" dir="rtl">
                <?php echo nl2br(html_entity_decode($challenge['description'])); ?>
            </div>
            
            <form method="POST" class="flag-form">
                <div class="input-group">
                    <input type="text" name="flag" class="flag-input" placeholder="أدخل العلم هنا..." required autocomplete="off" spellcheck="false">
                    <button type="submit" class="submit-btn">
                        <span>ارسال</span>
                    </button>
                </div>
            </form>
            
            <div class="solved-count">
                <i class="fas fa-users"></i>
                <span><?php echo $solved_count; ?> أشخاص قاموا بحل هذا التحدي</span>
            </div>
            
            <?php if (!empty($message)): ?>
                <div class="message-container">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Add animation to input on focus
        const flagInput = document.querySelector('.flag-input');
        flagInput.addEventListener('focus', () => {
            flagInput.style.transform = 'scale(1.02)';
        });
        
        flagInput.addEventListener('blur', () => {
            flagInput.style.transform = 'scale(1)';
        });
    </script>

    <script>
        // إخفاء رسالة $message بعد 4 ثواني
        setTimeout(() => {
            const messageCard = document.querySelector('.message-container');
            if (messageCard) {
                messageCard.style.transition = 'opacity 0.5s ease';
                messageCard.style.opacity = '0';
                setTimeout(() => {
                    messageCard.remove();
                }, 500); // بعد انتهاء الـ fade out
            }
        }, 4000);

  
    </script>
</body>
</html>