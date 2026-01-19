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
require_once '../src/Controllers/HomeController.php';
require_once '../src/Controllers/ProductController.php';
require_once '../src/Controllers/ProfileController.php';
require_once '../src/Controllers/CartController.php';
require_once '../src/Controllers/AuthController.php';

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
        $controller->index();
        break;

    default:
        echo "404 - Page not found";
        break;
}
