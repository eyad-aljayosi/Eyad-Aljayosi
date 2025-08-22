const maxLength = 2000;  // الحد الأقصى للطول

// قاعدة التباديل المعدلة (بدون تكرار للحروف العربية)
const substitutionKey = {
  // الحروف اللاتينية الصغيرة والكبيرة
  'a': 't', 'b': 'f', 'c': 'z', 'd': 'v', 'e': 'x',
  'f': 'q', 'g': 'b', 'h': 'm', 'i': 'r', 'j': 'u',
  'k': 'p', 'l': 'w', 'm': 's', 'n': 'o', 'o': 'y',
  'p': 'j', 'q': 'a', 'r': 'h', 's': 'n', 't': 'c',
  'u': 'e', 'v': 'g', 'w': 'k', 'x': 'l', 'y': 'd',
  'z': 'i',

  // الحروف اللاتينية الكبيرة
  'A': 'T', 'B': 'F', 'C': 'Z', 'D': 'V', 'E': 'X',
  'F': 'Q', 'G': 'B', 'H': 'M', 'I': 'R', 'J': 'U',
  'K': 'P', 'L': 'W', 'M': 'S', 'N': 'O', 'O': 'Y',
  'P': 'J', 'Q': 'A', 'R': 'H', 'S': 'N', 'T': 'C',
  'U': 'E', 'V': 'G', 'W': 'K', 'X': 'L', 'Y': 'D',
  'Z': 'I',

  // الأرقام
  '1': '5', '2': '7', '3': '9', '4': '3',
  '5': '0', '6': '8', '7': '2', '8': '6', '9': '4',
  '0': '1',

  // الحروف العربية المعدلة (بدون تكرار وتعريفات واضحة)
  'ا': 'ق', 'أ': 'ت', 'ب': 'ف', 'ج': 'ز', 'د': 'ص',
  'ه': 'ض', 'و': 'ع', 'ز': 'ي', 'ح': 'ر', 'ط': 'ش',
  'ي': 'ل', 'ك': 'م', 'ل': 'س', 'م': 'ن', 'ن': 'ه',
  'س': 'خ', 'ع': 'د', 'ف': 'ج', 'ص': 'ب', 'ق': 'ا',
  'ر': 'ح', 'ش': 'و', 'ت': 'ز', 'ة': 'غ', 'ء': 'ذ',
  'ؤ': 'ض', 'ئ': 'ط', 'إ': 'ك', 'آ': 'ظ',
};

// بناء الخريطة العكسية
const reverseSubstitutionKey = {};
Object.entries(substitutionKey).forEach(([key, value]) => {
  reverseSubstitutionKey[value] = key;
});

// دالة لعرض الرسائل (كما هي بدون تغيير)
function showMessage(message, type) {
  const messageElement = document.getElementById('message');
  const icon = type === 'success' ? 
    '<i class="fas fa-check-circle"></i>' : 
    '<i class="fas fa-exclamation-circle"></i>';
  
  messageElement.innerHTML = `${icon} ${message}`;
  messageElement.className = `message ${type}`;
  messageElement.style.display = 'block';
  
  setTimeout(() => {
    messageElement.classList.add('show');
  }, 10);
  
  setTimeout(() => {
    messageElement.classList.remove('show');
    messageElement.style.height = 'auto';
  }, 3000);
}

// دالة التشفير (بدون تغيير باستثناء التعليق التحذيري)
function encrypt() {
  const inputText = document.getElementById('text-input').value.trim();
  if (inputText === "") {
      showMessage("يرجى إدخال نص للتشفير", "error");
      return;
  }

  if (inputText.length > maxLength) {
      showMessage("النص المدخل طويل جدًا! الحد الأقصى للطول هو " + maxLength + " حرفًا.", "error");
      return;
  }

  const inputChars = inputText.split('');

  const outputChars = inputChars.map(char => {
      if (substitutionKey[char] !== undefined) {
          return substitutionKey[char];
      } else {
          console.warn(`الحرف '${char}' غير مدعوم في مفتاح التبديل وسيبقى كما هو`);
          return char;
      }
  });

  const outputText = outputChars.join('');
  document.getElementById('output').value = outputText;
  showMessage("تم تشفير النص بنجاح", "success");
  saveActivity(inputText, outputText, 'تشفير باستخدام قاعدة التباديل');
}

// دالة فك التشفير (بدون تغيير باستثناء التحقق من وجود الحرف)
function decrypt() {
  const inputText = document.getElementById('text-input').value.trim();
  if (inputText === "") {
      showMessage("يرجى إدخال نص لفك التشفير", "error");
      return;
  }

  const inputChars = inputText.split('');

  const outputChars = inputChars.map(char => {
      if (reverseSubstitutionKey[char] !== undefined) {
          return reverseSubstitutionKey[char];
      } else {
          console.warn(`الحرف '${char}' غير مدعوم في مفتاح فك التشفير وسيبقى كما هو`);
          return char;
      }
  });

  const outputText = outputChars.join('');
  document.getElementById('output').value = outputText;
  showMessage("تم فك تشفير النص بنجاح", "success");
  saveActivity(inputText, outputText, 'فك تشفير باستخدام قاعدة التباديل');
}

// دالة حفظ النشاط (كما هي بدون تغيير)
function saveActivity(message, result, operation) {
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "save_activity.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.send("message=" + encodeURIComponent(message) + "&result=" + encodeURIComponent(result) + "&operation=" + operation);
}