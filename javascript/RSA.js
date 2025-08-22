let rsa = new JSEncrypt({default_key_size: 1024}); // تحديد حجم المفتاح 1024 بت عند الإنشاء
let publicKey = '';
let privateKey = '';

function generateKeys() {
    rsa = new JSEncrypt({default_key_size: 1024}); // إعادة إنشاء الكائن مع حجم 1024 بت
    rsa.getKey();
    publicKey = rsa.getPublicKey();
    privateKey = rsa.getPrivateKey();
    document.getElementById('publicKey').value = publicKey;
    document.getElementById('privateKey').value = privateKey;
    document.getElementById('errorMsg').textContent = '';  // Clear error message
}

// باقي الدوال تبقى كما هي دون تغيير
function showMessage(message, type) {
    const messageElement = document.getElementById('message');
    const icon = type === 'success' ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-exclamation-circle"></i>';
    messageElement.innerHTML = `${icon} ${message}`;
    messageElement.style.height = 'auto';  
    messageElement.className = `message ${type}`; 
    messageElement.style.display = 'block';
    
    setTimeout(() => {
      messageElement.classList.add('show');
    }, 10);
    
    setTimeout(() => {
      messageElement.classList.remove('show');
      messageElement.style.height = '0';
    }, 3000);
}

function encryptText() {
    let text = document.getElementById('plainText').value.trim();

    if (!publicKey) {
        alert(' يرجى توليد المفاتيح أولاً!');
        return;
    }

    if (!text) {
        showMessage("يرجى إدخال نص للتشفير!", "error");
        return;
    }

    rsa.setPublicKey(publicKey);
    let encrypted = rsa.encrypt(text);
    if (encrypted) {
        document.getElementById('encryptedText').value = encrypted;
        showMessage("تم التشفير بنجاح!", "success");
    } else {
        document.getElementById('encryptedText').value = 'خطأ في التشفير';
        showMessage("فشل التشفير، حاول مرة أخرى.", "error");
    }

    saveActivity(text, encrypted, 'تشفير باستخدام RSA');
}

function decryptText() {
    let encryptedText = document.getElementById('cipherText').value.trim();
    let userPrivateKey = document.getElementById('privateKey').value.trim();

    if (!userPrivateKey) {
        alert('يرجى إدخال المفتاح الخاص أولاً!');
        return;
    }

    if (!encryptedText) {
        showMessage("يرجى إدخال نص مشفر لفك التشفير!", "error");
        return;
    }

    rsa.setPrivateKey(userPrivateKey);
    let decrypted = rsa.decrypt(encryptedText);
    if (decrypted) {
        document.getElementById('decryptedText').value = decrypted;
        showMessage("تم فك التشفير بنجاح!", "success");
    } else {
        document.getElementById('decryptedText').value = '';
        showMessage("فشل فك التشفير! تأكد من أن النص المشفر صحيح.", "error");
    }

    saveActivity(encryptedText, decrypted, 'فك تشفير باستخدام RSA');
}

function saveActivity(message, result, operation) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "save_activity.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("message=" + encodeURIComponent(message) + "&result=" + encodeURIComponent(result) + "&operation=" + encodeURIComponent(operation));
}

function copyKey(id) {
    let copyText = document.getElementById(id);
    let copyButton = document.querySelector(`button[onclick="copyKey('${id}')"]`);

    navigator.clipboard.writeText(copyText.value).then(() => {
        copyButton.innerText = "تم النسخ";
        
        setTimeout(() => {
            copyButton.innerText = "نسخ " + (id === 'publicKey' ? "المفتاح العام" : "المفتاح الخاص");
        }, 2000);
    }).catch(err => {
        console.error("فشل النسخ:", err);
    });
}