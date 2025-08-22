
<?php
include 'conn.php'; 

session_start();

// التحقق من وجود الجلسة وبيانات المستخدم
include 'session_check.php'; // يضمن هذا الكود أن أي تفاعل مع الموقع سيجدد الجلسة، وإذا لم يتم التفاعل خلال 30 دقيقة، سيتم تسجيل الخروج تلقائيًا.

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style-home.css?v=1.1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="preload" href="img/background7.jpg" as="image">
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@400;700&display=swap" rel="stylesheet">

    <title>CyberBox Gate</title>

</head>
<?php
include 'header/header_home.php'
?>
<body>

<div class="hero-container">
    <div class="hero">
        <?php
        if (isset($_SESSION['usermail']) && isset($_SESSION['username'])) {
            echo "<h1 class='welcome-heading'>مرحباً بك <span class='username'>" . htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') . "</span></h1>";
            echo "<div class='hero-content'><p class='description-text'>استمتع بتجربة مميزة وسهلة، حيث يمكنك تنفيذ المهام بشكل سريع وآمن، مع أدوات مبتكرة تلبي احتياجاتك التقنية بكل سلاسة</p></div>";
        } else {
            echo "<h1 class='welcome-heading'>مرحباً بك في <span class='brand-name'>CyberBox Gate</span></h1>";
            echo "<div class='hero-content'><p class='hero-description'>قم بتسجيل الدخول الآن للاستمتاع بتجربة مميزة وللوصول لكل الميزات</p>";
            echo "<p class='description-text'>استمتع بتجربة مميزة وسهلة، حيث يمكنك تنفيذ المهام بشكل سريع وآمن، مع أدوات مبتكرة تلبي احتياجاتك التقنية بكل سلاسة</p></div>";
        }
        ?>
        <canvas id="hero-canvas" class="hero-background"></canvas>
        <div class="hero-overlay"></div>
        <div class="hero-shine"></div>
    </div>
</div>

    <!-- قسم الخدمات -->
    <div class="title-container">
  <h1 class="main-title">خدماتننا</h1>
</div>

<div class="service-category">
  <h2 class="tit">التشفير</h2>
</div>
    <div class="service-container">
    <div class="service-card">
            <a href="subbstitution.php">
             <img src="img/substitution-cipher.png" alt="subbstitution">
               <h3>subbstitution</h3>
               <p>شفير يتم فيها استبدال كل حرف في النص بحرف أو رمز آخر وفقًا لقواعد معينة. يُستخدم لتحويل النصوص إلى شكل غير قابل للقراءة، ويعتبر غير آمن مقارنة بالتقنيات الحديثة</p>
            </a>
        </div>


        <div class="service-card">
            <a href="Caesar_Cipher.php">
                <img src="https://play-lh.googleusercontent.com/YcS3eA7X_Vn6irQwTZe_1GgKO1_oA7DELnYJFsulILaiBdgIdl6ANw3ASKVMebx2Usg=s96-rw" alt="التشفير">
                <h3>Caesar Cipher</h3>
                <p>هو طريقة تشفير حيث يتم استبدال كل حرف في النص بحرف آخر يبعد عنه بعدد ثابت من المواضع في الأبجدية.</p>
            </a>
        </div>


        <div class="service-card">
    <a href="File_encryption.php">
        <img src="img/File_Encry.svg" alt="الثغرات الأمنية">
        <h3>File Encryption</h3>
        <p>تشفير الملفات هو عملية تحويل البيانات إلى صيغة غير قابلة للقراءة باستخدام خوارزميات رياضية، مما يضمن حماية المعلومات من الوصول غير المصرح به.</p>
    </a>
</div>



        <div class="service-card">
    <a href="AES.php">
        <img src="img/AES.png" alt="AES">
        <h3>تشفير AES</h3>
        <p>تشفير AES هو خوارزمية تشفير متقدمة تستخدم مفتاح ثابت الحجم (128 بت، 192 بت، أو 256 بت) لحماية البيانات وتوفير أمان عالي ضد الهجمات.</p>
        </a>
