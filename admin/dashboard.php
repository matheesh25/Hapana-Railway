<?php
session_start();
include("../config.php");

// 🔔 NOTIFICATIONS
$newOrders = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total FROM orders WHERE status='pending'
"))['total'];

$newUsers = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total FROM users WHERE created_at >= NOW() - INTERVAL 1 DAY
"))['total'];

// ORDER STATUS COUNTS
$pending = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) t FROM orders WHERE status='pending'"))['t'];
$approved = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) t FROM orders WHERE status='approved'"))['t'];
$completed = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) t FROM orders WHERE status='completed'"))['t'];

// TOTAL SALES
$totalSales = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(total) t FROM orders"))['t'];

if(!isset($_SESSION["role"]) || $_SESSION["role"] != "admin"){
    header("Location: ../Login.html");
    exit();
}

/* DATA */
$totalProducts = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) t FROM products"))['t'];
$totalSuppliers = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) t FROM suppliers"))['t'];
$totalOrders = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) t FROM orders"))['t'];
$totalUsers = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) t FROM users"))['t'];

$activeUsers = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) t FROM users WHERE status='active'"))['t'];
$blockedUsers = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) t FROM users WHERE status='blocked'"))['t'];

$lowStock = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) t FROM products WHERE stock < 10"))['t'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body{
    background:#000;
    color:white;
    font-family:'Segoe UI',sans-serif;
    animation:fadeIn 0.5s ease-in-out;
}
@keyframes fadeIn{
    from{opacity:0; transform:translateY(10px);}
    to{opacity:1;}
}

/* SIDEBAR FIXED */
.sidebar{
    min-height:100vh;
    background:#111;
    padding-top:30px;
    position:sticky;
    top:0;
}
.sidebar h4{color:#ffc107;}
.sidebar a{
    color:white;
    display:block;
    padding:12px 20px;
    text-decoration:none;
    transition:0.3s;
}
.sidebar a:hover,
.sidebar a.active{
    background:linear-gradient(45deg,#ff9800,#ffc107);
    color:black;
    transform:translateX(5px);
    border-radius:8px;
}

/* CARDS */
.card{
    background:#111;
    border:1px solid #333;
    border-radius:15px;
    transition:0.3s;
}
.card:hover{
    box-shadow:0 0 25px rgba(255,193,7,0.5);
    transform:translateY(-3px);
}

/* SMALL UI POLISH */
h2{letter-spacing:1px;}
canvas{max-height:300px;}
.alert-box{
    background:#111;
    border:1px solid #ffc107;
    padding:15px;
    border-radius:10px;
    margin-top:20px;
}

.dropdown-menu li{
    padding:5px 0;
}
</style>
</head>

<body>

<div class="container-fluid">
<div class="row">

<!-- SIDEBAR -->
<div class="col-md-2 sidebar">
    <h4 class="text-center">Admin Panel</h4>
    <a href="dashboard.php" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="suppliers.php"><i class="bi bi-people"></i> Suppliers</a>
    <a href="products.php"><i class="bi bi-box"></i> Products</a>
    <a href="orders.php"><i class="bi bi-cart"></i> Orders</a>
    <a href="users.php"><i class="bi bi-person"></i> Users</a>
    <a href="dilivery.php"><i class="bi bi-truck"></i> Delivery</a>
    <a href="../logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- MAIN -->
<div class="col-md-10 p-4">

<h2 class="text-warning mb-4">Dashboard Overview</h2>

<div class="d-flex justify-content-end mb-3">

<div class="dropdown">

<button class="btn btn-dark position-relative" data-bs-toggle="dropdown">
    <i class="bi bi-bell text-warning fs-5"></i>

    <!-- 🔴 COUNT -->
    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
        <?= $newOrders + $lowStock + $newUsers ?>
    </span>
</button>

<ul class="dropdown-menu dropdown-menu-end p-3" style="width:250px; background:#111; color:white;">

    <li><strong class="text-warning">Notifications</strong></li>
    <hr>

    <li>🆕 New Orders: <?= $newOrders ?></li>
    <li>⚠️ Low Stock: <?= $lowStock ?></li>
    <li>👤 New Users: <?= $newUsers ?></li>

</ul>

</div>

</div>

<!-- QUICK INFO -->
<div class="alert-box">
📦 Low Stock Products: <b><?= $lowStock ?></b>
</div>

<!-- STATS -->
<div class="row g-4 mt-2">

<div class="col-md-3">
<div class="card text-center p-4">
<i class="bi bi-box-seam fs-1 text-warning"></i>
<h3><?= $totalProducts ?></h3>
<p>Total Products</p>
</div>
</div>

<div class="col-md-3">
<div class="card text-center p-4">
<i class="bi bi-people-fill fs-1 text-warning"></i>
<h3><?= $totalSuppliers ?></h3>
<p>Total Suppliers</p>
</div>
</div>

<div class="col-md-3">
<div class="card text-center p-4">
<i class="bi bi-cart-fill fs-1 text-warning"></i>
<h3><?= $totalOrders ?></h3>
<p>Total Orders</p>
</div>
</div>

<div class="col-md-3">
<div class="card text-center p-4">
<i class="bi bi-person-circle fs-1 text-warning"></i>
<h3><?= $totalUsers ?></h3>
<p>Total Users</p>
</div>
</div>

</div>

<!-- CHARTS -->
<div class="row mt-5 g-4">

<!-- BAR -->
<div class="col-md-6">
<div class="card p-4">
<h5 class="text-warning">System Overview</h5>
<canvas id="barChart"></canvas>
</div>
</div>

<!-- PIE -->
<div class="col-md-6">
<div class="card p-4">
<h5 class="text-warning">User Status</h5>
<canvas id="pieChart"></canvas>
</div>
</div>

<!-- ORDER STATUS CHART -->
<div class="col-md-6">
<div class="card p-4">
<h5 class="text-warning">Order Status</h5>
<canvas id="orderChart"></canvas>
</div>
</div>

<!-- SALES BOX -->
<div class="col-md-6">
<div class="card p-4 text-center">
<h5 class="text-warning">Total Sales</h5>
<h2>LKR <?= $totalSales ? $totalSales : 0 ?></h2>
</div>
</div>

</div>

</div>
</div>
</div>

<script>

// BAR CHART
new Chart(document.getElementById("barChart"), {
    type: 'bar',
    data: {
        labels: ['Products','Suppliers','Orders','Users'],
        datasets: [{
            label: 'Count',
            data: [<?= $totalProducts ?>, <?= $totalSuppliers ?>, <?= $totalOrders ?>, <?= $totalUsers ?>],
            borderRadius:5
        }]
    },
    options:{
        plugins:{legend:{display:false}}
    }
});

// PIE
new Chart(document.getElementById("pieChart"), {
    type: 'doughnut',
    data: {
        labels: ['Active Users','Blocked Users'],
        datasets: [{
            data: [<?= $activeUsers ?>, <?= $blockedUsers ?>]
        }]
    }
});

// ORDER STATUS CHART
new Chart(document.getElementById("orderChart"), {
    type: 'doughnut',
    data: {
        labels: ['Pending','Approved','Completed'],
        datasets: [{
            data: [<?= $pending ?>, <?= $approved ?>, <?= $completed ?>],
            backgroundColor: ['#ff9800','#ffc107','#17a2b8','#28a745']
        }]
    }
});
</script>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</html>