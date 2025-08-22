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
    <meta name="description" content="تعرف على مسابقات الـ CTF (Capture The Flag) في الأمن السيبراني">
    <meta name="keywords" content="CTF, أمن سيبراني, مسابقات قرصنة, اختبار الاختراق">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>ما هو الـ CTF؟ - دليل شامل</title>
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

        .ctf-types {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin: 30px 0;
        }

        .ctf-type-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            width: 300px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border-top: 4px solid var(--accent-color);
            text-align: center;
        }

        .ctf-type-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .ctf-type-icon {
            font-size: 3rem;
            color: var(--secondary-color);
            margin-bottom: 20px;
        }

        .ctf-type-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--dark-color);
            margin-bottom: 15px;
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

<h1 class="title"><i class="fas fa-flag"></i> ما هو الـ CTF؟</h1>

<div class="container">
    <!-- مقدمة عن الـ CTF -->
    <section>
        <h2 class="section-title"><i class="fas fa-info-circle"></i> مقدمة عن مسابقات الـ CTF</h2>
        <div class="card">
            <div class="card-header"><i class="fas fa-flag-checkered"></i> تعريف الـ CTF</div>
            <p class="paragraph">مسابقات Capture The Flag (CTF) هي تحديات أمن سيبراني تتنافس فيها الفرق أو الأفراد لإيجاد "الأعلام" المخبأة في أنظمة أو تطبيقات مصممة خصيصًا لهذا الغرض. هذه الأعلام عادة ما تكون سلاسل نصية معينة أو ملفات مخفية يجب اكتشافها باستخدام مهارات القرصنة الأخلاقية.</p>
            
            <div class="note">
                <strong>أصل التسمية:</strong> الفكرة مستوحاة من لعبة "Capture The Flag" التقليدية حيث يحاول الفريق سرقة علم الفريق المنافس، لكن في النسخة السيبرانية يكون "العلم" عبارة عن بيانات أو معلومات مخفية.
            </div>
            
            
            <div class="statistics">
                <div class="stat-card">
                    <i class="fas fa-users" style="font-size: 2rem; color: var(--secondary-color);"></i>
                    <div class="stat-number">85%</div>
                    <div class="stat-label">من محترفي الأمن شاركوا في CTF</div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-calendar" style="font-size: 2rem; color: var(--secondary-color);"></i>
                    <div class="stat-number">200+</div>
                    <div class="stat-label">مسابقة CTF سنويًا حول العالم</div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-university" style="font-size: 2rem; color: var(--secondary-color);"></i>
                    <div class="stat-number">70%</div>
                    <div class="stat-label">من جامعات التقنية تقدم مسابقات CTF</div>
                </div>
            </div>
        </div>
    </section>

    <!-- أنواع مسابقات الـ CTF -->
    <section>
        <h2 class="section-title"><i class="fas fa-chess-queen"></i> أنواع مسابقات الـ CTF</h2>
        <div class="card">
            <div class="card-header"><i class="fas fa-tags"></i> التصنيفات الرئيسية</div>
            <p class="paragraph">توجد عدة أنواع رئيسية لمسابقات الـ CTF، كل منها يركز على مهارات مختلفة في الأمن السيبراني:</p>
            
            <div class="ctf-types">
                <div class="ctf-type-card">
                    <div class="ctf-type-icon"><i class="fas fa-lock"></i></div>
                    <div class="ctf-type-title">Jeopardy Style</div>
                    <p>تحديات متنوعة في فئات مختلفة مثل التشفير، الهندسة العكسية، اختبار الاختراق وغيرها</p>
                </div>
                
                <div class="ctf-type-card">
                    <div class="ctf-type-icon"><i class="fas fa-shield-alt"></i></div>
                    <div class="ctf-type-title">هجوم/دفاع</div>
                    <p>فرق تهاجم أنظمة الفرق الأخرى بينما تدافع عن أنظمتها الخاصة</p>
                </div>
                
                <div class="ctf-type-card">
                    <div class="ctf-type-icon"><i class="fas fa-running"></i></div>
                    <div class="ctf-type-title">المسابقات المختلطة</div>
                    <p>تجمع بين نمط Jeopardy وهجوم/دفاع في مسابقة واحدة</p>
                </div>
            </div>
            
            <div class="example-box">
                <h4><i class="fas fa-trophy"></i> أشهر مسابقات CTF عالميًا:</h4>
                <ul>
                    <li>DEF CON CTF - أقدم وأصعب مسابقة CTF</li>
                    <li>PicoCTF - للمبتدئين والطلاب</li>
                    <li>Hack The Box Challenges</li>
                    <li>CTFTime.org - تجمع العديد من المسابقات</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- فئات التحديات -->
    <section>
        <h2 class="section-title"><i class="fas fa-puzzle-piece"></i> فئات تحديات الـ CTF</h2>
        <div class="card">
            <div class="card-header"><i class="fas fa-layer-group"></i> التصنيفات التقنية</div>
            <p class="paragraph">تتنوع تحديات الـ CTF لتغطي مختلف مجالات الأمن السيبراني:</p>
            
            <div class="timeline">
                <div class="timeline-item left">
                    <div class="timeline-content">
                        <h4><i class="fas fa-lock"></i> التشفير (Cryptography)</h4>
                        <p>كسر أنظمة التشفير أو فك تشفير الرسائل</p>
                        <p><strong>مثال:</strong> كسر تشفير RSA ضعيف التكوين</p>
                    </div>
                </div>
                <div class="timeline-item right">
                    <div class="timeline-content">
                        <h4><i class="fas fa-bug"></i> اختبار الاختراق (Web Exploitation)</h4>
                        <p>استغلال ثغرات الويب مثل SQLi أو XSS</p>
                        <p><strong>مثال:</strong> استغلال ثغرة حقن SQL لسرقة البيانات</p>
                    </div>
                </div>
                <div class="timeline-item left">
                    <div class="timeline-content">
                        <h4><i class="fas fa-microscope"></i> الهندسة العكسية (Reverse Engineering)</h4>
                        <p>تحليل البرامج لفهم آلية عملها</p>
                        <p><strong>مثال:</strong> عكس برنامج لاستخراج كلمة المرور</p>
                    </div>
                </div>
                <div class="timeline-item right">
                    <div class="timeline-content">
                        <h4><i class="fas fa-memory"></i> التخزين المؤقت (Forensics)</h4>
                        <p>تحليل الملفات أو الذواكر لاستخراج المعلومات</p>
                        <p><strong>مثال:</strong> استخراج صورة مخفية من ملف PCAP</p>
                    </div>
                </div>
                <div class="timeline-item left">
                    <div class="timeline-content">
                        <h4><i class="fas fa-code"></i> البرمجة (Binary Exploitation)</h4>
                        <p>استغلال الثغرات في البرامج الثنائية</p>
                        <p><strong>مثال:</strong> استغلال تجاوز السعة المخزن (Buffer Overflow)</p>
                    </div>
                </div>
            </div>
            
            <div class="note">
                <strong>ملاحظة:</strong> بعض المسابقات قد تحتوي على فئات إضافية مثل الشبكات (Networking)، الهواتف المحمولة (Mobile)، أو إنترنت الأشياء (IoT).
            </div>
        </div>
    </section>

    <!-- كيفية المشاركة -->
    <section>
        <h2 class="section-title"><i class="fas fa-user-ninja"></i> كيف تبدأ في مسابقات الـ CTF؟</h2>
        <div class="card">
            <div class="card-header"><i class="fas fa-road"></i> خطة البدء للمبتدئين</div>
            <p class="paragraph">لبدء رحلتك في عالم مسابقات الـ CTF، اتبع هذه الخطوات:</p>
            
            <ol style="font-size: 1.1rem; color: var(--text-light); padding-right: 20px;">
                <li style="padding: 8px 0;"><strong>تعلم الأساسيات:</strong> فهم مفاهيم الشبكات، أنظمة التشغيل، والبرمجة</li>
                <li style="padding: 8px 0;"><strong>اختر مسابقة للمبتدئين:</strong> مثل PicoCTF أو OverTheWire</li>
                <li style="padding: 8px 0;"><strong>استخدم الأدوات الأساسية:</strong> مثل Wireshark، Burp Suite، Ghidra</li>
                <li style="padding: 8px 0;"><strong>انضم إلى مجتمع CTF:</strong> تعلم من الخبراء وشارك في المناقشات</li>
                <li style="padding: 8px 0;"><strong>تدرب بانتظام:</strong> حل التحديات على منصات مثل Hack The Box أو TryHackMe</li>
            </ol>
            
            <div class="example-box">
                <h4><i class="fas fa-tools"></i> أدوات أساسية لمسابقات CTF:</h4>
                <ul>
                    <li><strong>لأبحاث الويب:</strong> Burp Suite, OWASP ZAP</li>
                    <li><strong>للتحليل الثنائي:</strong> Ghidra, IDA Pro, radare2</li>
                    <li><strong>للتشفير:</strong> CyberChef, John the Ripper</li>
                    <li><strong>للشبكات:</strong> Wireshark, tcpdump, nmap</li>
                </ul>
            </div>
            
            <pre>
