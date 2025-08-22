<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preload" href="img/background7.jpg" as="image">
    <link rel="stylesheet" href="css/style-about.css?v=1.1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>نبذة عن الموقع</title>
</head>
<body>
    <?php
    include 'header/header_home.php'
    ?>
    
    <div class="container">
        
        <h1 class="title">نبذة عن CyberBox Gate</h1>
        
        <div class="card">
            <div class="card-header">
                <i class="fas fa-info-circle"></i> مقدمة عن الموقع
            </div>
            <p class="paragraph">
Cyberbox Gate منصة تفاعلية متخصصة في الأمن السيبراني، تهدف إلى تعزيز المهارات من خلال أدوات تعليمية، تطبيقات عملية، وتحديات CTF. توفر تجربة تعليمية تجمع بين الفهم النظري والتطبيق العملي بأسلوب مبسط وفعّال            </p>
        </div>
        
        <h2 class="section-title"><i class="fas fa-lightbulb"></i>  اهداف المشروع؟</h2>
        
        <div class="card">
            <p class="paragraph">
يهدف الموقع إلى تبسيط مفاهيم الأمن السيبراني وتقديمها بأسلوب سهل ومباشر لطلبة الجامعات والمبتدئين، من خلال أدوات تفاعلية وتجارب عملية تعزز الفهم التطبيقي. كما يسعى إلى تطوير مهارات التحليل والتفكير النقدي عبر تحديات CTF متنوعة، والمساهمة في إثراء المحتوى العربي في هذا المجال، ضمن بيئة تعليمية آمنة ومحفزة على التعلم        </div>
        
        <h2 class="section-title"><i class="fas fa-cogs"></i> الخدمات التي نقدمها</h2>
        
        <div class="card">
        <ul class="list">
                <li>أدوات تشفير النصوص باستخدام خوارزميات متعددة (AES، RSA، Caesar، Substitution)</li>
                <li>تشفير وفك تشفير الملفات بمعايير أمنية عالية</li>
                <li>نظام متكامل لإدارة كلمات المرور وإنشاء كلمات مرور قوية</li>
                <li>تقنية Steganography لإخفاء النصوص داخل الصور</li>
                <li>محول الأنظمة العددية بين (الثنائي، العشري، السداسي عشر)</li>
                <li>أدوات توليد وتحليل الهاش (MD5، SHA1، SHA256)</li>
                <li>سجل خاص بحفظ النشاطات مع تجنب تخزين النصوص المكررة</li>
                <li>نظام إشعارات لتنبيه المستخدم بنشاطاته</li>
            </ul>
        </div>
        
        <h2 class="section-title"><i class="fas fa-star"></i> مميزاتنا</h2>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <h3 class="feature-title">أدوات التشفير</h3>
                <p class="feature-desc">
                    مجموعة شاملة من أدوات التشفير تشمل AES، RSA، وخوارزميات التشفير الكلاسيكية مع واجهة سهلة الاستخدام.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-key"></i>
                </div>
                <h3 class="feature-title">إدارة كلمات المرور</h3>
                <p class="feature-desc">
                    أداة متكاملة لإنشاء وإدارة كلمات مرور قوية وعشوائية مع تقييم مستوى أمانها.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <h3 class="feature-title">محول الأنظمة</h3>
                <p class="feature-desc">
                    تحويل سريع ودقيق بين الأنظمة العددية المختلفة مع عرض النتائج بتنسيق واضح.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-image"></i>
                </div>
                <h3 class="feature-title">إخفاء البيانات</h3>
                <p class="feature-desc">
                    تقنية متقدمة لإخفاء النصوص والرسائل داخل الصور دون التأثير على مظهرها الأصلي.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3 class="feature-title">القسم التعليمي</h3>
                <p class="feature-desc">
                    محتوى تعليمي نظري يغطي أساسيات ومتقدمات الأمن السيبراني والتشفير بشكل مبسط.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-flag"></i>
                </div>
                <h3 class="feature-title">تحديات CTF</h3>
                <p class="feature-desc">
                    سلسلة من التحديات العملية في مختلف مجالات الأمن السيبراني لاختبار المهارات وتطويرها.
                </p>
            </div>
        </div>
        
        <h2 class="section-title"><i class="fas fa-users"></i> فريق العمل</h2>
        
        <div class="team-section">
            <div class="team-grid">
                <div class="team-card">
                    <img src="img/Eyad_Aljayosi.jpeg" alt="فريق العمل" class="team-img">
                    <div class="team-info">
                        <h3 class="team-name">اياد الجيوسي</h3>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="team-card">
                    <img src="img/Omar_maali.jpg" alt="فريق العمل" class="team-img">
                    <div class="team-info">
                        <h3 class="team-name">عمر معالي</h3>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="team-card">
                    <img src="img/Anas_lahloh.jpeg" alt="فريق العمل" class="team-img">
                    <div class="team-info">
                        <h3 class="team-name">انس لحلوح</h3>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>