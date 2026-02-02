<?php

class AdminController
{
    private $adminModel;
    private $productModel;
    private $orderModel;
    private $categoryModel;
    private $orderItemModel;
    private $paymentModel;

    public function __construct()
    {
        $this->adminModel = new User();
        $this->productModel = new Product();
        $this->orderModel = new Order();
        $this->categoryModel = new Category();
        $this->orderItemModel = new OrderItem();
    }

    // Admin check
    public function isAdmin()
    {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    public function dashboard() {
        $allProducts = $this->productModel->getAllProducts();
        $allUsers = $this->adminModel->getAllUsers();
        $allOrders = $this->orderModel->getAllOrders();
        $revenue = $this->orderModel->getRevenue();

        $this->view('admin/dashboard', [
            'title' => 'Admin dashboard',
            'allProducts' => $allProducts,
            'allUsers' => $allUsers,
            'allOrders' => $allOrders,
            'revenue' => $revenue
        ]);
    }

    public function products() {
        $allProducts = $this->productModel->getAllProducts();

        $this->view('admin/dashboard', [
            'allProducts' => $allProducts
        ]);
    }

    public function createProduct() {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
        }

        $this->view('admin/products/create', [
            'title' => 'Create Product'
        ]);
    }

    public function orders() {

    }

    public function users() {

    }

    private function view($view, $data = [])
    {
        extract($data);

        require_once '../src/Views/layout/header.php';
        require_once "../src/Views/{$view}.php";
        require_once '../src/Views/layout/footer.php';
    }
}
