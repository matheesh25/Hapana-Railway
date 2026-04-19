<?php
session_start();
include("config.php");

if(!isset($_GET['id'])){
    die("Order not found");
}

$id = intval($_GET['id']);

$res = mysqli_query($conn,"SELECT * FROM orders WHERE id='$id'");
$row = mysqli_fetch_assoc($res);

if(!$row){
    die("Order not found");
}

// CALCULATIONS
$subtotal = $row['total'];
$discount = 0;
$shipping = 300;
$grand_total = $subtotal - $discount + $shipping;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order Details</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:linear-gradient(to right,#000,#111,#000);
    color:white;
    font-family:'Segoe UI',sans-serif;
    padding-top:90px; /* ✅ FIX NAVBAR OVERLAP */
}

/* MAIN CARD */
.order-card{
    background:linear-gradient(145deg,#111,#1a1a1a);
    border-radius:20px;
    padding:30px;
    box-shadow:0 0 25px rgba(255,193,7,0.1);
}

/* HEADER */
.title{
    color:#ffc107;
    font-weight:600;
}

/* ITEM ROW */
.item{
    background:#0f0f0f;
    padding:15px;
    border-radius:12px;
    margin-bottom:12px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    transition:0.3s;
}
.item:hover{
    background:#161616;
}

/* SUMMARY */
.summary{
    border-top:1px solid #333;
    margin-top:20px;
    padding-top:15px;
}

.summary-row{
    display:flex;
    justify-content:space-between;
    margin-bottom:10px;
    color:#ccc;
}

.total{
    color:#ffc107;
    font-weight:bold;
    font-size:18px;
}

/* STATUS */
.status{
    padding:6px 14px;
    border-radius:20px;
    font-size:13px;
}

/* BACK BTN */
.btn-back{
    border:1px solid #ffc107;
    color:#ffc107;
}
.btn-back:hover{
    background:#ffc107;
    color:#000;
}
</style>

</head>

<body>



<div class="container mt-5">

<h3 class="text-center title">🧾 Order Details</h3>

<div class="order-card mt-4">

<!-- TOP -->
<div class="d-flex justify-content-between align-items-center">
<div>
<p><strong>Order ID:</strong> #ORD<?= $row['id'] ?></p>
<p><strong>Date:</strong> <?= $row['created_at'] ?></p>
</div>

<span class="status 
<?php
if($row['status']=="pending") echo "bg-warning text-dark";
elseif($row['status']=="approved") echo "bg-success";
elseif($row['status']=="completed") echo "bg-primary";
else echo "bg-danger";
?>">
<?= ucfirst($row['status']) ?>
</span>

</div>

<hr style="border-color:#333;">

<!-- ITEM -->
<div class="item">
<div>
<div><?= $row['product'] ?></div>
<small>Qty: <?= $row['quantity'] ?></small>
</div>

<span class="text-warning">Rs. <?= $row['total'] ?></span>
</div>

<!-- SUMMARY -->
<div class="summary">

<div class="summary-row">
<span>Subtotal</span>
<span>Rs. <?= $subtotal ?></span>
</div>

<div class="summary-row">
<span>Discount</span>
<span>- Rs. <?= $discount ?></span>
</div>



<div class="summary-row total">
<span>Total</span>
<span>Rs. <?= $grand_total ?></span>
</div>

</div>

<!-- BACK -->
<div class="text-center mt-4">
<a href="myOrders.php" class="btn btn-back">← Back</a>
</div>

</div>
</div>

</body>
</html>