</div>


<div class="service-card">
    <a href="RSA.php">
        <img src="img/RSA.jpg" alt="RSA">
        <h3>تشفير RSA</h3>
        <p>تشفير RSA هو خوارزمية تشفير غير متماثلة تستخدم مفتاحين، أحدهما للتشفير والآخر لفك التشفير. يعتمد على الرياضيات المعقدة لتمكين تبادل المعلومات بأمان على الإنترنت.</p>
    </a>
</div>
        </div>
<!-- كلمات المرور -->

        <div class="service-category">
  <h2 class="tit">كلمات المرور</h2>
</div>

    <div class="service-container">
    <div class="service-card">
            <a href="Password-Generator.php">
             <img src="img/password.png" alt="password">
               <h3>Password Generator</h3>
               <p>توليد كلمات مرور قوية يساعد في تعزيز الأمان وحماية الحسابات من الاختراق. يتم إنشاء كلمات مرور عشوائية وطويلة تجمع بين الحروف الكبيرة والصغيرة والأرقام والرموز لتوفير حماية إضافية.</p>
               </a>
        </div>


        <div class="service-card">
    <a href="password_analysis.php">
        <img src="img/Password analysis.jpg" alt="Password analysis">
        <h3>Password Analysis</h3>
        <p>اختبار قوة كلمة المرور يساعد في التأكد من مدى أمان كلمة المرور الخاصة بك عن طريق فحص طولها وتعقيدها ومقاومتها للهجمات المحتملة. استخدم هذه الأداة لتحليل كلمات المرور وتعزيز الأمان الرقمي.</p>
    </a>
</div>
        </div>


        <!-- steganography -->
        <div class="service-category">
  <h2 class="tit">steganography</h2>
</div>


        <div class="service-container">
        <div class="service-card">
    <a href="steganography.php">
        <img src="img/steganography.png" alt="steganography">
        <h3>Steganography</h3>
        <p>التخفي (Steganography) هو إخفاء البيانات داخل ملفات مثل الصور والفيديو لنقل المعلومات بشكل سري وآمن.</p>
        </a>
</div>



<div class="service-card">
    <a href="Steganography_Reveal.php">
        <img src="img/Steganography_Reveal.jpg" alt="Steganography_Reveal">
        <h3>مكتشف Steganography</h3>
        <p>أداة للكشف عن النصوص المخفية داخل الصور وعرض معلومات حول محتوى الصورة المخفي.</p>
    </a>  
</div>

        </div>

                <!-- محول الأنظمة الرقمية -->

                <div class="service-category">
  <h2 class="tit">محول الأنظمة الرقمية</h2>
</div>

                <div class="service-container">
                <div class="service-card">
    <a href="multi_system_converter.php">
        <img src="img/binary-code.jpg" alt="مترجم الاعداد">
        <h3>محول الأنظمة الرقمية</h3>
        <p>محول الأنظمة الرقمية يتيح لك تحويل الأعداد بين مختلف الأنظمة مثل الثنائي، الست عشري، العشري، الثماني والنص. يدعم التحويلات بين هذه الأنظمة بكل سهولة وسرعة.</p>
    </a>
</div>


        </div>

                <!-- HASH  -->


        <div class="service-category">
  <h2 class="tit">Hash</h2>
</div>

<div class="service-container">
<div class="service-card">
    <a href="MD5.php">
        <img src="img/MD5.png" alt="MD5">
        <h3>MD5</h3>
        <p>MD5 هو خوارزمية تجزئة تستخدم لتحويل البيانات إلى قيمة ثابتة بطول معين، وتستخدم بشكل شائع للتحقق من سلامة البيانات. تُستخدم لتوليد قيم تجزئة فريدة للتحقق من صحة الملفات والرسائل.</p>
    </a>
</div>

