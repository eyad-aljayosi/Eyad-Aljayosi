<?php
// إعداد قاعدة بيانات SQLite
$pdo = new PDO('sqlite:db.sqlite'); // ملف db.sqlite سيتم إنشاؤه تلقائيًا في المجلد الحالي

// التأكد من تفعيل التعامل مع الأخطاء
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// حذف الجدول إذا كان موجودًا
$pdo->exec("DROP TABLE IF EXISTS employees");

// إنشاء جدول
$pdo->exec("CREATE TABLE employees (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    department TEXT,
    salary INTEGER,
    notes TEXT
)");

// إدخال بيانات وهمية كثيرة
$names = ['Ali', 'Laila', 'Omar', 'Salma', 'Nour', 'Yazan', 'Dana', 'Zaid'];
$departments = ['IT', 'HR', 'Finance', 'Marketing'];

for ($i = 0; $i < 50; $i++) {
    $name = $names[array_rand($names)] . rand(100,999);
    $dept = $departments[array_rand($departments)];
    $salary = rand(500, 3000);
    $note = '';

    $pdo->exec("INSERT INTO employees (name, department, salary, notes) VALUES ('$name', '$dept', $salary, '$note')");
}

// إدخال سجل الفلاج
$flag = "CTF{hidden_flag_in_the_middle}";
$pdo->exec("INSERT INTO employees (name, department, salary, notes) VALUES ('admin', 'Secret', 9999, '$flag')");

echo "✔️ قاعدة البيانات تم إنشاؤها باستخدام SQLite!";
?>
