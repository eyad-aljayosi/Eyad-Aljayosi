

<?php
session_start();
include '../conn.php';

if (!isset($_SESSION['usermail']) || !isset($_SESSION['id'])) {
    $_SESSION['login_befor'] = "قم بعمل تسجيل دخول بالبداية";
    header("Location: ../login.php");
    exit();
}

include '../session_check.php'; 

$user_id = $_SESSION['id']; 

// استعلام للحصول على اسم المستخدم وعدد التحديات التي حلها
$user_info_query = "SELECT user_form.name, COUNT(user_scores.challenge_id) AS challenges_solved FROM user_form 
                    LEFT JOIN user_scores ON user_form.id = user_scores.user_id
                    WHERE user_form.id = $user_id
                    GROUP BY user_form.id";
$user_info_result = mysqli_query($conn, $user_info_query);
$user_info = mysqli_fetch_assoc($user_info_result);

$query = "SELECT user_form.name, SUM(user_scores.points) AS total_points FROM user_form 
          JOIN user_scores ON user_form.id = user_scores.user_id 
          GROUP BY user_scores.user_id ORDER BY total_points DESC";
$result = mysqli_query($conn, $query);

if ($user_info['challenges_solved'] == 0) {
    $user_rank_display = "غير مصنف";
} else {
    $rank_query = "SELECT COUNT(*) AS rank FROM ( 
                    SELECT user_form.id, SUM(user_scores.points) AS total_points 
                    FROM user_form 
                    JOIN user_scores ON user_form.id = user_scores.user_id 
                    GROUP BY user_scores.user_id 
                    ORDER BY total_points DESC) AS subquery 
                    WHERE subquery.total_points >= (SELECT SUM(user_scores.points) 
                                                     FROM user_scores 
                                                     WHERE user_scores.user_id = $user_id)";
    $rank_result = mysqli_query($conn, $rank_query);

    // تحقق مما إذا كانت الاستعلامات قد رجعت بيانات
    if ($rank_result && mysqli_num_rows($rank_result) > 0) {
        $rank_data = mysqli_fetch_assoc($rank_result);
        $user_rank_display = $rank_data['rank']; // مركز المستخدم بناءً على مجموع النقاط
    } else {
        // إذا لم تُرجع الاستعلامات بيانات، عين قيمة افتراضية للمركز
        $user_rank_display = "غير مصنف";
    }
}
?>


