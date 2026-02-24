<?php
session_start();
require 'includes/db.php';
include 'includes/header.php';

if(isset($_POST['checkout'])){

    $user_id = $_SESSION['user_id'];
    $total = $_POST['total'];

    $stmt = $conn->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
    $stmt->bind_param("id", $user_id, $total);
    $stmt->execute();
    $stmt->close();

    unset($_SESSION['cart']);

    echo "Order placed successfully!";
}
$total = 0;

foreach($_SESSION['cart'] as $item){
    $product = $conn->query("SELECT * FROM products WHERE id=$item")->fetch_assoc();
    $total += $product['price'];
}
?>

<main class="container">
    <h2>Checkout</h2>
    <p>This is a simple checkout page.</p>
    <p>Your total will be processed here.</p>
</main>

<?php include 'includes/footer.php'; ?>