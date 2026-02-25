<?php
session_start();

if(isset($_POST['theme'])){
    $theme = $_POST['theme'];
    $_SESSION['theme'] = $theme; // save in session
    echo "Theme saved";
}