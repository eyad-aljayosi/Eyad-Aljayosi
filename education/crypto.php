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
    <meta name="description" content="صفحة تعليمية شاملة عن أساسيات التشفير وأهميته في الأمن السيبراني">
    <meta name="keywords" content="التشفير, الأمن السيبراني, تشفير البيانات, AES, RSA">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>التشفير في الأمن السيبراني - دليل شامل</title>
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

<h1 class="title"><i class="fas fa-lock"></i> مقدمة في التشفير</h1>

<div class="container">
    <!-- مقدمة عن التشفير -->
    <section>
        <h2 class="section-title"><i class="fas fa-key"></i> ما هو التشفير؟</h2>
        <div class="card">
            <div class="card-header"><i class="fas fa-shield-alt"></i> تعريف التشفير</div>
            <p class="paragraph">التشفير هو عملية تحويل البيانات من صيغة قابلة للقراءة إلى صيغة مشفرة باستخدام خوارزميات رياضية معقدة لضمان أن البيانات تبقى سرية وآمنة، بحيث لا يمكن لأحد الاطلاع عليها إلا للأطراف المخولة بذلك.</p>
            
            <div class="note">
                وفقًا لتقرير (ISC)² لعام 2023، يتم تشفير أكثر من 87% من حركة البيانات على الإنترنت باستخدام تقنيات تشفير متقدمة مثل AES وRSA.
            </div>
            
            <p class="paragraph">يُعتبر التشفير من العناصر الأساسية في الأمن السيبراني حيث يتم استخدامه لحماية البيانات الحساسة مثل:</p>
            <ul class="custom-ul advantages">
                <li class="custom-li">كلمات المرور والمعلومات الشخصية</li>
                <li class="custom-li">المعاملات البنكية عبر الإنترنت</li>
                <li class="custom-li">الاتصالات السرية بين الحكومات</li>
                <li class="custom-li">بيانات الرعاية الصحية الحساسة</li>
            </ul>
            
        </div>
    </section>

    <!-- أهداف التشفير -->
    <section>
        <h2 class="section-title"><i class="fas fa-bullseye"></i> أهداف التشفير</h2>
        <div class="card">
            <div class="card-header"><i class="fas fa-chess-board"></i> الأهداف الأساسية للتشفير</div>
            <p class="paragraph">يهدف التشفير إلى تحقيق عدة أهداف أساسية في أمن المعلومات:</p>
            
            <div class="statistics">
                <div class="stat-card">
                    <i class="fas fa-user-secret" style="font-size: 2rem; color: var(--secondary-color);"></i>
                    <div class="stat-number">السرية</div>
                    <div class="stat-label">حماية البيانات من الوصول غير المصرح به</div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-check-circle" style="font-size: 2rem; color: var(--secondary-color);"></i>
                    <div class="stat-number">التكامل</div>
                    <div class="stat-label">ضمان عدم تعديل البيانات</div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-id-card" style="font-size: 2rem; color: var(--secondary-color);"></i>
                    <div class="stat-number">المصادقة</div>
                    <div class="stat-label">تأكيد هوية الأطراف المتواصلة</div>
                </div>
            </div>
            
            <div class="example-box">
                <h4><i class="fas fa-lightbulb"></i> مثال تطبيقي:</h4>
                <p>عند إرسال رسالة بريد إلكتروني مشفرة:</p>
                <ul>
                    <li><strong>السرية:</strong> لا يمكن قراءة المحتوى إلا من قبل المستقبل</li>
                    <li><strong>التكامل:</strong> التأكد من أن الرسالة لم تتغير أثناء النقل</li>
                    <li><strong>المصادقة:</strong> التأكد من أن المرسل هو الشخص الذي يدعيه</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- أنواع التشفير -->
    <section>
        <h2 class="section-title"><i class="fas fa-project-diagram"></i> أنواع خوارزميات التشفير</h2>
        <div class="card">
            <div class="card-header"><i class="fas fa-code-branch"></i> التشفير المتماثل وغير المتماثل</div>
            
            <div class="timeline">
                <div class="timeline-item left">
                    <div class="timeline-content">
                        <h4><i class="fas fa-key"></i> التشفير المتماثل</h4>
                        <p>يستخدم نفس المفتاح للتشفير وفك التشفير</p>
                        <ul>
                            <li>أسرع في الأداء</li>
                            <li>يتطلب تبادل آمن للمفاتيح</li>
                            <li>أمثلة: AES, DES, RC4</li>
                        </ul>
                    </div>
                </div>
                <div class="timeline-item right">
                    <div class="timeline-content">
                        <h4><i class="fas fa-key"></i><i class="fas fa-key" style="margin-right: 10px;"></i> التشفير غير المتماثل</h4>
                        <p>يستخدم مفتاحين: عام للتشفير وخاص لفك التشفير</p>
                        <ul>
                            <li>أكثر أمانًا</li>
                            <li>أبطأ في الأداء</li>
                            <li>أمثلة: RSA, ECC, ElGamal</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="note">
                <strong>الحل الأمثل:</strong> غالبًا ما تستخدم الأنظمة الحديثة كلا النوعين معًا، حيث يتم استخدام التشفير غير المتماثل لتبادل مفتاح التشفير المتماثل، ثم استخدام التشفير المتماثل لتشفير البيانات الفعلية.
            </div>
        </div>
    </section>

    <!-- خوارزميات التشفير المتماثل -->
    <section>
        <h2 class="section-title"><i class="fas fa-lock"></i> خوارزميات التشفير المتماثل</h2>
        <div class="card">
            <div class="card-header"><i class="fas fa-bolt"></i> AES (Advanced Encryption Standard)</div>
            <p class="paragraph">AES هو المعيار الفيدرالي الأمريكي للتشفير ويُعتبر من أقوى خوارزميات التشفير المتماثل وأكثرها استخدامًا لحماية البيانات. تم اعتماده من قبل الحكومة الأمريكية لحماية المعلومات السرية.</p>
            
            <div class="example-box">
                <h4><i class="fas fa-cog"></i> طريقة عمل AES:</h4>
                <ol>
                    <li><strong>SubBytes:</strong> استبدال كل بايت باستخدام جدول S-Box</li>
                    <li><strong>ShiftRows:</strong> تحريك صفوف البيانات لتعقيد النص المشفر</li>
                    <li><strong>MixColumns:</strong> خلط الأعمدة لتحسين الأمان</li>
                    <li><strong>AddRoundKey:</strong> دمج المفتاح في كل جولة للتشفير</li>
                </ol>
            </div>
            
            <pre>
