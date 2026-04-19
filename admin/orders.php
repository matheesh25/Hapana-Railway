<?php
session_start();

if(!isset($_SESSION["role"]) || $_SESSION["role"] != "admin"){
    header("Location: ../Login.html");
    exit();
}

include("../config.php");

// COUNTS
$totalOrders = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM orders"))[0];
$pending = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM orders WHERE status='pending'"))[0];
$approved = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM orders WHERE status='approved'"))[0];
$completed = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM orders WHERE status='completed'"))[0];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order Management</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>

body{
    background:linear-gradient(135deg,#000,#0a0a0a,#000);
    color:white;
    font-family:'Segoe UI',sans-serif;
}

/* SIDEBAR */
.sidebar{
    height:100vh;
    background:#0d0d0d;
    padding-top:30px;
    border-right:1px solid #222;
}
.sidebar h4{
    color:#ffc107;
    margin-bottom:20px;
}
.sidebar a{
    color:#ccc;
    display:block;
    padding:12px 20px;
    text-decoration:none;
    transition:0.3s;
    border-radius:8px;
}
.sidebar a:hover,
.sidebar .active{
    background:linear-gradient(45deg,#ff9800,#ffc107);
    color:black;
    transform:translateX(5px);
}

/* STATS */
.stat-card{
    background:rgba(255,255,255,0.03);
    border:1px solid #222;
    border-radius:20px;
    padding:25px;
    text-align:center;
    backdrop-filter:blur(10px);
    transition:0.3s;
}
.stat-card:hover{
    transform:translateY(-5px);
    box-shadow:0 0 20px rgba(255,193,7,0.2);
}
.stat-card h3{
    color:#ffc107;
}

/* TABLE BOX */
.table-box{
    background:rgba(255,255,255,0.02);
    padding:25px;
    border-radius:20px;
    border:1px solid #222;
    backdrop-filter:blur(8px);
    overflow-x:auto;
}

/* TABLE */
.table thead{
    background:#111;
}
.table th{
    color:#ffc107;
    border:none;
}
.table td{
    border-color:#222;
    vertical-align:middle;
}

/* ROW HOVER */
.table tbody tr{
    transition:0.3s;
}
.table tbody tr:hover{
    background:rgba(255,193,7,0.05);
    transform:scale(1.01);
}

/* BADGES */
.badge{
    padding:7px 14px;
    border-radius:20px;
}

/* BUTTONS */
.btn-action{
    border-radius:20px;
    font-size:12px;
    padding:6px 14px;
}

/* SEARCH + FILTER */
#searchInput, #filterPayment, #filterStatus{
    background:#111;
    color:white;
    border:1px solid #333;
}
#searchInput:focus, #filterPayment:focus, #filterStatus:focus{
    border-color:#ffc107;
    box-shadow:none;
}

</style>
</head>

<body>

<div class="container-fluid">
<div class="row">

<!-- SIDEBAR -->
<div class="col-md-2 sidebar">
            <h4 class="text-center">Admin Panel</h4>
            <a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="suppliers.php" ><i class="bi bi-people"></i> Suppliers</a>
            <a href="products.php"><i class="bi bi-box"></i> Products</a>
            <a href="orders.php"class="active"><i class="bi bi-cart"></i> Orders</a>
            <a href="users.php"><i class="bi bi-person"></i> Users</a>
            <a href="dilivery.php"><i class="bi bi-truck"></i> Dilivery</a>
            <a href="../logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>

<!-- MAIN -->
<div class="col-md-10 p-4">

<h2 class="text-warning mb-4">Order Management</h2>

<!-- STATS -->
<div class="row mb-4">
<div class="col-md-3"><div class="stat-card"><h3><?= $totalOrders ?></h3>Total</div></div>
<div class="col-md-3"><div class="stat-card"><h3><?= $pending ?></h3>Pending</div></div>
<div class="col-md-3"><div class="stat-card"><h3><?= $approved ?></h3>Approved</div></div>
<div class="col-md-3"><div class="stat-card"><h3><?= $completed ?></h3>Completed</div></div>
</div>

