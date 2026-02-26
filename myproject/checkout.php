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

    <?php if(empty($_SESSION['cart'])): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>

        <?php foreach($_SESSION['cart'] as $item): ?>
            <?php if(isset($cart_products[$item])): ?>
                <p>
                    <?= htmlspecialchars($cart_products[$item]['name']); ?>
                    - Ksh <?= number_format($cart_products[$item]['price'],2); ?>
                </p>
            <?php endif; ?>
        <?php endforeach; ?>

        <p><strong>Total: Ksh <?= number_format($total, 2); ?></strong></p>

        <form method="POST">
            <button type="submit" name="checkout" class="checkout-btn">
                Place Order
            </button>
        </form>

    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>