<!DOCTYPE html>
<html>
<head>
    <title>Store Search</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-box {
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 10px;
            width: 300px;
        }
        button {
            padding: 10px 15px;
            background: #0066cc;
            color: white;
            border: none;
            cursor: pointer;
        }
        .results {
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .featured {
            background-color: #fffaf0;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
        .hint {
            font-size: 14px;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Product Search</h1>
    
    <div class="search-box">
        <form method="GET">
            <input type="text" name="query" placeholder="Search products..." required>
            <button type="submit">Search</button>
        </form>
    </div>
    
    <?php
    $conn = new mysqli('localhost', 'root', '', 'store_db');
    
    if (isset($_GET['query'])) {
        $search = $_GET['query'];
        
        // Basic filtering that can be bypassed
        $search = str_replace("'", "''", $search);
        $search = str_replace("--", "", $search);
        
        $query = "SELECT * FROM products WHERE name LIKE '%$search%' AND is_featured = false";
        $result = $conn->query($query);
        
        if ($result === false) {
            echo "<div class='error'>Error in search query</div>";
        } elseif ($result->num_rows > 0) {
            echo "<div class='results'>";
            echo "<h3>Search Results:</h3>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Name</th><th>Price</th><th>Stock</th></tr>";
            
            while($row = $result->fetch_assoc()) {
                $featured_class = $row['is_featured'] ? 'featured' : '';
                echo "<tr class='$featured_class'>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>$" . htmlspecialchars($row['price']) . "</td>";
                echo "<td>" . htmlspecialchars($row['stock']) . "</td>";
                echo "</tr>";
            }
            
            echo "</table>";
            echo "</div>";
            
            // Hidden flag mechanism
            if (strpos($query, 'UNION') !== false || strpos($search, 'config') !== false) {
                $flag_query = "SELECT value FROM config WHERE param = 'flag'";
                $flag_result = $conn->query($flag_query);
                
                if ($flag_result && $flag_result->num_rows > 0) {
                    $flag = $flag_result->fetch_assoc()['value'];
                    echo "<div class='results'>";
                    echo "<h3>System Message:</h3>";
                    echo "<p>Secret Flag: " . htmlspecialchars($flag) . "</p>";
                    echo "</div>";
                }
            }
        } else {
            echo "<p>No products found matching your search.</p>";
        }
    }
    ?>
    
    <div class="hint">
        <p>Note: Some products are only available to featured members.</p>
    </div>
</body>
</html>