
<?php
include '../conn.php';

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

include '../session_check.php'; // للتأكد من الجلسة


// التعامل مع تعديل التحدي
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_challenge'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $points = $_POST['points'];
    $flag = $_POST['flag'];
    $category = $_POST['category'];
    $difficulty = $_POST['difficulty'];

    // تحديث البيانات
    $update_query = "UPDATE challenges SET title=?, description=?, points=?, flag=?, category=?, difficulty=? WHERE id=?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssisssi", $title, $description, $points, $flag, $category, $difficulty, $id);

    if ($stmt->execute()) {
        echo "<script>alert('تم حفظ التعديلات بنجاح'); window.location.href='manage_challenges.php';</script>";
    } else {
        echo "<script>alert('حدث خطأ أثناء حفظ التعديلات');</script>";
    }
}

// التعامل مع حذف التحدي
if (isset($_POST['delete_challenge'])) {
    $challenge_id = $_POST['challenge_id'];

    // حذف السكور المرتبط بالتحدي أولاً
    $delete_scores_query = "DELETE FROM user_scores WHERE challenge_id = ?";
    $stmt = $conn->prepare($delete_scores_query);
    $stmt->bind_param("i", $challenge_id);
    $stmt->execute();

    // حذف التحدي
    $delete_challenge_query = "DELETE FROM challenges WHERE id = ?";
    $stmt = $conn->prepare($delete_challenge_query);
    $stmt->bind_param("i", $challenge_id);

    if ($stmt->execute()) {
        echo "<script>alert('تم حذف التحدي بنجاح'); window.location.href='manage_challenges.php';</script>";
    } else {
        echo "<script>alert('حدث خطأ أثناء الحذف');</script>";
    }
}

// استعلام جلب جميع التحديات
$query = "SELECT * FROM challenges";
$result = $conn->query($query);

// التحقق من وجود التحدي الذي تم تعديله
$challenge_to_edit = null;
if (isset($_GET['edit'])) {
    $id_to_edit = $_GET['edit'];
    $query_edit = "SELECT * FROM challenges WHERE id = ?";
    $stmt = $conn->prepare($query_edit);
    $stmt->bind_param("i", $id_to_edit);
    $stmt->execute();
    $result_edit = $stmt->get_result();
    if ($result_edit->num_rows > 0) {
        $challenge_to_edit = $result_edit->fetch_assoc();
    }
}

$query = "SELECT c.*, COUNT(us.id) as solvers_count 
          FROM challenges c
          LEFT JOIN user_scores us ON c.id = us.challenge_id
          GROUP BY c.id";
$result = $conn->query($query);


?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إدارة التحديات</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
/* تصميم الجسم العام */
body {
    font-family: 'Tajawal', sans-serif;
    background-color: #f4f7fc;
    margin: 0;
    padding: 0;
    overflow: auto; 
    text-align: center;
    background: #f9f9f9;
    background-image: url('../img/background6.jpg');
    background-size: cover;
}

.container {
    width: 85%;
    margin: 25px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    border-radius: 12px;
}

.challenge-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
    justify-content: center;
}

.challenge-card {
    background: #fff;
    padding: 2px 30px 20px 30px;
    border-radius: 15px;
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
    /*height: 337px;*/ 
    text-align: center; 

    display: flex; 
    flex-direction: column; 
    justify-content: space-between;
}



.card-buttons {
    margin-top: auto;
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: -10px;

}

.challenge-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
}

.challenge-card h3 {
    color: #0a57a0;
    font-size: 25px;
    margin-bottom: 15px;
}

.btn-edit, .btn-delete {
    padding: 10px 25px;
    background-color: #0a57a0;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px;
    text-decoration: none;
    font-weight: 500;
    font-size: 14px;
}

.btn-edit:hover {
    background-color: #1F6B8B;
}
 .btn-delete:hover{
    background-color:rgb(232, 33, 11);
 }

.btn-delete {
    background-color: #dc3545;
}



/* Form Container */
.edit-challenge-form {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 999;
    padding: 20px;
    margin: 0;
    padding: 0;
}

