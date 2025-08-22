<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الأسئلة الشائعة - CyberBox Gate</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style-comman_questions.css">
    <link rel="preload" href="img/background7.jpg" as="image">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet">

</head>
<body>
    <?php
    include 'header/header_home.php'
    ?>

    <div class="container">
        <h1 class="section-title">الأسئلة الشائعة</h1>

        <div class="accordion-container">
            <h3 class="category-title"><i class="fas fa-info-circle"></i> عام</h3>
            
            <button class="accordion">
                ما هو مشروع CyberBox Gate؟
                <i class="fas fa-chevron-down accordion-icon"></i>
            </button>
            <div class="panel">
                <div class="panel-content">
                    <p>CyberBox Gate هو منصة متكاملة للأمن السيبراني تقدم مجموعة من الأدوات المتقدمة والخدمات التعليمية في مجال التشفير وأمن المعلومات. تم تطويره كمشروع تخرج لتوفير بيئة تعليمية وتطبيقية شاملة لمحبي الأمن السيبراني.</p>
                </div>
            </div>

            <button class="accordion">
                ما هي الخدمات الأساسية التي يقدمها الموقع؟
                <i class="fas fa-chevron-down accordion-icon"></i>
            </button>
            <div class="panel">
                <div class="panel-content">
                    <p>يقدم الموقع مجموعة متنوعة من الخدمات:</p>
                    <ul style="padding-right: 20px; color: #555; line-height: 1.8;">
                        <li>أدوات التشفير (AES, RSA, Caesar, Substitution)</li>
                        <li>إخفاء البيانات في الصور (Steganography)</li>
                        <li>إدارة كلمات المرور وتقييم قوتها</li>
                        <li>محول الأنظمة العددية (Number Base Converter)</li>
                        <li>توليد وتحليل الهاش (MD5, SHA1, SHA256)</li>
                        <li>قسم تعليمي نظري متكامل </li>
                        <li>تحديات عملية متنوعة (CTF Challenges)</li>
                    </ul>
                </div>
            </div>
            

            <button class="accordion">
هل الموقع مخصص لفئة معينة؟                
<i class="fas fa-chevron-down accordion-icon"></i>
            </button>
            <div class="panel">
                <div class="panel-content">
                    <p>الموقع موجّه لطلبة الجامعات والمبتدئين في مجال الأمن السيبراني، لكنه مفيد أيضًا لأي شخص يرغب بتطوير معرفته في هذا المجال.</p>
                </div>
            </div>

                       <button class="accordion">
هل يتطلب استخدام الموقع خبرة مسبقة في الأمن السيبراني؟
               
