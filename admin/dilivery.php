<?php
session_start();

if(!isset($_SESSION["role"]) || $_SESSION["role"] != "admin"){
    header("Location: ../Login.html");
    exit();
}

include("../config.php");

$res = mysqli_query($conn,"
    SELECT delivery.*, orders.customer_name, orders.product
    FROM delivery
    JOIN orders ON delivery.order_id = orders.id
    ORDER BY delivery.id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Delivery Management</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>

/* SAME AS DASHBOARD */
body{
    background:#000;
    color:white;
    font-family:'Segoe UI',sans-serif;
    animation:fadeIn 0.6s ease-in-out;
}
@keyframes fadeIn{
    from{opacity:0; transform:translateY(10px);}
    to{opacity:1; transform:translateY(0);}
}

/* SIDEBAR (UNCHANGED STYLE) */
.sidebar{
    height:100vh;
    background:#111;
    padding-top:30px;
}
.sidebar h4{
    color:#ffc107;
}
.sidebar a{
    color:white;
    display:block;
    padding:12px 20px;
    text-decoration:none;
    transition:0.3s;
    margin:5px 10px;
    border-radius:8px;
}

/* SAME AS PRODUCTS PAGE */
.sidebar a:hover,
.sidebar a.active{
    background:linear-gradient(45deg,#ff9800,#ffc107);
    color:black;
    transform:translateX(5px);
}

/* CARD STYLE (MATCH DASHBOARD) */
.card-box{
    background:#111;
    border:1px solid #333;
    border-radius:15px;
    padding:20px;
    transition:0.3s;
}

/* GLOW EFFECT */
.card-box:hover{
    box-shadow:0 0 25px rgba(255,193,7,0.4);
}

/* TABLE */
.table{
    color:white;
}
.table thead{
    background:#111;
}
.table th{
    color:#ffc107;
    border:none;
}
.table td{
    border-color:#222;
}

/* ROW HOVER */
.table tbody tr:hover{
    background:rgba(255,193,7,0.08);
}

/* SEARCH */
input{
    background:#000 !important;
    color:white !important;
    border:1px solid #333 !important;
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
    <a href="suppliers.php"><i class="bi bi-people"></i> Suppliers</a>
    <a href="products.php"><i class="bi bi-box"></i> Products</a>
    <a href="orders.php"><i class="bi bi-cart"></i> Orders</a>
    <a href="users.php"><i class="bi bi-person"></i> Users</a>
    <a href="dilivery.php" class="active"><i class="bi bi-truck"></i> Delivery</a>
    <a href="../logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- MAIN -->
<div class="col-md-10 p-4">

<h2 class="text-warning mb-4">Delivery Management</h2>

<div class="card-box">

<!-- SEARCH -->
<input type="text" id="search" class="form-control mb-3" placeholder="Search customer...">
<a href="delivery_report.php" class="btn btn-warning mb-3">
    <i class="bi bi-file-earmark-pdf"></i> Download Delivery Report
</a>
<table class="table table-bordered text-center">

<thead>
<tr>
<th>Order</th>
<th>Customer</th>
<th>Product</th>
<th>Phone</th>
<th>Progress</th>
<th>Status</th>
<th>ETA</th>
<th>Courier</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($res)){ ?>

<tr>
<td>#<?= $row['order_id'] ?></td>
<td><?= $row['customer_name'] ?></td>
<td><?= $row['product'] ?></td>
<td><?= $row['phone'] ?></td>
<td><?= $row['progress'] ?>%</td>

<td>
<span class="badge bg-warning text-dark">
<?= $row['delivery_status'] ?>
</span>
</td>

<td><?= $row['estimated_time'] ?></td>
<td><?= $row['courier_service'] ?></td>

<td>
<a href="edit_delivery.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">
Edit
</a>
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
// SEARCH
document.getElementById("search").addEventListener("keyup", function(){
    let val = this.value.toLowerCase();
    document.querySelectorAll("tbody tr").forEach(row=>{
        row.style.display = row.innerText.toLowerCase().includes(val) ? "" : "none";
    });
});
</script>

</body>
</html>