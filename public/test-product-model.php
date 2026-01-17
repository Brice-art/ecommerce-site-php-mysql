<?php
require_once '../config/database.php';
require_once '../src/Database/Database.php';
require_once '../src/Models/Product.php';

$productModel = new Product();

// Get all products
$products = $productModel->getAllProducts();

echo "<h1>All Products</h1>";
foreach ($products as $product) {
    echo "<div>";
    echo "<h3>" . $product['name'] . "</h3>";
    echo "<p>Price: $" . $product['price'] . "</p>";
    echo "<p>Category: " . $product['category_name'] . "</p>";
    echo "</div><hr>";
}