<?php

class OrderController
{
    private $orderModel;
    public function __construct()
    {
        $this->orderModel = new Order();
    }
    public function index()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $orders = $this->orderModel->getOrdersByUserId($userId);
        
        $cartItems = (new Cart())->getCartItems($userId, session_id());
        
        $this->view('user/orders', [
            'orders' => $orders,
            'title' => 'My Orders',
            'count' => count($cartItems)
        ]);
    }

    public function show($orderId)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        
        // Get order and verify ownership
        $order = $this->orderModel->getOrderById($orderId, $userId);
        
        if (!$order) {
            // Order not found or doesn't belong to user
            header('Location: index.php?page=orders');
            exit;
        }

        // Get order items
        $orderItems = $this->orderModel->getOrderItemsByOrderId($orderId);
        
        $cartItems = (new Cart())->getCartItems($userId, session_id());
        
        $this->view('user/order_detail', [
            'order' => $order,
            'orderItems' => $orderItems,
            'title' => 'Order Details',
            'count' => count($cartItems)
        ]);
    }
    public function view($view, $data = [])
    {
        extract($data);
        require_once '../src/Views/layout/header.php';
        require_once "../src/Views/{$view}.php";
        require_once '../src/Views/layout/footer.php';
    }

}
