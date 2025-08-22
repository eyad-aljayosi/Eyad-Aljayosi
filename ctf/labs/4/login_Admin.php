<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal - CTF Challenge</title>
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --danger: #f72585;
            --success: #4cc9f0;
            --dark: #1b263b;
            --light: #f8f9fa;
        }
        
        body {
            font-family: 'Poppins', 'Segoe UI', Tahoma, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: var(--dark);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .container {
            max-width: 800px;
            width: 90%;
            margin: 20px auto;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .login-card:hover {
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header h2 {
            color: var(--primary);
            font-size: 2rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }
        
        .login-header p {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark);
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
            background-color: #f8f9fa;
        }
        
        .form-group input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
            background-color: white;
        }
        
        .btn {
            width: 100%;
            padding: 14px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 0.5rem;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .btn:hover {
            background-color: var(--secondary);
            transform: translateY(-2px);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        .response {
            margin-top: 2rem;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .success {
            background-color: rgba(76, 201, 240, 0.1);
            padding: 1.25rem;
            border-radius: 8px;
            border-left: 4px solid var(--success);
            margin-bottom: 1rem;
            color: var(--dark);
        }
        
        .error {
            background-color: rgba(247, 37, 133, 0.1);
            padding: 1.25rem;
            border-radius: 8px;
            border-left: 4px solid var(--danger);
            margin-bottom: 1rem;
            color: var(--dark);
        }
        
        .flag {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            font-size: 1.1rem;
            padding: 1rem;
            background-color: #fff3bf;
            border-radius: 8px;
            border-left: 4px solid #ffd43b;
            margin-top: 1.5rem;
            word-break: break-all;
            text-align: center;
            color: #d9480f;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.5rem;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        
        th {
            background-color: var(--primary);
            color: white;
            font-weight: 600;
        }
        
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        tr:hover {
            background-color: #e9ecef;
        }
        
        .user-data {
            margin-top: 1.5rem;
            padding: 1.25rem;
            background-color: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }
        
        .user-data h4 {
            margin-top: 0;
            color: var(--primary);
            border-bottom: 2px solid var(--primary);
            padding-bottom: 0.5rem;
        }
        
        .ctf-hint {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.85rem;
            color: #6c757d;
            font-style: italic;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="login-card">
            <div class="login-header">
                <h2>Admin Portal</h2>
                <p>Secure access for administrators only</p>
            </div>
            
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required placeholder="Enter admin username">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Enter your password">
                </div>
                
                <button type="submit" class="btn">Login</button>
            </form>
            
            <p class="ctf-hint">Note: This is a CTF challenge. Test your SQL injection skills!</p>
            
            <div class="response">
            <?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ctf_challenge";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("<div class='error'>Database connection failed</div>");
}

// Set charset
$conn->set_charset("utf8mb4");

// Get input data
$user = $_POST['username'] ?? '';
$pass = $_POST['password'] ?? '';

// Fix comment syntax issues
/*$user = preg_replace('/--(\S)/', '-- $1', $user);
$user = str_replace(['--', '#'], ['-- ', '# '], $user);*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Vulnerable SQL query
        $sql = "SELECT * FROM users WHERE username='$user' AND password='$pass'";
        $result = $conn->query($sql);

        if ($result === false) {
            throw new mysqli_sql_exception("SQL query failed");
        } elseif ($result->num_rows > 0) {
            // Successful login
            echo "<div class='success'>";
            echo "<h3>Login successful!</h3>";
            echo "<p>Welcome, " . htmlspecialchars($user) . ".</p>";
            
            // Display user data
            echo "<div class='user-data'>";
            echo "<h4>User Information:</h4>";
            echo "<table>";
            
            $fields = $result->fetch_fields();
            echo "<tr>";
            foreach ($fields as $field) {
                echo "<th>" . htmlspecialchars($field->name) . "</th>";
            }
            echo "</tr>";
            
            $result->data_seek(0);
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td>" . htmlspecialchars($value) . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
            
            // Get flag
            $secret_sql = "SELECT flag FROM secrets WHERE admin_only='yes'";
            $secret_result = $conn->query($secret_sql);
            
            if ($secret_result && $secret_result->num_rows > 0) {
                echo "<div class='flag'>";
                while($secret_row = $secret_result->fetch_assoc()) {
                    echo "SECRET FLAG: " . htmlspecialchars($secret_row["flag"]);
                }
                echo "</div>";
            }
            echo "</div>";
        } else {
            // Valid query but no matching users
            echo "<div class='error'>";
            echo "Invalid username or password";
            echo "</div>";
        }
    } catch (mysqli_sql_exception $e) {
        // Handle SQL syntax errors
        echo "<div class='error'>";
        echo "<h3>SQL Error</h3>";
        echo "<p>Invalid SQL syntax in your input</p>";
        echo "</div>";
    } catch (Exception $e) {
        // Handle other exceptions
        echo "<div class='error'>";
        echo "<h3>Error</h3>";
        echo "<p>An unexpected error occurred</p>";
        echo "</div>";
    }
}

$conn->close();
?>
            </div>
        </div>
    </div>
</body>
</html>