/* Form Styling */
.edit-challenge-form form {
    background: #fff;
    padding:  10px 70px 50px 50px;
    border-radius: 12px;
    width: 55%;
    max-width: 800px;
    box-shadow: 0px 10px 40px rgba(0, 0, 0, 0.3);
    position: relative;
    overflow-y: auto;
    box-sizing: border-box;
    max-height: 96%;
    animation: fadeIn 0.7s ease-in-out;
}

.challenge-header-container {
    position: relative;
    padding: 0px 0 15px;
    margin: 0 auto 10px;
    max-width: 800px;
    overflow: hidden;
}

.header-content-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    padding: 0 50px;
}

.premium-title {
    font-size: 2.2rem;
    font-weight: 700;
    color: rgb(0 59 128 / 80%);
    margin: 0;
    padding: 15px 0;
    position: relative;
    text-align: center;
    text-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.premium-close-btn {
    position: absolute;
    right: 5px;
    top: 50%;
    transform: translateY(-50%);
    width: 42px;
    height: 42px;
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    z-index: 10;
    border: none;
    outline: none;
}

.close-icon {
    width: 20px;
    height: 20px;
    fill: white;
    transition: transform 0.3s ease;
    z-index: 10;
}

.premium-close-btn:hover {
    transform: translateY(-50%) rotate(180deg) scale(1.1);
    background: linear-gradient(135deg, #ff6b6b 0%, #e74c3c 100%);
}

.title-underline-effect {
    position: absolute;
    bottom: 15px;
    left: 50%;
    transform: translateX(-50%);
    width: 60%;
    height: 3px;
    background: linear-gradient(90deg, 
        transparent 0%, 
        #052d5f 30%, 
        #052d5f 70%, 
        transparent 100%);
    border-radius: 3px;
}

.title-underline-effect::before,
.title-underline-effect::after {
    content: '';
    position: absolute;
    bottom: -5px;
    width: 12px;
    height: 12px;
    background:#052d5f;
    border-radius: 50%;
    animation: pulse 2s infinite ease-in-out;
}

.title-underline-effect::before {
    left: 25%;
    transform: translateX(-50%);
}

.title-underline-effect::after {
    right: 25%;
    transform: translateX(50%);
}

@keyframes pulse {
    0%, 100% {
        transform: translateX(-50%) scale(1);
        opacity: 1;
    }
    50% {
        transform: translateX(-50%) scale(1.3);
        opacity: 0.7;
    }
}



/* Input Fields */
.edit-challenge-form input,
.edit-challenge-form select,
.edit-challenge-form textarea {
    width: 100%;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 16px;
    transition: border-color 0.3s ease;
    background-color: #f7f7f7;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.01);
}

/* Focus Effects */
.edit-challenge-form input:focus,
.edit-challenge-form select:focus,
.edit-challenge-form textarea:focus {
    border-color: #3498db;
    background-color:rgb(243, 249, 251);
    box-shadow: 0px 0px 10px rgba(109, 181, 228, 0.1);
}

/* Label */
.edit-challenge-form label {
    font-size: 18px;
    font-weight: 600;
    color: #555;
    direction: rtl;
    margin-bottom: 8px;
    display: block;
    text-align: center;
}

/* Textarea */
.edit-challenge-form textarea {
    height: 160px;
    resize: vertical;
    font-size: 17px;
    font-family: 'Almarai', sans-serif;
    text-align: right;
}

/* Submit Button */
.edit-challenge-form button {
    width: 100%;
    padding: 16px;
    background-color: #2980b9;
    color: white;
    font-size: 18px;
    border: none;
    cursor: pointer;
    border-radius: 8px;
    margin-top: 20px;
    transition: background-color 0.3s ease;
}

.edit-challenge-form button:hover {
    background-color: #1F6B8B;
}


/* الطبقة الضبابية التي تغطي الصفحة */
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* semi-transparent background */
    justify-content: center;
    align-items: center;
    z-index: 9999;
    backdrop-filter: blur(5px); /* apply blur effect */
}


.main-title {
            font-size: 36px;
            font-weight: 700;
            color: #ffffff;
            background: linear-gradient(135deg, #07427d, #336eab);
            padding: 13px 20px;
            border-radius: 30px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
            max-width: 310px;
            margin: 15px auto;
            display: block;
            text-align: center;
            margin-top: 0px;
            margin-bottom: 42px;
            
        }

.title-edit{
    color: #052f62;
    font-size: 32px;
    margin-top: 2px;
    font-weight: bold;
}



.easy {
    color: #28a745;
    font-size: 20px;
    margin-bottom: 20px;
    font-weight: 700;
}

.medium {
    color: #ffc107;
    font-size: 20px;
    margin-bottom: 20px;
    font-weight: 700;
}

.hard {
    color: #dc3545;
    font-size: 20px;
    margin-bottom: 20px;
    font-weight: 700;
}

.challenge-card-para {
    padding: 3px 15px;
    direction: ltr;
    background-color: #f8f9fa4f;
    border-radius: 8px;
    margin: 10px 0;
    border: 1px solid #e0e3e7;
    font-family: 'Tajawal', sans-serif; /* استخدام الخط العربي المناسب */
    color: #495057;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
}

.challenge-card-para p {
    margin: 0;
    padding: 8px 0;
    display: flex;
    justify-content: flex-start; 
    align-items: center;
    border-bottom: 1px solid #eceff1;
}

.challenge-card-para p:last-child {
    border-bottom: none;
    margin-bottom: 8px;
    padding-bottom: 0;
}

.challenge-card-para .label {
    color: #374759;
    font-weight: 700;
    margin-left: 10px;
    flex: 1; /* يسمح للنص بالتمدد ليأخذ المساحة المتاحة */
    text-align: right; /* محاذاة التصنيف لليمين */
}

.challenge-card-para .value {
    font-weight: 450;
    font-size: 15px;
    color: #212529;
    text-align: center; /* محاذاة النص لمنتصف القيمة */
    flex: 2; /* يزيد من المسافة بين التصنيف والقيمة */
    direction: ltr; /* النص في القيم يكون من اليسار لليمين */
    overflow-wrap: break-word; /* كسر الكلمات الطويلة */
    word-break: break-word;
    max-width: 80%; /* تحديد عرض أقصى للقيم */
}

.challenge-card-para .flag-value {
    font-family: 'Courier New', Courier, monospace;
    background-color: #f1f3f56e;
    padding: 4px 8px;
    font-weight: 540;
    border-radius: 4px;
    font-size: 14px;
    overflow-x: auto; /* إضافة شريط تمرير أفقي إذا لزم الأمر */
    white-space: pre-wrap; /* الحفاظ على المسافات البيضاء مع السماح بالتفاف النص */
}

/* تخصيص مؤشر التمرير */
::-webkit-scrollbar {
    width: 12px; /* عرض المؤشر */
}

/* تخصيص شكل المؤشر */
::-webkit-scrollbar-thumb {
    background-color:rgb(101, 140, 183); /* اللون الأزرق */
    border-radius: 10px; /* تدوير الأطراف */
    border: 3px solid #fff; /* تحديد الحدود حول المؤشر */
    transition: background-color 0.3s ease; /* تأثير سلس عند التمرير */
}

/* تخصيص الخلفية التي يظهر عليها المؤشر */
::-webkit-scrollbar-track {
    background: #f1f1f1; /* لون الخلفية للمؤشر */
    border-radius: 10px;
}

/* تأثير عند المرور فوق المؤشر */
::-webkit-scrollbar-thumb:hover {
    background-color: #0056b3; /* اللون الأزرق الداكن عند التمرير */
}

/* إضافة هذه الأنماط لمنع التمرير في الصفحة الخلفية */
body.modal-open {
    overflow: hidden;
    position: fixed;
    width: 100%;
}

    </style>
</head>
<?php
include 'header_Admin.php'; // استدعاء الهيدر
?>
<body>
<div class="container">
    <h2 class="main-title">إدارة التحديات</h2>

    <!-- عرض التحديات -->
    <div class="challenge-grid" dir="rtl">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <!--<div class="challenge-card" style="border-top: 5px solid <?php /*echo ($row['difficulty'] == 'Hard' ? '#dc3545' : ($row['difficulty'] == 'Medium' ? '#ffc107' : '#28a745'));*/ ?>;">-->
            <div class="challenge-card" style="border-top: 5px solid <?php echo ($row['difficulty']); ?>; border-top: 5px solid #0a57a0e8;">

                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                
                <div  class="challenge-card-para">
    <p>
        <span class="value"><?php echo htmlspecialchars($row['category']); ?></span>
        <span class="label">التصنيف</span>
    </p>
    <p>
        <span class="value flag-value"><?php echo htmlspecialchars($row['flag']); ?></span>
        <span class="label">الفلاغ</span>
    </p>
    <p>
        <span class="value"><?php echo (int)$row['solvers_count']; ?></span>
        <span class="label"> الحلول</span>
    </p>

</div>


<div class="difficulty-box">
    <div class="difficulty <?php echo strtolower($row['difficulty']); ?>">
        <?php echo htmlspecialchars($row['difficulty']); ?>
    </div>
</div>              

                <div class="card-buttons">
    <a href="?edit=<?php echo $row['id']; ?>" class="btn-edit"><i class="fas fa-edit"></i> تعديل</a>
    <form action="" method="post" style="display:inline;">
        <input type="hidden" name="challenge_id" value="<?php echo $row['id']; ?>">
        <button type="button" class="btn-delete" onclick="showDeleteModal(<?php echo $row['id']; ?>)">
            <i class="fas fa-trash-alt"></i> حذف
        </button>
    </form>
</div>
            </div>
        <?php } ?>
    </div>

    <!-- نموذج تعديل التحدي -->
    <?php if ($challenge_to_edit) { ?>
        <div class="overlay">
        <div class="edit-challenge-form">
    <form method="POST">

    <div class="challenge-header-container">
    <div class="header-content-wrapper">
        <h3 class="premium-title">تعديل التحدي</h3>
        <a href="manage_challenges.php" class="premium-close-btn">
            <svg class="close-icon" viewBox="0 0 24 24" width="24" height="24">
                <path fill="currentColor" d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
            </svg>
        </a>
    </div>
    <div class="title-underline-effect"></div>
</div>

        

        <!-- Hidden Input for Challenge ID -->
        <div class="form-group">
            <input type="hidden" name="id" value="<?php echo $challenge_to_edit['id']; ?>">
        </div>

        <!-- Challenge Title -->
        <div class="form-group">
            <label for="title">عنوان التحدي:</label>
            <input type="text" name="title" value="<?php echo $challenge_to_edit['title']; ?>" required>
        </div>

        <!-- Challenge Description -->
        <div class="form-group">
            <label for="description">وصف التحدي:</label>
            <textarea name="description" required><?php echo $challenge_to_edit['description']; ?></textarea>
        </div>

        <!-- Challenge Points -->
        <div class="form-group">
            <label for="points">النقاط:</label>
            <input type="number" name="points" value="<?php echo $challenge_to_edit['points']; ?>" required>
        </div>

        <!-- Challenge Flag -->
        <div class="form-group">
            <label for="flag">العلم:</label>
            <input type="text" name="flag" value="<?php echo $challenge_to_edit['flag']; ?>" required>
        </div>

        <!-- Challenge Category -->
        <div class="form-group">
            <label for="category">التصنيف:</label>
            <input type="text" name="category" value="<?php echo $challenge_to_edit['category']; ?>" required>
        </div>

        <!-- Challenge Difficulty -->
        <div class="form-group">
            <label for="difficulty">مستوى الصعوبة:</label>
            <select name="difficulty" required>
                <option value="Easy" <?php echo $challenge_to_edit['difficulty'] == 'Easy' ? 'selected' : ''; ?>>سهل</option>
                <option value="Medium" <?php echo $challenge_to_edit['difficulty'] == 'Medium' ? 'selected' : ''; ?>>متوسط</option>
                <option value="Hard" <?php echo $challenge_to_edit['difficulty'] == 'Hard' ? 'selected' : ''; ?>>صعب</option>
            </select>
        </div>

        <button type="submit" name="update_challenge" class="btn-save">حفظ التعديلات</button>
    </form>
</div>

    <?php } ?>
</div>

<!-- نافذة تأكيد الحذف -->
<div id="deleteModal" style="display:none;">
    <div class="backdrop"></div> <!-- خلفية مغبشة -->
    <div class="modal-content">
        <h3>هل أنت متأكد من حذف التحدي؟</h3>
        <p style="color: red;">في حال قمت بحذف هذا التحدي، سيتم حذف النقاط المرتبطة به من سجلات المستخدمين الذين قاموا بحله.</p>
        <button class="confirm-btn" onclick="deleteChallenge()">تأكيد الحذف</button>
        <button class="cancel-btn" onclick="closeDeleteModal()">إلغاء</button>
    </div>
</div>

<script>


    /* ******************** */

    function showDeleteModal(challengeId) {
        // تحريك الصفحة إلى أعلى لعرض الفورم في البداية
        window.scrollTo(0, 0); // تحريك التمرير إلى أعلى الصفحة

        // عرض النافذة
        document.getElementById('deleteModal').style.display = 'block';

        // تخزين id التحدي الذي سيتم حذفه
        window.challengeIdToDelete = challengeId;
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }

    function deleteChallenge() {
        const form = document.createElement('form');
        form.method = 'post';
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'delete_challenge';
        input.value = '1'; // سيتم التحقق من ذلك في الكود الباكيند

        const challengeInput = document.createElement('input');
        challengeInput.type = 'hidden';
        challengeInput.name = 'challenge_id';
        challengeInput.value = window.challengeIdToDelete;

        form.appendChild(input);
        form.appendChild(challengeInput);

        document.body.appendChild(form);
        form.submit();
    }


</script>

<style>
/* خلفية مغبشة */
.backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7); /* لون داكن مع شفافية */
    backdrop-filter: blur(5px); /* تأثير الضبابية */
    z-index: 999; /* وضعه خلف النافذة */
}

