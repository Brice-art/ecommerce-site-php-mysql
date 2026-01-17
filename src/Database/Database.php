<?php
/**
 * Database Wrapper Class
 * 
 * A simple PDO wrapper to make database operations easier and safer.
 */

class Database {
    private $pdo;
    private $stmt;
    private $error;

    /**
     * Constructor - Establishes database connection
     */
    public function __construct() {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            error_log("Database connection error: " . $this->error);
            die("Database connection failed. Please try again later.");
        }
    }

    /**
     * Prepare SQL query
     * 
     * @param string $sql SQL query with placeholders
     * @return void
     */
    public function query($sql) {
        $this->stmt = $this->pdo->prepare($sql);
    }

    /**
     * Bind values to prepared statement
     * 
     * @param string|int $param Parameter identifier
     * @param mixed $value Value to bind
     * @param int|null $type PDO data type
     * @return void
     */
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    /**
     * Execute prepared statement
     * 
     * @return bool True on success
     */
    public function execute() {
        return $this->stmt->execute();
    }

    /**
     * Fetch all results as array of objects
     * 
     * @return array
     */
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    /**
     * Fetch single record as object
     * 
     * @return mixed
     */
    public function single() {
        $this->execute();
        return $this->stmt->fetch();
    }

    /**
     * Get row count
     * 
     * @return int
     */
    public function rowCount() {
        return $this->stmt->rowCount();
    }

    /**
     * Get last inserted ID
     * 
     * @return string
     */
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }

    /**
     * Begin transaction
     * 
     * @return bool
     */
    public function beginTransaction() {
        return $this->pdo->beginTransaction();
    }

    /**
     * Commit transaction
     * 
     * @return bool
     */
    public function commit() {
        return $this->pdo->commit();
    }

    /**
     * Rollback transaction
     * 
     * @return bool
     */
    public function rollBack() {
        return $this->pdo->rollBack();
    }

    /**
     * Debug dump parameters
     * 
     * @return void
     */
    public function debugDumpParams() {
        return $this->stmt->debugDumpParams();
    }
}

// ============================================
// USAGE EXAMPLES
// ============================================

/*

// 1. SELECT ALL PRODUCTS
// ----------------------
$db = new Database();
$db->query("SELECT * FROM products WHERE is_active = :active");
$db->bind(':active', 1);
$products = $db->resultSet();

foreach ($products as $product) {
    echo $product['name'] . " - $" . $product['price'] . "<br>";
}


// 2. SELECT SINGLE PRODUCT
// -------------------------
$db = new Database();
$db->query("SELECT * FROM products WHERE id = :id");
$db->bind(':id', 5);
$product = $db->single();

echo $product['name'];


// 3. INSERT NEW PRODUCT
// ----------------------
$db = new Database();
$db->query("INSERT INTO products (name, price, category_id, sku, quantity) 
            VALUES (:name, :price, :category_id, :sku, :quantity)");

$db->bind(':name', 'New Product');
$db->bind(':price', 99.99);
$db->bind(':category_id', 1);
$db->bind(':sku', 'PROD-001');
$db->bind(':quantity', 50);

if ($db->execute()) {
    $newProductId = $db->lastInsertId();
    echo "Product created with ID: " . $newProductId;
}


// 4. UPDATE PRODUCT
// ------------------
$db = new Database();
$db->query("UPDATE products SET price = :price WHERE id = :id");
$db->bind(':price', 79.99);
$db->bind(':id', 5);

if ($db->execute()) {
    echo "Product updated successfully!";
}


// 5. DELETE PRODUCT
// ------------------
$db = new Database();
$db->query("DELETE FROM products WHERE id = :id");
$db->bind(':id', 5);

if ($db->execute()) {
    echo "Product deleted!";
}


// 6. COUNT ROWS
// --------------
$db = new Database();
$db->query("SELECT * FROM products WHERE category_id = :category_id");
$db->bind(':category_id', 1);
$db->resultSet();

echo "Total products in category: " . $db->rowCount();


// 7. TRANSACTION EXAMPLE (for orders)
// ------------------------------------
$db = new Database();

try {
    $db->beginTransaction();
    
    // Insert order
    $db->query("INSERT INTO orders (user_id, total) VALUES (:user_id, :total)");
    $db->bind(':user_id', 1);
    $db->bind(':total', 199.99);
    $db->execute();
    $orderId = $db->lastInsertId();
    
    // Insert order items
    $db->query("INSERT INTO order_items (order_id, product_id, quantity, price) 
                VALUES (:order_id, :product_id, :quantity, :price)");
    $db->bind(':order_id', $orderId);
    $db->bind(':product_id', 10);
    $db->bind(':quantity', 2);
    $db->bind(':price', 99.99);
    $db->execute();
    
    // Update inventory
    $db->query("UPDATE products SET quantity = quantity - :qty WHERE id = :id");
    $db->bind(':qty', 2);
    $db->bind(':id', 10);
    $db->execute();
    
    $db->commit();
    echo "Order placed successfully!";
    
} catch (Exception $e) {
    $db->rollBack();
    echo "Order failed: " . $e->getMessage();
}


// 8. SEARCH PRODUCTS
// -------------------
$searchTerm = "laptop";
$db = new Database();
$db->query("SELECT * FROM products 
            WHERE name LIKE :search 
            OR description LIKE :search");
$db->bind(':search', '%' . $searchTerm . '%');
$results = $db->resultSet();


// 9. JOIN EXAMPLE - Products with Categories
// -------------------------------------------
$db = new Database();
$db->query("SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.is_active = :active");
$db->bind(':active', 1);
$products = $db->resultSet();

foreach ($products as $product) {
    echo $product['name'] . " (" . $product['category_name'] . ")<br>";
}

*/