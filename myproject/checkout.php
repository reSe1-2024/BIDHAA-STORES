<?php
session_start();
require 'includes/db.php';
include 'includes/header.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$total = 0;
$cart_products = [];

if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0){

    foreach($_SESSION['cart'] as $item){

        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $item);
        $stmt->execute();
        $result = $stmt->get_result();

        if($product = $result->fetch_assoc()){
            $cart_products[] = $product;
            $total += $product['price'];
        }

        $stmt->close();
    }
}

if(isset($_POST['checkout']) && $total > 0){

    $stmt = $conn->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
    $stmt->bind_param("id", $_SESSION['user_id'], $total);
    $stmt->execute();
    $stmt->close();

    unset($_SESSION['cart']);

    echo "<p style='color:green;'>Order placed successfully!</p>";
}
?>

<main class="container">
    <h2>Checkout</h2>
    <ul id="cart-items"></ul>
    <p><strong>Total: $<?= number_format($total, 2); ?></strong></p>

    <form method="POST">
        <input type="hidden" name="total" value="<?= $total; ?>">
        <button type="submit" name="checkout" class="checkout-btn">
            Place Order
        </button>
    </form>
</main>
<h2>Checkout</h2>

<?php foreach($cart_products as $product): ?>
    <p><?= htmlspecialchars($product['name']); ?> - Ksh <?= number_format($product['price'],2); ?></p>
<?php endforeach; ?>

<p><strong>Total: Ksh <?= number_format($total,2); ?></strong></p>

<form method="POST">
    <button type="submit" name="checkout" class="toggle-btn">Place Order</button>
</form>

<?php include 'includes/footer.php'; ?>