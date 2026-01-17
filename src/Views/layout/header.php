<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'E-Commerce Store'; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/cart.js" defer></script>
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="container">
                <div class="logo">
                    <h1><a href="index.php">MyStore</a></h1>
                </div>
                <ul class="nav-menu">
                    <li><a href="index.php?page=home">Home</a></li>
                    <li><a href="index.php?page=products">Products</a></li>
                    <li><a href="index.php?page=cart">Cart (<?php echo $count ?? 0 ?>)</a></li>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="index.php?page=profile">Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></a></li>
                        <li><a href="index.php?page=logout">Logout</a></li>
                    <?php else: ?>
                        <li><a href="index.php?page=login">Login</a></li>
                        <li><a href="index.php?page=register">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    <main>