/* تصميم نافذة التأكيد */
.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    padding: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.3);
    z-index: 1000; /* وضعه فوق الخلفية المغبشة */
    width: 80%; /* العرض يمكن تعديله حسب الحاجة */
    max-width: 500px;
    text-align: center;
    border: red 4px inset; /* لإضافة تأثير الإطار */
}

/* تصميم النص داخل النافذة */
.modal-content h3 {
    font-size: 24px;
    margin-bottom: 10px;
}

.modal-content p {
    font-size: 16px;
    margin-bottom: 20px;
}

/* تصميم الأزرار */
.modal-content button {
    padding: 12px 25px;
    margin: 10px 5px;
    border: none;
    cursor: pointer;
    font-size: 16px;
    border-radius: 5px;
    transition: all 0.3s ease;
}

/* زر تأكيد الحذف */
.confirm-btn {
    background-color: #ff4d4d; /* أحمر */
    color: white;
}

/* زر إلغاء */
.cancel-btn {
    background-color: #cccccc; /* رمادي */
    color: white;
}

/* تأثير hover عند المرور بالماوس */
.modal-content button:hover {
    transform: scale(1.05);
}

.confirm-btn:hover {
    background-color: #ff1a1a; /* الأحمر الداكن */
}

.cancel-btn:hover {
    background-color: #b3b3b3; /* الرمادي الداكن */
}


