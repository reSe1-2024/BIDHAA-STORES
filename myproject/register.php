<?php
require 'includes/db.php';
include 'includes/header.php';

if(isset($_POST['register'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    if($email === "admin@minishop.com"){
       $role = "admin";
       } else {
       $role = "user";
    }


    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);
    $stmt->execute();
    $stmt->close();

    echo "Registered Successfully!";
}
?>

<h2>Register</h2>

<form method="POST">
    <input type="text" name="name" placeholder="Name" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit" name="register">Register</button>
    <p>Already have an Account?<a href="login.php">Login</a></p>
</form>