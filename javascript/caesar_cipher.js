const maxLength = 2000;  // الحد الأقصى للطول

// دالة لعرض الرسائل في div
function showMessage(message, type) {
    const messageElement = document.getElementById('message-2');
    
    const icon = type === 'success' ? 
        '<i class="fas fa-check-circle"></i>' : 
        '<i class="fas fa-exclamation-circle"></i>';
    
    messageElement.innerHTML = `${icon} ${message}`;
    messageElement.style.height = 'auto';  
    messageElement.className = `message-2 ${type}`;
    
    setTimeout(() => {
        messageElement.classList.add('show');
    }, 10);
    
    setTimeout(() => {
        messageElement.classList.remove('show');
        messageElement.style.height = 'auto';
    }, 3000);
}

function encrypt() {
    var message = document.getElementById("message").value;
    var shift = parseInt(document.getElementById("shift").value);
    var result = "";

    if (isNaN(shift)) {
        showMessage("يرجى إدخال قيمة صحيحة لمقدار الإزاحة", "error");
        return;
    }

    if (message.trim() === "") {
        showMessage("يرجى إدخال نص للتشفير", "error");
        return;
    }

    if (message.length > maxLength) {
        showMessage("النص المدخل طويل جدًا! الحد الأقصى للطول هو " + maxLength + " حرفًا.", "error");
        return;
    }

    // خريطة الحروف العربية مرتبة حسب الترتيب الأبجدي
    const arabicLetters = [
        'ا', 'ب', 'ت', 'ث', 'ج', 'ح', 'خ', 'د', 'ذ', 'ر',
        'ز', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع', 'غ', 'ف',
        'ق', 'ك', 'ل', 'م', 'ن', 'ه', 'و', 'ي'
    ];

    for (var i = 0; i < message.length; i++) {
        var char = message[i];
        var lowerChar = char.toLowerCase();
        
        if (lowerChar >= 'a' && lowerChar <= 'z') { // حروف إنجليزية
            var code = char.charCodeAt(0);
            var base = lowerChar === char ? 97 : 65;
            result += String.fromCharCode((code - base + shift) % 26 + base);
        } 
        else if (arabicLetters.includes(char)) { // حروف عربية
            var index = arabicLetters.indexOf(char);
            var newIndex = (index + shift) % arabicLetters.length;
            if (newIndex < 0) newIndex += arabicLetters.length;
            result += arabicLetters[newIndex];
        }
        else {
            result += char; // باقي الرموز
        }
    }

    document.getElementById("output").value = result;
    saveActivity(message, result, 'تشفير باستخدام قاعدة قيصر');
    showMessage("تم التشفير بنجاح", "success");
}

function decrypt() {
    var message = document.getElementById("message").value;
    var shift = parseInt(document.getElementById("shift").value);
    var result = "";

    if (isNaN(shift)) {
        showMessage("يرجى إدخال قيمة صحيحة لمقدار الإزاحة", "error");
        return;
    }

    if (message.trim() === "") {
        showMessage("يرجى إدخال نص لفك التشفير", "error");
        return;
    }

    // خريطة الحروف العربية مرتبة حسب الترتيب الأبجدي
    const arabicLetters = [
        'ا', 'ب', 'ت', 'ث', 'ج', 'ح', 'خ', 'د', 'ذ', 'ر',
        'ز', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع', 'غ', 'ف',
        'ق', 'ك', 'ل', 'م', 'ن', 'ه', 'و', 'ي'
    ];

    for (var i = 0; i < message.length; i++) {
        var char = message[i];
        var lowerChar = char.toLowerCase();
        
        if (lowerChar >= 'a' && lowerChar <= 'z') { // حروف إنجليزية
            var code = char.charCodeAt(0);
            var base = lowerChar === char ? 97 : 65;
            result += String.fromCharCode((code - base - shift + 26) % 26 + base);
        } 
        else if (arabicLetters.includes(char)) { // حروف عربية
            var index = arabicLetters.indexOf(char);
            var newIndex = (index - shift) % arabicLetters.length;
            if (newIndex < 0) newIndex += arabicLetters.length;
            result += arabicLetters[newIndex];
        }
        else {
            result += char; // باقي الرموز
        }
    }

    document.getElementById("output").value = result;
    saveActivity(message, result, 'فك تشفير باستخدام قاعدة قيصر');
    showMessage("تم فك التشفير بنجاح", "success");
}

function saveActivity(message, result, operation) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "save_activity.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("message=" + encodeURIComponent(message) + "&result=" + encodeURIComponent(result) + "&operation=" + operation);
}