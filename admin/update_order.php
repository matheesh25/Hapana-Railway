<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../config.php");

if(!isset($_GET['id']) || !isset($_GET['status'])){
    die("Invalid request");
}

$id = intval($_GET['id']);
$status = $_GET['status'];

$allowed = ['pending','approved','completed','cancelled'];

if(in_array($status,$allowed)){

    $result = mysqli_query($conn,"UPDATE orders SET status='$status' WHERE id='$id'");

    if(!$result){
        die("Update error: " . mysqli_error($conn));
    }

}else{
    die("Invalid status");
}

header("Location: orders.php");
exit();
?>