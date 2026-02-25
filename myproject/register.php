<?php session_start();
require 'includes/db.php';
include 'includes/header.php';

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
            echo "<p>Registration successful. <a href='./login.php'>Login here</a></p>";
        } else {
            echo "Email already exists!";
        }

        $stmt->close();
    }
}
?>
<div class="register-container">
<h2>Register</h2>
<form method="POST">
    <input type="text" name="name" placeholder="Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="register">Register</button>
    <p>
    Already have an account?
    <a href="login.php">Login</a>
</p>

</form>
</div>