<div class="service-card">
    <a href="SHA-1.php">
        <img src="img/SHA-1.png" alt="SHA-1">
        <h3>SHA-1</h3>
        <p>SHA-1 هو خوارزمية تجزئة تُستخدم لتحويل البيانات إلى قيمة ثابتة بطول 160 بت. كانت تُستخدم بشكل واسع للتحقق من سلامة البيانات، لكنها أصبحت أقل أمانًا بسبب تطور تقنيات الهجوم عليها.</p>
    </a>
</div>


<div class="service-card">
    <a href="SHA-256.php">
        <img src="https://files.oaiusercontent.com/file-S8Kbf6Ypdiu8BcD9Gper6r?se=2025-03-21T20%3A56%3A33Z&sp=r&sv=2024-08-04&sr=b&rscc=max-age%3D604800%2C%20immutable%2C%20private&rscd=attachment%3B%20filename%3D489ed627-4cbf-44c2-a96e-49fb42628af0.webp&sig=on5BwChWD84kBjSYebN%2BzwlJeZtG0RYPFLcHMoJmd5s%3D" alt="SHA-256">
        <h3>SHA-256</h3>
        <p>SHA-256 هو خوارزمية تجزئة تُنتج قيمة ثابتة بطول 256 بت. تُستخدم بشكل واسع في تأكيد سلامة البيانات وضمان الأمان في العديد من التطبيقات، بما في ذلك التشفير والتحقق من صحة البيانات.</p>
    </a>
</div>
</div>



                <!-- القسم التعليمي   -->


<!--******************************-->
<div class="title-container">
  <h1 class="main-title">القسم التعليمي</h1>
</div>
    <div class="service-container">

        <div class="service-card">
            <a href="education/cybersecurity.php">
             <img src="img/cybersecurity.jpeg" alt="مقدمة في الأمن السيبراني">
               <h3>مقدمة في الأمن السيبراني</h3>
               <p>استكشف أساسيات الأمن السيبراني وكيفية حماية الأنظمة والشبكات من الهجمات الإلكترونية باستخدام أحدث تقنيات الأمان. تعلم كيفية الحفاظ على سرية المعلومات وحمايتها من المهاجمين.</p>
            </a>
        </div>


        <div class="service-card">
            <a href="education/crypto.php">
                <img src="img/crypto.jpg" alt="التشفير">
                <h3>التشفير</h3>
                <p>تعلم أساسيات التشفير وكيفية تأمين البيانات باستخدام تقنيات التشفير المختلفة.</p>
            </a>
        </div>


        <div class="service-card">
            <a href="education/vulnerability.php">
                <img src="img/vulnerability.png" alt="الثغرات الأمنية">
                <h3>الثغرات الأمنية</h3>
                <p>استكشف الثغرات الأمنية المختلفة وكيفية اكتشافها واستغلالها في بيئة الاختراق.</p>
            </a>
        </div>


        <div class="service-card">
    <a href="education/exploittools.php">
        <img src="img/exploittools.jpg" alt="اختبار الاختراق">
        <h3>أدوات الاختراق</h3>
        <p>تعلم كيفية استخدام أدوات الاختراق لاختبار الأنظمة والشبكات بحثًا عن الثغرات الأمنية واستغلالها لتحسين مستوى الأمان.</p>
    </a>
</div>


        <div class="service-card">
            <a href="education/MITRE_ATT&CK.php">
                <img src="img/MITRE_ATT&CK.jpg" alt="MITRE_ATT&CK">
                <h3>MITRE ATT&CK</h3>
                <p>>تعرف على إطار العمل الشامل للتصدي للهجمات الإلكترونية باستخدام MITRE ATT&CK وكيفية حماية الأنظمة من التهديدات المختلفة</p>
            </a>
        </div>



        <div class="service-card">
            <a href="education/NetworkS.php">
                <img src="img/Network-security.png" alt="Network-security">
                <h3>Network Security</h3>
                <p>تعرف على أساليب تأمين الشبكات ضد الهجمات الإلكترونية باستخدام الجدران النارية، وأنظمة كشف التسلل، وتقنيات التشفير.</p>
            </a>
        </div>


    </div>

<div style="flex: 1;"></div> <!-- عنصر افتراضي لدفع الفوتر للأسفل -->

