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
    <meta name="description" content="دليل شامل لأمن الشبكات - المفاهيم الأساسية، التهديدات، واستراتيجيات الحماية">
    <meta name="keywords" content="أمن الشبكات, الأمن السيبراني, حماية الشبكات, جرائم المعلومات, تأمين الشبكات">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>أمن الشبكات - دليل شامل</title>
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
            content: "أمر";
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
            content: "\f054";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            right: 0;
            color: var(--secondary-color);
        }

        .card a {
            color: var(--secondary-color);
            font-size: 1.1rem;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }

        .card a:hover {
            color: var(--primary-color);
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

        .matrix-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
            margin: 30px 0;
        }

        .matrix-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border-left: 4px solid var(--accent-color);
        }

        .matrix-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
        }

        .matrix-title {
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        .matrix-desc {
            font-size: 0.9rem;
            color: var(--text-light);
        }

        .subsection-title {
            font-size: 1.4rem;
            color: var(--dark-color);
            margin: 20px 0 15px;
            font-weight: 700;
            position: relative;
            padding-right: 15px;
        }

        .subsection-title::before {
            content: "";
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 8px;
            height: 8px;
            background-color: var(--accent-color);
            border-radius: 50%;
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

<h1 class="title"><i class="fas fa-shield-alt"></i> مقدمة في أمن الشبكات</h1>

<div class="container">
    <!-- مقدمة عن أمن الشبكات -->
    <section>
        <h2 class="section-title"><i class="fas fa-network-wired"></i> مقدمة</h2>
        <div class="card">
            <div class="card-header"><i class="fas fa-history"></i> التطور التاريخي للشبكات</div>
            <p class="paragraph">إن فكرة نقل المعلومات وتبادلها عبر الشبكات ليست جديدة، بل هي موجودة منذ العصور القديمة، بدءًا من شبكات البريد، وشبكات توزيع الكتب والصحف، وصولًا إلى الشبكات الحديثة. في القرن التاسع عشر، تمكن الإنسان من نقل المعلومات سلكيًا ثم لا سلكيًا، مما ساهم في تطور الأنظمة الهاتفية وإمكانية التخاطب عبر مسافات بعيدة.</p>
            
            <div class="note">
                وفقًا لتقرير Cybersecurity Ventures، من المتوقع أن تصل الخسائر العالمية بسبب الجرائم الإلكترونية إلى 10.5 تريليون دولار سنويًا بحلول عام 2025.
            </div>
            
            <img src="" alt="أمن الشبكات" class="img-tool">
            
            <div class="example-box">
                <h4><i class="fas fa-lightbulb"></i> أهمية أمن الشبكات:</h4>
                <ul class="advantages">
                    <li class="custom-li">حماية البيانات الحساسة من الوصول غير المصرح به</li>
                    <li class="custom-li">ضمان استمرارية الأعمال وتجنب التكاليف الباهظة للاختراقات</li>
                    <li class="custom-li">الحفاظ على سمعة المؤسسة وثقة العملاء</li>
                    <li class="custom-li">الامتثال للوائح والقوانين الخاصة بحماية البيانات</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- تعريف الشبكات وأمنها -->
    <section>
        <h2 class="section-title"><i class="fas fa-book"></i> التعريفات الأساسية</h2>
        
        <!-- تعريف الشبكات -->
        <div class="card">
            <div class="card-header"><i class="fas fa-sitemap"></i> تعريف الشبكات</div>
            <p class="paragraph">يقصد بالشبكات نظامًا معينًا لربط جهازين حاسوب أو أكثر باستخدام إحدى تقنيات الاتصالات، بهدف تبادل المعلومات والبيانات، بالإضافة إلى مشاركة الموارد مثل الطابعات والتطبيقات البرمجية. كما أنها تسهل التواصل بين المستخدمين وتبادل المعلومات بشكل آمن وفعال.</p>
            
            <div class="subsection-title">أنواع الشبكات:</div>
            <div class="matrix-grid">
                <div class="matrix-item">
                    <div class="matrix-title"><i class="fas fa-home"></i> LAN</div>
                    <div class="matrix-desc">شبكة محلية تغطي منطقة صغيرة مثل منزل أو مكتب</div>
                </div>
                <div class="matrix-item">
                    <div class="matrix-title"><i class="fas fa-city"></i> MAN</div>
                    <div class="matrix-desc">شبكة حضرية تغطي مدينة أو منطقة حضرية</div>
                </div>
                <div class="matrix-item">
                    <div class="matrix-title"><i class="fas fa-globe"></i> WAN</div>
                    <div class="matrix-desc">شبكة واسعة تربط بين شبكات محلية عبر مسافات بعيدة</div>
                </div>
                <div class="matrix-item">
                    <div class="matrix-title"><i class="fas fa-wifi"></i> WLAN</div>
                    <div class="matrix-desc">شبكة محلية لاسلكية توفر اتصالاً لاسلكيًا بالأجهزة</div>
                </div>
            </div>
        </div>
        
        <!-- تعريف أمن الشبكات -->
        <div class="card">
            <div class="card-header"><i class="fas fa-lock"></i> تعريف أمن شبكات المعلومات</div>
            <p class="paragraph">أمن شبكات المعلومات هو مجموعة من الإجراءات والأنظمة التي تهدف إلى توفير الحماية القصوى للمعلومات والبيانات في الشبكات، وذلك من خلال تأمينها ضد المخاطر الداخلية والخارجية عبر أدوات وتقنيات حديثة مثل التشفير، أنظمة كشف التسلل، وجدران الحماية.</p>
            
            <div class="subsection-title">مكونات أمن الشبكات:</div>
            <div class="statistics">
                <div class="stat-card">
                    <i class="fas fa-user-secret" style="font-size: 2rem; color: var(--secondary-color);"></i>
                    <div class="stat-number">السرية</div>
                    <div class="stat-label">حماية البيانات من الوصول غير المصرح به</div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-check-circle" style="font-size: 2rem; color: var(--secondary-color);"></i>
                    <div class="stat-number">التكامل</div>
                    <div class="stat-label">ضمان عدم تعديل البيانات أثناء النقل</div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-unlock-alt" style="font-size: 2rem; color: var(--secondary-color);"></i>
                    <div class="stat-number">التوافر</div>
                    <div class="stat-label">ضمان وصول المستخدمين المصرح لهم عند الحاجة</div>
                </div>
            </div>
        </div>
    </section>

    <!-- جرائم المعلومات -->
    <section>
        <h2 class="section-title"><i class="fas fa-gavel"></i> جرائم المعلومات</h2>
        <div class="card">
            <div class="card-header"><i class="fas fa-exclamation-triangle"></i> تصنيف جرائم المعلومات</div>
            <p class="paragraph">تتنوع جرائم المعلومات وتتطور باستمرار مع تطور التقنيات. فيما يلي بعض أنواع الجرائم المعلوماتية الأكثر شيوعًا:</p>
            
            <div class="timeline">
                <div class="timeline-item left">
                    <div class="timeline-content">
                        <h4><i class="fas fa-file-alt"></i> نشر المعلومات السرية</h4>
                        <p>اختراق الشبكات وتسريب بيانات حساسة مثل المعلومات الشخصية أو الأسرار التجارية</p>
                        <ul>
                            <li>اختراق قواعد البيانات</li>
                            <li>تصوير وثائق سرية</li>
                            <li>تسجيل المحادثات السرية</li>
                        </ul>
                    </div>
                </div>
                <div class="timeline-item right">
                    <div class="timeline-content">
                        <h4><i class="fas fa-bullhorn"></i> الترويج للإشاعات</h4>
                        <p>نشر معلومات مغلوطة تؤدي إلى زعزعة الأمن والاستقرار</p>
                        <ul>
                            <li>إنشاء أخبار مزيفة</li>
                            <li>التلاعب بالصور والفيديوهات</li>
                            <li>نشر معلومات مضللة على وسائل التواصل</li>
                        </ul>
                    </div>
                </div>
                <div class="timeline-item left">
                    <div class="timeline-content">
                        <h4><i class="fas fa-edit"></i> التزوير الإلكتروني</h4>
                        <p>استخدام التكنولوجيا في تزوير البيانات والمعاملات المالية</p>
                        <ul>
                            <li>تزوير المستندات الرقمية</li>
                            <li>انتحال الشخصيات</li>
                            <li>تزوير البطاقات الائتمانية</li>
                        </ul>
                    </div>
                </div>
                <div class="timeline-item right">
                    <div class="timeline-content">
                        <h4><i class="fas fa-skull"></i> القرصنة</h4>
                        <p>الاستيلاء غير المشروع على البرمجيات وبيعها بدون ترخيص</p>
                        <ul>
                            <li>اختراق أنظمة الحماية</li>
                            <li>توزيع برامج مقرصنة</li>
                            <li>بيع بيانات مسروقة</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="warning">
                <strong>تحذير:</strong> وفقًا لتقرير FBI 2023، زادت الجرائم الإلكترونية بنسبة 300% منذ بداية جائحة كورونا، مع خسائر تقدر بمليارات الدولارات.
            </div>
        </div>
    </section>

    <!-- طرق تأمين الشبكات -->
    <section>
        <h2 class="section-title"><i class="fas fa-user-shield"></i> طرق تأمين الشبكات</h2>
        <div class="card">
            <div class="card-header"><i class="fas fa-tools"></i> استراتيجيات حماية الشبكات</div>
            <p class="paragraph">توجد العديد من الطرق والتقنيات المستخدمة لتأمين الشبكات وحمايتها من التهديدات المختلفة. فيما يلي أهم هذه الطرق:</p>
            
            <div class="matrix-grid">
                <div class="matrix-item">
                    <div class="matrix-title"><i class="fas fa-user-lock"></i> التحكم في الوصول (NAC)</div>
                    <div class="matrix-desc">فرض سياسات أمان تمنع الوصول غير المصرح به للشبكة</div>
                </div>
                <div class="matrix-item">
                    <div class="matrix-title"><i class="fas fa-virus"></i> برامج مكافحة الفيروسات</div>
                    <div class="matrix-desc">الحماية من البرمجيات الخبيثة والفيروسات</div>
                </div>
                <div class="matrix-item">
                    <div class="matrix-title"><i class="fas fa-fire"></i> جدران الحماية</div>
                    <div class="matrix-desc">منع الاتصالات غير المصرح بها من وإلى الشبكة</div>
                </div>
                <div class="matrix-item">
                    <div class="matrix-title"><i class="fas fa-lock"></i> VPN</div>
                    <div class="matrix-desc">تأمين الاتصال بالشبكة عبر الإنترنت باستخدام تشفير قوي</div>
                </div>
                <div class="matrix-item">
                    <div class="matrix-title"><i class="fas fa-desktop"></i> اكتشاف نقاط النهاية (EDR)</div>
                    <div class="matrix-desc">مراقبة وتحليل سلوك الأجهزة لاكتشاف الأنشطة المشبوهة</div>
                </div>
                <div class="matrix-item">
                    <div class="matrix-title"><i class="fas fa-shield-virus"></i> Zero Trust</div>
                    <div class="matrix-desc">نموذج يعتمد على عدم الثقة المطلقة بأي جهاز أو مستخدم</div>
                </div>
            </div>
            
            <div class="subsection-title">أمثلة عملية:</div>
            <pre>
# مثال على تكوين جدار حماية باستخدام iptables في لينكس
iptables -A INPUT -p tcp --dport 22 -j ACCEPT  # السماح بـ SSH
iptables -A INPUT -p tcp --dport 80 -j ACCEPT  # السماح بـ HTTP
iptables -A INPUT -p tcp --dport 443 -j ACCEPT # السماح بـ HTTPS
iptables -A INPUT -j DROP # منع كل شيء آخر
            </pre>
            
            <div class="note">
                <strong>ملاحظة:</strong> وفقًا لتقرير Gartner 2023، 60% من المؤسسات الكبرى ستتبنى نموذج Zero Trust بحلول عام 2025 لتعزيز أمان شبكاتها.
            </div>
        </div>
    </section>

    <!-- أدوات حماية الشبكات -->
    <section>
        <h2 class="section-title"><i class="fas fa-toolbox"></i> أدوات حماية الشبكات</h2>
        <div class="card">
            <div class="card-header"><i class="fas fa-shield-alt"></i> أهم الأدوات المستخدمة في أمن الشبكات</div>
            <p class="paragraph">يستخدم خبراء أمن الشبكات مجموعة متنوعة من الأدوات لحماية الشبكات وتحليل التهديدات. فيما يلي بعض الأدوات الأساسية:</p>
            
            <div class="timeline">
                <div class="timeline-item left">
                    <div class="timeline-content">
                        <h4><i class="fas fa-search"></i> Wireshark</h4>
                        <p>أداة لتحليل البيانات المتدفقة عبر الشبكة</p>
                        <ul>
                            <li>تحليل حزم البيانات</li>
                            <li>كشف الأنشطة المشبوهة</li>
                            <li>تشخيص مشكلات الشبكة</li>
                        </ul>
                    </div>
                </div>
                <div class="timeline-item right">
                    <div class="timeline-content">
                        <h4><i class="fas fa-bug"></i> Metasploit</h4>
                        <p>منصة لاختبار الاختراق وتقييم الأمان</p>
                        <ul>
                            <li>اكتشاف الثغرات</li>
                            <li>محاكاة الهجمات</li>
                            <li>تقييم إجراءات الحماية</li>
                        </ul>
                    </div>
                </div>
                <div class="timeline-item left">
                    <div class="timeline-content">
                        <h4><i class="fas fa-dragon"></i> Snort</h4>
                        <p>نظام كشف ومنع عمليات التسلل</p>
                        <ul>
                            <li>مراقبة حركة الشبكة</li>
                            <li>تحليل الأنماط المشبوهة</li>
                            <li>منع الهجمات المعروفة</li>
                        </ul>
                    </div>
                </div>
                <div class="timeline-item right">
                    <div class="timeline-content">
                        <h4><i class="fas fa-chart-bar"></i> SIEM</h4>
                        <p>أنظمة إدارة الأحداث الأمنية</p>
                        <ul>
                            <li>تجميع وتحليل السجلات</li>
                            <li>كشف التهديدات</li>
                            <li>الاستجابة للحوادث</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="example-box">
                <h4><i class="fas fa-terminal"></i> مثال على استخدام Wireshark:</h4>
                <p>لتصفية حركة HTTP في Wireshark:</p>
                <pre>http</pre>
                <p>لتصفية حركة من عنوان IP معين:</p>
                <pre>ip.addr == 192.168.1.100</pre>
            </div>
        </div>
    </section>

    <!-- الخاتمة -->
    <section>
        <h2 class="section-title"><i class="fas fa-check-circle"></i> الخاتمة</h2>
        <div class="card">
            <div class="card-header"><i class="fas fa-graduation-cap"></i> مستقبل أمن الشبكات</div>
            <p class="paragraph">يعد أمن الشبكات ركيزة أساسية في الحفاظ على البيانات وحماية البنية التحتية الرقمية. مع تزايد التهديدات وتعقيدها، أصبح من الضروري تبني استراتيجيات أمنية متقدمة تشمل:</p>
            
            <ul class="advantages">
                <li class="custom-li">التعلم الآلي والذكاء الاصطناعي لاكتشاف التهديدات</li>
                <li class="custom-li">التحول الكامل إلى نموذج Zero Trust</li>
                <li class="custom-li">تعزيز الوعي الأمني لدى الموظفين</li>
                <li class="custom-li">تبني تقنيات التشفير المتقدمة</li>
                <li class="custom-li">التكامل بين أدوات الأمان المختلفة</li>
            </ul>
            

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