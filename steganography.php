<?php
include 'conn.php'; // الاتصال بقاعدة البيانات
session_start();

// التحقق من وجود الجلسة وبيانات المستخدم
if (!isset($_SESSION['usermail'])) {
    $_SESSION['login_befor'] = "الرجاء عمل تسجيل دخول";
    header("Location: login.php");
    exit();
}

// دالة للتحقق من نوع الصورة
function checkImageType($imagePath) {
    $allowedTypes = ['image/jpeg', 'image/png'];
    $imageInfo = @getimagesize($imagePath);
    return $imageInfo !== false && in_array($imageInfo['mime'], $allowedTypes);
}

function validateImage($file) {
    $allowedTypes = [
        'image/jpeg' => ['jpg', 'jpeg'],
        'image/png'  => ['png']
    ];
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['status' => 'error', 'message' => 'حدث خطأ أثناء رفع الملف'];
    }
    
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!array_key_exists($mime, $allowedTypes)) {
        return ['status' => 'error', 'message' => "نوع الملف غير مدعوم. المسموح فقط: jpg, jpeg, png"];
    }
    
    $maxSize = 5 * 1024 * 1024;
    if ($file['size'] > $maxSize) {
        return ['status' => 'error', 'message' => 'حجم الملف كبير جداً. الحد الأقصى 5MB'];
    }
    
    $imageInfo = @getimagesize($file['tmp_name']);
    if ($imageInfo === false) {
        return ['status' => 'error', 'message' => 'الملف ليس صورة صالحة'];
    }
    
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedTypes[$mime])) {
        return ['status' => 'error', 'message' => 'امتداد الملف لا يتطابق مع نوعه'];
    }
    
    return [
        'status' => 'success',
        'extension' => $ext,
        'mime' => $mime,
        'width' => $imageInfo[0],
        'height' => $imageInfo[1]
    ];
}

// دالة لتشفير النص
function encryptText($text, $password) {
    $method = 'AES-256-CBC';
    $key = hash('sha256', $password, true);
    $iv = openssl_random_pseudo_bytes(16);
    $encrypted = openssl_encrypt($text, $method, $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}

// دالة لفك تشفير النص
function decryptText($encodedText, $password) {
    $method = 'AES-256-CBC';
    $key = hash('sha256', $password, true);

    $decodedData = base64_decode($encodedText);
    $iv = substr($decodedData, 0, 16);
    $encryptedText = substr($decodedData, 16);

    return openssl_decrypt($encryptedText, $method, $key, 0, $iv);
}

// دالة لاستخراج النص المخفي
function extractMessage($imagePath) {
    if (!checkImageType($imagePath)) {
        return false;
    }

    $imageInfo = getimagesize($imagePath);
    $image = $imageInfo['mime'] === 'image/png' ? imagecreatefrompng($imagePath) : imagecreatefromjpeg($imagePath);

    $width = imagesx($image);
    $height = imagesy($image);
    $binaryMessage = '';
    $message = '';

    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            $rgb = imagecolorat($image, $x, $y);
            $b = $rgb & 0xFF;

            $binaryMessage .= ($b & 1);

            if (strlen($binaryMessage) % 8 == 0) {
                $char = chr(bindec(substr($binaryMessage, -8)));
                if ($char === '|') {
                    break 2;
                }
                $message .= $char;
            }
        }
    }

    imagedestroy($image);
    return $message;
}

// دالة لإخفاء النص
function embedMessage($imagePath, $originalName, $message) {
    if (!checkImageType($imagePath)) {
        $_SESSION['error'] = "الملف الذي تم تحميله ليس صورة صالحة!";
        return false;
    }

    $imageInfo = getimagesize($imagePath);
    $mime = $imageInfo['mime'];
    $image = $mime === 'image/png' ? imagecreatefrompng($imagePath) : imagecreatefromjpeg($imagePath);

    $message .= '|END';
    $binaryMessage = '';
    for ($i = 0; $i < strlen($message); $i++) {
        $binaryMessage .= str_pad(decbin(ord($message[$i])), 8, '0', STR_PAD_LEFT);
    }

    $width = imagesx($image);
    $height = imagesy($image);
    $index = 0;

    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            if ($index < strlen($binaryMessage)) {
                $rgb = imagecolorat($image, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;

                $b = ($b & 0xFE) | $binaryMessage[$index];
                $newColor = imagecolorallocate($image, $r, $g, $b);
                imagesetpixel($image, $x, $y, $newColor);
                $index++;
            }
        }
    }

    ob_start();
    imagepng($image);
    $imageData = ob_get_clean();
    imagedestroy($image);

    $_SESSION['encoded_image'] = base64_encode($imageData);
    $_SESSION['original_name'] = pathinfo($originalName, PATHINFO_FILENAME) . "_encoded.png";
    return true;
}

