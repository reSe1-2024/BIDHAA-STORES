<?php
session_start();
require 'includes/db.php';
include 'includes/header.php';

// Initialize cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add product
if (isset($_POST['add'])) {
    $id = intval($_POST['product_id']);
    $_SESSION['cart'][] = $id;
}

// Remove product
if (isset($_POST['remove'])) {
    $id = intval($_POST['product_id']);

    // Remove first occurrence
    if (($key = array_search($id, $_SESSION['cart'])) !== false) {
        unset($_SESSION['cart'][$key]);
    }

}

if(isset($_GET['logout'])){
    session_destroy();
    header("Location: index.php");
    exit();
}
if(isset($_POST['add_product']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin'){

    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $description = trim($_POST['description']);

    $stmt = $conn->prepare("INSERT INTO products (name, price, description) VALUES (?, ?, ?)");
    $stmt->bind_param("sds", $name, $price, $description);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit();
}
if(isset($_POST['delete_product']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin'){

    $id = intval($_POST['delete_id']);

    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit();
}

$result = $conn->query("SELECT * FROM products");

 if(isset($_SESSION['user_id'])){
echo"<p>Welcome, ".htmlspecialchars($_SESSION['user_name'])." 
<a href=\"?logout=true\">Logout</a></p>";
 }

?>

<main class="container">
    

<?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>

<h2>Add Product</h2>
<form method="POST">
    <input type="text" name="name" placeholder="Product Name" required>
    <input type="number" name="price" step="0.01" placeholder="Price" required>
    <textarea name="description" placeholder="Description"></textarea>
    <button name="add_product">Add Product</button>
</form>

<?php endif; ?>





    <!-- Product Section -->
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

    <!-- Add to Cart -->
    <form method="POST">
        <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
        <button type="submit" name="add">Add to Cart</button>
    </form>

    <!-- Remove from Cart -->
    <form method="POST">
        <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
        <button type="submit" name="remove">Remove</button>
    </form>
    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
<form method="POST">
    <input type="hidden" name="delete_id" value="<?= $row['id']; ?>">
    <button name="delete_product">Delete</button>
</form>
<?php endif; ?>

       </div>
       <?php
            }
        } else {
            echo "<p>No products available.</p>";
        }
        ?>
        </div>
    </section>
<h2>Cart</h2>

<?php


if(isset($_SESSION['cart'])){
    foreach($_SESSION['cart'] as $item){
        $product = $conn->query("SELECT * FROM products WHERE id = $item")->fetch_assoc();
        echo "<p>".$product['name']." - Ksh ".$product['price']."</p>";
    }
}

$total = 0;

foreach($_SESSION['cart'] as $item){
    $product = $conn->query("SELECT * FROM products WHERE id=$item")->fetch_assoc();
    $total += $product['price'];
}
?>
    <!-- Cart Section -->
    <section class="cart">
        <h2>Shopping Cart</h2>
        <ul id="cart-items"></ul>
        <p><strong>Total: $<?= number_format($total, 2); ?></strong></p>
        <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
    </section>

</main>

<?php include 'includes/footer.php'; ?>