<a href="orders_report.php" class="btn btn-warning mb-3">
    <i class="bi bi-file-earmark-pdf"></i> Download Orders Report
</a>

<!-- TABLE -->
<div class="table-box">

<!-- SEARCH + FILTER -->
<div class="d-flex flex-wrap gap-2 mb-3">
    <input type="text" id="searchInput" class="form-control w-auto" placeholder="Search customer...">

    <select id="filterPayment" class="form-select w-auto">
        <option value="all">All Payments</option>
        <option value="cash">Cash</option>
        <option value="card">Card</option>
    </select>

    <select id="filterStatus" class="form-select w-auto">
        <option value="all">All Status</option>
        <option value="pending">Pending</option>
        <option value="approved">Approved</option>
        <option value="completed">Completed</option>
        <option value="cancelled">Cancelled</option>
    </select>
</div>

<table class="table table-bordered text-center">

<thead>
<tr>
<th>ID</th>
<th>Customer</th>
<th>Total</th>
<th>Payment</th>
<th>Status</th>
<th>Date</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php
$res = mysqli_query($conn,"SELECT * FROM orders ORDER BY id DESC");
while($row = mysqli_fetch_assoc($res)){
?>

<tr>
<td>#<?= $row['id'] ?></td>
<td><?= $row['customer_name'] ?></td>
<td class="text-warning">Rs. <?= $row['total'] ?></td>

<td>
<?php
if($row['payment'] === "cod"){
    echo '<span class="badge bg-secondary">Cash</span>';
}else{
    if(!empty($row['card_number'])){
        echo '<span class="badge bg-success">💳 Card (****'.$row['card_number'].')</span>';
    }else{
        echo '<span class="badge bg-info text-dark">Card</span>';
    }
}
?>
</td>

<td>
<span class="badge 
<?= ($row['status']=="pending") ? "bg-warning text-dark" : 
(($row['status']=="approved") ? "bg-success" : 
(($row['status']=="completed") ? "bg-primary" : "bg-danger")) ?>">
<?= ucfirst($row['status']) ?>
</span>
</td>

<td><?= $row['created_at'] ?></td>

<td>
<?php if($row['status']=="pending"){ ?>
<a href="update_order.php?id=<?= $row['id'] ?>&status=approved" class="btn btn-success btn-sm btn-action">Approve</a>
<a href="update_order.php?id=<?= $row['id'] ?>&status=cancelled" class="btn btn-danger btn-sm btn-action">Cancel</a>

<?php } elseif($row['status']=="approved"){ ?>
<a href="update_order.php?id=<?= $row['id'] ?>&status=completed" class="btn btn-primary btn-sm btn-action">Complete</a>

<?php } else { ?>
<button class="btn btn-secondary btn-sm btn-action" disabled>Done</button>
<?php } ?>
</td>

</tr>

<?php } ?>

</tbody>
</table>
</div>

</div>
</div>
</div>

<script>

// SEARCH + FILTER
const searchInput = document.getElementById("searchInput");
const filterPayment = document.getElementById("filterPayment");
const filterStatus = document.getElementById("filterStatus");

function filterTable(){
    let search = searchInput.value.toLowerCase();
    let pay = filterPayment.value;
    let status = filterStatus.value;

    let rows = document.querySelectorAll("tbody tr");

    rows.forEach(row => {

        let customer = row.children[1].innerText.toLowerCase();
        let payment = row.children[3].innerText.toLowerCase();
        let stat = row.children[4].innerText.toLowerCase();

        let matchSearch = customer.includes(search);
        let matchPayment = (pay === "all") || payment.includes(pay);
        let matchStatus = (status === "all") || stat.includes(status);

        row.style.display = (matchSearch && matchPayment && matchStatus) ? "" : "none";
    });
}

searchInput.addEventListener("keyup", filterTable);
filterPayment.addEventListener("change", filterTable);
filterStatus.addEventListener("change", filterTable);

</script>

</body>
</html>