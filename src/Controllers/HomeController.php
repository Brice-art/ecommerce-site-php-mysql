<?php
class HomeController {
    private $productModel;
    private $categoryModel;

    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }

    public function index() {
        // Get data from models
        $featuredProducts = $this->productModel->getFeaturedProducts(8);
        $latestProducts = $this->productModel->getLatestProducts(8);
        $categories = $this->categoryModel->getAllCategories();

        $userId = $_SESSION['user_id'] ?? null;
        $sessionId = session_id();

        $cartItems = (new Cart())->getCartItems($userId, $sessionId);
        
        // Pass data to view
        $data = [
            'title' => 'Welcome to Our Store',
            'featuredProducts' => $featuredProducts,
            'latestProducts' => $latestProducts,
            'categories' => $categories,
            'count' => count($cartItems)
        ];
        
        // Load view
        $this->view('home/index', $data);
    }

    // Helper method to load views
    private function view($view, $data = []) {
        // Extract data array to variables
        extract($data);
        
        // Load view file
        require_once '../src/Views/layout/header.php';
        require_once "../src/Views/{$view}.php";
        require_once '../src/Views/layout/footer.php';
    }
}
?>
