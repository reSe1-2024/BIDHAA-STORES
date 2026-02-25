<?php
session_start();
require 'includes/db.php';
include 'includes/header.php';

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

            header("Location: index.php");
            exit();
        } else {
            echo "Wrong password!";
        }
    } else {
        echo "User not found!";
    }

    $stmt->close();
}
if(!isset($_SESSION['user_id'])):
    ?>

<h2>Login</h2>
<form method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="login" onclick="window.location.href='index.php'">Login</button>
    <p>
    Don't have an account?
    <a href="register.php">Sign up</a>
</p>
</form>
<?php else: ?>

 
<?php endif; ?>