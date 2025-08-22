<?php
include 'conn.php';
session_start();

if (!isset($_SESSION['usermail'])) {
    $_SESSION['login_befor'] = "الرجاء عمل تسجيل دخول";
    header("Location: login.php");
    exit();
}

function extractMessage($imageData) {
    $image = imagecreatefromstring(base64_decode($imageData));

    if (!$image) {
        return false;
    }

    $width = imagesx($image);
    $height = imagesy($image);
    $binaryMessage = '';
    $message = '';
    $modifiedPixels = 0;

    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            $rgb = imagecolorat($image, $x, $y);
            $b = $rgb & 0xFF;

            // استخراج البت الأقل أهمية
            $binaryMessage .= ($b & 1);

            // عندما يكون لدينا 8 بت (1 بايت)
            if (strlen($binaryMessage) % 8 == 0) {
                $char = chr(bindec(substr($binaryMessage, -8)));
                
                // توقف عند العلامة النهائية '|'
                if ($char === '|') {
                    break 2;
                }
                
                $message .= $char;
                $modifiedPixels += 8; // كل حرف يحتاج 8 بت (8 بكسل)
            }
        }
    }

    return [
        'message' => $message,
        'modifiedPixels' => $modifiedPixels,
        'width' => $width,
        'height' => $height
    ];
}

function validateImage($file) {
    $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
    if (!in_array($file['type'], $allowedTypes)) {
        return "يرجى تحميل صورة بصيغة JPG او PNG أو JPEG فقط!";
    }
    return null;
}

function uploadImage($file) {
    $imageName = $file["name"];
    $imageData = file_get_contents($file["tmp_name"]);
    return [
        'imageData' => base64_encode($imageData),
        'imageName' => $imageName
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $validationError = validateImage($_FILES['image']);
        if ($validationError) {
            $_SESSION['error'] = $validationError;
        } else {
            $uploadResult = uploadImage($_FILES['image']);
            $imageData = $uploadResult['imageData'];
            $imageName = $uploadResult['imageName'];

            if ($imageData) {
                $extractedData = extractMessage($imageData);

                if (empty($extractedData['message'])) {
                    $_SESSION['error'] = "لم يتم العثور على نص مخفي في الصورة!";
                } else {
                    $_SESSION['extracted_text'] = $extractedData['message'];
                    $_SESSION['modified_pixels'] = $extractedData['modifiedPixels'];
                    $_SESSION['image_width'] = $extractedData['width'];
                    $_SESSION['image_height'] = $extractedData['height'];
                    $_SESSION['image_data'] = $imageData;
                    $_SESSION['image_name'] = $imageName;
                }
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                $_SESSION['error'] = "فشل تحميل الصورة!";
            }
        }
    } else {
        $_SESSION['error'] = "يرجى تحميل صورة صالحة!";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كشف النص المخفي - steganography</title>
    <link rel="stylesheet" href="css/style-Steganography_Reveal.css?v=1.2">
    <link rel="preload" href="img/background6.jpg" as="image">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<?php include 'header/header_stenography.php'; ?>

<body>
    <div class="main-container">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
            </div>
        <?php endif; ?>

        <div class="section">
            <div class="section-header">
                <i class="fas fa-eye"></i>
                <h2>كشف النص المخفي في الصورة</h2>
            </div>
            
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="form-label">اختر صورة:</label>
                    <div class="file-input-container">
                        <div class="file-input-button">
                            <i class="fas fa-image"></i>
                            <span>اختر ملف الصورة</span>
                        </div>
                        <input type="file" name="image" accept="image/png, image/jpeg" class="file-input" required>
                    </div>
                    <div class="file-name-display" id="file-name">لم يتم اختيار ملف</div>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> فحص الصورة
                </button>
            </form>
            
            <?php if (isset($_SESSION['extracted_text'])): ?>
                <div class="result-box">
                    <div class="result-title">
                        <i class="fas fa-file-alt"></i>
                        <span>النص المخفي:</span>
                    </div>
                    <div class="result-content">
                        <?= htmlspecialchars($_SESSION['extracted_text']); ?>
                    </div>
                </div>

                <div class="image-container">
                    <img src="data:image/jpeg;base64,<?= $_SESSION['image_data']; ?>" alt="الصورة المشفرة" />
                </div>
                
                <div class="result-box">
                    <div class="result-title">
                        <i class="fas fa-info-circle"></i>
                        <span>تفاصيل الصورة:</span>
                    </div>
                    <div class="details-container">
                        <div class="detail-item">
                            <span class="detail-label">اسم الصورة:</span>
                            <span class="detail-value"><?= $_SESSION['image_name']; ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">حجم الصورة:</span>
                            <span class="detail-value"><?= round(strlen($_SESSION['image_data']) / 1024, 2); ?> KB</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">عدد البكسلات المعدلة:</span>
                            <span class="detail-value"><?= $_SESSION['modified_pixels']; ?> بكسل (<?= round(($_SESSION['modified_pixels'] / ($_SESSION['image_width'] * $_SESSION['image_height'])) * 100, 2); ?>% من الصورة)</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">أبعاد الصورة:</span>
                            <span class="detail-value"><?= $_SESSION['image_width']; ?> × <?= $_SESSION['image_height']; ?> بكسل</span>
                        </div>
                    </div>
                </div>

                <?php 
                unset(
                    $_SESSION['extracted_text'], 
                    $_SESSION['modified_pixels'],
                    $_SESSION['image_width'], 
                    $_SESSION['image_height'], 
                    $_SESSION['image_data'], 
                    $_SESSION['image_name']
                ); 
                ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.querySelector('input[name="image"]').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'لم يتم اختيار ملف';
            document.getElementById('file-name').textContent = fileName;
        });
    </script>
</body>
</html>