<!-- الفوتر -->
<footer>
    <div class="footer-content">
    <p class="para-footer">&copy; 2025 جامعة آل البيت (AABU). جميع الحقوق محفوظة. موقع: CyberBox Gate</p>

    </div>
</footer>


<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // إعداد المشهد ثلاثي الأبعاد
    const canvas = document.getElementById('hero-canvas');
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, canvas.clientWidth / canvas.clientHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true });
    
    renderer.setSize(canvas.clientWidth, canvas.clientHeight);
    renderer.setPixelRatio(window.devicePixelRatio);
    
    // إضافة الضوء
    const ambientLight = new THREE.AmbientLight(0x404040);
    scene.add(ambientLight);
    
    const directionalLight = new THREE.DirectionalLight(0xffffff, 0.5);
    directionalLight.position.set(1, 1, 1);
    scene.add(directionalLight);
    
    // إنشاء الجسيمات
    const particlesGeometry = new THREE.BufferGeometry();
    const particlesCount = 500;
    
    const posArray = new Float32Array(particlesCount * 3);
    const sizeArray = new Float32Array(particlesCount);
    const colorArray = new Float32Array(particlesCount * 3);
    
    for(let i = 0; i < particlesCount * 3; i++) {
        posArray[i] = (Math.random() - 0.5) * 10;
        if(i % 3 === 0) {
            sizeArray[i/3] = Math.random() * 0.5 + 0.1;
            
            // ألوان الجسيمات (درجات الأزرق والأبيض)
            const blueTone = Math.random() * 0.5 + 0.5;
            colorArray[i] = 0.2 + Math.random() * 0.3; // R
            colorArray[i+1] = 0.5 + Math.random() * 0.5; // G
            colorArray[i+2] = blueTone; // B
        }
    }
    
    particlesGeometry.setAttribute('position', new THREE.BufferAttribute(posArray, 3));
    particlesGeometry.setAttribute('size', new THREE.BufferAttribute(sizeArray, 1));
    particlesGeometry.setAttribute('color', new THREE.BufferAttribute(colorArray, 3));
    
    const particlesMaterial = new THREE.PointsMaterial({
        size: 0.2,
        vertexColors: true,
        transparent: true,
        opacity: 0.8,
        blending: THREE.AdditiveBlending
    });
    
    const particlesMesh = new THREE.Points(particlesGeometry, particlesMaterial);
    scene.add(particlesMesh);
    
    camera.position.z = 5;
    
    // إضافة شبكة متحركة ثلاثية الأبعاد
    const gridGeometry = new THREE.BoxGeometry(15, 15, 15, 15, 15, 15);
    const gridMaterial = new THREE.MeshBasicMaterial({ 
        color: 0x1a68b8, 
        wireframe: true,
        transparent: true,
        opacity: 0.15
    });
    const gridMesh = new THREE.Mesh(gridGeometry, gridMaterial);
    scene.add(gridMesh);
    
    // التحريك
    function animate() {
        requestAnimationFrame(animate);
        
        particlesMesh.rotation.x += 0.0005;
        particlesMesh.rotation.y += 0.001;
        
        gridMesh.rotation.x += 0.001;
        gridMesh.rotation.y += 0.002;
        
        renderer.render(scene, camera);
    }
    
    animate();
    
    // إعادة ضبط الحجم عند تغيير حجم النافذة
    window.addEventListener('resize', function() {
        camera.aspect = canvas.clientWidth / canvas.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(canvas.clientWidth, canvas.clientHeight);
    });
    
    // تأثير تفاعلي عند تحريك الماوس
    canvas.addEventListener('mousemove', function(e) {
        const x = (e.clientX / window.innerWidth) * 2 - 1;
        const y = -(e.clientY / window.innerHeight) * 2 + 1;
        
        particlesMesh.rotation.y = x * 0.2;
        particlesMesh.rotation.x = y * 0.2;
        
        gridMesh.rotation.y = x * 0.1;
        gridMesh.rotation.x = y * 0.1;
    });
});
</script>

</body>
</html>



