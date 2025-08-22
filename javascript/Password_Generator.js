function generatePassword() {
    const length = document.getElementById("password-length").value;
    const includeUppercase = document.getElementById("include-uppercase").checked;
    const includeNumbers = document.getElementById("include-numbers").checked;
    const includeSymbols = document.getElementById("include-symbols").checked;

    let password = '';
    const lowerChars = 'abcdefghijklmnopqrstuvwxyz';
    let allChars = lowerChars;
    if (includeUppercase) allChars += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if (includeNumbers) allChars += '0123456789';
    if (includeSymbols) allChars += '!@#$%^&*()-_=+[]{}|;:,.<>?';

    for (let i = 0; i < length; i++) {
        password += allChars.charAt(Math.floor(Math.random() * allChars.length));
    }

    document.getElementById("passwordField").value = password;
    showPasswordInfo(password);
    evaluateStrength(password);
    document.getElementById("password-info").style.display = 'block';
    document.getElementById("strength-bar").style.display = 'block';
    document.getElementById("strength-text").style.display = 'block';
    document.getElementById("strength-label").style.display = 'block';
}

function showPasswordInfo(password) {
    const infoBox = document.getElementById("password-details");
    const uniqueChars = new Set(password).size;
    const hasSequential = /(012|123|234|345|456|567|678|789|890|abc|bcd|cde|def|efg|fgh|ghi|hij|ijk|jkl|klm|lmn|mno|nop|opq|pqr|qrs|rst|stu|tuv|uvw|vwx|wxy|xyz)/i.test(password);
    const numUppercase = (password.match(/[A-Z]/g) || []).length;
    const numLowercase = (password.match(/[a-z]/g) || []).length;
    const numDigits = (password.match(/[0-9]/g) || []).length;
    const numSpecial = (password.match(/[^a-zA-Z0-9]/g) || []).length;

    let infoText = "";
    infoText += `<li>عدد الأحرف الفريدة: ${uniqueChars}</li>`;
    infoText += `<li>${hasSequential ? "تتضمن تسلسلات شائعة (ضعف)" : "لا تتضمن تسلسلات شائعة"}</li>`;
    infoText += `<li>عدد الأحرف الكبيرة: ${numUppercase}</li>`;
    infoText += `<li>عدد الأحرف الصغيرة: ${numLowercase}</li>`;
    infoText += `<li>عدد الأرقام: ${numDigits}</li>`;
    infoText += `<li>عدد الرموز الخاصة: ${numSpecial}</li>`;
    infoBox.innerHTML = infoText;
}

function evaluateStrength(password) {
    const strengthText = document.getElementById("strength-text");
    const progressBar = document.getElementById("strength-bar").children[0];
    let strength = 0;

    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;

    switch (strength) {
        case 1:
            strengthText.textContent = "القوة: ضعيفة - حاول إضافة أرقام ورموز";
            strengthText.style.color = "#e74c3c";
            progressBar.className = 'weak';
            break;
        case 2:
            strengthText.textContent = "القوة: متوسطة - يمكن إضافة أحرف كبيرة أو رموز";
            strengthText.style.color = "#f1c40f";
            progressBar.className = 'medium';
            break;
        case 3:
            strengthText.textContent = "القوة: قوية - جيد لكن يمكنك تحسينها";
            strengthText.style.color = "#2ecc71";
            progressBar.className = 'strong';
            break;
        case 4:
            strengthText.textContent = "القوة: ممتازة - كلمة مرور قوية جدًا!";
            strengthText.style.color = "#27ae60";
            progressBar.className = 'strong';
            break;
        default:
            strengthText.textContent = "القوة: غير محددة";
            strengthText.style.color = "#555";
            progressBar.className = '';
    }
}