// معالجة النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // معالجة إخفاء النص
    if (isset($_FILES['image']) && isset($_POST['text']) && isset($_POST['key']) && $_FILES['image']['error'] === 0) {
        $text = trim($_POST['text']);
        $password = trim($_POST['key']);
        $originalName = $_FILES['image']['name'];

        if (empty($text) || empty($password)) {
            $_SESSION['error'] = "يجب إدخال النص وكلمة السر!";
        } else {
            $validation = validateImage($_FILES['image']);
            if ($validation['status'] === 'error') {
                $_SESSION['error'] = $validation['message'];
            } else {
                $encryptedText = encryptText($text, $password);
                if (embedMessage($_FILES['image']['tmp_name'], $originalName, $encryptedText)) {
                    $_SESSION['message_saved'] = "تم حفظ النص بالصورة بنجاح";
                }
            }
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // معالجة استخراج النص
    if (isset($_FILES['decodeImage']) && isset($_POST['decodeKey']) && $_FILES['decodeImage']['error'] === 0) {
        $password = trim($_POST['decodeKey']);
        $imagePath = $_FILES['decodeImage']['tmp_name'];

        $validation = validateImage($_FILES['decodeImage']);
        if ($validation['status'] === 'error') {
            $_SESSION['error'] = $validation['message'];
        } else {
            $extractedMessage = extractMessage($imagePath);
            if ($extractedMessage === false) {
                $_SESSION['error'] = "لم يتم العثور على نص مخفي في الصورة!";
            } else {
                $decryptedText = decryptText($extractedMessage, $password);
                if ($decryptedText === false) {
                    $_SESSION['error'] = "كلمة السر خاطئة أو لا يوجد نص مخفي!";
                } else {
                    $_SESSION['decoded_text'] = $decryptedText;
                }
            }
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إخفاء النص في الصور - Steganography</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/style-steganography.css">
    <link rel="preload" href="img/background6.jpg" as="image">
    
    <style>

    </style>
</head>
<?php include 'header/header_stenography.php'; ?>

<body>

    <div class="main-container">
        <!-- Alert Messages -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['message_saved'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span><?php echo $_SESSION['message_saved']; unset($_SESSION['message_saved']); ?></span>
            </div>
        <?php endif; ?>

        <div class="sections-grid">
            <!-- Hide Text Section -->
             
            <div class="section">
                <div class="section-header">
                    <i class="fas fa-eye-slash"></i>
                    <h2>إخفاء النص في صورة</h2>
                </div>
                
                <form method="post" enctype="multipart/form-data" id="hideForm">
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
                    
                    <div class="form-group">
                        <label for="text" class="form-label">النص المراد إخفاؤه:</label>
                        <textarea name="text" id="text" class="form-control" placeholder="اكتب النص الذي تريد إخفاءه هنا..." required></textarea>
                        <div class="char-counter"><span id="char-count">0</span>/200 حرف</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="key" class="form-label">كلمة السر:</label>
                        <div class="input-with-icon">
                            <i class="fas fa-key"></i>
                            <input type="password" name="key" id="key" class="form-control" placeholder="أدخل كلمة سر لتشفير النص" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-lock"></i> إخفاء النص
                    </button>
                </form>
                
                <?php if (isset($_SESSION['encoded_image'])): ?>
                    <div class="result-box active">
                        <div class="result-title">
                            <i class="fas fa-download"></i>
                            <span>الصورة المشفرة جاهزة</span>
                        </div>
                        <div class="result-content">
                            <a href="data:image/png;base64,<?php echo $_SESSION['encoded_image']; ?>" 
                               download="<?php echo $_SESSION['original_name']; ?>" 
                               class="btn btn-secondary">
                                <i class="fas fa-file-download"></i> تحميل الصورة
                            </a>
                        </div>
                    </div>
                    <?php unset($_SESSION['encoded_image']); ?>
                <?php endif; ?>
            </div>
            
            <!-- Extract Text Section -->
            <div class="section">
                <div class="section-header">
                    <i class="fas fa-eye"></i>
                    <h2>استخراج النص من صورة</h2>
                </div>
                
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="form-label">اختر صورة تحتوي على نص مخفي:</label>
                        <div class="file-input-container">
                            <div class="file-input-button">
                                <i class="fas fa-image"></i>
                                <span>اختر ملف الصورة</span>
                            </div>
                            <input type="file" name="decodeImage" accept="image/png, image/jpeg" class="file-input" required>
                        </div>
                        <div class="file-name-display" id="decode-file-name">لم يتم اختيار ملف</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="decodeKey" class="form-label">كلمة السر:</label>
                        <div class="input-with-icon">
                            <i class="fas fa-key"></i>
                            <input type="password" name="decodeKey" id="decodeKey" class="form-control" placeholder="أدخل كلمة السر المستخدمة في التشفير" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-unlock"></i> استخراج النص
                    </button>
                </form>
                
                <?php if (isset($_SESSION['decoded_text'])): ?>
                    <div class="result-box active">
                        <div class="result-title">
                            <i class="fas fa-file-alt"></i>
                            <span>النص المستخرج:</span>
                        </div>
                        <div class="result-content">
                            <?php echo htmlspecialchars($_SESSION['decoded_text']); ?>
                        </div>
                    </div>
                    <?php unset($_SESSION['decoded_text']); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
                </div>

    <script>
        // Update file name display
        document.querySelector('input[name="image"]').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'لم يتم اختيار ملف';
            document.getElementById('file-name').textContent = fileName;
        });
        
        document.querySelector('input[name="decodeImage"]').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'لم يتم اختيار ملف';
            document.getElementById('decode-file-name').textContent = fileName;
        });
        
        // Character counter
        const textArea = document.getElementById('text');
        const charCount = document.getElementById('char-count');
        const counter = document.querySelector('.char-counter');
        
        textArea.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = count;
            
            if (count > 180 && count < 200) {
                counter.classList.add('warning');
                counter.classList.remove('danger');
            } else if (count >= 200) {
                counter.classList.remove('warning');
                counter.classList.add('danger');
                this.value = this.value.substring(0, 200);
                charCount.textContent = 200;
            } else {
                counter.classList.remove('warning');
                counter.classList.remove('danger');
            }
        });
        
        // Form validation
        document.getElementById('hideForm').addEventListener('submit', function(e) {
            if (textArea.value.length > 200) {
                e.preventDefault();
                alert('النص يجب أن يكون أقل من 200 حرف!');
            }
        });
    </script>
</body>
</html>