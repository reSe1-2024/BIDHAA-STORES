<?php
session_start();
require 'includes/db.php';

if(isset($_POST['register'])){

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if(empty($name) || empty($email) || empty($password)){
        echo "All fields required!";
    } else {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if($stmt->execute()){
            echo "Registration successful!";
        } else {
            echo "Email already exists!";
        }

        $stmt->close();
    }
}

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result_user = $stmt->get_result();

    if($result_user->num_rows == 1){

        $user = $result_user->fetch_assoc();

        if(password_verify($password, $user['password'])){

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            echo "Login successful!";
        } else {
            echo "Wrong password!";
        }
    } else {
        echo "User not found!";
    }

    $stmt->close();
}

if(isset($_GET['logout'])){
    session_destroy();
    header("Location: index.php");
    exit();
}


$result = $conn->query("SELECT * FROM products");

include 'includes/header.php'; ?>

<main class="container">
<?php if(!isset($_SESSION['user_id'])): ?>

<h2>Login</h2>
<form method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="login">Login</button>
</form>

<h2>Register</h2>
<form method="POST">
    <input type="text" name="name" placeholder="Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="register">Register</button>
</form>

<?php else: ?>

<p>Welcome, <?= htmlspecialchars($_SESSION['user_name']); ?> |
<a href="?logout=true">Logout</a></p>

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

    <!-- Cart Section -->
    <section class="cart">
        <h2>Shopping Cart</h2>
        <ul id="cart-items"></ul>
        <p>Total: $<span id="total">0</span></p>
        <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
    </section>

</main>

<?php include 'includes/footer.php'; ?>