<?php

class CartController
{
    private $cartModel;

    public function __construct()
    {
        $this->cartModel = new Cart();
    }

    public function index()
    {
        $userId = $_SESSION['user_id'] ?? null;
        $sessionId = session_id();

        $cartItems = $this->cartModel->getCartItems($userId, $sessionId);
        $totalData = $this->cartModel->getCartTotal($userId, $sessionId);

        $this->view('cart/index', [
            'cartItems' => $cartItems,
            'count' => count($cartItems),
            'total' => $totalData['total'] ?? 0,
            'title' => 'Shopping Cart'
        ]);
    }

    public function add()
    {
        $userId = $_SESSION['user_id'] ?? null;
        $sessionId = session_id();
        $productId = $_POST['product_id'] ?? $_GET['product_id'] ?? null;
        $quantity = $_POST['quantity'] ?? 1;

        if ($productId) {
            $this->cartModel->addToCart($userId, $sessionId, $productId, $quantity);
        }

        // Redirect back to cart
        header('Location: index.php?page=cart');
        exit;
    }

    public function update()
    {
        $cartItemId = $_POST['cart_id'] ?? null;
        $quantity = $_POST['quantity'] ?? 1;

        if ($cartItemId && $quantity > 0) {
            $this->cartModel->updateQuantity($cartItemId, $quantity);
        }

        header('Location: index.php?page=cart');
        exit;
    }

    public function remove()
    {
        $cartItemId = $_GET['cart_id'] ?? null;

        if ($cartItemId) {
            $this->cartModel->removeItem($cartItemId);
        }

        header('Location: index.php?page=cart');
        exit;
    }

    private function view($view, $data = [])
    {
        extract($data);

        require_once '../src/Views/layout/header.php';
        require_once "../src/Views/{$view}.php";
        require_once '../src/Views/layout/footer.php';
    }
}