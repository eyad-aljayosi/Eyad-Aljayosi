// دالة لعرض الرسائل في div
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

function generateSHA256() {
    let text = document.getElementById("inputText").value.trim();
    let outputContainer = document.getElementById("outputContainer");
    let outputHash = document.getElementById("outputHash");

    // إذا كان النص فارغًا، إضافة حدود حمراء وإظهار رسالة
    if (text === "") {
        document.getElementById("inputText").classList.add("input-error");
        showMessage("الرجاء إدخال نص", "error"); // عرض رسالة "الرجاء إدخال نص"
        outputContainer.style.display = "none"; // إخفاء الـ output إذا كان النص فارغًا
        return;
    } else {
        document.getElementById("inputText").classList.remove("input-error");
    }

    // التحقق من أن النص لا يتجاوز 2000 حرف
    if (text.length > 2000) {
        showMessage("النص المدخل يتجاوز الحد الأقصى 2000 حرف يرجى تقليصه.", "error");
        outputContainer.style.display = "none"; // إخفاء الـ output إذا كان النص طويلًا
        return;
    }

    // توليد الـ SHA256
    let hash = CryptoJS.SHA256(text).toString();
    outputHash.innerHTML = '<i class="fas fa-fingerprint" style="margin-left: 6px;"></i> ' + hash;
    outputContainer.style.display = "flex"; // عرض الـ output

    // عرض رسالة نجاح بعد التوليد
    showMessage("تم إنشاء الهاش بنجاح", "success");
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
