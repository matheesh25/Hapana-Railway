<?php
session_start();
include("config.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

if(!isset($_GET['id'])){
    die("Order not found");
}

$order_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$checkOrder = mysqli_query($conn,"SELECT * FROM orders WHERE id='$order_id' AND customer_id='$user_id'");
$order = mysqli_fetch_assoc($checkOrder);

if(!$order){
    die("Invalid order");
}

$deliveryRes = mysqli_query($conn,"SELECT * FROM delivery WHERE order_id='$order_id'");
$delivery = mysqli_fetch_assoc($deliveryRes);

if(!$delivery){
    die("Delivery details not found");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Delivery Status</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body{
    background:#000;
    color:white;
    font-family:'Segoe UI',sans-serif;
}

/* CARD */
.card-dark{
    background:#1a1a1a;
    border-radius:20px;
    padding:30px;
    box-shadow:0 0 25px rgba(255,193,7,0.1);
    margin-top:50px;
}

/* ICON */
.delivery-icon{
    font-size:80px;
    color:#ffc107;
    text-shadow:0 0 20px #ffc107;
}

/* SUBTEXT */
.subtitle{
    font-size:14px;
    color:#bbb;
}

/* PROGRESS BAR */
.progress{
    height:10px;
    background:#333;
    border-radius:10px;
    margin-top:10px;
}

.progress-bar{
    background:linear-gradient(90deg,#ffc107,#ff9800);
}

/* TIMELINE */
.timeline{
    border-left:3px solid #ffc107;
    padding-left:20px;
    margin-top:20px;
}

.step{
    margin-bottom:20px;
    position:relative;
}

.step::before{
    content:'';
    position:absolute;
    left:-29px;
    top:5px;
    width:15px;
    height:15px;
    background:#ffc107;
    border-radius:50%;
}

.completed{
    color:#28a745;
}

.pending{
    color:#ffc107;
}

/* BOXES */
.box{
    background:#111;
    padding:15px;
    border-radius:12px;
    margin-top:20px;
}
</style>
</head>

<body>

<div class="container mt-5">
    
    <div class="card-dark text-center">

        <div class="delivery-icon mb-2">
            <i class="bi bi-truck"></i>
        </div>

        <h3 class="text-warning">Delivery Status</h3>
        <p class="subtitle"><?= htmlspecialchars($delivery['top_message']) ?></p>

        <div class="text-start mt-4">
            <small>Delivery Progress</small>
            <div class="progress">
                <div class="progress-bar" style="width:<?= $delivery['progress'] ?>%"></div>
            </div>
            <small class="text-warning"><?= $delivery['progress'] ?>% Completed</small>
        </div>

        <div class="box text-start">
            <h5 class="text-warning">📦 Delivery Summary</h5>
            <p><strong>Order ID:</strong> #<?= $delivery['order_id'] ?></p>
            <p><strong>Estimated Arrival:</strong> <?= htmlspecialchars($delivery['estimated_time']) ?></p>
            <p><strong>Courier:</strong> <?= htmlspecialchars($delivery['courier_service']) ?></p>
            <p><strong>Delivery Status:</strong> <?= htmlspecialchars($delivery['delivery_status']) ?></p>
        </div>

        <div class="box text-start">
            <h5 class="text-warning">📍 Shipping Address</h5>
            <p>
                <?= nl2br(htmlspecialchars($delivery['address'])) ?><br>
                📞 <?= htmlspecialchars($delivery['phone']) ?>
            </p>
        </div>

        <div class="timeline text-start">
            <div class="step <?= ($delivery['progress'] >= 25) ? 'completed' : 'text-muted' ?>">Order Placed</div>
            <div class="step <?= ($delivery['progress'] >= 50) ? 'completed' : 'text-muted' ?>">Processing</div>
            <div class="step <?= ($delivery['progress'] >= 75) ? 'completed' : 'text-muted' ?>">Dispatched</div>
            <div class="step <?= ($delivery['delivery_status'] == 'Out for Delivery' || $delivery['progress'] >= 90) ? 'pending' : 'text-muted' ?>">Out for Delivery</div>
            <div class="step <?= ($delivery['delivery_status'] == 'Delivered' || $delivery['progress'] == 100) ? 'completed' : 'text-muted' ?>">Delivery Completed</div>
        </div>

        <div class="mt-4 d-flex gap-2 justify-content-center">
            <a href="myOrders.php" class="btn btn-warning">
                Back
            </a>
        </div>

    </div>

</div>

</body>
</html>