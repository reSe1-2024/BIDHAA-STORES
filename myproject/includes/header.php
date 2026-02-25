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

<header>
    <h1>BIDHAA-STORES</h1>
    <nav>
    <?php if(isset($_SESSION['user_id'])): ?>
        <a href="./index.php">Home</a>
        <a href="./checkout.php">Checkout</a>
        <a href="?logout=true">Logout</a>
    <?php else: ?>
        <a href="./index.php">Home</a>
        <a href="./login.php">Login</a>
        <a href="./register.php">Register</a>
    <?php endif; ?>
    <button id="theme-toggle">Toggle Theme</button>
</nav>
<hr>
</header>