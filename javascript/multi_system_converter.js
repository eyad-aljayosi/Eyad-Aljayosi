function adjustHeight(element) {
    element.style.height = "60px";
    element.style.height = (element.scrollHeight) + "px";
}

function convertNumber() {
    let format = document.getElementById("format").value;
    let inputValue = document.getElementById("inputValue").value.trim();
    let outputDiv = document.getElementById("output");
    let initialMessage = document.getElementById("initialMessage");
    let resultsMessage = document.getElementById("resultsMessage");
    outputDiv.innerHTML = "";

    // إخفاء النص "سوف تظهر النتائج هنا" وإظهار "النتائج" عند إدخال النص
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
            // معالجة كل قيمة ثنائية على حدة
            let binaryParts = inputValue.split(' ');
            let decimalValues = binaryParts.map(part => parseInt(part, 2));
            let text = decimalValues.map(d => isNaN(d) ? '' : String.fromCharCode(d)).join('');
            
            output += `<div class="output-item"><span>عشري:</span> ${decimalValues.map(d => isNaN(d) ? "NaN" : d).join(' ')}</div>`;
            output += `<div class="output-item"><span>ثماني:</span> ${decimalValues.map(d => isNaN(d) ? "NaN" : d.toString(8)).join(' ')}</div>`;
            output += `<div class="output-item"><span>سداسي عشري:</span> ${decimalValues.map(d => isNaN(d) ? "NaN" : d.toString(16).toUpperCase()).join(' ')}</div>`;
            output += `<div class="output-item"><span>نص:</span> ${text || "NaN"}</div>`;
        } else if (format === "hexadecimal") {
            // معالجة كل قيمة سداسية عشرية على حدة
            let hexParts = inputValue.split(' ');
            let decimalValues = hexParts.map(part => parseInt(part, 16));
            let text = decimalValues.map(d => isNaN(d) ? '' : String.fromCharCode(d)).join('');
            
            output += `<div class="output-item"><span>عشري:</span> ${decimalValues.map(d => isNaN(d) ? "NaN" : d).join(' ')}</div>`;
            output += `<div class="output-item"><span>ثنائي:</span> ${decimalValues.map(d => isNaN(d) ? "NaN" : d.toString(2)).join(' ')}</div>`;
            output += `<div class="output-item"><span>ثماني:</span> ${decimalValues.map(d => isNaN(d) ? "NaN" : d.toString(8)).join(' ')}</div>`;
            output += `<div class="output-item"><span>نص:</span> ${text || "NaN"}</div>`;
        } else if (format === "decimal") {
            // معالجة كل قيمة عشرية على حدة
            let decimalParts = inputValue.split(' ');
            let decimalValues = decimalParts.map(part => parseInt(part, 10));
            let text = decimalValues.map(d => isNaN(d) ? '' : String.fromCharCode(d)).join('');
            
            output += `<div class="output-item"><span>ثنائي:</span> ${decimalValues.map(d => isNaN(d) ? "NaN" : d.toString(2)).join(' ')}</div>`;
            output += `<div class="output-item"><span>ثماني:</span> ${decimalValues.map(d => isNaN(d) ? "NaN" : d.toString(8)).join(' ')}</div>`;
            output += `<div class="output-item"><span>سداسي عشري:</span> ${decimalValues.map(d => isNaN(d) ? "NaN" : d.toString(16).toUpperCase()).join(' ')}</div>`;
            output += `<div class="output-item"><span>نص:</span> ${text || "NaN"}</div>`;
        } else if (format === "octal") {
            // معالجة كل قيمة ثمانية على حدة
            let octalParts = inputValue.split(' ');
            let decimalValues = octalParts.map(part => parseInt(part, 8));
            let text = decimalValues.map(d => isNaN(d) ? '' : String.fromCharCode(d)).join('');
            
            output += `<div class="output-item"><span>عشري:</span> ${decimalValues.map(d => isNaN(d) ? "NaN" : d).join(' ')}</div>`;
            output += `<div class="output-item"><span>ثنائي:</span> ${decimalValues.map(d => isNaN(d) ? "NaN" : d.toString(2)).join(' ')}</div>`;
            output += `<div class="output-item"><span>سداسي عشري:</span> ${decimalValues.map(d => isNaN(d) ? "NaN" : d.toString(16).toUpperCase()).join(' ')}</div>`;
            output += `<div class="output-item"><span>نص:</span> ${text || "NaN"}</div>`;
        } else if (format === "text") {
            // هذا الجزء يبقى كما هو لأنه يعمل بشكل صحيح
            let binary = inputValue.split('').map(c => c.charCodeAt(0).toString(2)).join(' ');
            let decimalValues = inputValue.split('').map(c => c.charCodeAt(0)).join(' ');
            let octal = inputValue.split('').map(c => c.charCodeAt(0).toString(8)).join(' ');
            let hex = inputValue.split('').map(c => c.charCodeAt(0).toString(16).toUpperCase()).join(' ');

            output += `<div class="output-item"><span>ثنائي:</span> ${binary || "NaN"}</div>`;
            output += `<div class="output-item"><span>عشري:</span> ${decimalValues || "NaN"}</div>`;
            output += `<div class="output-item"><span>ثماني:</span> ${octal || "NaN"}</div>`;
            output += `<div class="output-item"><span>سداسي عشري:</span> ${hex || "NaN"}</div>`;
        }
    } catch (error) {
        output = "<div class='output-item' style='color: red;'>حدث خطأ في الإدخال!</div>";
    }

    outputDiv.innerHTML = `<div class='output-box'>${output}</div>`;
}