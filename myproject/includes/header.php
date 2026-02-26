<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Simple E-commerce platform built with PHP, CSS Flexbox, and JavaScript.">
    <meta name="keywords" content="Ecommerce, Online Store, PHP Shop, Shopping Cart">
    <meta name="author" content="Kamande,Selywn,Lloyd">

    <title>Simple E-Commerce</title>

    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>
    
<header class="main-header">

    <!-- Logo + Title -->
    <div class="brand-row">
        <img src="img/shopping-cart.png" alt="bidhaa-stores logo" class="logo">
        <h1 class="title">BIDHAA STORES</h1>
    </div>

    <!-- Navigation -->
    <nav class="main-nav">
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="./index.php" class="nav-btn">Home</a>
            <a href="./checkout.php" class="nav-btn">Checkout</a>
            <a href="?logout=true" class="nav-btn logout">Logout</a>
            <a href="./admin.php" class="nav-btn">Admin</a>

        <?php elseif(isset($_SESSION['user_id'])): ?>
            <a href="./index.php" class="nav-btn">Home</a>
            <a href="./checkout.php" class="nav-btn">Checkout</a>
            <a href="?logout=true" class="nav-btn logout">Logout</a>

        <?php else: ?>
            <a href="./index.php" class="nav-btn">Home</a>
            <a href="./login.php" class="nav-btn">Login</a>
            <a href="./register.php" class="nav-btn">Register</a>
        <?php endif; ?>

        <button class="theme-btn" onclick="toggleTheme()">🌙</button>
    </nav>

</header>
</nav><hr>
</header>
