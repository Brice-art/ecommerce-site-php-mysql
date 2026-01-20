<?php
class ProfileController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index()
    {
        $userId = $_SESSION['user_id'] ?? null;
        $sessionId = session_id();

        $user = $this->userModel->getUserById($userId);
        $userOrders = $this->userModel->getUserOrders($userId);

        $cartItems = (new Cart())->getCartItems($userId, $sessionId);

        $this->view('user/profile', [
            'user' => $user,
            'orders' => $userOrders,
            'title' => 'Profile',
            'count' => count($cartItems)
        ]);
    }

    public function edit() {
        $userId = $_SESSION['user_id'] ?? null;
        $sessionId = session_id();

        $user = $this->userModel->getUserById($userId);

        $cartItems = (new Cart())->getCartItems($userId, $sessionId);

        $this->view('user/edit', [
            'user' => $user,
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
}
