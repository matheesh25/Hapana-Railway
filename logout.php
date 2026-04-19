<?php
session_start();
include("config.php");

if(isset($_SESSION["user_id"])){
    $id = $_SESSION["user_id"];
    mysqli_query($conn,"DELETE FROM users WHERE id='$id'");
}

session_destroy();
header("Location: login.html");
exit();
?>