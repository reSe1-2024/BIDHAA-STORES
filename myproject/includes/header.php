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

    <div class="brand-row">
        <img src="./images/LOGO.jpg" alt="Logo" class="brand-logo">
        <h1 class="brand-title">BIDHAA STORES</h1>
    </div>

      <nav class="main-nav">
        <?php if(isset($_SESSION['user_id'])): ?>
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


    
<header>
    <img src="img/shopping-cart.png" alt="bidhaa-stores logo" class="logo" width="50" height="50">
    <h1>BIDHAA-STORES</h1>
<nav>
<?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <ul>
    <li class="nav-item"><a href="./index.php">Home</a></li >
    <li class="nav-item"><a href="./checkout.php">Checkout</a></li >
    <li class="nav-item"><a href="?logout=true">Logout</a></li >
    <li class="nav-item"><a href="./admin.php">Admin</a></li >
    </ul>
   

<?php elseif(isset($_SESSION['user_id'])): ?>
    <ul>
    <li class="nav-item"><a href="./index.php">Home</a></li >
    <li class="nav-item"><a href="./checkout.php">Checkout</a></li >
    <li class="nav-item"><a href="?logout=true">Logout</a></li >
    </ul>
    

<?php else: ?>
    <ul>
    <li class="nav-item"><a href="./index.php">Home</a></li >
    <li class="nav-item"><a href="./login.php">Login</a></li >
    <li class="nav-item"><a href="./register.php">Register</a></li >
    </ul>
    

<?php endif; ?>

<button id="theme-toggle" class="toggle-btn">Toggle Theme</button>
</nav><hr>
</header>