<?php

/**
 * Product Model
 * 
 * Handles all database operations related to products
 */

class Product
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Get all active products
     * 
     * @return array
     */
    public function getAllProducts()
    {
        $this->db->query("
            SELECT p.*, c.name as category_name 
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.is_active = :active
            ORDER BY p.created_at DESC
        ");

        $this->db->bind(':active', 1);

        return $this->db->resultSet();
    }

    /**
     * Get single product by ID
     * 
     * @param int $id Product ID
     * @return mixed
     * 
    1. USER ACTION
        └─> User clicks "View Product" button for product ID 5

    2. CONTROLLER
        └─> Controller receives request
        └─> Creates Product model: $productModel = new Product();
        └─> Calls: $product = $productModel->getProductById(5);

    3. PRODUCT MODEL - getProductById(5)
        ├─> Step 1: Prepare SQL Query
        │   └─> SQL: "SELECT p.*, c.name as category_name 
        │              FROM products p
        │              LEFT JOIN categories c ON p.category_id = c.id
        │              WHERE p.id = :id"
        │
        ├─> Step 2: Bind Parameter
        │   └─> Replace :id with actual value 5
        │   └─> Result: "WHERE p.id = 5"
        │
        ├─> Step 3: Execute Query
        │   └─> Database runs the SQL
        │   └─> Searches for product with ID = 5
        │
        ├─> Step 4: Fetch Result
        │   └─> Gets ONE row from database
        │   └─> Returns as associative array
        │
        └─> Step 5: Return to Controller
            └─> Returns: ['id' => 5, 'name' => 'Wireless Headphones', ...]
            └─> OR returns: NULL (if not found)

    4. CONTROLLER
        └─> Receives the product data
        └─> Passes it to the View

    5. VIEW
        └─> Displays product information on the page
    */


    public function getProductById($id)
    {
        $this->db->query("
            SELECT p.*, c.name as category_name 
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.id = :id
        ");

        $this->db->bind(':id', $id);

        return $this->db->single();
    }

    /**
     * Get product by slug (URL-friendly name)
     * 
     * @param string $slug Product slug
     * @return mixed
     */
    public function getProductBySlug($slug)
    {
        $this->db->query("
            SELECT p.*, c.name as category_name 
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.slug = :slug AND p.is_active = :active
        ");

        $this->db->bind(':slug', $slug);
        $this->db->bind(':active', 1);

        return $this->db->single();
    }

    /**
     * Get products by category
     * 
     * @param int $categoryId Category ID
     * @return array
     */
    public function getProductsByCategory($categoryId)
    {
        $this->db->query("
            SELECT p.*, c.name as category_name 
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.category_id = :category_id AND p.is_active = :active
            ORDER BY p.created_at DESC
        ");

        $this->db->bind(':category_id', $categoryId);
        $this->db->bind(':active', 1);

        return $this->db->resultSet();
    }

    /**
     * Search products by name or description
     * 
     * @param string $searchTerm Search keyword
     * @return array
     */
    public function searchProducts($searchTerm)
    {
        $this->db->query("
            SELECT p.*, c.name as category_name 
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE CONCAT(p.name, ' ', IFNULL(p.description, '')) LIKE :search
            AND p.is_active = :active
            ORDER BY p.created_at DESC
        ");

        $searchParam = '%' . $searchTerm . '%';
        $this->db->bind(':search', $searchParam);
        $this->db->bind(':active', 1);

        return $this->db->resultSet();
    }

    /**
     * Get featured products
     * 
     * @param int $limit Number of products to return
     * @return array
     */
    public function getFeaturedProducts($limit = 8)
    {
        $this->db->query("
            SELECT p.*, c.name as category_name 
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.is_featured = :featured AND p.is_active = :active
            ORDER BY p.created_at DESC
            LIMIT :limit
        ");

        $this->db->bind(':featured', 1);
        $this->db->bind(':active', 1);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);

        return $this->db->resultSet();
    }

    /**
     * Get latest products
     * 
     * @param int $limit Number of products to return
     * @return array
     */
    public function getLatestProducts($limit = 12)
    {
        $this->db->query("
            SELECT p.*, c.name as category_name 
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.is_active = :active
            ORDER BY p.created_at DESC
            LIMIT :limit
        ");

        $this->db->bind(':active', 1);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);

        return $this->db->resultSet();
    }

    /**
     * Create new product
     * 
     * @param array $data Product data
     * @return bool|string Returns product ID on success, false on failure
     */
    public function createProduct($data)
    {
        $this->db->query("
            INSERT INTO products (
                category_id, name, slug, description, price, 
                compare_price, sku, quantity, main_image, is_featured, is_active
            ) VALUES (
                :category_id, :name, :slug, :description, :price,
                :compare_price, :sku, :quantity, :main_image, :is_featured, :is_active
            )
        ");

        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':slug', $data['slug']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':compare_price', $data['compare_price'] ?? null);
        $this->db->bind(':sku', $data['sku']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':main_image', $data['main_image'] ?? null);
        $this->db->bind(':is_featured', $data['is_featured'] ?? 0);
        $this->db->bind(':is_active', $data['is_active'] ?? 1);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    /**
     * Update product
     * 
     * @param int $id Product ID
     * @param array $data Product data
     * @return bool
     */
    public function updateProduct($id, $data)
    {
        $this->db->query("
            UPDATE products SET
                category_id = :category_id,
                name = :name,
                slug = :slug,
                description = :description,
                price = :price,
                compare_price = :compare_price,
                sku = :sku,
                quantity = :quantity,
                main_image = :main_image,
                is_featured = :is_featured,
                is_active = :is_active
            WHERE id = :id
        ");

        $this->db->bind(':id', $id);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':slug', $data['slug']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':compare_price', $data['compare_price'] ?? null);
        $this->db->bind(':sku', $data['sku']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':main_image', $data['main_image'] ?? null);
        $this->db->bind(':is_featured', $data['is_featured'] ?? 0);
        $this->db->bind(':is_active', $data['is_active'] ?? 1);

        return $this->db->execute();
    }

    /**
     * Delete product (soft delete - set inactive)
     * 
     * @param int $id Product ID
     * @return bool
     */
    public function deleteProduct($id)
    {
        $this->db->query("UPDATE products SET is_active = :active WHERE id = :id");

        $this->db->bind(':id', $id);
        $this->db->bind(':active', 0);

        return $this->db->execute();
    }

    /**
     * Update product inventory (when order is placed)
     * 
     * @param int $productId Product ID
     * @param int $quantity Quantity to reduce
     * @return bool
     */
    public function reduceInventory($productId, $quantity)
    {
        $this->db->query("
            UPDATE products 
            SET quantity = quantity - :quantity 
            WHERE id = :id AND quantity >= :quantity
        ");

        $this->db->bind(':id', $productId);
        $this->db->bind(':quantity', $quantity);

        return $this->db->execute();
    }

    /**
     * Check if product has sufficient stock
     * 
     * @param int $productId Product ID
     * @param int $quantity Quantity needed
     * @return bool
     */
    public function checkStock($productId, $quantity)
    {
        $this->db->query("SELECT quantity FROM products WHERE id = :id");
        $this->db->bind(':id', $productId);

        $product = $this->db->single();

        return $product && $product['quantity'] >= $quantity;
    }

    /**
     * Get product images
     * 
     * @param int $productId Product ID
     * @return array
     */
    public function getProductImages($productId)
    {
        $this->db->query("
            SELECT * FROM product_images 
            WHERE product_id = :product_id 
            ORDER BY sort_order ASC
        ");

        $this->db->bind(':product_id', $productId);

        return $this->db->resultSet();
    }

    /**
     * Get total product count
     * 
     * @return int
     */
    public function getTotalProducts()
    {
        $this->db->query("SELECT COUNT(*) as count FROM products WHERE is_active = :active");
        $this->db->bind(':active', 1);

        $result = $this->db->single();
        return $result['count'];
    }
}

// ============================================
// USAGE EXAMPLES
// ============================================

/*

// Include required files
require_once '../config/database.php';
require_once '../src/Database/Database.php';
require_once '../src/Models/Product.php';

// Create Product model instance
$productModel = new Product();


// 1. GET ALL PRODUCTS
// --------------------
$products = $productModel->getAllProducts();
foreach ($products as $product) {
    echo $product['name'] . " - $" . $product['price'] . "<br>";
}


// 2. GET SINGLE PRODUCT BY ID
// ----------------------------
$product = $productModel->getProductById(1);
if ($product) {
    echo "Product: " . $product['name'];
    echo "Price: $" . $product['price'];
    echo "Category: " . $product['category_name'];
}


// 3. GET PRODUCT BY SLUG (for SEO-friendly URLs)
// -----------------------------------------------
$product = $productModel->getProductBySlug('wireless-headphones');
if ($product) {
    echo $product['name'];
}


// 4. SEARCH PRODUCTS
// -------------------
$results = $productModel->searchProducts('headphones');
foreach ($results as $product) {
    echo $product['name'] . "<br>";
}


// 5. GET FEATURED PRODUCTS (for homepage)
// ----------------------------------------
$featured = $productModel->getFeaturedProducts(4);
foreach ($featured as $product) {
    echo $product['name'] . "<br>";
}


// 6. GET PRODUCTS BY CATEGORY
// ----------------------------
$categoryProducts = $productModel->getProductsByCategory(1);
foreach ($categoryProducts as $product) {
    echo $product['name'] . "<br>";
}


// 7. CREATE NEW PRODUCT
// ----------------------
$newProduct = [
    'category_id' => 1,
    'name' => 'Gaming Mouse',
    'slug' => 'gaming-mouse',
    'description' => 'High precision gaming mouse',
    'price' => 49.99,
    'compare_price' => 69.99,
    'sku' => 'GM-001',
    'quantity' => 100,
    'main_image' => 'gaming-mouse.jpg',
    'is_featured' => 1,
    'is_active' => 1
];

$productId = $productModel->createProduct($newProduct);
if ($productId) {
    echo "Product created with ID: " . $productId;
}


// 8. UPDATE PRODUCT
// ------------------
$updateData = [
    'category_id' => 1,
    'name' => 'Gaming Mouse Pro',
    'slug' => 'gaming-mouse-pro',
    'description' => 'Professional gaming mouse',
    'price' => 59.99,
    'compare_price' => 79.99,
    'sku' => 'GM-001',
    'quantity' => 100,
    'main_image' => 'gaming-mouse-pro.jpg',
    'is_featured' => 1,
    'is_active' => 1
];

if ($productModel->updateProduct(1, $updateData)) {
    echo "Product updated successfully!";
}


// 9. CHECK STOCK BEFORE ADDING TO CART
// -------------------------------------
$productId = 5;
$quantityNeeded = 2;

if ($productModel->checkStock($productId, $quantityNeeded)) {
    echo "Product available!";
} else {
    echo "Insufficient stock!";
}


// 10. REDUCE INVENTORY (when order is placed)
// --------------------------------------------
if ($productModel->reduceInventory(5, 2)) {
    echo "Inventory updated!";
}


// 11. GET PRODUCT IMAGES
// -----------------------
$images = $productModel->getProductImages(1);
foreach ($images as $image) {
    echo "<img src='" . $image['image_url'] . "'>";
}

*/