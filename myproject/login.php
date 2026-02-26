<?php
session_start();
require 'includes/db.php';
include 'includes/header.php';

$error = "";

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($user = $result->fetch_assoc()){

        if(password_verify($password, $user['password'])){

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];
            
            if(isset($_POST['remember'])){
                setcookie("email", $user['email'], time() + (86400 * 30), "/"); // 30 days
            } else {
                // Clear cookie if not checked
                setcookie("email", "", time() - 3600, "/");
            }

            header("Location: index.php");
            exit();
        } else {
            $error = "Incorrect password";
        }
    } else {
        $error = "Email not found!";
    }
    echo "Invalid login!";
}
?>

<h2 class="login-title">Login</h2>

<?php if($error != ""): ?>
    <p style="color:red;"><?= $error; ?></p>
<?php endif; ?>

<?php if(!isset($_SESSION['user_id'])): ?>

<div class="login-container">
    <form method="POST">
        <input 
            type="email" 
            name="email" 
            placeholder="Email" 
            required 
            value="<?= htmlspecialchars($_COOKIE['email'] ?? '') ?>"
        >

        <input 
            type="password" 
            name="password" 
            placeholder="Password" 
            required
        >

        <label>
            <input type="checkbox" name="remember">
            Remember me
        </label>

        <button type="submit" name="login">Login</button>

        <p>
            Don't have an account?
            <a href="register.php">Sign up</a>
        </p>
    </form>
</div>

<?php else: ?>

    <p>You are already logged in.</p>

<?php endif; ?>