<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة المتصدرين</title>
    <style>
        body {
            font-family: 'Tajawal', 'Cairo', sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;

            background-image: url('../img/background6.jpg'); 
            background-size: cover;
        }

        .container {
            text-align: center;
            width: 70%;
            margin: 0 auto;
            padding: 0 6px 6px 6px;
            background-image: linear-gradient(white, white), linear-gradient(45deg, #07427d, #07427d);
            background-clip: content-box, border-box;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
            margin-bottom: 120px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 20px;
        }

        th, td {
            padding: 20px;
            font-size: 1.4rem;
            text-align: center;
        }

        th {
            background-color: #07427d;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #e2e2e2;
        }

        .rank {
            font-size: 1.8rem;
            font-weight: bold;
            color: #07427d;
        }

        .name {
            font-size: 1.4rem;
            font-weight: 500;
            color: #333;
        }

        .points {
            font-size: 1.4rem;
            color: #07427d;
            font-weight: bold;
        }

        .top-three {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .top-three .rank-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 10px;
            font-size: 1.2rem;
            font-weight: bold;
            color: #333333;
            text-align: center;
            line-height: 30px;
        }

        .rank-1 .rank-icon {
            background-color: gold;
        }

        .rank-2 .rank-icon {
            background-color: silver;
        }

        .rank-3 .rank-icon {
            background-color: #cd7f32;
        }

        .title-header {
            font-family: 'Poppins', sans-serif;
            font-size: 30px;
            font-weight: 700;
            color: #ffffff;
            text-align: center;
            background: linear-gradient(135deg, #2093df, #07427d);
            padding: 15px 40px;
            border-radius: 30px;
            box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.1);
            max-width: 350px;
            margin: 20px auto;
            display: block;
            margin-bottom: 30px;
            position: relative;
        }

        .title-header::before {
            content: '🏆';
            position: absolute;
            left: 10px;
            top: -25px;
            font-size: 35px;
        }

        .title-header::after {
            content: '🏅';
            position: absolute;
            right: 10px;
            bottom: -25px;
            font-size: 35px;
        }


        /* تصميم بطاقة اللاعب */

        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;600;700&display=swap');
@import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500&display=swap');

:root {
    --primary-dark: #07427d;
    --primary-main: #1971c2;
    --primary-light: #4dabf7;
    --primary-lighter: #e7f5ff;
    --dark-bg: #0f172a;
    --card-bg: #1e293b;
    --card-surface: #334155;
    --text-primary: #f8fafc;
    --text-secondary: #94a3b8;
    --text-tertiary: #64748b;
    --border-color: rgba(99, 179, 237, 0.15);
}

.ctf-player-card {
    font-family: 'Tajawal', sans-serif;
    position: relative;
    background: linear-gradient(145deg, #07427d, #083562);    
    border-radius: 10px;
    padding: 6px 25px;
    width: 340px;
    height: 250px;
    box-shadow: 0 6px 18px rgba(7, 66, 125, 0.25);
    margin: 10px 120px;
    border: 1px solid var(--border-color);
    overflow: hidden;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}

.ctf-player-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(7, 66, 125, 0.35);
    border-color: rgba(99, 179, 237, 0.3);
}

.player-badge {
    position: absolute;
    top: -22px;
    left: -22px;
    width: 80px;
    height: 80px;
    background: var(--primary-dark);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid var(--primary-light);
    z-index: 2;
    transition: all 0.3s ease;
}

.ctf-player-card:hover .player-badge {
    transform: scale(1.05);
    box-shadow: 0 0 0 4px rgba(77, 171, 247, 0.2);
}

.badge-icon {
    width: 36px;
    height: 36px;
    transition: all 0.3s ease;
}

.badge-glow {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(77,171,247,0.25) 0%, rgba(77,171,247,0) 70%);
    animation: pulse 3s infinite ease-in-out;
}

.player-title {
    color: var(--primary-light);
    font-size: 1.7rem;
    margin: 0 0 25px 0;
    text-align: right;
    font-weight: 600;
    position: relative;
    padding-bottom: 10px;
    letter-spacing: 0.3px;
    margin-bottom: 10px;

}

.player-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    right: 0;
    width: 50px;
    height: 2px;
    background: linear-gradient(90deg, var(--primary-light), transparent);
    border-radius: 2px;
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 14px;
}

.info-item {
    display: flex;
    align-items: center;
    direction: rtl;
    background: rgba(25, 113, 194, 0.08);
    border-radius: 6px;
    padding: 6px;
    transition: all 0.3s ease;
    border-right: 2px solid transparent;
}

.info-item:hover {
    background: rgba(25, 113, 194, 0.12);
    border-right: 2px solid var(--primary-light);
    transform: translateX(-3px);
}

.info-icon {
    width: 22px;
    height: 22px;
    margin-left: 12px;
    flex-shrink: 0;
    opacity: 0.8;
}

.info-content {
    display: flex;
    flex-direction: column;
    text-align: right;
    flex-grow: 1;
}

.info-label {
    color: var(--text-secondary);
    font-size: 0.8rem;
    margin-bottom: 4px;
    font-weight: 500;
}

.info-value {
    color: var(--text-primary);
    font-size: 1rem;
    font-weight: 600;
    font-family: 'IBM Plex Mono', monospace;
    letter-spacing: 0.3px;
}

.card-highlight {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(120deg, rgba(25, 113, 194, 0.03) 0%, transparent 60%);
    pointer-events: none;
}

.card-corner {
    position: absolute;
    top: 0;
    right: 0;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 0 50px 50px 0;
    border-color: transparent var(--primary-dark) transparent transparent;
    opacity: 0.5;
}

