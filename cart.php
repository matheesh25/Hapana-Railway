<?php
session_start();
?>
<script>
let userId = "<?php echo $_SESSION['user_id']; ?>";
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hapana Fireworks - Cart</title>
    <style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

/* GENERAL */
body{
    background:#000;
    color:#fff;
    font-family:'Poppins',sans-serif;
    margin:0;
}

/* NAVBAR (SAME AS OTHER PAGES) */
.navbar{
    background:rgba(0,0,0,0.85);
    backdrop-filter:blur(12px);
    border-bottom:1px solid rgba(255,193,7,0.2);
}

.navbar-brand{
    display:flex;
    align-items:center;
    gap:10px;
}

.logo{
    width:42px;
    height:42px;
    border-radius:50%;
    border:2px solid rgba(255,193,7,0.7);
    box-shadow:0 0 12px rgba(255,193,7,0.4);
}

.navbar .nav-link{
    color:#fff !important;
    padding:8px 14px !important;
    border-radius:30px;
    transition:0.3s;
}

.navbar .nav-link:hover{
    color:#ffc107 !important;
    background:rgba(255,193,7,0.1);
}

.navbar .nav-link.active{
    background:linear-gradient(45deg,#ff9800,#ffc107);
    color:#000 !important;
}

/* CART SECTION */
.cart-glass{
    width:100%;
    max-width:950px;
    margin-top:130px;
    padding:35px;
    border-radius:20px;
    background:rgba(255,255,255,0.05);
    backdrop-filter:blur(12px);
    border:1px solid rgba(255,193,7,0.2);
    box-shadow:0 0 30px rgba(255,193,7,0.15);
}

/* TITLE */
.cart-glass h2{
    text-align:center;
    color:#ffc107;
    font-weight:700;
}

/* TABLE */
.table{
    border-radius:12px;
    overflow:hidden;
}

.table thead{
    background:rgba(255,255,255,0.05);
}

/* QTY */
.qty-box{
    display:flex;
    justify-content:center;
    align-items:center;
    gap:10px;
}

/* BUTTONS */
.btn-warning{
    background:linear-gradient(45deg,#ff9800,#ffc107);
    border:none;
    color:#000;
    border-radius:25px;
    font-weight:bold;
}

.btn-warning:hover{
    transform:scale(1.05);
}

.btn-danger{
    border-radius:8px;
}

.btn-success{
    border-radius:8px;
}

/* SUMMARY */
.cart-summary{
    margin-top:25px;
    padding:20px;
    border-radius:15px;
    background:#111;
    border:1px solid rgba(255,193,7,0.3);
}

.cart-summary h4{
    color:#ffc107;
}

/* EMPTY */
#emptyCart{
    margin-top:30px;
}

/* RESPONSIVE */
@media(max-width:768px){
    .cart-glass{
        padding:20px;
    }
}
</style>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- ================= NAVBAR ================= -->

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
<div class="container-fluid px-3 px-md-4 px-lg-5">
        <a class="navbar-brand fw-bold text-warning" href="cart.php">
            <img src="images/PHOTO-2026-02-12-17-21-53.jpg" class="logo">
            Hapana Fireworks
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link " href="Index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="products.php">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="cart.php">Cart</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.html">Login</a>
                </li>
                <li class="nav-item ms-3">
                    <a class="nav-link" href="profile.php">
                        <i class="bi bi-person-circle fs-4"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- ================= CART SECTION ================= -->
<section class="container d-flex justify-content-center align-items-center">

    <div class="cart-glass">

        <h2 class="text-center mb-5">Your Shopping Cart</h2>

        <div class="table-responsive">
            <table class="table table-dark align-middle text-center">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price (LKR)</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody id="cartBody"></tbody>

        <!-- EMPTY CART -->
        <div id="emptyCart" class="text-center">
            <i class="bi bi-cart-x display-1 text-secondary"></i>
            <h4 class="mt-3 text-light">Your cart is empty</h4>
            <p class="text-muted">Looks like you haven't added anything yet.</p>
            <a href="products.php" class="btn btn-warning mt-3">
                Continue Shopping
            </a>
        </div>

         <!-- CART SUMMARY -->
         <div class="cart-summary">
            <h4>Total: LKR <span id="grandTotal">0</span></h4>
            <button onclick="checkoutAll()" class="btn btn-warning btn-lg mt-3 w-100">
                Proceed to Checkout
            </button>
        </div>


    </div>
</section>






<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


<!-- ================= CART SCRIPT ================= -->

<script>

function loadCart(){

let cart = JSON.parse(localStorage.getItem("cart_" + userId)) || [];
const table = document.getElementById("cartBody");
table.innerHTML = "";

let grandTotal = 0;

if(cart.length === 0){
document.getElementById("emptyCart").style.display = "block";
}else{
document.getElementById("emptyCart").style.display = "none";
}

cart.forEach((item,index)=>{

let total = item.price * item.quantity;
grandTotal += total;

table.innerHTML += `
<tr>
<td>${item.name}</td>
<td>${item.price}</td>

<td>
<div class="qty-box">
<button class="btn btn-sm btn-warning" onclick="decrease(${index})">-</button>
<span>${item.quantity}</span>
<button class="btn btn-sm btn-warning" onclick="increase(${index})">+</button>
</div>
</td>

<td>${total}</td>

<td>
<button class="btn btn-success btn-sm" onclick="buySingle(${index})">
<i class="bi bi-lightning-charge"></i>
</button>

<button class="btn btn-danger btn-sm ms-2" onclick="removeItem(${index})">
<i class="bi bi-trash"></i>
</button>
</td>

</tr>
`;
});

document.getElementById("grandTotal").innerText = grandTotal;
}

// INCREASE
function increase(i){
let cart = JSON.parse(localStorage.getItem("cart_" + userId)) || [];
cart[i].quantity++;
localStorage.setItem("cart_" + userId, JSON.stringify(cart));
loadCart();
}

// DECREASE
function decrease(i){
let cart = JSON.parse(localStorage.getItem("cart_" + userId)) || [];
if(cart[i].quantity > 1){
cart[i].quantity--;
}
localStorage.setItem("cart_" + userId, JSON.stringify(cart));
loadCart();
}

// REMOVE
function removeItem(i){
let cart = JSON.parse(localStorage.getItem("cart_" + userId)) || [];
cart.splice(i,1);
localStorage.setItem("cart_" + userId, JSON.stringify(cart));
loadCart();
}

// BUY SINGLE
function buySingle(i){
let cart = JSON.parse(localStorage.getItem("cart_" + userId)) || [];
localStorage.setItem("checkoutItem", JSON.stringify(cart[i]));
window.location.href = "checkout.html";
}

// CHECKOUT ALL
function checkoutAll(){
let cart = JSON.parse(localStorage.getItem("cart_" + userId)) || [];
localStorage.setItem("checkoutAll", JSON.stringify(cart));
window.location.href = "checkout.html";
}

loadCart();

</script>
</body>
</html>
