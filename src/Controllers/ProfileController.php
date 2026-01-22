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
        // Redirect if not logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $userId = $_SESSION['user_id'];
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

    public function edit()
    {
        // Check login
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $userId = $_SESSION['user_id'];

        // If POST, process the update
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName = trim($_POST['last_name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');

            // Validation
            $errors = [];

            if (empty($firstName)) {
                $errors[] = 'First name is required';
            }

            if (empty($lastName)) {
                $errors[] = 'Last name is required';
            }

            // If no errors, update
            if (empty($errors)) {
                $updateData = [
                    'email' => $_SESSION['user_email'], // Keep same
                    'password' => '', // Not changing password
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'phone' => $phone,
                    'role' => $_SESSION['user_role']
                ];

                if ($this->userModel->updateUser($userId, $updateData)) {
                    // Update session
                    $_SESSION['user_name'] = $firstName;

                    // Redirect to profile with success message
                    $_SESSION['success_message'] = 'Profile updated successfully!';
                    header('Location: index.php?page=profile');
                    exit;
                } else {
                    $errors[] = 'Failed to update profile';
                }
            }

            // Show form again with errors
            $user = $this->userModel->getUserById($userId);
            $cartItems = (new Cart())->getCartItems($userId, session_id());

            $this->view('user/edit', [
                'user' => $user,
                'errors' => $errors,
                'title' => 'Edit Profile',
                'count' => count($cartItems)
            ]);
            return;
        }

        // GET request - show form
        $user = $this->userModel->getUserById($userId);
        $cartItems = (new Cart())->getCartItems($userId, session_id());

        $this->view('user/edit', [
            'user' => $user,
            'title' => 'Edit Profile',
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
