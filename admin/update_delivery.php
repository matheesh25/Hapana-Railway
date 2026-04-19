<?php
session_start();

if(!isset($_SESSION["role"]) || $_SESSION["role"] != "admin"){
    header("Location: ../Login.html");
    exit();
}

include("../config.php");
include("../mail.php"); // ✅ ADD THIS

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $id = $_POST['id'];
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $courier_service = mysqli_real_escape_string($conn, $_POST['courier_service']);
    $estimated_time = mysqli_real_escape_string($conn, $_POST['estimated_time']);
    $top_message = mysqli_real_escape_string($conn, $_POST['top_message']);
    $progress = (int)$_POST['progress'];
    $delivery_status = mysqli_real_escape_string($conn, $_POST['delivery_status']);

    // ✅ UPDATE DELIVERY
    mysqli_query($conn,"
        UPDATE delivery SET
        address='$address',
        phone='$phone',
        courier_service='$courier_service',
        estimated_time='$estimated_time',
        top_message='$top_message',
        progress='$progress',
        delivery_status='$delivery_status'
        WHERE id='$id'
    ");

    // 🔥 GET USER EMAIL
    $userRes = mysqli_query($conn, "
    SELECT users.email, orders.customer_name 
    FROM delivery
    JOIN orders ON delivery.order_id = orders.id
    JOIN users ON orders.customer_id = users.id
    WHERE delivery.id='$id'
    ");

    $userData = mysqli_fetch_assoc($userRes);
    $userEmail = $userData['email'];
    $customerName = $userData['customer_name'];

    // 🔥 SEND EMAIL
    $subject = "Delivery Update ";
    $message = "
    <h2>Hello $customerName 👋</h2>
    <p>Your delivery has been updated!</p>
    <hr>
    <p><b>Status:</b> $delivery_status</p>
    <p><b>Progress:</b> $progress%</p>
    <p><b>Estimated Time:</b> $estimated_time</p>
    <p><b>Courier Service:</b> $courier_service</p>
    <hr>
    <p>Track your order in Hapana Fireworks 🎆</p>
    ";

    sendMail($userEmail, $subject, $message);

    header("Location: dilivery.php");
    exit();
}
?>