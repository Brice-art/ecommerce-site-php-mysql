<?php
// Start session
session_start();

// Load configuration and classes
require_once '../config/database.php';
require_once '../src/Database/Database.php';
require_once '../src/Models/Product.php';
require_once '../src/Models/Category.php';
require_once '../src/Models/Cart.php';
require_once '../src/Models/User.php';
require_once '../src/Models/Order.php';
require_once '../src/Controllers/HomeController.php';
require_once '../src/Controllers/ProductController.php';
require_once '../src/Controllers/ProfileController.php';
require_once '../src/Controllers/CartController.php';
require_once '../src/Controllers/AuthController.php';
require_once '../src/Controllers/OrderController.php';
require_once '../src/Controllers/CheckoutController.php';
require_once '../src/Models/OrderItem.php';

// Simple routing
$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'home':
        $controller = new HomeController();
        $controller->index();
        break;

    case 'products':
        $controller = new ProductController();
        $controller->index();
        break;

    case 'product':
        $controller = new ProductController();
        $controller->product($_GET['id'] ?? null);
        break;

    case 'cart':
        $controller = new CartController();
        $action = $_GET['action'] ?? 'index';

        switch ($action) {
            case 'add':
                $controller->add();
                break;
            case 'update':
                $controller->update();
                break;
            case 'remove':
                $controller->remove();
                break;
            default:
                $controller->index();
        }
        break;

    case 'login':
        $controller = new AuthController();
        $controller->login();
        break;

    case 'register':
        $controller = new AuthController();
        $controller->register();
        break;

    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'profile':
        $controller = new ProfileController();
        $action = $_GET['action'] ?? 'index';

        switch ($action) {
            case 'edit':
                $controller->edit();
                break;
            case 'change_password':
                $controller->changePassword();
                break;
            default:
                $controller->index();
        }

        break;
    case 'orders':
        $controller = new OrderController();
        $orderId = $_GET['id'] ?? null;
        if ($orderId) {
            $controller->show($orderId);
        } else {
            $controller->index();
        }
        break;

    case 'checkout':
        $controller = new CheckoutController();
        $action = $_GET['action'] ?? 'shipping';
        
        switch ($action) {
            case 'shipping':
                $controller->shipping();
                break;
            case 'payment':
                $controller->payment();
                break;
            case 'confirmation':
                $controller->confirmation();
                break;
            default:
                $controller->shipping();
        }
        break;

    default:
        echo "404 - Page not found";
        break;
}
