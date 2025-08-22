
function showMessage(message, type) {
    const messageElement = document.getElementById('message'); // استخدام id الصحيح
    
    // إضافة الأيقونة المناسبة حسب نوع الرسالة
    const icon = type === 'success' ? 
      '<i class="fas fa-check-circle"></i>' : 
      '<i class="fas fa-exclamation-circle"></i>';
    
    messageElement.innerHTML = `${icon} ${message}`;
    messageElement.style.height = 'auto';  
    messageElement.className = `message ${type}`; 
    messageElement.style.display = 'block'; // عرض الرسالة
    
    // إظهار الرسالة مع تأثير fadeIn
    setTimeout(() => {
      messageElement.classList.add('show');
    }, 10);
    
    // إخفاء الرسالة بعد 3 ثواني
    setTimeout(() => {
      messageElement.classList.remove('show');
            // إعادة تعيين الخصائص بعد اختفاء الرسالة
            messageElement.style.height = '0';  // يمكن ضبطه على 'auto' أو '0'
    }, 3000);
  }

    function encryptText() {
        const inputText = document.getElementById('inputText').value;
        const password = document.getElementById('password').value;
        const outputText = document.getElementById('outputText');
        const loadingElement = document.getElementById('loading');
        const maxLength = 1500;  // الحد الأقصى للطول

        if (inputText.length > maxLength) {
            showMessage("النص المدخل طويل جدًا! الحد الأقصى للطول هو " + maxLength + " حرفًا.", "error");
            return;
        }

        if (!inputText || !password) {
            showMessage("يرجى إدخال النص وكلمة المرور!", "error");
            return;
        }

        showMessage("", "info");
        loadingElement.style.display = 'block';

        try {
            const iv = CryptoJS.lib.WordArray.random(16);  // توليد IV عشوائي
            const key = CryptoJS.SHA256(password);  // تحويل كلمة المرور باستخدام SHA-256
            const encryptedText = CryptoJS.AES.encrypt(inputText, key, { iv: iv, mode: CryptoJS.mode.CBC }).toString();

            // دمج الـ IV مع النص المشفر
            const result = iv.toString(CryptoJS.enc.Base64) + encryptedText;

            outputText.value = result;  // النص المشفر مع الـ IV
            showMessage("تم التشفير بنجاح", "success");

            // إرسال النشاط إلى الخادم بعد التشفير
            sendActivityToServer(inputText, result, 'تشفير باستخدام AES');
        } catch (error) {
            showMessage("حدث خطأ أثناء التشفير!", "error");
        } finally {
            loadingElement.style.display = 'none';
        }
    }

    function decryptText() {
        const inputText = document.getElementById('inputText').value; // النص المشفر المرسل من PHP
        const password = document.getElementById('password').value; // كلمة المرور
        const outputText = document.getElementById('outputText');
        const loadingElement = document.getElementById('loading');

        if (!inputText || !password) {
            showMessage("يرجى إدخال النص المشفر وكلمة المرور!", "error");
            return;
        }

        showMessage("", "info");
        loadingElement.style.display = 'block';

        try {
            const ivBase64 = inputText.slice(0, 24);  // استخراج الـ IV من بداية النص المشفر
            const iv = CryptoJS.enc.Base64.parse(ivBase64);  // تحويل الـ IV من Base64 إلى WordArray
            const encryptedText = inputText.slice(24);  // النص المشفر بعد الـ IV

            const key = CryptoJS.SHA256(password);  // تحويل كلمة المرور باستخدام SHA-256
            const decryptedBytes = CryptoJS.AES.decrypt(encryptedText, key, { iv: iv, mode: CryptoJS.mode.CBC });
            const decryptedText = decryptedBytes.toString(CryptoJS.enc.Utf8);  // تحويل النص المفكوك إلى نص

            if (!decryptedText) {
                showMessage("فك التشفير فشل!", "error");
                return;
            }

            outputText.value = decryptedText;
            showMessage("تم فك التشفير بنجاح!", "success");

            // إرسال النشاط إلى الخادم بعد فك التشفير
            sendActivityToServer(inputText, decryptedText, 'فك تشفير باستخدام AES');
        } catch (error) {
            showMessage("حدث خطأ أثناء فك التشفير!", "error");
        } finally {
            loadingElement.style.display = 'none';
        }
    }

    // دالة لإرسال النشاط إلى الخادم
    function sendActivityToServer(message, result, operation) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "save_activity.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        var data = "message=" + encodeURIComponent(message) + 
                    "&result=" + encodeURIComponent(result) + 
                    "&operation=" + encodeURIComponent(operation);

        xhr.send(data);
    }