<i class="fas fa-chevron-down accordion-icon"></i>
            </button>
            <div class="panel">
                <div class="panel-content">
                    <p>لا، تم تصميم المنصة لتكون مناسبة لجميع المستويات، مع محتوى يبدأ من الأساسيات ويصل إلى مفاهيم متقدمة بشكل تدريجي.</pى>
                </div>
            </div>
            
            

            <button class="accordion">
                هل المنصة مجانية؟
                <i class="fas fa-chevron-down accordion-icon"></i>
            </button>
            <div class="panel">
                <div class="panel-content">
                    <p>نعم، المنصة توفر جميع الخدمات الأساسية مجانًا للمستخدمين المسجلين</p>
                </div>
            </div>

            <h3 class="category-title"><i class="fas fa-user"></i> الحسابات والتسجيل</h3>
            
            <button class="accordion">
                هل يمكنني استخدام الأدوات بدون تسجيل؟
                <i class="fas fa-chevron-down accordion-icon"></i>
            </button>
            <div class="panel">
                <div class="panel-content">
                    <p>بعض الأدوات الأساسية متاحة للاستخدام بدون تسجيل، ولكن للاستفادة الكاملة من جميع الميزات وحفظ نتائجك، ننصحك بتسجيل حساب مجاني. التسجيل سريع وسهل ولا يتطلب أكثر من دقيقة.</p>
                </div>
            </div>

            <button class="accordion">
                كيف أبدأ في استخدام المنصة؟
                <i class="fas fa-chevron-down accordion-icon"></i>
            </button>
            <div class="panel">
                <div class="panel-content">
                    <p>يمكنك البدء باتباع هذه الخطوات البسيطة:</p>
                    <ol style="padding-right: 20px; color: #555; line-height: 1.8;">
                        <li>قم بإنشاء حساب جديد (أو تسجيل الدخول إذا كنت تمتلك حسابًا)</li>
                        <li>تصفح الأدوات المتاحة واختر ما يناسب احتياجاتك</li>
                        <li>استكشف القسم التعليمي لتعلم الأساسيات</li>
                        <li>جرب التحديات الأمنية لاختبار مهاراتك</li>
                        <li>احفظ نتائجك وتتبع تقدمك من لوحة التحكم</li>
                    </ol>
                </div>
            </div>

            <button class="accordion">
                هل يمكنني استخدام الأدوات بدون حساب؟
                <i class="fas fa-chevron-down accordion-icon"></i>
            </button>
            <div class="panel">
                <div class="panel-content">
                    <p>للاستفادة الكاملة من جميع الميزات يجب إنشاء حساب. بعض الأدوات الأساسية متاحة للاستخدام بدون تسجيل، ولكن التسجيل يتيح لك حفظ النتائج، تتبع التقدم، والمشاركة في التحديات.</p>
                </div>
            </div>

            <h3 class="category-title"><i class="fas fa-lock"></i> الأمان والتشفير</h3>
            
            <button class="accordion">
                ما هي خوارزميات التشفير المتاحة؟
                <i class="fas fa-chevron-down accordion-icon"></i>
            </button>
            <div class="panel">
                <div class="panel-content">
                    <p>نقدم مجموعة واسعة من خوارزميات التشفير:</p>
                    <ul style="padding-right: 20px; color: #555; line-height: 1.8;">
                        <li>التشفير الحديث: AES, RSA, Blowfish</li>
                        <li>التشفير الكلاسيكي: Caesar, Vigenère, Substitution</li>
                        <li>تشفير الملفات</li>
                        <li>أنظمة الهاش: MD5, SHA1, SHA256</li>
                    </ul>
                </div>
            </div>

            <button class="accordion">
                كيف يعمل نظام إخفاء البيانات في الصور؟
                <i class="fas fa-chevron-down accordion-icon"></i>
            </button>
            <div class="panel">
                <div class="panel-content">
                    <p>تستخدم تقنية الستيغانوغرافي (Steganography) لإخفاء النصوص داخل الصور:</p>
                    <ul style="padding-right: 20px; color: #555; line-height: 1.8;">
                        <li>إمكانية إخفاء أي نص داخل صورة JPG أو PNG</li>
                        <li>تشفير النص قبل إخفائه لزيادة الأمان</li>
                        <li>استخراج النص المخفي باستخدام كلمة مرور</li>
                        <li>حفظ الصورة مع المحافظة على جودتها</li>
                    </ul>
                </div>
            </div>

            <button class="accordion">
                هل يمكنني اختبار قوة كلمات المرور؟
                <i class="fas fa-chevron-down accordion-icon"></i>
            </button>
            <div class="panel">
                <div class="panel-content">
                    <p>نعم، نوفر أداة متكاملة لتحليل قوة كلمات المرور تعمل على:</p>
                    <ul style="padding-right: 20px; color: #555; line-height: 1.8;">
                        <li>فحص طول كلمة المرور وتعقيدها</li>
                        <li>الكشف عن الكلمات الشائعة والأنماط المتوقعة</li>
                        <li>تقدير الوقت اللازم لكسرها باستخدام هجمات القوة الغاشمة</li>
                        <li>تقديم نصائح لتحسين قوة كلمة المرور</li>
                    </ul>
                    <p>بالإضافة إلى مولد كلمات مرور عشوائية وقوية.</p>
                </div>
            </div>

            <h3 class="category-title"><i class="fas fa-graduation-cap"></i> التعليم والتحديات</h3>
            
            <button class="accordion">
                ما الذي يقدمه القسم التعليمي؟
                <i class="fas fa-chevron-down accordion-icon"></i>
            </button>
            <div class="panel">
                <div class="panel-content">
                    <p>القسم التعليمي يحتوي على:</p>
                    <ul style="padding-right: 20px; color: #555; line-height: 1.8;">
                        <li>شروحات نظرية لأساسيات التشفير</li>
                        <li>أمثلة تطبيقية على الخوارزميات</li>
                        <li>دروس في أمن المعلومات</li>
                    </ul>
                </div>
            </div>

           

            <button class="accordion">
                ما هي تحديات الـ CTF المتاحة؟
                <i class="fas fa-chevron-down accordion-icon"></i>
            </button>
            <div class="panel">
                <div class="panel-content">
                    <p>تتضمن التحديات:</p>
                    <ul style="padding-right: 20px; color: #555; line-height: 1.8;">
                        <li>تحديات فك التشفير (Cryptography)</li>
                        <li>تحديات في مجال الويب </li>
                        <li>تحديات استخراج البيانات المخفية</li>
                        <li>تحديات كثير بمختلف انواعها</li>
                        <li>تصنيف للمستخدمين حسب النقاط</li>
                    </ul>
                </div>
            </div>



            <h3 class="category-title"><i class="fas fa-mobile-alt"></i> الاستخدام الفني</h3>
            


            <button class="accordion">
                كيف يمكنني التواصل مع الدعم الفني؟
                <i class="fas fa-chevron-down accordion-icon"></i>
            </button>
            <div class="panel">
                <div class="panel-content">
                    <p>يمكنك التواصل معنا عبر:</p>
                    <ul style="padding-right: 20px; color: #555; line-height: 1.8;">
                        <li>نموذج التواصل الموجود في قسم "تواصل معنا"</li>
                        <li>البريد الإلكتروني: cyberboxgate@gmail.com</li>
                    </ul>
                </div>
            </div>

            <button class="accordion">
                هل تخططون لإضافة ميزات جديدة؟
                <i class="fas fa-chevron-down accordion-icon"></i>
            </button>
            <div class="panel">
                <div class="panel-content">
                    <p>نعم، نعمل باستمرار على تطوير المنصة وإضافة:</p>
                    <ul style="padding-right: 20px; color: #555; line-height: 1.8;">
                        <li>خوارزميات تشفير إضافية</li>
                        <li>أنواع جديدة من التحديات</li>
                        <li>ميزات لإدارة المشاريع الأمنية</li>
                        <li>تحسينات على واجهة المستخدم</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Accordion functionality
        var acc = document.getElementsByClassName("accordion");
        var i;
        
        for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var panel = this.nextElementSibling;
                
                if (panel.style.maxHeight) {
                    panel.style.maxHeight = null;
                    panel.style.padding = "0";
                } else {
                    panel.style.maxHeight = panel.scrollHeight + "px";
                    panel.style.padding = "0 0 10px 0";
                } 
            });
        }
        
        // Search functionality
        const searchInput = document.querySelector('.search-input');
        const accordions = document.querySelectorAll('.accordion');
        const noResults = document.querySelector('.no-results');
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            let hasResults = false;
            
            accordions.forEach(accordion => {
                const text = accordion.textContent.toLowerCase();
                const panel = accordion.nextElementSibling;
                
                if (text.includes(searchTerm)) {
                    accordion.style.display = "flex";
                    panel.style.display = "block";
                    hasResults = true;
                } else {
                    accordion.style.display = "none";
                    panel.style.display = "none";
                }
            });
            
            if (hasResults || searchTerm === '') {
                noResults.style.display = "none";
            } else {
                noResults.style.display = "block";
            }
            
            // Show category titles if any accordion in category is visible
            document.querySelectorAll('.category-title').forEach(title => {
                const nextElements = [];
                let el = title.nextElementSibling;
                
                while (el && !el.classList.contains('category-title')) {
                    if (el.classList.contains('accordion')) {
                        nextElements.push(el);
                    }
                    el = el.nextElementSibling;
                }
                
                const anyVisible = nextElements.some(el => el.style.display !== 'none');
                title.style.display = anyVisible ? 'block' : 'none';
            });
        });
        
        // Open first accordion by default
        if (accordions.length > 0) {
            accordions[0].click();
        }
    </script>

</body>
</html>