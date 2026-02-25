<?php
session_start();
require 'includes/db.php';
include 'includes/header.php';

/* ===============================
   INIT CART
================================ */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* ===============================
   LOGOUT
================================ */

if(isset($_GET['logout'])){
    session_destroy();
    header("Location: index.php");
    exit();
}


/* ===============================
   ADD TO CART
================================ */
if (isset($_POST['add'])) {
    $id = intval($_POST['product_id']);
    $_SESSION['cart'][] = $id;
}

/* ===============================
   REMOVE FROM CART
================================ */
if (isset($_POST['remove'])) {
    $id = intval($_POST['product_id']);
    if (($key = array_search($id, $_SESSION['cart'])) !== false) {
        unset($_SESSION['cart'][$key]);
    }
}

/* ===============================
   ADMIN: ADD PRODUCT
================================ */
if(isset($_POST['add_product']) && $_SESSION['role'] === 'admin'){

    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $description = trim($_POST['description']);

    if($name && $price > 0){
        $stmt = $conn->prepare("INSERT INTO products (name, price, description) VALUES (?, ?, ?)");
        $stmt->bind_param("sds", $name, $price, $description);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: index.php");
    exit();
}

/* ===============================
   ADMIN: DELETE PRODUCT
================================ */
if(isset($_POST['delete_product']) && $_SESSION['role'] === 'admin'){

    $id = intval($_POST['delete_id']);

    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit();
}

/* ===============================
   FETCH PRODUCTS
================================ */
$result = $conn->query("SELECT * FROM products");

/* ===============================
   OPTIMIZED CART FETCH
================================ */
$cart_items = [];
$total = 0;

if(!empty($_SESSION['cart'])){

    // Remove duplicates (optional but cleaner)
    $ids = array_unique($_SESSION['cart']);

    // Create placeholders (?, ?, ?)
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $types = str_repeat('i', count($ids));

    $stmt = $conn->prepare("SELECT id, name, price FROM products WHERE id IN ($placeholders)");
    $stmt->bind_param($types, ...$ids);
    $stmt->execute();

    $result_cart = $stmt->get_result();

    while($row = $result_cart->fetch_assoc()){
        $cart_items[$row['id']] = $row;
    }

    $stmt->close();

    // Calculate total including duplicates
    foreach($_SESSION['cart'] as $item){
        if(isset($cart_items[$item])){
            $total += $cart_items[$item]['price'];
        }
    }
}
?>

<main class="container">

<?php if(isset($_SESSION['user_id']) && isset($_SESSION['name'])): ?>
<p>
Welcome, <?= htmlspecialchars($_SESSION['name']); ?>
</p>
<?php else: ?>
    <p>Welcome, Guest</p>
<?php endif; ?>

<?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
<h2>Add Product</h2>
<form method="POST">
    <input type="text" name="name" placeholder="Product Name" required>
    <input type="number" name="price" step="0.01" required>
    <textarea name="description" placeholder="Description"></textarea>
    <button type="submit" name="add_product">Add Product</button>
</form>
<?php endif; ?>

<section class="products">
<h2>Our Products</h2>

<div class="product-grid">
<?php while($row = $result->fetch_assoc()): ?>
    <div class="product-card">
        <h3><?= htmlspecialchars($row['name']); ?></h3>
        <img src="img/products (1).png" alt="product image" class="product-image" width="150" height="150">
        <p>Ksh <?= number_format($row['price'], 2); ?></p>
        <p><?= htmlspecialchars($row['description']); ?></p>

        <form method="POST">
            <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
            <button name="add" class="add-btn">Add to Cart</button>
        </form>

        <form method="POST">
            <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
            <button name="remove" class="remove-btn">Remove</button>
        </form>

        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <form method="POST">
            <input type="hidden" name="delete_id" value="<?= $row['id']; ?>">
            <button name="delete_product" class="delete-btn" onclick="return confirm('Delete this product?')">Delete</button>
        </form>
        <?php endif; ?>
    </div>
<?php endwhile; ?>
</div>
</section>

<section class="cart">
<h2>Shopping Cart</h2>

<?php if(empty($_SESSION['cart'])): ?>
    <p>Your cart is empty.</p>
<?php else: ?>

    <?php foreach($_SESSION['cart'] as $item): ?>
        <?php if(isset($cart_items[$item])): ?>
            <p><?= htmlspecialchars($cart_items[$item]['name']); ?>
               - Ksh <?= number_format($cart_items[$item]['price'],2); ?></p>
        <?php endif; ?>
    <?php endforeach; ?>

    <p><strong>Total: Ksh <?= number_format($total, 2); ?></strong></p>
    <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>

<?php endif; ?>
</section>

</main>

<?php include 'includes/footer.php'; ?>