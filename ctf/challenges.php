<?php
session_start();
include '../conn.php';

if (!isset($_SESSION['usermail']) || !isset($_SESSION['id'])) {
    $_SESSION['login_befor'] = "قم بعمل تسجيل دخول بالبداية";
    header("Location: ../login.php");
    exit();
}

include '../session_check.php';

// استخدام Prepared Statement لتجنب SQL Injection
$query = "SELECT * FROM challenges";
$stmt = $conn->prepare($query);

// تنفيذ الاستعلام
$stmt->execute();
$result = $stmt->get_result();

// ترتيب البيانات حسب الفئة (category)
$challenges_by_category = [];
while ($row = $result->fetch_assoc()) {
    $challenges_by_category[$row['category']][] = $row;
}

// تأكد من إغلاق الاستعلام بعد التنفيذ
$stmt->close();


/*session_start();
include '../conn.php';

if (!isset($_SESSION['usermail']) || !isset($_SESSION['id'])) {
    $_SESSION['login_befor'] = "قم بعمل تسجيل دخول بالبداية";
    header("Location: ../login.php");
    exit();
}

include '../session_check.php';

$query = "SELECT * FROM challenges";
$result = $conn->query($query);

$challenges_by_category = [];
while ($row = $result->fetch_assoc()) {
    $challenges_by_category[$row['category']][] = $row;
}*/
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>التحديات المتاحة - CTF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>

        *{
            font-family: 'Tajawal', 'Cairo', sans-serif;
    }

        :root {
            --primary: #07427d;
            --primary-light: #336eab;
            --primary-dark: #052d56;
            --easy: #28a745;
            --medium: #ffc107;
            --hard: #dc3545;
            --text: #2b2d42;
            --text-light: #8d99ae;
            --bg: #f8f9fa;
            --card-bg: #ffffff;
            --border: #e9ecef;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background-image: url('../img/f1a3bcd7-a890-41e1-8d67-7e87e7ec3f6a.jpg');
            background-size: cover;
            background-position: center center;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            color: var(--text);
            min-height: 100vh;
            line-height: 1.6;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1px 10px;
            
        }

        .page-header {
            text-align: center;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            padding: 15px 50px;
            border-radius: 30px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            margin: 20px auto;
            display: inline-block;
        }


        .category-section {
            margin-bottom: 3.5rem;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px 33px 40px 33px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .category-title {
            font-size: 1.9rem;
            font-weight: 700;
            color: #07427d;
            margin-bottom:1.5rem;
            margin-top: 6px;
            padding-bottom: 0.7rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            text-transform: uppercase;
        }

        .challenges-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .challenge-card {
            background: var(--card-bg);
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            height: 100%;
            border-top: 6px solid;
        }

        .challenge-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .challenge-header {
            padding: 1.2rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .challenge-difficulty {
            font-size: 0.9rem;
            font-weight: 700;
            padding: 0.4rem 1rem;
            border-radius: 1rem;
            color: white;
        }

        .difficulty-easy {
            background-color: var(--easy);
        }

        .difficulty-medium {
            background-color: var(--medium);
        }

        .difficulty-hard {
            background-color: var(--hard);
        }

        .challenge-points {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .challenge-body {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .challenge-title {
            font-size: 23px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            margin: 0;
        }

        .challenge-icon {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .challenge-footer {
            padding: 1.2rem;
            background-color: rgba(248, 249, 250, 0.9);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
        }

        .challenge-category {
            font-size: 1rem;
            color: #677997;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
        }

        .challenge-category.reverse-engineering {
    font-size: 0.9rem;
}

        .challenge-btn {
            background: linear-gradient(to right, var(--primary), var(--primary-light));
            color: white;
            border: none;
            padding: 0.6rem 1.3rem;
            border-radius: 8px;
            font-size: 0.90rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(7, 66, 125, 0.2);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .challenge-btn.reverse-engineering{
  
            font-size: 0.8rem;
  
        }

        .challenge-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(7, 66, 125, 0.3);
            background: linear-gradient(to right, var(--primary-dark), var(--primary));
        }

        /* أيقونات الفئات */
        .category-icon {
            color: var(--primary);
            font-size: 1.5rem;
        }

        .web-icon { color: #07427d; }
        .crypto-icon { color: #07427d; }
        .forensics-icon { color: #28a745; }
        .pwn-icon { color: #dc3545; }
        .reverse-icon { color: #07427d; }

        /* تأثيرات الحركة */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .challenge-card {
            animation: fadeIn 0.5s ease-out forwards;
            opacity: 0;
        }

        .challenge-card:nth-child(1) { animation-delay: 0.1s; }
        .challenge-card:nth-child(2) { animation-delay: 0.2s; }
        .challenge-card:nth-child(3) { animation-delay: 0.3s; }
        .challenge-card:nth-child(4) { animation-delay: 0.4s; }
        .challenge-card:nth-child(5) { animation-delay: 0.5s; }
        .challenge-card:nth-child(6) { animation-delay: 0.6s; }

        @media (max-width: 1024px) {
            .challenges-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 1.5rem 1rem;
            }
            
            .page-title {
                font-size: 2rem;
                padding: 10px 30px;
            }
            
            .category-title {
                font-size: 1.5rem;
            }
            
            .challenges-grid {
                grid-template-columns: 1fr;
            }
        }

    </style>
</head>
<body>
<?php include 'header-ctf.php'; ?>

<div class="container">
    <div class="page-header">
        <h1 class="page-title">
              تحديات CTF المتاحة  <i class="fas fa-flag-checkered"></i>
        </h1>
    </div>

    <?php 
    $category_icons = [
        'Web' => ['fa-globe', 'web-icon'],
        'Crypto' => ['fa-lock', 'crypto-icon'],
        'Forensics' => ['fa-search', 'forensics-icon'],
        'Pwn' => ['fa-bug', 'pwn-icon'],
        'Reverse Engineering' => ['fa-code', 'reverse-icon'],
    ];
    
    foreach ($challenges_by_category as $category => $challenges) { 
        $icon = $category_icons[$category] ?? ['fa-puzzle-piece', ''];
    ?>
        <div class="category-section">
            <h2 class="category-title">
                <i class="fas <?php echo $icon[0]; ?> <?php echo $icon[1]; ?> category-icon"></i>
                <?php echo htmlspecialchars($category); ?>
            </h2>
            
            <div class="challenges-grid">
                <?php foreach ($challenges as $index => $row) { 
                    $difficulty_class = 'difficulty-' . strtolower($row['difficulty']);
                    $icon = $category_icons[$row['category']] ?? ['fa-puzzle-piece', ''];
                    $border_color = ($row['difficulty'] == 'Hard') ? 'var(--hard)' : 
                                  (($row['difficulty'] == 'Medium') ? 'var(--medium)' : 'var(--easy)');
                ?>
                    <a href="<?= $row['id'] ?> " class="challenge-card" style="animation-delay: <?php echo ($index % 6) * 0.1; ?>s; border-top-color: <?php echo $border_color; ?>">


                        <div class="challenge-header">
                            <span class="challenge-difficulty <?php echo $difficulty_class; ?>">
                                <?php echo htmlspecialchars($row['difficulty']); ?>
                            </span>
                            <span class="challenge-points">
                                <i class="fas fa-star"></i> <?php echo htmlspecialchars($row['points']); ?> نقطة
                            </span>
                        </div>
                        
                        <div class="challenge-body">
                            <i class="fas <?php echo $icon[0]; ?> <?php echo $icon[1]; ?> challenge-icon"></i>
                            <h3 class="challenge-title">
                                <?php echo htmlspecialchars($row['title']); ?>
                            </h3>
                        </div>
                        
                        <div class="challenge-footer">
                        <span class="challenge-category <?php echo ($row['category'] == 'Reverse Engineering') ? 'reverse-engineering' : ''; ?>">
                            <i class="fas fa-tag"></i> <?php echo htmlspecialchars($row['category']); ?>
                        </span>

                        <button class="challenge-btn <?php echo ($row['category'] == 'Reverse Engineering') ? 'reverse-engineering' : ''; ?>">
                               ابدأ التحدي <i class="fas fa-arrow-left"></i>
                        </button>

                        </div>
                    </a>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</div>
                    

<?php include '../footer.php'; ?>

<script>
    // تأثيرات تفاعلية بسيطة
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.challenge-card');
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                const title = card.querySelector('.challenge-title');
                const icon = card.querySelector('.challenge-icon');
                if (title) title.style.transform = 'scale(1.05)';
                if (icon) icon.style.transform = 'rotate(10deg)';
            });
            
            card.addEventListener('mouseleave', () => {
                const title = card.querySelector('.challenge-title');
                const icon = card.querySelector('.challenge-icon');
                if (title) title.style.transform = 'scale(1)';
                if (icon) icon.style.transform = 'rotate(0)';
            });
        });
    });
</script>
</body>
</html>



