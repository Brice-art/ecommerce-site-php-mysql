<?php
// Test database connection
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_NAME', $_ENV['DB_NAME']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASS', $_ENV['DB_PASS']);    

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2 style='color: green;'>✅ Database Connected Successfully!</h2>";
    echo "<p>Host: " . DB_HOST . "</p>";
    echo "<p>Database: " . DB_NAME . "</p>";
    
    // Test if tables exist
    echo "<h3>Tables in database:</h3>";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "<p style='color: orange;'>⚠️ No tables found! You need to import your SQL file.</p>";
    } else {
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>" . $table . "</li>";
        }
        echo "</ul>";
        
        // Count products
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
        $result = $stmt->fetch();
        echo "<p><strong>Products in database:</strong> " . $result['count'] . "</p>";
        
        // Count users
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
        $result = $stmt->fetch();
        echo "<p><strong>Users in database:</strong> " . $result['count'] . "</p>";
    }
    
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>❌ Connection Failed!</h2>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<h3>Troubleshooting:</h3>";
    echo "<ul>";
    echo "<li>Check your DB_HOST (should be like sql###.infinityfree.com)</li>";
    echo "<li>Check your DB_NAME (should start with if0_)</li>";
    echo "<li>Check your DB_USER (should start with if0_)</li>";
    echo "<li>Check your DB_PASS (from InfinityFree control panel)</li>";
    echo "</ul>";
}
?>
```

### **Step 2: Upload This Test File**

1. Open **FileZilla** (or your FTP client)
2. Connect to InfinityFree
3. Navigate to `/htdocs/` folder
4. Upload `test-db.php` to `/htdocs/`

### **Step 3: Visit the Test File**

Open your browser and go to:
```
https://yoursite.infinityfreeapp.com/test-db.php
```

Replace `yoursite` with your actual subdomain.

---

## **What You Should See:**

### **✅ If Connection Works:**
```
✅ Database Connected Successfully!
Host: sql###.infinityfree.com
Database: if0_#######_ecommerce

Tables in database:
- users
- products
- categories
- cart
- orders
- order_items
- etc...

Products in database: 4
Users in database: 1
```

### **❌ If Connection Fails:**
```
❌ Connection Failed!
Error: SQLSTATE[HY000] [1045] Access denied for user 'xxx'@'xxx'