@keyframes pulse {
    0% { opacity: 0.3; transform: scale(0.98); }
    50% { opacity: 0.6; transform: scale(1); }
    100% { opacity: 0.3; transform: scale(0.98); }
}

/* تأثيرات إضافية */
.ctf-player-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: 
        linear-gradient(135deg, transparent 48%, var(--primary-light) 49%, var(--primary-light) 51%, transparent 52%),
        linear-gradient(45deg, transparent 48%, var(--primary-light) 49%, var(--primary-light) 51%, transparent 52%);
    background-size: 10px 10px;
    opacity: 0.03;
    pointer-events: none;
}

/* تصميم متجاوب */
@media (max-width: 480px) {
    .ctf-player-card {
        width: 88%;
        padding: 22px 18px;
    }
    
    .player-title {
        font-size: 1.35rem;
        margin-bottom: 22px;
    }
    
    .info-item {
        padding: 10px;
    }
    
    .info-value {
        font-size: 0.95rem;
    }
    
    .player-badge {
        width: 70px;
        height: 70px;
        top: -18px;
        left: -18px;
    }
    
    .badge-icon {
        width: 32px;
        height: 32px;
    }
}
    </style>
</head>
<body>

<?php include 'header-ctf.php'; ?>

<section class="ctf-player-card">
    <div class="player-badge">
        <div class="badge-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#4dabf7">
                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11V11.99z"/>
            </svg>
        </div>
        <div class="badge-glow"></div>
    </div>
    
    <div class="player-info">
        <h2 class="player-title">معلومات اللاعب</h2>
        
        <div class="info-grid">
            <div class="info-item">
                <div class="info-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#4dabf7">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
                <div class="info-content">
                    <span class="info-label">الاسم:</span>
                    <span class="info-value"><?php echo htmlspecialchars($user_info['name']); ?></span>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#4dabf7">
                        <path d="M19 5h-2V3H7v2H5c-1.1 0-2 .9-2 2v1c0 2.55 1.92 4.63 4.39 4.94.63 1.5 1.98 2.63 3.61 2.96V19H7v2h10v-2h-4v-3.1c1.63-.33 2.98-1.46 3.61-2.96C19.08 12.63 21 10.55 21 8V7c0-1.1-.9-2-2-2zM5 8V7h2v3.82C5.84 10.4 5 9.3 5 8zm14 0c0 1.3-.84 2.4-2 2.82V7h2v1z"/>
                    </svg>
                </div>
                <div class="info-content">
                    <span class="info-label">التحديات المحلولة:</span>
                    <span class="info-value"><?php echo htmlspecialchars($user_info['challenges_solved']); ?></span>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#4dabf7">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z"/>
                    </svg>
                </div>
                <div class="info-content">
                    <span class="info-label">المركز:</span>
                    <span class="info-value"><?php echo $user_rank_display; ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card-highlight"></div>
    <div class="card-corner"></div>
</section>

<h2 class="title-header">لوحة المتصدرين</h2>

<div class="container">
    <table dir="rtl">
        <tr>
            <th>الترتيب</th>
            <th>الاسم</th>
            <th>النقاط</th>
        </tr>
        <?php 
        $rank = 1; 
        while ($row = mysqli_fetch_assoc($result)) { 
            $rank_class = "";
            
            if ($rank == 1) {
                $rank_class = "rank-1";
            } elseif ($rank == 2) {
                $rank_class = "rank-2";
            } elseif ($rank == 3) {
                $rank_class = "rank-3";
            }
        ?>
        <tr>
            <td class="rank <?php echo $rank_class; ?>">
                <div class="top-three">
                    <div class="rank-icon"><?php echo $rank++; ?></div>
                </div>
            </td>
            <td class="name"><?php echo htmlspecialchars($row['name']); ?></td>
            <td class="points"><?php echo htmlspecialchars($row['total_points']); ?></td>
        </tr>
        <?php } ?>
    </table>
</div>


</body>
</html>
