// دالة فحص كلمات المرور الشائعة مثل '123' و 'abc'
function containsCommonPatterns(password) {
    const commonPatterns = [
        /123/, /abc/, /password/, /letmein/, /qwerty/,
        /111/, /welcome/, /admin/, /monkey/, /dragon/,
        /\d{3}/, /[a-zA-Z]{3}/, /123456789/, /qwerty123/, /password123/,
        /letmein123/, /admin123/, /guest123/, /iloveyou/,
        /football/, /welcome123/, /sunshine/,
        /password1/, /qwerty1/, /123qwe/, /abc123/
    ];
    return commonPatterns.some(pattern => pattern.test(password));
}

// دالة للتحقق من وجود تسلسل للأرقام أو الحروف
function containsSequence(password) {
    const sequences = ["123", "abc", "qwerty", "111"];
    return sequences.some(seq => password.includes(seq));
}

// التحقق من أن كلمة المرور تحتوي على مزيج من الحروف الكبيرة والصغيرة والأرقام
function isStrongPassword(password) {
    const lengthValid = password.length >= 8; // التأكد من أن كلمة المرور تحتوي على 8 أحرف على الأقل
    const hasUpperCase = /[A-Z]/.test(password); // التحقق من وجود حروف كبيرة
    const hasLowerCase = /[a-z]/.test(password); // التحقق من وجود حروف صغيرة
    const hasDigits = /\d/.test(password); // التحقق من وجود أرقام
    const hasSymbols = /[^a-zA-Z0-9]/.test(password); // التحقق من وجود رموز خاصة

    return lengthValid && hasUpperCase && hasLowerCase && hasDigits && hasSymbols;
}

function evaluatePassword() {
    const password = document.getElementById("password").value;
    const strengthBar = document.getElementById("strength-bar").children[0];
    const passwordInfo = document.getElementById("password-info");
    const passwordDetails = document.getElementById("password-details");
    const resultText = document.getElementById("result-text");
    const errorMessage = document.getElementById("error-message");
    const resultContainer = document.getElementsByClassName("result-container")[0];

    // إخفاء رسالة الخطأ في حالة عدم وجود خطأ
    errorMessage.style.display = "none";

    // التحقق من أن كلمة المرور غير فارغة
    if (password === "") {
        errorMessage.style.display = "block";
        return;
    }

    let strength = 0;
    let details = [];

    // التحقق من طول كلمة المرور
    if (password.length >= 12) {
        strength++; // يعطي نقاط بناءً على الطول
    } else {
        details.push("زيادة طول كلمة المرور يمكن أن يجعلها أكثر أمانًا.");
    }

    // تحقق من وجود الحروف الكبيرة
    if (/[A-Z]/.test(password)) {
        strength++;
    } else {
        details.push("يُفضل إضافة أحرف كبيرة لزيادة قوة كلمة المرور.");
    }

    // تحقق من وجود الأرقام
    if (/\d/.test(password)) {
        strength++;
    } else {
        details.push("إضافة أرقام سيجعل كلمة المرور أقوى.");
    }

    // تحقق من وجود الرموز الخاصة
    if (/[^a-zA-Z0-9]/.test(password)) {
        strength++;
    } else {
        details.push("إضافة رموز خاصة (مثل !@#) تجعل كلمة المرور أقوى.");
    }

    // التحقق من أن كلمة المرور ليست شائعة
    if (containsCommonPatterns(password)) {
        details.push("تجنب استخدام كلمات المرور الشائعة مثل '123' أو 'abc'.");
    }

    // التحقق من وجود تسلسل للأحرف أو الأرقام
    if (containsSequence(password)) {
        details.push("تجنب استخدام التسلسلات المتوقعة مثل '123' أو 'qwerty'.");
    }

    // تصنيف قوة كلمة المرور
    if (isStrongPassword(password)) {
        resultText.textContent = "قوة كلمة المرور: ممتازة";
        strengthBar.className = 'very-strong';
    } else if (strength === 4) {
        resultText.textContent = "قوة كلمة المرور: قوية";
        strengthBar.className = 'strong';
    } else if (strength === 3) {
        resultText.textContent = "قوة كلمة المرور: متوسطة";
        strengthBar.className = 'medium';
    } else if (strength === 2) {
        resultText.textContent = "قوة كلمة المرور: ضعيفة";
        strengthBar.className = 'weak';
    } else {
        resultText.textContent = "قوة كلمة المرور: ضعيفة جدًا";
        strengthBar.className = 'weak';
    }

    // إخفاء النصائح في حالة كلمة المرور ممتازة
    if (strength === 4) {
        passwordInfo.style.display = 'none';
    } else {
        // عرض النصائح فقط إذا كانت هناك نصائح
        passwordInfo.style.display = 'block';
        passwordDetails.innerHTML = details.join("<li></li>");
    }

    // إظهار النتيجة بعد التحليل
    resultContainer.style.display = 'block';
}