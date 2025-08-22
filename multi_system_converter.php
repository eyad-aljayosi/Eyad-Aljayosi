<?php
include 'conn.php'; // الاتصال بقواعد البيانات

// التحقق من وجود الجلسة وبيانات المستخدم
session_start();

if (!isset($_SESSION['usermail'])) {
    // إذا لم يكن المستخدم مسجلاً الدخول، يتم توجيههم إلى صفحة تسجيل الدخول
    $_SESSION['login_befor']="قم بعمل تسجيل دخول بالبداية ";
    header("Location: login.php");
    exit();
}

include 'session_check.php'; // يضمن هذا الكود أن أي تفاعل مع الموقع سيجدد الجلسة

$currentPage = basename($_SERVER['PHP_SELF']); // الحصول على اسم الصفحة الحالية
?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>محول الأنظمة العددية المتقدم</title>
    
    <!-- خطوط جوجل -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- أيقونات Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preload" href="img/background6.jpg" as="image">
    <link rel="stylesheet" href="css/style-multi_system_converter.css?v=1.1">

</head>
<?php include 'header/header_system_converter.php'; ?>
<body>
    <div class="container">
        <h2 class="title-h2">
            <i class="fas fa-exchange-alt"></i> محول الأنظمة العددية المتقدم
        </h2>
        
        <div class="converter-header">
            <select id="format" onchange="convertNumber()">
                <option value="binary">ثنائي (Binary)</option>
                <option value="hexadecimal">سداسي عشري (Hex)</option>
                <option value="decimal">عشري (Decimal)</option>
                <option value="octal">ثماني (Octal)</option>
                <option value="text">نص (Text)</option>
            </select>
        </div>
        
        <textarea id="inputValue" oninput="adjustHeight(this); convertNumber()" autocomplete="off" placeholder="أدخل القيمة هنا..."></textarea>
        
        <h3 class="title-h3" id="initialMessage">
            <i class="fas fa-info-circle"></i> سوف تظهر النتائج هنا
        </h3>
        
        <h3 class="title-h3" id="resultsMessage" style="display:none;">
            <i class="fas fa-list-ul"></i> النتائج:
        </h3>
        
        <div id="output"></div>
    </div>

    <script>
        function adjustHeight(element) {
            element.style.height = "auto";
            element.style.height = (element.scrollHeight) + "px";
        }

        function convertNumber() {
            let format = document.getElementById("format").value;
            let inputValue = document.getElementById("inputValue").value.trim();
            let outputDiv = document.getElementById("output");
            let initialMessage = document.getElementById("initialMessage");
            let resultsMessage = document.getElementById("resultsMessage");
            outputDiv.innerHTML = "";

            if (inputValue) {
                initialMessage.style.display = "none";
                resultsMessage.style.display = "block";
            } else {
                initialMessage.style.display = "block";
                resultsMessage.style.display = "none";
                return;
            }

            let output = "";

            try {
                if (format === "binary") {
                    let binaryParts = inputValue.split(' ');
                    let decimalValues = binaryParts.map(part => parseInt(part, 2));
                    let text = decimalValues.map(d => isNaN(d) ? '' : String.fromCharCode(d)).join('');
                    
                    output += createOutputItem("عشري (Decimal)", decimalValues.map(d => isNaN(d) ? "NaN" : d).join(' '));
                    output += createOutputItem("ثماني (Octal)", decimalValues.map(d => isNaN(d) ? "NaN" : d.toString(8)).join(' '));
                    output += createOutputItem("سداسي عشري (Hex)", decimalValues.map(d => isNaN(d) ? "NaN" : d.toString(16).toUpperCase()).join(' '));
                    output += createOutputItem("نص (Text)", text || "NaN");
                } else if (format === "hexadecimal") {
                    let hexParts = inputValue.split(' ');
                    let decimalValues = hexParts.map(part => parseInt(part, 16));
                    let text = decimalValues.map(d => isNaN(d) ? '' : String.fromCharCode(d)).join('');
                    
                    output += createOutputItem("عشري (Decimal)", decimalValues.map(d => isNaN(d) ? "NaN" : d).join(' '));
                    output += createOutputItem("ثنائي (Binary)", decimalValues.map(d => isNaN(d) ? "NaN" : d.toString(2)).join(' '));
                    output += createOutputItem("ثماني (Octal)", decimalValues.map(d => isNaN(d) ? "NaN" : d.toString(8)).join(' '));
                    output += createOutputItem("نص (Text)", text || "NaN");
                } else if (format === "decimal") {
                    let decimalParts = inputValue.split(' ');
                    let decimalValues = decimalParts.map(part => parseInt(part, 10));
                    let text = decimalValues.map(d => isNaN(d) ? '' : String.fromCharCode(d)).join('');
                    
                    output += createOutputItem("ثنائي (Binary)", decimalValues.map(d => isNaN(d) ? "NaN" : d.toString(2)).join(' '));
                    output += createOutputItem("ثماني (Octal)", decimalValues.map(d => isNaN(d) ? "NaN" : d.toString(8)).join(' '));
                    output += createOutputItem("سداسي عشري (Hex)", decimalValues.map(d => isNaN(d) ? "NaN" : d.toString(16).toUpperCase()).join(' '));
                    output += createOutputItem("نص (Text)", text || "NaN");
                } else if (format === "octal") {
                    let octalParts = inputValue.split(' ');
                    let decimalValues = octalParts.map(part => parseInt(part, 8));
                    let text = decimalValues.map(d => isNaN(d) ? '' : String.fromCharCode(d)).join('');
                    
                    output += createOutputItem("عشري (Decimal)", decimalValues.map(d => isNaN(d) ? "NaN" : d).join(' '));
                    output += createOutputItem("ثنائي (Binary)", decimalValues.map(d => isNaN(d) ? "NaN" : d.toString(2)).join(' '));
                    output += createOutputItem("سداسي عشري (Hex)", decimalValues.map(d => isNaN(d) ? "NaN" : d.toString(16).toUpperCase()).join(' '));
                    output += createOutputItem("نص (Text)", text || "NaN");
                } else if (format === "text") {
                    let binary = inputValue.split('').map(c => c.charCodeAt(0).toString(2)).join(' ');
                    let decimalValues = inputValue.split('').map(c => c.charCodeAt(0)).join(' ');
                    let octal = inputValue.split('').map(c => c.charCodeAt(0).toString(8)).join(' ');
                    let hex = inputValue.split('').map(c => c.charCodeAt(0).toString(16).toUpperCase()).join(' ');

                    output += createOutputItem("ثنائي (Binary)", binary || "NaN");
                    output += createOutputItem("عشري (Decimal)", decimalValues || "NaN");
                    output += createOutputItem("ثماني (Octal)", octal || "NaN");
                    output += createOutputItem("سداسي عشري (Hex)", hex || "NaN");
                }
            } catch (error) {
                output = `<div class="output-item" style="color: #dc3545;"><i class="fas fa-exclamation-circle"></i> حدث خطأ في الإدخال!</div>`;
            }

            outputDiv.innerHTML = `<div class='output-box'>${output}</div>`;
            addCopyButtons();
        }

        function createOutputItem(label, value) {
            return `
                <div class="output-item">
                    <span>${label}</span>
                    <div class="tooltip">
                        <button class="copy-btn" onclick="copyToClipboard(this)" data-value="${escapeHtml(value)}">
                            <i class="far fa-copy"></i>
                        </button>
                        <span class="tooltiptext">نسخ إلى الحافظة</span>
                    </div>
                    <span class="output-value">${value}</span>
                </div>
            `;
        }




        function escapeHtml(unsafe) {
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        function addCopyButtons() {
            document.querySelectorAll('.copy-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    copyToClipboard(this);
                });
            });
        }

        function copyToClipboard(button) {
            const textToCopy = button.getAttribute('data-value');
            navigator.clipboard.writeText(textToCopy).then(() => {
                const tooltip = button.nextElementSibling;
                tooltip.textContent = 'تم النسخ!';
                setTimeout(() => {
                    tooltip.textContent = 'نسخ إلى الحافظة';
                }, 2000);
            });
        }

        // تهيئة أولية لضبط ارتفاع textarea
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('inputValue');
            adjustHeight(textarea);
        });
    </script>
</body>
</html>



