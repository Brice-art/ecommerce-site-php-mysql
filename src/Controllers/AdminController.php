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

    public function dashboard()
    {
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

    public function products()
    {
        $allProducts = $this->productModel->getAllProducts();

        $this->view('admin/products/list', [
            'allProducts' => $allProducts
        ]);
        return;
    }

    public function createProduct()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category = $_POST['category'];
            $name = trim($_POST['name']);
            $slug = trim($_POST['slug']);
            $description = trim($_POST['description']);
            $price = $_POST['price'];
            $compare_price = $_POST['compare_price'];
            $cost_price = $_POST['cost_price'];
            $sku = $_POST['sku'];
            $quantity = $_POST['quantity'];
            $weight = $_POST['weight'];
            $main_image = $_POST['main_image'];
            $is_featured = $_POST['is_featured'];
            $is_active = $_POST['is_active'];

            $errors = [];

            if (empty($category)) {
                $errors[] = 'Choose category';
            }

            if (empty($name)) {
                $errors[] = 'Please input a valid name';
            }

            if (empty($price)) {
                $errors[] = 'Input price';
            }

            if (empty($cost_price)) {
                $errors[] = 'Input cost price';
            }

            if (empty($quantity)) {
                $errors[] = 'Input quantity';
            }

            // If errors, show form again with errors
            if (!empty($errors)) {
                $this->view('admin/products/create', [
                    'title' => 'Create Product',
                    'errors' => $errors,
                    'category' => $category,
                    'name' => $name,
                    'slug' => $slug,
                    'description' => $description,
                    'price' => $price,
                    'compare_price' => $compare_price,
                    'cost_price' => $cost_price,
                    'sku' => $sku,
                    'quantity' => $quantity,
                    'weight' => $weight,
                    'main_image' => $main_image,
                    'is_featured' => $is_featured,
                    'is_active' => $is_active
                ]);
                return;
            }

            // Create Product
            $productData = [
                'category_id' => $category,
                'name' => $name,
                'slug' => $slug,
                'description' => $description,
                'price' => $price,
                'compare_price' => $compare_price,
                'cost_price' => $cost_price,
                'sku' => $sku,
                'quantity' => $quantity,
                'weight' => $weight,
                'main_image' => $main_image,
                'is_featured' => $is_featured,
                'is_active' => $is_active
            ];

            if ($this->productModel->createProduct($productData)) {
                header('Location: index.php?page=admin&action=products');
                exit;
            } else {
                $errors[] = 'Something went wrong. Please try again.';
                $this->view('admin/products/create', [
                    'title' => 'Create Product',
                    'errors' => $errors
                ]);
            }

            return;
        }

        $this->view('admin/products/create', [
            'title' => 'Create Product'
        ]);
    }

    public function edit()
    {
        // Get product ID
        $productId = $_GET['id'] ?? null;

        // Validate product ID exists
        if (empty($productId)) {
            header('Location: index.php?page=admin&action=products');
            exit;
        }

        // Get existing product
        $product = $this->productModel->getProductById($productId);

        // Check if product exists
        if (!$product) {
            header('Location: index.php?page=admin&action=products');
            exit;
        }

        // If POST request, process update
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category = $_POST['category'] ?? '';
            $name = trim($_POST['name'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $price = $_POST['price'] ?? '';
            $compare_price = $_POST['compare_price'] ?? null;
            $cost_price = $_POST['cost_price'] ?? '';
            $sku = $_POST['sku'] ?? '';
            $quantity = $_POST['quantity'] ?? '';
            $weight = $_POST['weight'] ?? null;
            $main_image = $_POST['main_image'] ?? '';
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            $errors = [];

            // Validation
            if (empty($category)) {
                $errors[] = 'Choose category';
            }

            if (empty($name)) {
                $errors[] = 'Please input a valid name';
            }

            if (empty($price)) {
                $errors[] = 'Input price';
            }

            if (empty($cost_price)) {
                $errors[] = 'Input cost price';
            }

            if (empty($quantity) && $quantity !== '0') {
                $errors[] = 'Input quantity';
            }

            // If errors, show form again with errors AND product data
            if (!empty($errors)) {
                // Merge POST data with product for display
                $product = array_merge($product, [
                    'category_id' => $category,
                    'name' => $name,
                    'slug' => $slug,
                    'description' => $description,
                    'price' => $price,
                    'compare_price' => $compare_price,
                    'cost_price' => $cost_price,
                    'sku' => $sku,
                    'quantity' => $quantity,
                    'weight' => $weight,
                    'main_image' => $main_image,
                    'is_featured' => $is_featured,
                    'is_active' => $is_active
                ]);

                $this->view('admin/products/edit', [
                    'title' => 'Edit Product',
                    'errors' => $errors,
                    'product' => $product
                ]);
                return;
            }

            // Update Product
            $productData = [
                'category_id' => $category,
                'name' => $name,
                'slug' => $slug,
                'description' => $description,
                'price' => $price,
                'compare_price' => $compare_price,
                'cost_price' => $cost_price,
                'sku' => $sku,
                'quantity' => $quantity,
                'weight' => $weight,
                'main_image' => $main_image,
                'is_featured' => $is_featured,
                'is_active' => $is_active
            ];

            if ($this->productModel->updateProduct($productId, $productData)) {
                // Success - redirect to products list
                $_SESSION['success_message'] = 'Product updated successfully!';
                header('Location: index.php?page=admin&action=products');
                exit;
            } else {
                $errors[] = 'Something went wrong. Please try again.';
                $this->view('admin/products/edit', [
                    'title' => 'Edit Product',
                    'errors' => $errors,
                    'product' => $product
                ]);
            }

            return;
        }

        // GET request - show edit form
        $this->view('admin/products/edit', [
            'title' => 'Edit Product',
            'product' => $product
        ]);
    }

    public function orders()
    {

        $orders = $this->orderModel->getAllOrders();
        $this->view('admin/orders/list', [
            'title' => 'Orders List',
            'orders' => $orders
        ]);
        return;
    }

    public function users()
    {
        $users = $this->adminModel->getAllUsers();
        $this->view('admin/users/list', [
            'title' => 'Users list',
            'users' => $users
        ]);
        return;
    }

    private function view($view, $data = [])
    {
        extract($data);

        require_once '../src/Views/layout/header.php';
        require_once "../src/Views/{$view}.php";
        require_once '../src/Views/layout/footer.php';
    }
}
