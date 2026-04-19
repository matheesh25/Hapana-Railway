<?php
session_start();
include("config.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$id = $_SESSION['user_id'];

$res = mysqli_query($conn,"SELECT * FROM orders WHERE customer_id='$id' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Orders</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body{
    background:linear-gradient(to right,#000,#111,#000);
    color:white;
    font-family:'Segoe UI',sans-serif;
    padding-top:90px; /* ✅ FIX NAVBAR OVERLAP */
}

.navbar{
    background:rgba(0,0,0,0.85);
}

.navbar .nav-link{
    color:white !important;
}

.navbar .nav-link.profile-active i{
    color:#ffc107 !important;
    text-shadow:0 0 10px #ffc107,
                0 0 20px #ffc107,
                0 0 30px #ff9800;
}

.logo{
    width:40px;
    height:40px;
    border-radius:50%;
    object-fit:cover;
}
/* HEADER */
.header{
    text-align:center;
    margin-top:40px;
}

.header-icon{
    font-size:70px;
    color:#ffc107;
    text-shadow:0 0 20px #ffc107;
}

.subtitle{
    font-size:14px;
    color:#bbb;
}

/* MAIN CARD */
.main-card{
    background:#1a1a1a;
    padding:30px;
    border-radius:20px;
    box-shadow:0 0 25px rgba(255,193,7,0.08);
    margin-top:30px;
}

/* ORDER CARD */
.order-card{
    background:#111;
    border-radius:15px;
    padding:18px;
    margin-bottom:20px;
    transition:0.3s;
    border:1px solid #222;
}

.order-card:hover{
    transform:scale(1.02);
    box-shadow:0 0 15px rgba(255,193,7,0.25);
}

/* BADGES */
.badge-status{
    font-size:12px;
    padding:6px 12px;
    border-radius:20px;
}

/* BUTTONS */
.btn-sm-custom{
    font-size:13px;
    padding:6px 12px;
    border-radius:20px;
}

/* EMPTY */
.empty{
    text-align:center;
    color:#aaa;
    padding:40px;
}
</style>
</head>

<body>
<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-warning" href="Index.php">
            <img src="images/PHOTO-2026-02-12-17-21-53.jpg" class="logo">
            Hapana Fireworks
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="Index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Products.php">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Cart.html">Cart</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Login.html">Login</a>
                </li>
                <li class="nav-item ms-3">
                    <!-- ✅ ACTIVE PROFILE ICON -->
                    <a class="nav-link profile-active" href="profile.php">
                        <i class="bi bi-person-circle fs-4"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- HEADER -->
<div class="header">
    <div class="header-icon">
        <i class="bi bi-bag-check-fill"></i>
    </div>
    <h2 class="text-warning">My Orders</h2>
    <p class="subtitle">Track and manage your orders easily</p>
</div>

<div class="container">
<div class="main-card">

<h4 class="text-warning mb-3">📦 All Orders</h4>
<hr>

<?php
if(mysqli_num_rows($res) == 0){
    echo "<div class='empty'>No orders found</div>";
}
?>

<?php while($row = mysqli_fetch_assoc($res)){ ?>

<div class="order-card">

<!-- TOP -->
<div class="d-flex justify-content-between align-items-center">
<span><strong>#ORD<?= $row['id'] ?></strong></span>

<span class="badge badge-status
<?php
if($row['status']=="pending") echo "bg-warning text-dark";
elseif($row['status']=="approved") echo "bg-success";
elseif($row['status']=="completed") echo "bg-primary";
else echo "bg-danger";
?>">
<?= ucfirst($row['status']) ?>
</span>
</div>

<!-- INFO -->
<p class="mt-2 mb-1">📦 Product: <?= $row['product'] ?></p>
<p class="mb-1">💰 Total: <strong class="text-warning">Rs. <?= $row['total'] ?></strong></p>
<p class="text-muted" style="font-size:12px;">🗓 <?= $row['created_at'] ?></p>

<!-- ACTION -->
<div class="mt-2">
<button class="btn btn-warning btn-sm-custom"
onclick="location.href='orderDetail.php?id=<?= $row['id'] ?>'">
View Details
</button>

<a href="track.php?id=<?= $row['id'] ?>" class="btn btn-outline-warning btn-sm-custom ms-2">
        <i class="bi bi-truck"></i> Track
    </a>
</div>

</div>

<?php } ?>

<!-- BACK -->
<div class="text-center mt-4">
    <a href="profile.php" class="btn btn-outline-warning">
        <i class="bi bi-arrow-left"></i> Go Back
    </a>
</div>

</div>
</div>

</body>
</html>