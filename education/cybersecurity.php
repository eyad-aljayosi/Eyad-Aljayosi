<?php
include('../conn.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../session_check.php';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="صفحة تعليمية شاملة عن أساسيات الأمن السيبراني وأهميته في العصر الرقمي">
    <meta name="keywords" content="الأمن السيبراني, أمن المعلومات, الحماية الإلكترونية, أمن الشبكات">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>دليل شامل للأمن السيبراني | Cyber Security Guide</title>
    <style>
        :root {
            --primary-color: #084584;
            --secondary-color: #2980b9;
            --accent-color: #f39c12;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
            --danger-color: #e74c3c;
            --success-color: #27ae60;
            --text-color: #333;
            --text-light: #555;
        }
        
        body {
            font-family: 'Tajawal', 'Cairo', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: var(--text-color);
            direction: rtl;
            text-align: right;
            background-image: url('../img/background6.jpg');
            background-size: cover;
            background-position: center center;
            background-attachment: fixed;
            line-height: 1.8;
        }

        .title {
            font-size: 2.8rem;
            color: var(--primary-color);
            text-align: center;
            padding: 20px;
            margin: 12px auto 20px;
            position: relative;
            width: fit-content;
        }

        .title::after {
            content: "";
            position: absolute;
            bottom: 10px;
            right: 0;
            width: 70%;
            height: 3px;
            background: linear-gradient(to right, var(--secondary-color), transparent);
            border-radius: 3px;
        }

        .container {
            padding: 40px;
            max-width: 1200px;
            margin: 20px auto 50px;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .container:hover {
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            transform: translateY(-5px);
        }

        .section-title {
            font-size: 2rem;
            color: var(--secondary-color);
            margin-bottom: 30px;
            font-weight: 800;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--secondary-color);
            position: relative;
        }

        .section-title::before {
            content: "";
            position: absolute;
            bottom: -2px;
            right: 0;
            width: 100px;
            height: 4px;
            background: var(--accent-color);
            border-radius: 2px;
        }

        .card {
            padding: 30px;
            margin: 30px 0;
            border-radius: 12px;
            background-color: rgba(250, 250, 250, 0.9);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            border-left: 5px solid var(--secondary-color);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .card:hover {
            background-color: rgba(241, 241, 241, 0.95);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
            transform: translateX(-10px);
        }

        .card::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            width: 5px;
            height: 0;
            background: var(--accent-color);
            transition: all 0.4s ease;
        }

        .card:hover::before {
            height: 100%;
        }

        .card-header {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .card-header i {
            margin-left: 10px;
            color: var(--secondary-color);
        }

        .paragraph, .custom-ul {
            font-size: 1.1rem;
            color: var(--text-light);
            line-height: 1.9;
        }

        pre {
            background-color: var(--dark-color);
            color: #fff;
            padding: 20px;
            border-radius: 8px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 1rem;
            overflow-x: auto;
            white-space: pre-wrap;
            position: relative;
        }

        pre::before {
            content: "كود";
            position: absolute;
            top: 0;
            left: 0;
            background: var(--accent-color);
            color: white;
            padding: 2px 10px;
            font-size: 0.8rem;
            border-radius: 0 0 5px 0;
        }

        .example-box {
            background-color: var(--light-color);
            padding: 20px;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            border-right: 3px solid var(--accent-color);
        }

        .note {
            background-color: #fff3cd;
            padding: 20px;
            border-left: 5px solid var(--accent-color);
            margin: 25px 0;
            font-size: 1.1rem;
            border-radius: 8px;
            position: relative;
        }

        .note::before {
            content: "ملاحظة هامة";
            font-weight: bold;
            display: block;
            margin-bottom: 10px;
            color: var(--dark-color);
        }

        .warning {
            background-color: #f8d7da;
            border-left: 5px solid var(--danger-color);
        }

        .warning::before {
            content: "تحذير";
            color: var(--danger-color);
        }

        .img-tool {
            width: 70%;
            max-width: 800px;
            border-radius: 12px;
            margin: 30px auto;
            display: block;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
            border: 3px solid white;
        }

        .img-tool:hover {
            transform: scale(1.02);
        }

        .advantages .custom-ul {
            list-style-type: none;
            padding-right: 0;
        }

        .advantages .custom-li {
            font-size: 1.1rem;
            color: var(--text-light);
            padding: 10px 0;
            position: relative;
            padding-right: 30px;
        }

        .advantages .custom-li::before {
            content: "\f00c";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            right: 0;
            color: var(--success-color);
        }

        .card a {
            color: var(--secondary-color);
            font-size: 1.1rem;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            display: inline-flex;
            color: wheat;
            align-items: center;
        }

        .card a:hover {
            color: var(--primary-color);
            color: #ecf0f1;
            text-decoration: underline;
        }

        .card a i {
            margin-right: 5px;
        }

        .button {
            background-color: var(--secondary-color);
            color: #fff;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 1.1rem;
            text-align: center;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .button:hover {
            background-color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .button i {
            margin-right: 8px;
        }

        .statistics {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin: 30px 0;
            justify-content: center;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            flex: 1 1 200px;
            text-align: center;
            transition: all 0.3s ease;
            border-top: 4px solid var(--secondary-color);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary-color);
            margin: 10px 0;
        }

        .stat-label {
            font-size: 1.1rem;
            color: var(--text-light);
        }

        .timeline {
            position: relative;
            max-width: 1000px;
            margin: 40px auto;
        }

        .timeline::after {
            content: '';
            position: absolute;
            width: 3px;
            background-color: var(--secondary-color);
            top: 0;
            bottom: 0;
            right: 50%;
            margin-right: -1.5px;
        }

        .timeline-item {
            padding: 10px 40px;
            position: relative;
            width: 50%;
            box-sizing: border-box;
        }

        .timeline-item::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            background-color: white;
            border: 4px solid var(--secondary-color);
            border-radius: 50%;
            top: 15px;
            z-index: 1;
        }

        .left {
            right: 0;
        }

        .right {
            right: 50%;
        }

        .left::after {
            left: -10px;
        }

        .right::after {
            right: -10px;
        }

        .timeline-content {
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        @media screen and (max-width: 768px) {
            .title {
                font-size: 2rem;
            }
            
            .container {
                padding: 20px;
                margin: 10px auto 30px;
            }
            
            .section-title {
                font-size: 1.5rem;
            }
            
            .card {
                padding: 20px;
            }
            
            .img-tool {
                width: 100%;
            }
            
            .timeline::after {
                right: 31px;
            }
            
            .timeline-item {
                width: 100%;
                padding-right: 70px;
                padding-left: 25px;
            }
            
            .timeline-item::after {
                right: 21px;
            }
            
            .right {
                right: 0%;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card {
            animation: fadeIn 0.6s ease forwards;
        }

        .card:nth-child(1) { animation-delay: 0.1s; }
        .card:nth-child(2) { animation-delay: 0.2s; }
        .card:nth-child(3) { animation-delay: 0.3s; }
        .card:nth-child(4) { animation-delay: 0.4s; }
        .card:nth-child(5) { animation-delay: 0.5s; }
    </style>
</head>
<body>
<?php
include('header-learn-home.php');
?>

<h1 class="title"><i class="fas fa-shield-alt"></i>مقدمة في الامن السيبراني</h1>

<div class="container">
    <!-- مقدمة عامة -->
    <section>
        <h2 class="section-title"><i class="fas fa-info-circle"></i> ما هو الأمن السيبراني؟</h2>
        <div class="card">
            <div class="card-header"><i class="fas fa-book"></i> تعريف الأمن السيبراني</div>
            <p class="paragraph">الأمن السيبراني هو حماية الأنظمة المعلوماتية مثل أجهزة الكمبيوتر، الشبكات، البرمجيات، والبيانات من الهجمات أو التهديدات التي تأتي عبر الإنترنت. يتعلق الأمر بالوقاية من الوصول غير المصرح به أو التدمير أو التغيير غير المسموح به للمعلومات أو الأنظمة.</p>
            
            <div class="note">
                وفقًا لتقرير (ISC)² لعام 2023، هناك نقص يقدر بـ 3.4 مليون متخصص في الأمن السيبراني عالميًا، مما يجعله من أكثر المجالات طلبًا في سوق العمل.
            </div>
            
            <p class="paragraph">يشمل الأمن السيبراني عدة مجالات رئيسية:</p>
            <ul class="custom-ul advantages">
                <li class="custom-li">أمن الشبكات (Network Security)</li>
                <li class="custom-li">أمن التطبيقات (Application Security)</li>
                <li class="custom-li">أمن المعلومات (Information Security)</li>
                <li class="custom-li">الأمن التشغيلي (Operational Security)</li>
                <li class="custom-li">استمرارية الأعمال والتعافي من الكوارث (Disaster Recovery)</li>
            </ul>
            
            <a href="https://mawdoo3.com/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_%D8%A7%D9%84%D8%A3%D9%85%D9%86_%D8%A7%D9%84%D8%B3%D9%8A%D8%A8%D8%B1%D8%A7%D9%86%D9%8A" class="button">
                <i class="fas fa-external-link-alt"></i> تعرف أكثر
            </a>
        </div>
    </section>

    <!-- إحصائيات -->
    <section>
        <h2 class="section-title"><i class="fas fa-chart-bar"></i> إحصائيات وأرقام</h2>
        <div class="statistics">
            <div class="stat-card">
                <i class="fas fa-clock" style="font-size: 2rem; color: var(--secondary-color);"></i>
                <div class="stat-number">كل 39 ثانية</div>
                <div class="stat-label">هجوم إلكتروني يحدث في العالم</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-dollar-sign" style="font-size: 2rem; color: var(--secondary-color);"></i>
                <div class="stat-number">6 تريليون</div>
                <div class="stat-label">دولار خسائر الجرائم الإلكترونية سنوياً</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-user-secret" style="font-size: 2rem; color: var(--secondary-color);"></i>
                <div class="stat-number">95%</div>
                <div class="stat-label">من الاختراقات سببها خطأ بشري</div>
            </div>
        </div>
    </section>

    <!-- أهداف الأمن السيبراني -->
    <section>
        <h2 class="section-title"><i class="fas fa-bullseye"></i> أهداف الأمن السيبراني</h2>
        <div class="card">
            <div class="card-header"><i class="fas fa-chess-board"></i> مثلث الأمان (CIA Triad)</div>
            <p class="paragraph">يعتمد الأمن السيبراني على ثلاث ركائز أساسية تعرف بمثلث الأمان (CIA Triad):</p>
            <ul class="custom-ul advantages">
                <li class="custom-li"><strong>السرية (Confidentiality):</strong> ضمان أن المعلومات لا يتم الكشف عنها إلا للأشخاص المصرح لهم. يتم تحقيق ذلك من خلال التشفير، التحكم في الوصول، والتوثيق القوي.</li>
                <li class="custom-li"><strong>التكامل (Integrity):</strong> التأكد من أن البيانات لا يتم تعديلها أو تلاعب بها من قبل أطراف غير مصرح لها. يتم تحقيق ذلك من خلال التوقيعات الرقمية، وظائف التجزئة (Hashing)، وآليات التحكم في التغييرات.</li>
                <li class="custom-li"><strong>التوافر (Availability):</strong> ضمان أن الأنظمة والبيانات متاحة للمستخدمين المصرح لهم بها عند الحاجة. يتم تحقيق ذلك من خلال أنظمة النسخ الاحتياطي، موازنة الأحمال، والحماية من هجمات حجب الخدمة (DDoS).</li>
            </ul>
            
            <div class="example-box">
                <h4><i class="fas fa-lightbulb"></i> مثال تطبيقي:</h4>
                <p>في نظام البنوك الإلكترونية:</p>
                <ul>
                    <li><strong>السرية:</strong> تشفير بيانات العملاء وكلمات المرور</li>
                    <li><strong>التكامل:</strong> التأكد من أن تحويل الأموال لا يتم تغييره أثناء النقل</li>
                    <li><strong>التوافر:</strong> ضمان أن النظام يعمل 24/7 للعملاء</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- التهديدات الأمنية -->
    <section>
        <h2 class="section-title"><i class="fas fa-bug"></i> أنواع التهديدات الأمنية</h2>
        <div class="card">
            <div class="card-header"><i class="fas fa-shield-virus"></i> التهديدات السيبرانية الشائعة</div>
            <p class="paragraph">يواجه الأمن السيبراني تهديدات متطورة باستمرار، ومن أهمها:</p>
            
            <div class="timeline">
                <div class="timeline-item left">
                    <div class="timeline-content">
                        <h4><i class="fas fa-virus"></i> البرمجيات الخبيثة (Malware)</h4>
                        <p>برامج تهدف إلى إلحاق الضرر بأنظمة الحاسوب، وتشمل:</p>
                        <ul>
                            <li>الفيروسات (Viruses)</li>
                            <li>ديدان الحاسوب (Worms)</li>
                            <li>أحصنة طروادة (Trojans)</li>
                            <li>برامج التجسس (Spyware)</li>
                        </ul>
                    </div>
                </div>
                <div class="timeline-item right">
                    <div class="timeline-content">
                        <h4><i class="fas fa-fishing-rod"></i> التصيد الاحتيالي (Phishing)</h4>
                        <p>محاولات خداع للحصول على بيانات المستخدمين من خلال رسائل أو مواقع زائفة تبدو ككيانات موثوقة.</p>
                    </div>
                </div>
                <div class="timeline-item left">
                    <div class="timeline-content">
                        <h4><i class="fas fa-lock"></i> برامج الفدية (Ransomware)</h4>
                        <p>برامج تقوم بتشفير بيانات الضحية وطلب فدية مقابل استعادتها. مثل هجمات WannaCry الشهيرة التي أثرت على أكثر من 200,000 جهاز في 150 دولة.</p>
                    </div>
                </div>
                <div class="timeline-item right">
                    <div class="timeline-content">
                        <h4><i class="fas fa-network-wired"></i> هجمات حجب الخدمة (DDoS)</h4>
                        <p>هجمات تستهدف تعطيل الخدمة عن طريق إغراق النظام بالكثير من الطلبات من مصادر متعددة.</p>
                    </div>
                </div>
            </div>
            
            <div class="warning">
                وفقًا لتقرير Verizon 2023، 74% من خروقات البيانات تنطوي على عنصر بشري، سواء كان خطأ أو سوء استخدام أو اختراق اجتماعي.
            </div>
        </div>
    </section>

    <!-- أهمية الأمن السيبراني -->
    <section>
        <h2 class="section-title"><i class="fas fa-exclamation-triangle"></i> أهمية الأمن السيبراني</h2>
        <div class="card">
            <div class="card-header"><i class="fas fa-shield-alt"></i> لماذا يعتبر الأمن السيبراني حاسمًا اليوم؟</div>
            <p class="paragraph">مع التوسع الكبير في الاعتماد على التكنولوجيا والإنترنت في جميع جوانب الحياة، أصبح الأمن السيبراني ضرورة لا غنى عنها:</p>
            
            <ul class="custom-ul advantages">
                <li class="custom-li"><strong>حماية البيانات الشخصية:</strong> مع تزايد الخدمات الإلكترونية، تزداد كمية البيانات الشخصية المخزنة رقميًا والتي تحتاج حماية.</li>
                <li class="custom-li"><strong>الأمن المالي:</strong> تحمي الأنظمة المصرفية الإلكترونية والمعاملات المالية من الاختراق.</li>
                <li class="custom-li"><strong>الأمن القومي:</strong> حماية البنية التحتية الحيوية مثل محطات الطاقة، أنظمة المياه، والمرافق الصحية من الهجمات الإلكترونية.</li>
                <li class="custom-li"><strong>سمعة المؤسسات:</strong> خرق البيانات يمكن أن يدمر سمعة الشركات ويؤدي إلى خسائر مالية كبيرة.</li>
                <li class="custom-li"><strong>الامتثال القانوني:</strong> العديد من القوانين مثل GDPR تفرض معايير صارمة لحماية البيانات.</li>
            </ul>
            
            <img src="https://www.imperva.com/learn/wp-content/uploads/sites/13/2019/01/cyber-security-career-path.jpg" alt="مجالات الأمن السيبراني" class="img-tool">
        </div>
    </section>

    <!-- ممارسات الأمان الأساسية -->
    <section>
        <h2 class="section-title"><i class="fas fa-check-circle"></i> أفضل ممارسات الأمن السيبراني</h2>
        <div class="card">
            <div class="card-header"><i class="fas fa-user-shield"></i> نصائح أمان للجميع</div>
            
            <div class="note">
                <strong>قاعدة 3-2-1 للنسخ الاحتياطي:</strong>
                <p>احتفظ بـ 3 نسخ من بياناتك، على 2 نوعين مختلفين من الوسائط، مع 1 نسخة خارج الموقع.</p>
            </div>
            
            <ul class="custom-ul advantages">
                <li class="custom-li"><strong>كلمات مرور قوية:</strong> استخدم كلمات مرور معقدة (12 حرفًا على الأقل) مع تفعيل المصادقة الثنائية (2FA). استخدم مدير كلمات المرور مثل LastPass أو Bitwarden.</li>
                
                <li class="custom-li"><strong>تحديث البرمجيات:</strong> حافظ على تحديث أنظمة التشغيل والتطبيقات لإغلاق الثغرات الأمنية.</li>
                
                <li class="custom-li"><strong>النسخ الاحتياطي:</strong> أنشئ نسخًا احتياطية منتظمة للبيانات المهمة واختبرها دوريًا.</li>
                
                <li class="custom-li"><strong>التوعية الأمنية:</strong> تدريب الموظفين على التعرف على محاولات التصيد والهندسة الاجتماعية.</li>
                
                <li class="custom-li"><strong>أمان الشبكات:</strong> استخدم شبكات VPN عند الاتصال بشبكات عامة، وقم بتقسيم الشبكات (Network Segmentation).</li>
                
                <li class="custom-li"><strong>مراقبة الأنظمة:</strong> تطبيق أنظمة SIEM لاكتشاف الأنشطة المشبوهة في الوقت المناسب.</li>
            </ul>
            
            <div class="example-box">
                <h4><i class="fas fa-code"></i> مثال على كلمة مرور قوية:</h4>
                <pre># ضعيف: password123
# قوي: J4v@_Scr1pt-Is_Aw3s0m3!</pre>
            </div>
        </div>
    </section>

    <!-- مستقبل الأمن السيبراني -->
    <section>
        <h2 class="section-title"><i class="fas fa-robot"></i> مستقبل الأمن السيبراني</h2>
        <div class="card">
            <div class="card-header"><i class="fas fa-crystal-ball"></i> اتجاهات وتحديات مستقبلية</div>
            <p class="paragraph">يتطور مجال الأمن السيبراني بسرعة لمواكبة التهديدات الجديدة:</p>
            
            <ul class="custom-ul advantages">
                <li class="custom-li"><strong>الذكاء الاصطناعي:</strong> استخدام AI لاكتشاف التهديدات والاستجابة لها تلقائيًا، ولكن أيضًا استخدامه من قبل المهاجمين لإنشاء هجمات أكثر ذكاءً.</li>
                
                <li class="custom-li"><strong>إنترنت الأشياء (IoT):</strong> مع تزايد الأجهزة المتصلة، تزداد نقاط الاختراق المحتملة التي تحتاج حماية.</li>
                
                <li class="custom-li"><strong>الحوسبة الكمية:</strong> قد تكسر الحواسيب الكمية أنظمة التشفير الحالية، مما يتطلب تطوير خوارزميات تشفير كمومية.</li>
                
                <li class="custom-li"><strong>الخصوصية:</strong> مع تزايد الوعي بالخصوصية، تزداد الحاجة إلى حلول تحمي البيانات مع الحفاظ على الخصوصية.</li>
                
                <li class="custom-li"><strong>التشريعات:</strong> تزايد القوانين واللوائح الخاصة بحماية البيانات على مستوى العالم.</li>
            </ul>
            
            <img src="https://www.simplilearn.com/ice9/free_resources_article_thumb/Cyber_Security_Technologies.jpg" alt="تقنيات الأمن السيبراني المستقبلية" class="img-tool">
        </div>
    </section>

    <!-- خاتمة -->
    <section>
        <h2 class="section-title"><i class="fas fa-graduation-cap"></i> خاتمة وبداية الرحلة</h2>
        <div class="card">
            <div class="card-header"><i class="fas fa-road"></i> رحلة التعلم في الأمن السيبراني</div>
            <p class="paragraph">الأمن السيبراني ليس وجهة بل رحلة مستمرة من التعلم والتكيف. مع تطور التكنولوجيا، تتطور التهديدات أيضًا، مما يتطلب تحديثًا مستمرًا للمعرفة والمهارات.</p>
            
            <p class="paragraph">لبدء رحلتك في الأمن السيبراني:</p>
            <ol style="font-size: 1.1rem; color: var(--text-light); padding-right: 20px;">
                <li style="padding: 8px 0;">تعلم الأساسيات: الشبكات، أنظمة التشغيل، البرمجة</li>
                <li style="padding: 8px 0;">احصل على شهادات ابتدائية مثل CompTIA Security+</li>
                <li style="padding: 8px 0;">جرب أدوات الأمن مثل Wireshark، Nmap، Metasploit</li>
                <li style="padding: 8px 0;">شارك في تحديات الأمن السيبراني مثل CTF (Capture The Flag)</li>
                <li style="padding: 8px 0;">تابع آخر الأخبار والتطورات في المجال</li>
            </ol>
        
        </div>
    </section>
</div>

<script>
    // Simple animation on scroll
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.card');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = 1;
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, {threshold: 0.1});
        
        cards.forEach(card => {
            card.style.opacity = 0;
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.6s ease';
            observer.observe(card);
        });
    });
</script>
</body>
</html>