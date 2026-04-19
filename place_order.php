<?php
session_start();
include("config.php");
include("mail.php");

if(!isset($_SESSION['user_id'])){
    echo "login_required";
    exit();
}

$customer_id = $_SESSION['user_id'];
$customer_name = $_POST['name'];
$phone = $_POST['phone'];
$address = $_POST['address'];

$product = $_POST['product'];
$qty = $_POST['quantity'];
$total = $_POST['total'];
$payment = $_POST['payment'];

$card = "";

if(isset($_POST['card_number']) && !empty($_POST['card_number'])){
    $card = substr($_POST['card_number'], -4);
}

$sql = "INSERT INTO orders 
(customer_id, customer_name, product, quantity, total, payment, card_number, address, phone, status) 
VALUES 
('$customer_id','$customer_name','$product','$qty','$total','$payment','$card','$address','$phone','pending')";

if(mysqli_query($conn,$sql)){

    $order_id = mysqli_insert_id($conn);

    mysqli_query($conn,"INSERT INTO delivery 
    (order_id, address, phone, courier_service, estimated_time, delivery_status, progress, top_message) 
    VALUES 
    ('$order_id','$address','$phone','Hapana Delivery Service','2-3 Working Days','Order Placed',25,'Your order has been placed successfully!')");

    // 🔥 GET USER EMAIL
    $userRes = mysqli_query($conn, "SELECT email FROM users WHERE id='$customer_id'");
    $userData = mysqli_fetch_assoc($userRes);
    $userEmail = $userData['email'];

    // 🔥 SEND EMAIL
    $subject = "Order Placed Successfully ";
    $message = "
    <h2>Order Confirmation</h2>
    <p>Hello $customer_name 👋</p>
    <p>Your order has been placed successfully!</p>
    <hr>
    <p><b>Product:</b> $product</p>
    <p><b>Quantity:</b> $qty</p>
    <p><b>Total:</b> LKR $total</p>
    <p><b>Payment:</b> $payment</p>
    <hr>
    <p>Thank you for choosing Hapana Fireworks 🎆</p>
    ";

    sendMail($userEmail, $subject, $message);

    echo "success";

}else{
    echo "error";
}
?>