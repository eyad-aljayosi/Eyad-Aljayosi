function showMessage(message, type) {
    const messageElement = document.getElementById('message-2');
    
    // إضافة الأيقونة المناسبة حسب نوع الرسالة
    const icon = type === 'success' ? 
        '<i class="fas fa-check-circle"></i>' : 
        '<i class="fas fa-exclamation-circle"></i>';
    
    messageElement.innerHTML = `${icon} ${message}`;
    messageElement.className = `message-2 ${type}`;
    
    // إظهار الرسالة مع تأثير fadeIn
    setTimeout(() => {
        messageElement.classList.add('show');
    }, 10);
    
    // إخفاء الرسالة بعد 3 ثواني
    setTimeout(() => {
        messageElement.classList.remove('show');
    }, 3000);
}

function generateMD5() {
    let text = document.getElementById("inputText").value.trim();
    let outputContainer = document.getElementById("outputContainer");
    let outputHash = document.getElementById("outputHash");

    // إذا كان النص فارغًا
    if (text === "") {
        document.getElementById("inputText").classList.add("input-error");
        showMessage("الرجاء إدخال نص", "error");
        outputContainer.style.display = "none";
        return;
    } else {
        document.getElementById("inputText").classList.remove("input-error");
    }

    // التحقق من طول النص
    if (text.length > 2000) {
        showMessage("النص المدخل يتجاوز الحد الأقصى 2000 حرف يرجى تقليصه.", "error");
        outputContainer.style.display = "none";
        return;
    }

    // توليد الـ MD5
    let hash = CryptoJS.MD5(text).toString();
    outputHash.innerHTML = '<i class="fas fa-fingerprint" style="margin-left: 6px;"></i> ' + hash;
    outputContainer.style.display = "flex";

    // عرض رسالة نجاح
    showMessage("تم إنشاء الهاش بنجاح", "success");
}

function copyToClipboard() {
    let hashText = document.getElementById("outputHash").innerText;
    let copyButton = document.querySelector(".btn-copy");

    navigator.clipboard.writeText(hashText).then(() => {
        copyButton.innerHTML = '<i class="fas fa-check"></i> تم النسخ';
        setTimeout(() => {
            copyButton.innerHTML = '<i class="far fa-copy"></i> نسخ';
        }, 2000);
        
        // عرض رسالة نجاح النسخ
        showMessage("تم نسخ الهاش بنجاح", "success");
    });
}