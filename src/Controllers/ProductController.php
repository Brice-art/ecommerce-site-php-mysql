<?php
class ProductController
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    public function index()
    {
        // Get search and category filters
        $searchTerm = $_GET['search'] ?? '';
        $categoryId = $_GET['category'] ?? null;

        // Get all categories for sidebar
        $categoryModel = new Category();
        $categories = $categoryModel->getAllCategories();

        // Filter products based on parameters
        if (!empty($searchTerm)) {
            $allProducts = $this->productModel->searchProducts($searchTerm);
            $title = 'Search Results';
        } elseif ($categoryId) {
            $allProducts = $this->productModel->getProductsByCategory($categoryId);
            // Get category name
            $selectedCategory = $categoryModel->getCategoryById($categoryId);
            $title = $selectedCategory['name'] ?? 'Products';
        } else {
            $allProducts = $this->productModel->getAllProducts();
            $title = 'All Products';
        }

        $userId = $_SESSION['user_id'] ?? null;
        $sessionId = session_id();

        $cartItems = (new Cart())->getCartItems($userId, $sessionId);

        // Pass to view
        $this->view('products/list', [
            'allProducts' => $allProducts,
            'categories' => $categories,
            'selectedCategory' => $categoryId,
            'title' => $title,
            'count' => count($cartItems)
        ]);
    }

    // Load view with header/footer
    private function view($view, $data = [])
    {
        extract($data);

        require_once '../src/Views/layout/header.php';
        require_once "../src/Views/{$view}.php";
        require_once '../src/Views/layout/footer.php';
    }

    public function category($categoryId)
    {
        $products = $this->productModel->getProductsByCategory($categoryId);
        $categories = (new Category())->getAllCategories();

        $this->view('products/list', [
            'allProducts' => $products,
            'categories' => $categories,
            'title' => 'Products by Category'
        ]);
    }

    public function product($productId)
    {
        $product = $this->productModel->getProductById($productId);

        if (!$product) {
            echo '404 Page Not Found';
            return;
        }

        $category = null;
        if (!empty($product['category_id'])) {
            $category = (new Category())->getCategoryById($product['category_id']);
        }

        $userId = $_SESSION['user_id'] ?? null;
        $sessionId = session_id();

        $cartItems = (new Cart())->getCartItems($userId, $sessionId);

        $this->view('products/detail', [
            'product' => $product,
            'title' => $product['name'] ?? 'Product',
            'category' => $category['name'] ?? null,
            'count' => count($cartItems)
        ]);
    }
}
