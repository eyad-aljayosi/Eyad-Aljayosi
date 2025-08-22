<?php
// إعداد الاتصال بقاعدة بيانات SQLite
$pdo = new PDO('sqlite:db.sqlite');

// إذا تم إرسال الفورم
if (isset($_POST['filter'])) {
    // استلام البيانات من الحقل
    $filter = $_POST['filter'];

    // استعلام SQL بدون حماية (SQL Injection متعمدة)
    $query = "SELECT * FROM employees WHERE department = '$filter'"; 
    try {
        $result = $pdo->query($query);

        // التحقق من وجود نتائج
        if ($result->rowCount() > 0) {
            echo "<h3>نتائج البحث:</h3>";
            echo "<pre>";

            // عرض النتائج
            foreach ($result as $row) {
                echo "ID: {$row['id']} | Name: {$row['name']} | Dept: {$row['department']} | Salary: {$row['salary']} | Notes: {$row['notes']}\n";
            }

            echo "</pre>";
        } else {
            // في حال عدم وجود نتائج
            echo "<p style='color:orange;'>لم يتم العثور على أي نتائج للقسم: $filter. حاول إدخال قسم آخر.</p>";
        }
    } catch (PDOException $e) {
        // في حال حدوث خطأ أثناء تنفيذ الاستعلام
        echo "<p style='color:red;'>حدث خطأ أثناء تنفيذ الاستعلام: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تحدي CTF - البحث في قاعدة البيانات</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            color: #333;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        h1 {
            background-color: #333;
            color: white;
            padding: 20px;
            margin: 0;
        }

        .form-container {
            background-color: #fff;
            width: 40%;
            margin: 30px auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container input[type="text"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
        }

        .form-container button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #45a049;
        }

        .results-container {
            margin: 20px auto;
            width: 80%;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .results-container pre {
            font-size: 1em;
            color: #333;
        }

        footer {
            background-color: #333;
            color: white;
            padding: 10px 0;
            text-align: center;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>

<h1>تحدي CTF - البحث في قاعدة البيانات</h1>

<div class="form-container">
    <h2>🔍 ابحث حسب القسم (department)</h2>
    <form method="POST">
        <input name="filter" type="text" placeholder="مثلاً IT أو HR أو Finance" required><br><br>
        <button type="submit">بحث</button>
    </form>
</div>

<footer>
    <p>© 2025 جميع الحقوق محفوظة.</p>
</footer>

</body>
</html>
