<?php
session_start();
include("config.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// ADD SUPPLIER
if(isset($_POST['add_supplier'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $company = mysqli_real_escape_string($conn, $_POST['company_name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $insert = "INSERT INTO suppliers (name, contact, email, address, company_name, category, status)
               VALUES ('$name', '$contact', '$email', '$address', '$company', '$category', '$status')";
    mysqli_query($conn, $insert);

    header("Location: suppliers.php");
    exit();
}

// DELETE SUPPLIER
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM suppliers WHERE id='$id'");
    header("Location: suppliers.php");
    exit();
}

// GET EDIT DATA
$editData = null;
if(isset($_GET['edit'])){
    $id = intval($_GET['edit']);
    $editQuery = mysqli_query($conn, "SELECT * FROM suppliers WHERE id='$id'");
    $editData = mysqli_fetch_assoc($editQuery);
}

// UPDATE SUPPLIER
if(isset($_POST['update_supplier'])){
    $id = intval($_POST['supplier_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $company = mysqli_real_escape_string($conn, $_POST['company_name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $update = "UPDATE suppliers 
               SET name='$name',
                   contact='$contact',
                   email='$email',
                   address='$address',
                   company_name='$company',
                   category='$category',
                   status='$status'
               WHERE id='$id'";
    mysqli_query($conn, $update);

    header("Location: suppliers.php");
    exit();
}

// SEARCH + FILTER
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$filter_status = isset($_GET['filter_status']) ? mysqli_real_escape_string($conn, $_GET['filter_status']) : '';

$where = "WHERE 1";

if($search != ''){
    $where .= " AND (
        name LIKE '%$search%' OR
        contact LIKE '%$search%' OR
        email LIKE '%$search%' OR
        address LIKE '%$search%' OR
        company_name LIKE '%$search%' OR
        category LIKE '%$search%'
    )";
}

if($filter_status != '' && ($filter_status == 'active' || $filter_status == 'inactive')){
    $where .= " AND status='$filter_status'";
}

// COUNTS
$totalSuppliersRes = mysqli_query($conn, "SELECT COUNT(*) AS total FROM suppliers");
$totalSuppliers = mysqli_fetch_assoc($totalSuppliersRes)['total'];

$activeSuppliersRes = mysqli_query($conn, "SELECT COUNT(*) AS total FROM suppliers WHERE status='active'");
$activeSuppliers = mysqli_fetch_assoc($activeSuppliersRes)['total'];

$inactiveSuppliersRes = mysqli_query($conn, "SELECT COUNT(*) AS total FROM suppliers WHERE status='inactive'");
$inactiveSuppliers = mysqli_fetch_assoc($inactiveSuppliersRes)['total'];

$categoryCountRes = mysqli_query($conn, "SELECT COUNT(DISTINCT category) AS total FROM suppliers");
$totalCategories = mysqli_fetch_assoc($categoryCountRes)['total'];

// FETCH SUPPLIERS
$suppliers = mysqli_query($conn, "SELECT * FROM suppliers $where ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Supplier Management - Hapana Fireworks</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body{
    margin:0;
    background:#000;
    color:white;
    font-family:'Segoe UI',sans-serif;
}
.sidebar{
    min-height:100vh;
    background:#0d0d0d;
    padding-top:25px;
    border-right:1px solid #222;
}
.sidebar h4{
    color:#ffc107;
    margin-bottom:25px;
}
.sidebar a{
    color:white;
    display:block;
    padding:12px 18px;
    margin:6px 10px;
    text-decoration:none;
    border-radius:10px;
    transition:0.3s;
}
.sidebar a:hover,
.sidebar a.active{
    background:linear-gradient(45deg,#ff9800,#ffc107);
    color:black;
}
.main-content{
    padding:30px;
}
.page-title{
    color:#ffc107;
    font-weight:700;
    margin-bottom:25px;
}
.stat-card{
    background:#111;
    border:1px solid #2a2a2a;
    border-radius:18px;
    padding:20px;
    box-shadow:0 0 20px rgba(255,193,7,0.06);
    height:100%;
}
.stat-card .icon{
    font-size:34px;
    color:#ffc107;
}
.stat-card h3{
    margin:10px 0 4px;
    font-weight:700;
}
.stat-card p{
    color:#bbb;
    margin:0;
}
.box{
    background:#111;
    border:1px solid #2a2a2a;
    border-radius:18px;
    padding:25px;
    box-shadow:0 0 20px rgba(255,193,7,0.06);
}
.box-title{
    color:#ffc107;
    font-weight:700;
    margin-bottom:18px;
}
.form-control, .form-select{
    background:#000;
    color:white;
    border:1px solid #444;
    border-radius:10px;
}
.form-control:focus, .form-select:focus{
    background:#000;
    color:white;
    border-color:#ffc107;
    box-shadow:none;
}
.form-control::placeholder{
    color:#999;
}
.table{
    color:white;
    margin-bottom:0;
}
.table thead th{
    background:#1a1a1a !important;
    color:#ffc107;
    border-color:#333 !important;
    vertical-align:middle;
}
.table td{
    border-color:#333 !important;
    vertical-align:middle;
}
.badge-active{
    background:#198754;
    color:white;
    padding:8px 12px;
    border-radius:20px;
    font-size:12px;
}
.badge-inactive{
    background:#dc3545;
    color:white;
    padding:8px 12px;
    border-radius:20px;
    font-size:12px;
}
.btn-gold{
    background:linear-gradient(45deg,#ff9800,#ffc107);
    border:none;
    color:black;
    font-weight:600;
}
.btn-gold:hover{
    color:black;
    opacity:0.95;
}
.btn-dark2{
    background:#1a1a1a;
    border:1px solid #444;
    color:white;
}
.btn-dark2:hover{
    background:#2a2a2a;
    color:white;
}
.search-row{
    gap:10px;
}
.small-text{
    color:#aaa;
    font-size:13px;
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
            <a href="suppliers.php" class="active"><i class="bi bi-people"></i> Suppliers</a>
            <a href="products.php"><i class="bi bi-box"></i> Products</a>
            <a href="orders.php"><i class="bi bi-cart"></i> Orders</a>
            <a href="users.php"><i class="bi bi-person"></i> Users</a>
            <a href="dilivery.php"><i class="bi bi-truck"></i> Dilivery</a>
            <a href="../logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>

        <!-- MAIN CONTENT -->
        <div class="col-md-10 main-content">
            <h2 class="page-title">Supplier Management</h2>

            <!-- STATS -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="stat-card text-center">
                        <div class="icon"><i class="bi bi-people-fill"></i></div>
                        <h3><?php echo $totalSuppliers; ?></h3>
                        <p>Total Suppliers</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-card text-center">
                        <div class="icon"><i class="bi bi-check-circle-fill"></i></div>
                        <h3><?php echo $activeSuppliers; ?></h3>
                        <p>Active Suppliers</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-card text-center">
                        <div class="icon"><i class="bi bi-x-circle-fill"></i></div>
                        <h3><?php echo $inactiveSuppliers; ?></h3>
                        <p>Inactive Suppliers</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-card text-center">
                        <div class="icon"><i class="bi bi-tags-fill"></i></div>
                        <h3><?php echo $totalCategories; ?></h3>
                        <p>Supplier Categories</p>
                    </div>
                </div>
            </div>

            <!-- FORM -->
            <div class="box mb-4">
                <h4 class="box-title"><?php echo $editData ? 'Edit Supplier' : 'Add New Supplier'; ?></h4>

                <form method="POST" onsubmit="return validateSupplier()">
                    <?php if($editData){ ?>
                        <input type="hidden" name="supplier_id" value="<?php echo $editData['id']; ?>">
                    <?php } ?>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="mb-2">Supplier Name</label>
                            <input type="text" name="name" class="form-control" required 
onkeypress="return onlyLetters(event)"
value="<?php echo $editData ? htmlspecialchars($editData['name']) : ''; ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="mb-2">Contact Number</label>
                            <input  type="text" name="contact" class="form-control"
                                     pattern="[0-9]{10}" maxlength="10"
                                     title="Enter exactly 10 digits"
                                     required
                                   value="<?php echo $editData ? htmlspecialchars($editData['contact']) : ''; ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="mb-2">Email</label>
                            <input type="email" name="email" class="form-control"
                                   required
                                title="Enter a valid email (must include @)"
                                   value="<?php echo $editData ? htmlspecialchars($editData['email']) : ''; ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="mb-2">Address</label>
                            <input type="text" name="address" class="form-control"
                                   value="<?php echo $editData ? htmlspecialchars($editData['address']) : ''; ?>">
                        </div>

                        <div class="col-md-3">
                            <label class="mb-2">Company Name</label>
                            <input type="text" name="company_name" class="form-control"
                                   value="<?php echo $editData ? htmlspecialchars($editData['company_name']) : ''; ?>">
                        </div>

                        <div class="col-md-3">
                            <label class="mb-2">Category</label>
                            <input type="text" name="category" class="form-control"
                                   placeholder="Chemicals / Packaging / Paper / Tools"
                                   value="<?php echo $editData ? htmlspecialchars($editData['category']) : ''; ?>">
                        </div>

                        <div class="col-md-3">
                            <label class="mb-2">Status</label>
                            <select name="status" class="form-select">
                                <option value="active" <?php if($editData && $editData['status']=='active') echo 'selected'; ?>>Active</option>
                                <option value="inactive" <?php if($editData && $editData['status']=='inactive') echo 'selected'; ?>>Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-9 d-flex align-items-end gap-2">
                            <?php if($editData){ ?>
                                <button type="submit" name="update_supplier" class="btn btn-gold">Update Supplier</button>
                                <a href="suppliers.php" class="btn btn-dark2">Cancel</a>
                            <?php } else { ?>
                                <button type="submit" name="add_supplier" class="btn btn-gold">Add Supplier</button>
                            <?php } ?>
                        </div>
                    </div>
                </form>
            </div>

            <!-- SEARCH -->
            <div class="box mb-4">
                <h4 class="box-title">Search and Filter</h4>
                <form method="GET">
                    <div class="row search-row">
                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control" placeholder="Search by name, company, email, category..."
                                   value="<?php echo htmlspecialchars($search); ?>">
                        </div>

                        <div class="col-md-3">
                            <select name="filter_status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active" <?php if($filter_status=='active') echo 'selected'; ?>>Active</option>
                                <option value="inactive" <?php if($filter_status=='inactive') echo 'selected'; ?>>Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-gold w-100">Apply</button>
                            <a href="suppliers.php" class="btn btn-dark2 w-100">Reset</a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- TABLE -->
            <div class="box">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="box-title mb-0">All Suppliers</h4>
                    <span class="small-text">Showing supplier records</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-dark table-bordered table-hover align-middle text-center">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Company</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th width="220">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($suppliers) > 0){ ?>
                                <?php while($row = mysqli_fetch_assoc($suppliers)){ ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['contact']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                                        <td><?php echo htmlspecialchars($row['company_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                                        <td>
                                            <?php if($row['status'] == 'active'){ ?>
                                                <span class="badge-active">Active</span>
                                            <?php } else { ?>
                                                <span class="badge-inactive">Inactive</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <a href="suppliers.php?edit=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                            <a href="suppliers.php?delete=<?php echo $row['id']; ?>" 
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Are you sure you want to delete this supplier?');">
                                                <i class="bi bi-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="9">No suppliers found</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
<script>
document.querySelector('input[name="contact"]').addEventListener('input', function(){
    this.value = this.value.replace(/[^0-9]/g,'');
});
</script>
<script>
function validateSupplier() {
    let name = document.querySelector('input[name="name"]').value.trim();
    let phone = document.querySelector('input[name="contact"]').value.trim();
    let email = document.querySelector('input[name="email"]').value.trim();

    let emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;

    if(name === ""){
        alert("Supplier name required");
        return false;
    }
    

    if(!/^\d{10}$/.test(phone)){
        alert("Phone must be 10 digits");
        return false;
    }

    if(!email.match(emailPattern)){
        alert("Invalid email");
        return false;
    }

    return true;
}
</script>
<script>
function onlyLetters(e) {
    let char = String.fromCharCode(e.which);

    if (!/^[a-zA-Z\s]+$/.test(char)) {
        return false;
    }

    return true;
}
</script>
</html>