</style>

</body>
</html>


<script>
// التحكم في التمرير عند فتح وإغلاق النموذج
document.addEventListener('DOMContentLoaded', function() {
    // عند فتح النموذج
    <?php if ($challenge_to_edit) { ?>
        document.body.classList.add('modal-open');
    <?php } ?>

    // عند النقر على زر الإغلاق
    document.querySelector('.btn-close')?.addEventListener('click', function() {
        document.body.classList.remove('modal-open');
    });
});

// منع تمرير الصفحة عند وجود النموذج
document.querySelector('.edit-challenge-form')?.addEventListener('wheel', function(e) {
    e.stopPropagation();
});

// الكود السابق الخاص بالحذف
function showDeleteModal(challengeId) {
    window.scrollTo(0, 0);
    document.getElementById('deleteModal').style.display = 'block';
    window.challengeIdToDelete = challengeId;
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

function deleteChallenge() {
    const form = document.createElement('form');
    form.method = 'post';
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'delete_challenge';
    input.value = '1';

    const challengeInput = document.createElement('input');
    challengeInput.type = 'hidden';
    challengeInput.name = 'challenge_id';
    challengeInput.value = window.challengeIdToDelete;

    form.appendChild(input);
    form.appendChild(challengeInput);

    document.body.appendChild(form);
    form.submit();
}
</script>
