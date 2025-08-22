// متغير لحفظ حالة الملف المحدد
let selectedFile = null;
        
// العناصر DOM
const fileInput = document.getElementById('fileInput');
const dropArea = document.getElementById('dropArea');
const fileInfo = document.getElementById('fileInfo');
const fileName = document.getElementById('fileName');
const fileSize = document.getElementById('fileSize');
const fileType = document.getElementById('fileType');
const passwordInput = document.getElementById('password');
const messageElement = document.getElementById('message');
const loadingElement = document.getElementById('loading');

// أنواع الملفات المدعومة
const supportedExtensions = {
    'txt': 'ملف نصي',
    'pdf': 'ملف PDF',
    'doc': 'مستند Word',
    'docx': 'مستند Word',
    'ppt': 'عرض تقديمي',
    'pptx': 'عرض تقديمي',
    'xls': 'جدول بيانات',
    'xlsx': 'جدول بيانات',
    'jpg': 'صورة',
    'jpeg': 'صورة',
    'png': 'صورة',
    'zip': 'ملف مضغوط',
    'rar': 'ملف مضغوط'
};

// أحداث سحب وإفلات الملفات
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dropArea.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, unhighlight, false);
});

function highlight() {
    dropArea.classList.add('dragover');
}

function unhighlight() {
    dropArea.classList.remove('dragover');
}

dropArea.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    
    if (files.length) {
        handleFiles(files);
    }
}

fileInput.addEventListener('change', function() {
    if (this.files.length) {
        handleFiles(this.files);
    }
});

function handleFiles(files) {
    selectedFile = files[0];
    const fileExt = selectedFile.name.split('.').pop().toLowerCase();
    
    // عرض معلومات الملف
    fileName.textContent = selectedFile.name;
    fileSize.textContent = formatFileSize(selectedFile.size);
    fileType.textContent = supportedExtensions[fileExt] || 'ملف غير معروف';
    fileInfo.classList.add('show');
    
    // إخفاء الرسالة إذا كانت ظاهرة
    hideMessage();
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 بايت';
    
    const k = 1024;
    const sizes = ['بايت', 'كيلوبايت', 'ميجابايت', 'جيجابايت'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function showMessage(message, type) {
    messageElement.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 
                              type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i> ${message}`;
    messageElement.className = `message ${type} show`;
    
    // إخفاء الرسالة بعد 4 ثواني
    setTimeout(hideMessage, 4000);
}

function hideMessage() {
    messageElement.classList.remove('show');
}

function showLoading() {
    loadingElement.style.display = 'block';
}

function hideLoading() {
    loadingElement.style.display = 'none';
}

function encryptFile() {
    if (!selectedFile) {
        showMessage('يرجى اختيار ملف أولاً!', 'error');
        return;
    }
    
    const maxFileSize = 50 * 1024 * 1024; // 50MB
    if (selectedFile.size > maxFileSize) {
        showMessage(`حجم الملف أكبر من الحد المسموح به (${maxFileSize / 1024 / 1024}MB)`, 'error');
        return;
    }
    
    const password = passwordInput.value.trim();
    if (!password) {
        showMessage('يرجى إدخال كلمة المرور!', 'error');
        return;
    }
    
    const fileExt = selectedFile.name.split('.').pop().toLowerCase();
    
    if (!Object.keys(supportedExtensions).includes(fileExt)) {
        showMessage('نوع الملف غير مدعوم', 'error');
        return;
    }
    
    showLoading();
    showMessage('جاري تشفير الملف...', 'warning');
    
    const reader = new FileReader();
    reader.onload = function(e) {
        try {
            const fileContent = e.target.result;
            const encryptedContent = CryptoJS.AES.encrypt(fileContent, password).toString();
            
            const encryptedBlob = new Blob([encryptedContent], { type: 'application/octet-stream' });
            const url = URL.createObjectURL(encryptedBlob);
            
            const a = document.createElement('a');
            a.href = url;
            a.download = `encrypted_${selectedFile.name}`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            
            showMessage('تم تشفير الملف بنجاح', 'success');
        } catch (error) {
            showMessage('حدث خطأ أثناء التشفير!', 'error');
            console.error(error);
        } finally {
            hideLoading();
        }
    };
    
    reader.onerror = function() {
        showMessage('حدث خطأ أثناء قراءة الملف!', 'error');
        hideLoading();
    };
    
    // قراءة الملف كبيانات ثنائية (لضمان دعم جميع أنواع الملفات)
    reader.readAsDataURL(selectedFile);
}

function decryptFile() {
    if (!selectedFile) {
        showMessage('يرجى اختيار ملف أولاً!', 'error');
        return;
    }
    
    const password = passwordInput.value.trim();
    if (!password) {
        showMessage('يرجى إدخال كلمة المرور!', 'error');
        return;
    }
    
    showLoading();
    showMessage('جاري فك تشفير الملف...', 'warning');
    
    const reader = new FileReader();
    reader.onload = function(e) {
        try {
            const encryptedContent = e.target.result;
            const decryptedBytes = CryptoJS.AES.decrypt(encryptedContent, password);
            const decryptedContent = decryptedBytes.toString(CryptoJS.enc.Utf8);
            
            if (!decryptedContent) {
                throw new Error('كلمة المرور غير صحيحة');
            }
            
            // تحويل base64 إلى Blob
            const base64Data = decryptedContent.split(',')[1] || decryptedContent;
            const byteCharacters = atob(base64Data);
            const byteNumbers = new Array(byteCharacters.length);
            for (let i = 0; i < byteCharacters.length; i++) {
                byteNumbers[i] = byteCharacters.charCodeAt(i);
            }
            const byteArray = new Uint8Array(byteNumbers);
            
            // تحديد نوع الملف بناء على المحتوى
            let fileType = 'application/octet-stream';
            if (decryptedContent.startsWith('data:')) {
                fileType = decryptedContent.match(/^data:(.*?);/)[1];
            }
            
            const decryptedBlob = new Blob([byteArray], { type: fileType });
            const url = URL.createObjectURL(decryptedBlob);
            
            let originalName = selectedFile.name;
            if (originalName.startsWith('encrypted_')) {
                originalName = originalName.replace('encrypted_', '');
            }
            
            const a = document.createElement('a');
            a.href = url;
            a.download = `decrypted_${originalName}`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            
            showMessage('تم فك تشفير الملف بنجاح!', 'success');
        } catch (error) {
            showMessage('فشل فك التشفير! تأكد من كلمة المرور.', 'error');
            console.error(error);
        } finally {
            hideLoading();
        }
    };
    
    reader.onerror = function() {
        showMessage('حدث خطأ أثناء قراءة الملف!', 'error');
        hideLoading();
    };
    
    reader.readAsText(selectedFile);
}