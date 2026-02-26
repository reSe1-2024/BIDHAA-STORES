<?php
session_start();
require 'includes/db.php';
include 'includes/header.php';

// 🔒 Only admin can access
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    echo "Access Denied!";
    exit();
}

// Add Product
if(isset($_POST['add_product'])){

    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO products (name, price, description) VALUES (?, ?, ?)");
    $stmt->bind_param("sds", $name, $price, $description);
    $stmt->execute();
    $stmt->close();

    echo "<p style='color:green;'>Product Added Successfully!</p>";
}

// Delete Product
if(isset($_GET['delete'])){

    $id = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    echo "<p style='color:red;'>Product Deleted!</p>";
}
?>

<h2>Admin Panel</h2>

<h3>Add Product</h3>
<form method="POST">
    <input type="text" name="name" placeholder="Product Name" required><br>
    <input type="number" step="0.01" name="price" placeholder="Price" required><br>
    <textarea name="description" placeholder="Description"></textarea><br>
    <button type="submit" name="add_product" class=" addition-btn">Add Product</button>
</form>

<h3>All Products</h3>

<?php
$result = $conn->query("SELECT * FROM products");

while($product = $result->fetch_assoc()):
?>

<p>
<?= htmlspecialchars($product['name']); ?> - 
Ksh <?= number_format($product['price'],2); ?>
<a href="admin.php?delete=<?= $product['id']; ?>" onclick="return confirm('Delete this product?')">Delete</a>
</p>

<?php endwhile; ?>

<?php include 'includes/footer.php'; ?>