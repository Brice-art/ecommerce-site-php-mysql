<?php
require_once '../config/database.php';
require_once '../src/Database/Database.php';
require_once '../src/Models/Category.php';

$categoryModel = new Category();

// Get all categories
$categories = $categoryModel->getAllCategories();

echo "<h1>All Categories</h1>";
foreach ($categories as $category) {
    echo "<div>";
    echo "<h3>" . $category['name'] . "</h3>";
    echo "<p>Slug: " . $category['slug'] . "</p>";
    echo "<p>Description: " . $category['description'] . "</p>";
    echo "</div><hr>";
}