<?php
session_start();
include("config.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(!isset($_SESSION["user_id"])){
    header("Location: login.html");
    exit();
}

$id = $_SESSION["user_id"];

// UPDATE PROFILE
if(isset($_POST['update'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    mysqli_query($conn,"UPDATE users SET 
        name='$name',
        email='$email',
        phone='$phone'
        WHERE id='$id'");

    echo "<script>alert('Updated');window.location='profile.php';</script>";
    exit();
}

// GET USER
$user = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM users WHERE id='$id'"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hapana Fireworks - My Profile</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
    margin: 0;
    background: linear-gradient(to right, #000000, #111111, #000000);
    color: white;
    font-family: Arial, sans-serif;
}
.navbar{
    background:rgba(0,0,0,0.85);
}
.navbar .nav-item{
    margin:0 6px;
}
.navbar .nav-link{
    color:white !important;
    font-weight:500;
    transition:0.3s;
}


.navbar .nav-link.profile-active i{
    color:#ffc107 !important;
    text-shadow:0 0 10px #ffc107,
                0 0 20px #ffc107,
                0 0 30px #ff9800;
}
.logo {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
}
.profile-section {
    min-height: 100vh;
    padding-top: 120px;
    padding-bottom: 60px;
    display: flex;
    align-items: center;
}
.profile-section .container {
    max-width: 1100px;
}
.profile-card {
    background: #1a1a1a;
    padding: 35px;
    border-radius: 15px;
    box-shadow: 0 0 25px rgba(255, 193, 7, 0.1);
    height: 100%;
}
.profile-avatar {
    font-size: 110px;
    color: #ffc107;
}
.profile-detail {
    margin-bottom: 20px;
}
.profile-detail label {
    font-size: 14px;
    color: #ffc107;
    font-weight: 600;
}
.profile-detail p {
    margin: 0;
    font-size: 16px;
}
.footer {
    background: black;
    text-align: center;
    padding: 20px;
    margin-top: 40px;
}
@media (max-width: 768px) {
    .profile-section {
        padding-top: 100px;
    }
    .profile-avatar {
        font-size: 80px;
    }
}
</style>
</head>

<body>

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
<section class="profile-section">
    <div class="container">
        <h2 class="text-center text-warning mb-5">My Profile</h2>
        <div class="row g-4 align-items-stretch">

            <div class="col-md-4">
                <div class="profile-card text-center">
                    <i class="bi bi-person-circle profile-avatar"></i>
                    <p id="leftName"><?php echo $user['name']; ?></p>
                    <p id="leftEmail"><?php echo $user['email']; ?></p>

                    <div class="dropdown mt-3">
                        <button class="btn btn-warning dropdown-toggle w-100" data-bs-toggle="dropdown">Account Options</button>
                        <ul class="dropdown-menu w-100">
                            <li><a class="dropdown-item" href="#" onclick="toggleUpdate()"><i class="bi bi-pencil-square me-2"></i>Update Profile</a></li>
                            <li><a class="dropdown-item" href="signup.php"><i class="bi bi-person-plus me-2"></i>Add Another Account</a></li>
                        </ul>
                    </div>

                    <div class="mt-3">
                        <a href="myOrders.php" class="btn btn-outline-warning w-100">
                            <i class="bi bi-bag-check me-2"></i> My Orders
                        </a>
                    </div>

                    <div class="mt-3">
                        <a href="logout.php" class="btn btn-danger w-100">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </a>
                    </div>

                    <div id="updateSection" class="mt-4" style="display:none;">
                        <h6 class="text-warning">Update Profile</h6>
                        <form method="POST">
                            <div class="mb-2">
                                <input type="text" id="updateName" name="name" class="form-control" value="<?php echo $user['name']; ?>" required>
                            </div>
                            <div class="mb-2">
                                <input type="email" id="updateEmail" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
                            </div>
                            <div class="mb-2">
                                <input type="text" id="updatePhone" name="phone" class="form-control" value="<?php echo $user['phone']; ?>">
                            </div>
                            <button type="submit" name="update" class="btn btn-warning btn-sm w-100">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="profile-card">
                    <h4>Profile Details</h4>
                    <hr>
                    <div class="profile-detail">
                        <label>Full Name</label>
                        <p id="profileName"><?php echo $user['name']; ?></p>
                    </div>
                    <div class="profile-detail">
                        <label>Email</label>
                        <p id="profileEmail"><?php echo $user['email']; ?></p>
                    </div>
                    <div class="profile-detail">
                        <label>Phone</label>
                        <p id="profilePhone"><?php echo $user['phone']; ?></p>
                    </div>
    
                    <div class="profile-detail">
                        <label>Account Type</label>
                        <p class="text-warning">Customer</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<footer class="footer">
    <p>© 2026 Hapana Fireworks | FireTech Solutions</p>
    <p>Negombo, Sri Lanka</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function toggleUpdate() {
    const section = document.getElementById("updateSection");
    section.style.display = section.style.display === "block" ? "none" : "block";
}
</script>

</body>
</html>