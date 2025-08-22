<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>أرشيف مالك الشفرات | التحدي السري</title>
    <style>
        :root {
            --primary: #2b5876;
            --secondary: #4e4376;
            --accent: #f54ea2;
            --dark: #0f2027;
            --light: #f8f9fa;
        }
        
        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, var(--dark), var(--primary));
            color: var(--light);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            text-align: center;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            background: rgba(0, 0, 0, 0.7);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 3rem;
        }
        
        h1 {
            color: var(--accent);
            font-size: 2.5rem;
            margin-bottom: 1rem;
            text-shadow: 0 0 10px rgba(245, 78, 162, 0.5);
        }
        
        .subtitle {
            color: #aaa;
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }
        
        .warning-box {
            background: rgba(255, 0, 0, 0.1);
            border-left: 4px solid red;
            padding: 1.5rem;
            border-radius: 8px;
            margin: 2rem 0;
            text-align: right;
        }
        
        .btn {
            background: linear-gradient(90deg, var(--accent), #ff7676);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(245, 78, 162, 0.4);
            font-weight: bold;
            margin: 1rem;
        }
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(245, 78, 162, 0.6);
        }
        
        .btn:active {
            transform: translateY(1px);
        }
        
        .fake-flag {
            background: rgba(0, 0, 0, 0.3);
            padding: 1rem;
            border-radius: 5px;
            font-family: monospace;
            color: #ff5252;
            margin: 1rem auto;
            max-width: 300px;
            border: 1px dashed #ff5252;
        }
        
        footer {
            margin-top: 3rem;
            color: #666;
            font-size: 0.9rem;
        }
        
        /* الجزء 1: CTF{7h3_ */
        .hidden-data {
            position: absolute;
            left: -9999px;
            opacity: 0.001;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>أرشيف مالك الشفرات السري</h1>
        <p class="subtitle">التحدي الأمني المتقدم - المستوى الخامس</p>
        
        <div class="warning-box">
            <h3>تحذير أمني!</h3>
            <p>هذا الأرشيف يحتوي على معلومات حساسة. جميع المحاولات غير المصرح بها سيتم تسجيلها.</p>
        </div>
        
        <div class="fake-flag">CTF{FAKE_FLAG_DONT_TRUST}</div>
        
        <button class="btn" onclick="loadSecret()">بدء التحليل الأمني</button>
        
        <p id="status" style="margin-top: 2rem;"></p>
    </div>
    
    <footer>
        نظام التحديات الأمنية | إصدار v2.4.1
    </footer>

    <script src="secret.js"></script>
    <script>
        function loadSecret() {
            document.getElementById('status').innerHTML = 
                '<p style="color:#4CAF50">جارٍ تحميل البيانات السرية...</p>';
            
            fetch('data.bin')
                .then(r => r.text())
                .then(d => {
                    console.log('%c[نظام] البيانات المشفرة: ' + d, 'color:transparent');
                    document.getElementById('status').innerHTML +=
                        '<p style="color:#aaa">تم اكتشاف بيانات مشفرة. تحقق من أدوات المطور.</p>';
                })
                .catch(e => {
                    document.getElementById('status').innerHTML =
                        '<p style="color:red">خطأ في الوصول إلى الملفات السرية</p>';
                });
        }
        
        // إخفاء البيانات في الذاكرة
        const part1 = "CTF{7h3_";
    </script>
</body>
</html>