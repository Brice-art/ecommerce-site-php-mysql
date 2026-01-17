<?php
class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Login - Show form or process login
     */
    public function login()
    {
        // If POST request, process login
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            // Validation
            if (empty($email) || empty($password)) {
                $error = 'Email and password are required';
                $this->view('auth/login', [
                    'title' => 'Login',
                    'error' => $error,
                    'email' => $email
                ]);
                return;
            }

            // Check if user exists
            $user = $this->userModel->getUserByEmail($email);
            
            if (!$user) {
                $error = 'Invalid email or password';
                $this->view('user/login', [
                    'title' => 'Login',
                    'error' => $error,
                    'email' => $email
                ]);
                return;
            }

            // Verify password
            if (!$this->userModel->verifyPassword($email, $password)) {
                $error = 'Invalid email or password';
                $this->view('user/login', [
                    'title' => 'Login',
                    'error' => $error,
                    'email' => $email
                ]);
                return;
            }

            // Login successful - Create session
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['first_name'];
            $_SESSION['user_role'] = $user['role'];

            // Redirect to home page
            header('Location: index.php?page=home');
            exit;
        }

        // If GET request, show login form
        $this->view('user/login', ['title' => 'Login']);
    }

    /**
     * Register - Show form or process registration
     */
    public function register()
    {
        // If POST request, process registration
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName = trim($_POST['last_name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');

            // Validation
            $errors = [];

            if (empty($email)) {
                $errors[] = 'Email is required';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Invalid email format';
            }

            if (empty($password)) {
                $errors[] = 'Password is required';
            } elseif (strlen($password) < 8) {
                $errors[] = 'Password must be at least 8 characters';
            }

            if ($password !== $confirmPassword) {
                $errors[] = 'Passwords do not match';
            }

            if (empty($firstName)) {
                $errors[] = 'First name is required';
            }

            if (empty($lastName)) {
                $errors[] = 'Last name is required';
            }

            // Check if email already exists
            if (empty($errors) && $this->userModel->getUserByEmail($email)) {
                $errors[] = 'Email already registered';
            }

            // If errors, show form again with errors
            if (!empty($errors)) {
                $this->view('user/register', [
                    'title' => 'Register',
                    'errors' => $errors,
                    'email' => $email,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'phone' => $phone
                ]);
                return;
            }

            // Create user
            $userData = [
                'email' => $email,
                'password' => $password,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone' => $phone,
                'role' => 'customer'
            ];

            $userId = $this->userModel->createUser($userData);

            if ($userId) {
                // Auto-login after registration
                $_SESSION['user_id'] = $userId;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_name'] = $firstName;
                $_SESSION['user_role'] = 'customer';

                // Redirect to home
                header('Location: index.php?page=home');
                exit;
            } else {
                $errors[] = 'Registration failed. Please try again.';
                $this->view('user/register', [
                    'title' => 'Register',
                    'errors' => $errors
                ]);
            }

            return;
        }

        // If GET request, show registration form
        $this->view('user/register', ['title' => 'Register']);
    }

    /**
     * Logout - Destroy session and redirect
     */
    public function logout()
    {
        // Destroy session
        session_unset();
        session_destroy();

        // Redirect to home
        header('Location: index.php?page=home');
        exit;
    }

    /**
     * Check if user is logged in
     */
    public function checkAuth()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Get current logged-in user
     */
    public function getCurrentUser()
    {
        if (!$this->checkAuth()) {
            return null;
        }

        return [
            'id' => $_SESSION['user_id'],
            'email' => $_SESSION['user_email'] ?? '',
            'name' => $_SESSION['user_name'] ?? '',
            'role' => $_SESSION['user_role'] ?? 'customer'
        ];
    }

    /**
     * Require authentication (redirect if not logged in)
     */
    public function requireAuth()
    {
        if (!$this->checkAuth()) {
            header('Location: index.php?page=login');
            exit;
        }
    }

    /**
     * Load view with header/footer
     */
    private function view($view, $data = [])
    {
        extract($data);

        require_once '../src/Views/layout/header.php';
        require_once "../src/Views/{$view}.php";
        require_once '../src/Views/layout/footer.php';
    }
}