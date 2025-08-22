<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $classified_code = trim("CTF{sp4c3_1s_th3_f1n4l_fr0nti3r_Ey@d}");
    $encrypted_code = base64_encode($classified_code);
    setcookie("classified_code", $encrypted_code, time() + 3600, "/");
    
    echo '<script>window.location.href = window.location.href;</script>';
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AstroSec Access Portal</title>
    <style>
        :root {
            --space-blue: #0B3D91;
            --star-yellow: #FC3D21;
            --nebula-purple: #6A4C93;
            --void-black: #121212;
            --comet-white: #E6F1FF;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Orbitron', 'Courier New', monospace;
        }
        
        body {
            background: var(--void-black);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background-image: 
                radial-gradient(circle at 20% 30%, rgba(59, 0, 89, 0.3) 0%, transparent 30%),
                radial-gradient(circle at 80% 70%, rgba(252, 61, 33, 0.2) 0%, transparent 30%),
                url('https://images.unsplash.com/photo-1454789548928-9efd52dc4031?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: var(--comet-white);
        }
        
        .access-container {
            position: relative;
            width: 100%;
            max-width: 450px;
            background: rgba(11, 61, 145, 0.15);
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 0 30px rgba(252, 61, 33, 0.2);
            overflow: hidden;
            z-index: 1;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(252, 61, 33, 0.1);
        }
        
        .access-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border: 1px solid transparent;
            border-radius: 8px;
            background: linear-gradient(135deg, rgba(252, 61, 33, 0.3), rgba(106, 76, 147, 0.3)) border-box;
            -webkit-mask-composite: destination-out;
            mask-composite: exclude;
            pointer-events: none;
        }
        
        .access-container::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: 
                radial-gradient(circle, var(--star-yellow), transparent 70%),
                linear-gradient(45deg, transparent 48%, var(--star-yellow) 49%, var(--star-yellow) 51%, transparent 52%),
                linear-gradient(-45deg, transparent 48%, var(--star-yellow) 49%, var(--star-yellow) 51%, transparent 52%);
            background-size: 50% 50%, 30px 30px, 30px 30px;
            opacity: 0.1;
            z-index: -1;
            animation: space-pulse 15s linear infinite;
        }
        
        @keyframes space-pulse {
            0% { transform: rotate(0deg) scale(1); opacity: 0.1; }
            50% { transform: rotate(180deg) scale(1.1); opacity: 0.15; }
            100% { transform: rotate(360deg) scale(1); opacity: 0.1; }
        }
        
        .agency-logo {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        
        .agency-logo h2 {
            color: var(--comet-white);
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: 2px;
            text-shadow: 0 0 10px rgba(252, 61, 33, 0.7);
        }
        
        .agency-logo p {
            color: var(--comet-white);
            opacity: 0.7;
            font-size: 12px;
            letter-spacing: 1px;
        }
        
        .access-form .input-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .access-form .input-group input {
            width: 100%;
            padding: 15px 20px;
            border: 1px solid rgba(252, 61, 33, 0.3);
            border-radius: 4px;
            font-size: 14px;
            transition: all 0.3s ease;
            background-color: rgba(18, 18, 18, 0.7);
            color: var(--comet-white);
            letter-spacing: 1px;
        }
        
        .access-form .input-group input:focus {
            border-color: var(--star-yellow);
            outline: none;
            box-shadow: 0 0 0 2px rgba(252, 61, 33, 0.3);
        }
        
        .access-form .input-group input::placeholder {
            color: rgba(230, 241, 255, 0.5);
            letter-spacing: 1px;
        }
        
        .access-form button {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 4px;
            background: linear-gradient(135deg, var(--space-blue), var(--nebula-purple));
            color: var(--comet-white);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            letter-spacing: 1px;
            text-transform: uppercase;
            position: relative;
            overflow: hidden;
        }
        
        .access-form button::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                to bottom right,
                transparent 45%,
                rgba(252, 61, 33, 0.3) 50%,
                transparent 55%
            );
            transform: rotate(45deg);
            transition: all 0.5s ease;
        }
        
        .access-form button:hover {
            background: linear-gradient(135deg, var(--nebula-purple), var(--space-blue));
            box-shadow: 0 0 15px rgba(252, 61, 33, 0.5);
        }
        
        .access-form button:hover::before {
            left: 100%;
        }
        
        .access-form button:active {
            transform: scale(0.98);
        }
        
        .status-message {
            text-align: center;
            margin-top: 20px;
            animation: fadeIn 0.5s ease;
            font-size: 14px;
        }
        
        .warning {
            color: var(--star-yellow);
            font-weight: 500;
            margin-bottom: 10px;
            text-shadow: 0 0 5px rgba(252, 61, 33, 0.5);
        }
        
        .hint {
            color: var(--comet-white);
            background: rgba(106, 76, 147, 0.3);
            padding: 10px 15px;
            border-radius: 4px;
            display: inline-block;
            font-size: 12px;
            margin-top: 15px;
            border: 1px solid rgba(252, 61, 33, 0.2);
            letter-spacing: 1px;
        }
        
        .satellite {
            position: absolute;
            top: -30px;
            right: -30px;
            width: 100px;
            opacity: 0.3;
            transition: all 0.5s ease;
            filter: drop-shadow(0 0 5px rgba(252, 61, 33, 0.7));
        }
        
        .access-container:hover .satellite {
            transform: rotate(15deg);
        }
        
        .terminal-code {
            position: absolute;
            bottom: 10px;
            left: 10px;
            font-size: 10px;
            color: rgba(230, 241, 255, 0.2);
            user-select: none;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (max-width: 480px) {
            .access-container {
                padding: 30px 20px;
            }
            
            .agency-logo h2 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="access-container">
        <div class="agency-logo">
            <h2>ASTROSEC ACCESS PORTAL</h2>
            <p>CLASSIFIED LEVEL 9 CLEARANCE REQUIRED</p>
        </div>
        
        <form class="access-form" action="" method="post">
            <div class="input-group">
                <input type="text" name="agent_id" placeholder="AGENT ID" required>
            </div>
            <div class="input-group">
                <input type="password" name="access_code" placeholder="CRYPTO KEY" required>
            </div>
            <button type="submit">AUTHENTICATE</button>
        </form>
        
        <?php if(isset($_COOKIE['classified_code'])): ?>
            <div class="status-message">
                <p class="warning">ACCESS DENIED! INVALID CREDENTIALS.</p>
                <p class="hint">Hint: Have you checked your cookies lately?</p>
            </div>
        <?php endif; ?>
        
        <img src="https://www.svgrepo.com/show/354431/satellite-dish.svg" alt="Satellite" class="satellite">
        <div class="terminal-code">>_ TRANSMISSION_ID: XK-42-9A7B-FF00</div>
    </div>
</body>
</html>
            