// مثال بسيط لاستخدام AES في Python
from Crypto.Cipher import AES
from Crypto.Random import get_random_bytes

key = get_random_bytes(16) # مفتاح 128 بت
cipher = AES.new(key, AES.MODE_EAX)
data = "هذه رسالة سرية".encode()
ciphertext, tag = cipher.encrypt_and_digest(data)

print(f"النص المشفر: {ciphertext.hex()}")
            </pre>
            
            <div class="warning">
                <strong>تحذير:</strong> DES وRC4 لم يعودا آمنين للاستخدام في التطبيقات الحديثة بسبب الثغرات الأمنية التي اكتشفت فيهما.
            </div>
        </div>
    </section>

    <!-- خوارزميات التشفير غير المتماثل -->
    <section>
        <h2 class="section-title"><i class="fas fa-unlock-alt"></i> خوارزميات التشفير غير المتماثل</h2>
        <div class="card">
            <div class="card-header"><i class="fas fa-user-lock"></i> RSA (Rivest-Shamir-Adleman)</div>
            <p class="paragraph">RSA هي خوارزمية غير متماثلة تُستخدم بشكل واسع في تأمين البيانات عبر الإنترنت، مثل تشفير رسائل البريد الإلكتروني وحماية البيانات الحساسة. تعتمد على صعوبة تحليل الأعداد الكبيرة إلى عواملها الأولية.</p>
            
            <div class="example-box">
                <h4><i class="fas fa-cog"></i> طريقة عمل RSA:</h4>
                <ol>
                    <li>اختيار عددين أوليين كبيرين (p وq)</li>
                    <li>حاصل ضربهما (n = p*q) يستخدم كجزء من المفتاح العام</li>
                    <li>حساب دالة أويلر φ(n) = (p-1)*(q-1)</li>
                    <li>اختيار عدد صحيح e (عادة 65537) كجزء من المفتاح العام</li>
                    <li>حساب d بحيث (d*e) ≡ 1 mod φ(n) (المفتاح الخاص)</li>
                </ol>
            </div>
            
            
            <div class="note">
                <strong>ملاحظة:</strong> مع تطور الحواسيب الكمية، أصبحت خوارزميات مثل ECC (تشفير المنحنيات الإهليلجية) أكثر شيوعًا لأنها توفر أمانًا مماثلاً بمفاتيح أقصر.
            </div>
        </div>
    </section>

    <!-- التهديدات على التشفير -->
    <section>
        <h2 class="section-title"><i class="fas fa-bug"></i> التهديدات على أنظمة التشفير</h2>
        <div class="card">
            <div class="card-header"><i class="fas fa-shield-virus"></i> هجمات على أنظمة التشفير</div>
            <p class="paragraph">على الرغم من قوة أنظمة التشفير الحديثة، إلا أنها ليست محصنة ضد جميع الهجمات:</p>
            
            <ul class="custom-ul advantages">
                <li class="custom-li"><strong>هجوم القوة الغاشمة:</strong> محاولة تجربة جميع المفاتيح الممكنة حتى يتم العثور على المفتاح الصحيح. يمكن التخفيف منه باستخدام مفاتيح طويلة.</li>
                
                <li class="custom-li"><strong>هجوم الرجل في المنتصف:</strong> اعتراض الاتصال بين طرفين والتظاهر بأنه كل منهما للطرف الآخر. يمكن التخفيف منه باستخدام شهادات رقمية موثوقة.</li>
                
                <li class="custom-li"><strong>هجمات القنوات الجانبية:</strong> استغلال معلومات جانبية مثل استهلاك الطاقة أو الوقت المستغرق في التشفير. تتطلب حماية خاصة في التطبيقات الحساسة.</li>
                
                <li class="custom-li"><strong>هجمات التشفير المتكرر:</strong> إعادة إرسال بيانات مشفرة مسجلة مسبقًا. يمكن التخفيف منه باستخدام قيم عشوائية فريدة (nonce).</li>
            </ul>
            
            <div class="warning">
                <strong>تحذير:</strong> وفقًا لتقرير Verizon 2023، 43% من خروقات البيانات كانت بسبب أخطاء في تكوين التشفير أو استخدام خوارزميات ضعيفة.
            </div>
        </div>
    </section>

    <!-- مستقبل التشفير -->
    <section>
        <h2 class="section-title"><i class="fas fa-crystal-ball"></i> مستقبل التشفير</h2>
        <div class="card">
            <div class="card-header"><i class="fas fa-quantum-computing"></i> التشفير في عصر الحوسبة الكمية</div>
            <p class="paragraph">مع تطور الحواسيب الكمية، تواجه أنظمة التشفير الحالية تحديات كبيرة:</p>
            
            <ul class="custom-ul advantages">
                <li class="custom-li"><strong>تهديد خوارزميات التشفير الحالية:</strong> الحواسيب الكمية يمكنها كسر RSA وECC في وقت قصير باستخدام خوارزمية شور.</li>
                
                <li class="custom-li"><strong>التشفير الكمي:</strong> تطوير خوارزميات مقاومة للكمومية مثل تشفير الشبكات (Lattice-based cryptography).</li>
                
                <li class="custom-li"><strong>التوزيع الكمي للمفاتيح (QKD):</strong> استخدام مبادئ ميكانيكا الكم لتبادل المفاتيح بشكل آمن مطلقًا.</li>
                
                <li class="custom-li"><strong>التحديات التقنية:</strong> صعوبة تطبيق هذه الأنظمة على البنية التحتية الحالية.</li>
            </ul>
            
            <img src="https://www.researchgate.net/publication/344416283/figure/fig1/AS:940551129337859@1601464673807/Quantum-cryptography-and-quantum-key-distribution-principle.png" alt="التشفير الكمي" class="img-tool">
            
            <div class="note">
                <strong>ملاحظة:</strong> بدأت المعايير القومية الأمريكية (NIST) في عام 2022 في اعتماد خوارزميات تشفير مقاومة للكمومية للاستخدام المستقبلي.
            </div>
        </div>
    </section>

    <!-- خاتمة -->
    <section>
        <h2 class="section-title"><i class="fas fa-graduation-cap"></i> بداية رحلة التعلم</h2>
        <div class="card">
            <div class="card-header"><i class="fas fa-road"></i> كيف تبدأ في تعلم التشفير؟</div>
            <p class="paragraph">لتصبح خبيرًا في التشفير، تحتاج إلى بناء أساس قوي في عدة مجالات:</p>
            
            <ol style="font-size: 1.1rem; color: var(--text-light); padding-right: 20px;">
                <li style="padding: 8px 0;">تعلم الرياضيات الأساسية: نظرية الأعداد، الجبر، والاحتمالات</li>
                <li style="padding: 8px 0;">فهم أساسيات أمن المعلومات والشبكات</li>
                <li style="padding: 8px 0;">تعلم البرمجة بلغات مثل Python وC</li>
                <li style="padding: 8px 0;">دراسة خوارزميات التشفير الأساسية وتطبيقاتها</li>
                <li style="padding: 8px 0;">المشاركة في تحديات التشفير مثل CryptoPals</li>
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
