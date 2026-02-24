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

if(isset($_SESSION['cart']) && is_array($_SESSION['cart']) && count($_SESSION['cart']) > 0){

    foreach($_SESSION['cart'] as $item){

        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $item);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        $total += $product['price'];

        $stmt->close();
    }

} else {
    echo "Your cart is empty.";
}


if(isset($_SESSION['cart'])){
    foreach($_SESSION['cart'] as $item){
        $product = $conn->query("SELECT * FROM products WHERE id = $item")->fetch_assoc();
        echo "<p>".$product['name']." - Ksh ".$product['price']."</p>";
    }
}

?>

<main class="container">
    <h2>Checkout</h2>
     <ul id="cart-items"></ul>
    <p><strong>Total: $<?= number_format($total, 2); ?></strong></p>

    <form method="POST">
        <input type="hidden" name="total" value="<?= $total; ?>">
        <button type="submit" name="checkout">Place Order</button>
    </form>

</main>

<?php include 'includes/footer.php'; ?>