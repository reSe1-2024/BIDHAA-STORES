<?php include 'includes/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bidhaa</title>
</head>
<body>
    <main class="container">
            <section class="products">
        <h2>Our Products</h2>

        <div class="product-grid">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
        ?>
                <div class="product-card">
                    <h3><?= htmlspecialchars($row['name']); ?></h3>
                    <p>$<?= number_format($row['price'], 2); ?></p>
                    <p><?= htmlspecialchars($row['description']); ?></p>
                    <button onclick="addToCart('<?= htmlspecialchars($row['name']); ?>', <?= $row['price']; ?>)">
                        Add to Cart
                    </button>
                </div>
        <?php
            }
        } else {
            echo "<p>No products available.</p>";
        }
        ?>
        </div>
    </section>

    <section class="cart">
        <h2>Shopping Cart</h2>
        <ul id="cart-items"></ul>
        <p>Total: $<span id="total">0</span></p>
        <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
    </section>

</main>

</body>
</html>
  

<?php include 'includes/footer.php'; ?>