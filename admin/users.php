<?php
session_start();
include("../config.php");

// Admin check
if(!isset($_SESSION['role']) || $_SESSION['role']!="admin"){
    header("Location: ../login.php");
    exit();
}

// Block
if(isset($_GET['block'])){
    $id = intval($_GET['block']);

    $res = mysqli_query($conn,"UPDATE users SET status='blocked' WHERE id='$id'");

    if(!$res){
        die("Block Error: " . mysqli_error($conn));
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Unblock
if(isset($_GET['unblock'])){
    $id = intval($_GET['unblock']);

    $res = mysqli_query($conn,"UPDATE users SET status='active' WHERE id='$id'");

    if(!$res){
        die("Unblock Error: " . mysqli_error($conn));
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Delete
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);

    $res = mysqli_query($conn,"DELETE FROM users WHERE id='$id'");

    if(!$res){
        die("Delete Error: " . mysqli_error($conn));
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch users
$sql = "SELECT * FROM users";
$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>User Management</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body{background:#000;color:white;}
.sidebar{height:100vh;background:#111;padding-top:30px;}
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

.card{background:#111;border:1px solid #333;border-radius:10px;}
.stat-box{padding:20px;text-align:center;border-radius:10px;}
.stat1{background:#ff9800;}
.stat2{background:#2196f3;}
.stat3{background:#4caf50;}
.stat4{background:#f44336;}
</style>
</head>

<body>

<div class="container-fluid">
<div class="row">

<div class="col-md-2 sidebar">
            <h4 class="text-center">Admin Panel</h4>
            <a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="suppliers.php"><i class="bi bi-people"></i> Suppliers</a>
            <a href="products.php"><i class="bi bi-box"></i> Products</a>
            <a href="orders.php"><i class="bi bi-cart"></i> Orders</a>
            <a href="users.php" class="active"><i class="bi bi-person"></i> Users</a>
            <a href="dilivery.php"><i class="bi bi-truck"></i> Dilivery</a>
            <a href="../logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>

<div class="col-md-10 p-4">

<h2 class="text-warning">User Management</h2>

<div class="row mb-4">
<div class="col-md-3"><div class="stat-box stat1">
Total Users<br><?php echo mysqli_num_rows($result); ?>
</div></div>

<div class="col-md-3"><div class="stat-box stat2">
Active<br><?php echo mysqli_num_rows(mysqli_query($conn,"SELECT * FROM users WHERE status='active'")); ?>
</div></div>

<div class="col-md-3"><div class="stat-box stat3">
Blocked<br><?php echo mysqli_num_rows(mysqli_query($conn,"SELECT * FROM users WHERE status='blocked'")); ?>
</div></div>

<div class="col-md-3"><div class="stat-box stat4">
Admins<br><?php echo mysqli_num_rows(mysqli_query($conn,"SELECT * FROM users WHERE role='admin'")); ?>
</div></div>
</div>

<div class="card p-3">
<table class="table table-dark table-hover">
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Role</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['email']; ?></td>
<td><?php echo $row['role']; ?></td>

<td>
<?php if($row['status']=="active"){ ?>
<span class="badge bg-success">Active</span>
<?php } else { ?>
<span class="badge bg-danger">Blocked</span>
<?php } ?>
</td>

<td>
<?php if($row['status']=="active"){ ?>
<a href="?block=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Block</a>
<?php } else { ?>
<a href="?unblock=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Unblock</a>
<?php } ?>

<a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
</td>

</tr>
<?php } ?>

</table>
</div>

</div>
</div>
</div>

</body>
</html>