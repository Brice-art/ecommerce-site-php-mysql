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

    public function changePassword()
    {
        // Check login
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $userEmail = $_SESSION['user_email'];

        // If POST, process the password change
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Validation
            $errors = [];

            if (empty($currentPassword)) {
                $errors[] = 'Current password is required';
            }

            if (empty($newPassword)) {
                $errors[] = 'New password is required';
            } elseif (strlen($newPassword) < 8) {
                $errors[] = 'New password must be at least 8 characters';
            }

            if ($newPassword !== $confirmPassword) {
                $errors[] = 'New password and confirm password do not match';
            }

            // Verify current password
            if (empty($errors) && !$this->userModel->verifyPassword($userEmail, $currentPassword)) {
                $errors[] = 'Current password is incorrect';
            }

            // Check if new password is same as current password
            if (empty($errors) && $currentPassword === $newPassword) {
                $errors[] = 'New password must be different from current password';
            }

            // If no errors, update password
            if (empty($errors)) {
                if ($this->userModel->updatePassword($userId, $newPassword)) {
                    // Redirect to profile with success message
                    $_SESSION['success_message'] = 'Password changed successfully!';
                    header('Location: index.php?page=profile');
                    exit;
                } else {
                    $errors[] = 'Failed to update password. Please try again.';
                }
            }

            // Show form again with errors
            $user = $this->userModel->getUserById($userId);
            $cartItems = (new Cart())->getCartItems($userId, session_id());

            $this->view('user/change_password', [
                'user' => $user,
                'errors' => $errors,
                'title' => 'Change Password',
                'count' => count($cartItems)
            ]);
            return;
        }

        // GET request - show form
        $user = $this->userModel->getUserById($userId);
        $cartItems = (new Cart())->getCartItems($userId, session_id());

        $this->view('user/change_password', [
            'user' => $user,
            'title' => 'Change Password',
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