# مثال استخدام nmap لمسح الهدف
nmap -sV -sC -oA scan_results 192.168.1.100
            </pre>
        </div>
    </section>

    <!-- فوائد المشاركة -->
    <section>
        <h2 class="section-title"><i class="fas fa-award"></i> فوائد المشاركة في مسابقات الـ CTF</h2>
        <div class="card">
            <div class="card-header"><i class="fas fa-star"></i> لماذا يجب أن تشارك؟</div>
            <p class="paragraph">المشاركة في مسابقات الـ CTF توفر العديد من الفوائد للمهتمين بالأمن السيبراني:</p>
            
            <div class="advantages">
                <ul class="custom-ul">
                    <li class="custom-li"><strong>تطوير مهارات عملية:</strong> تتعلم تقنيات حقيقية تستخدم في مجال الأمن السيبراني</li>
                    <li class="custom-li"><strong>بناء شبكة علاقات:</strong> التعرف على محترفين وفرق عمل في المجال</li>
                    <li class="custom-li"><strong>تحسين السيرة الذاتية:</strong> المشاركة في مسابقات معروفة يعزز فرصك الوظيفية</li>
                    <li class="custom-li"><strong>التحدي والإثارة:</strong> تجربة ممتعة تنمي التفكير الإبداعي وحل المشكلات</li>
                    <li class="custom-li"><strong>فرص وظيفية:</strong> العديد من الشركات تبحث عن متسابقين CTF لتعيينهم</li>
                </ul>
            </div>
            
            <div class="note">
                <strong>حقيقة:</strong> 40% من المتسابقين في مسابقات CTF الكبرى يحصلون على عروض عمل خلال أو بعد المسابقة (تقرير DEF CON 2023).
            </div>
            
        </div>
    </section>

    <!-- نصائح للمبتدئين -->
    <section>
        <h2 class="section-title"><i class="fas fa-lightbulb"></i> نصائح للمبتدئين في الـ CTF</h2>
        <div class="card">
            <div class="card-header"><i class="fas fa-hands-helping"></i> كيف تنجح في أول مسابقة لك؟</div>
            
            <div class="timeline">
                <div class="timeline-item left">
                    <div class="timeline-content">
                        <h4><i class="fas fa-search"></i> ابدأ بالتحديات السهلة</h4>
                        <p>لا تبدأ بالتحديات الصعبة، ابحث عن التحديات ذات النقاط الأقل</p>
                    </div>
                </div>
                <div class="timeline-item right">
                    <div class="timeline-content">
                        <h4><i class="fas fa-users"></i> انضم إلى فريق</h4>
                        <p>التعلم مع فريق أفضل من العمل الفردي خاصة للمبتدئين</p>
                    </div>
                </div>
                <div class="timeline-item left">
                    <div class="timeline-content">
                        <h4><i class="fas fa-book"></i> تعلم من الحلول</h4>
                        <p>إذا لم تستطع حل تحدي، ابحث عن write-ups لتفهم الحل</p>
                    </div>
                </div>
                <div class="timeline-item right">
                    <div class="timeline-content">
                        <h4><i class="fas fa-history"></i> تدرب بانتظام</h4>
                        <p>المهارة تأتي مع الممارسة، خصص وقتًا أسبوعيًا للتدريب</p>
                    </div>
                </div>
                <div class="timeline-item left">
                    <div class="timeline-content">
                        <h4><i class="fas fa-sticky-note"></i> دوّن ملاحظاتك</h4>
                        <p>احتفظ بدفتر ملاحظات لتسجيل التقنيات والأدوات التي تتعلمها</p>
                    </div>
                </div>
            </div>
            
            <div class="warning">
                <strong></strong> لا تستخدم أدوات جاهزة دون فهم كيفية عملها، الهدف هو التعلم وليس فقط جمع النقاط!
            </div>
            
            <div style="text-align: center; margin-top: 30px;">

                <a href="http://localhost/CyberBox%20Gate/ctf/challenges.php" class="button" style="background-color: var(--accent-color); margin-right: 15px;">
                    <i class="fas fa-box-open"></i> ابدأ حل التحديات في موقع CyberBox Gate
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