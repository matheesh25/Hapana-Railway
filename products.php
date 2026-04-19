<?php
include("config.php");

// Fetch all products
$products = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>
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
<title>Hapana Fireworks - Products</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

/* RESET */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    background:#000;
    color:#fff;
    font-family:'Poppins',sans-serif;
    overflow-x:hidden;
}

img{
    max-width:100%;
}

/* FIREWORKS BACKGROUND */
#fireworksCanvas{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    z-index:-1;
}

/* ================= NAVBAR ================= */
.navbar{
    background:rgba(0,0,0,0.85);
    backdrop-filter:blur(12px);
    border-bottom:1px solid rgba(255,193,7,0.2);
}

.navbar-brand{
    display:flex;
    align-items:center;
    gap:10px;
    font-weight:bold;
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
    font-weight:600;
    box-shadow:0 0 15px rgba(255,193,7,0.4);
}

/* MOBILE NAV */
.navbar-toggler{
    border:1px solid rgba(255,193,7,0.4);
}

.navbar-toggler-icon{
    filter:brightness(10);
}

@media(max-width:991px){
    .navbar-collapse{
        background:#000;
        padding:15px;
        border-radius:10px;
    }
}

/* ================= HERO ================= */
#hero{
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    text-align:center;
}

#hero h1{
    font-size:clamp(2.5rem,5vw,4.5rem);
    font-weight:800;
    text-shadow:0 0 20px rgba(255,193,7,0.4);
}

.hero-btn{
    background:linear-gradient(45deg,#ff9800,#ffc107);
    padding:12px 30px;
    border-radius:30px;
    color:#000;
    font-weight:bold;
    border:none;
    transition:0.3s;
}

.hero-btn:hover{
    transform:scale(1.05);
}

/* ================= SECTIONS ================= */
.section-title{
    text-align:center;
    color:#ffc107;
    font-weight:700;
    font-size:2.2rem;
}

.section-sub{
    text-align:center;
    color:#bbb;
    margin-bottom:30px;
}

/* ================= ABOUT ================= */
#about{
    background:#111;
    padding:80px 0;
}

.about-box{
    background:rgba(255,255,255,0.05);
    border-radius:20px;
    padding:30px;
    border:1px solid rgba(255,193,7,0.2);
}

/* ================= PRODUCTS ================= */
.products-section{
    padding-top:120px;
    padding-bottom:80px;
}

/* CATEGORY */
.category-pill .nav-link{
    border-radius:30px;
    margin:5px;
    border:1px solid rgba(255,193,7,0.3);
}

.category-pill .nav-link.active{
    background:linear-gradient(45deg,#ff9800,#ffc107);
    color:black !important;
}

/* PRODUCT CARD */
.product-card{
    background:rgba(255,255,255,0.05);
    border-radius:15px;
    padding:15px;
    border:1px solid rgba(255,193,7,0.2);
    transition:0.3s;
}

.product-card:hover{
    transform:translateY(-10px);
    box-shadow:0 0 25px rgba(255,193,7,0.4);
}

.product-card img{
    height:220px;
    object-fit:cover;
    border-radius:10px;
}

.product-card h6{
    color:#ffc107;
}

/* BUTTONS */
.btn-warning{
    background:linear-gradient(45deg,#ff9800,#ffc107);
    border:none;
    color:#000;
    border-radius:25px;
    font-weight:bold;
}

.btn-outline-warning{
    border:1px solid #ffc107;
    color:#ffc107;
    border-radius:25px;
}

.btn-outline-warning:hover{
    background:#ffc107;
    color:#000;
}

/* ================= FEEDBACK ================= */
.feedback-section{
    padding:80px 0;
}

.feedback-card{
    background:rgba(255,255,255,0.05);
    border-radius:15px;
    padding:20px;
    transition:0.3s;
}

.feedback-card:hover{
    transform:translateY(-10px);
}

/* ================= FOOTER ================= */
.footer-section{
    background:#000;
    padding:80px 0 25px;
    color:#ccc;
    border-top:1px solid rgba(255,193,7,0.10);
}

.footer-logo{
    color:#ffc107;
    font-weight:700;
    font-size:1.5rem;
    margin-bottom:16px;
}

.footer-title{
    color:#ffc107;
    margin-bottom:20px;
    font-weight:600;
}

.footer-text{
    font-size:14px;
    line-height:1.8;
    color:#cfcfcf;
}

.footer-links{
    list-style:none;
    padding:0;
    margin:0;
}

.footer-links li{
    margin-bottom:10px;
}

.footer-links a{
    color:#ccc;
    text-decoration:none;
    transition:all 0.3s ease;
}

.footer-links a:hover{
    color:#ffc107;
    padding-left:6px;
}

.social-icons{
    margin-top:16px;
}

.social-icons a{
    width:38px;
    height:38px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    border-radius:50%;
    color:#ffc107;
    border:1px solid rgba(255,193,7,0.28);
    margin-right:10px;
    font-size:17px;
    transition:all 0.3s ease;
}

.social-icons a:hover{
    background:linear-gradient(45deg,#ff9800,#ffc107);
    color:#000;
    transform:translateY(-3px);
}

.footer-input{
    background:#111 !important;
    border:1px solid #333 !important;
    color:#fff !important;
    border-radius:10px 0 0 10px !important;
    min-height:46px;
}

.footer-input::placeholder{
    color:#9f9f9f;
}

.footer-section .btn-warning{
    font-weight:600;
    border:none;
    border-radius:0 10px 10px 0 !important;
    padding:0 18px;
}

.contact-info{
    font-size:14px;
    color:#d0d0d0;
    line-height:1.9;
    margin-top:10px;
}

/* ================= ANIMATION ================= */
.fade-in{
    animation:fadeIn 1s ease;
}

@keyframes fadeIn{
    from{opacity:0; transform:translateY(20px);}
    to{opacity:1; transform:translateY(0);}
}

/* ================= RESPONSIVE ================= */
@media(max-width:768px){
    .product-card img{
        height:180px;
    }
}
</style>
</head>

<body>

<!-- ✅ YOUR NAVBAR (UNCHANGED DESIGN) -->
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
                    <a class="nav-link active" href="products.php">Products</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="cart.php">Cart</a>
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

<section class="products-section fade-in">
<div class="container-fluid px-4 px-md-5">
<h2 class="section-title">Our Fireworks</h2>
<p class="section-sub">Explore premium quality fireworks for every celebration</p>

<ul class="nav nav-pills justify-content-center mb-4 category-pill">
<li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#rockets">Rockets</button></li>
<li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#crackers">Crackers</button></li>
<li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#fountains">Fountains</button></li>
<li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#sparklers">Sparklers</button></li>
</ul>

<div class="tab-content">
<?php
$categories = ['Rockets','Crackers','Fountains','Sparklers'];

foreach($categories as $cat){
    $active = strtolower($cat) == 'rockets' ? 'show active' : '';
    echo '<div class="tab-pane fade '.$active.'" id="'.strtolower($cat).'"><div class="row g-4">';

    mysqli_data_seek($products, 0);
    $found = false;

    while($row = mysqli_fetch_assoc($products)){
        if($row['category'] == $cat){
            $found = true;
            $img = !empty($row['image']) ? 'admin/uploads/'.$row['image'] : 'https://via.placeholder.com/400x400?text=No+Image';

            echo '<div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="product-card">
                        <div>
                            <img src="'.$img.'" alt="'.htmlspecialchars($row['name']).'">
                            <h5>'.htmlspecialchars($row['name']).'</h5>
                            <h6>LKR '.number_format($row['price'],2).'</h6>
                            <div class="stock-text">Stock: '.$row['stock'].'</div>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="product_view.php?id='.$row['id'].'" class="btn btn-warning">View Product</a>

                            <a href="#" 
                            onclick="addToCart(\''.htmlspecialchars($row['name']).'\', \''.$row['price'].'\')" 
                            class="btn btn-outline-warning">
                            Add to Cart
                            </a>

                        </div>
                    </div>
                  </div>';
        }
    }

    if(!$found){
        echo '<div class="col-12 text-center"><p>No products available in '.$cat.'</p></div>';
    }

    echo '</div></div>';
}
?>
</div>
</div>
</section>

<footer class="footer-section">
    <div class="container">
        <div class="row">

            <!-- Company Info -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h4 class="footer-logo">Hapana Fireworks</h4>
                <p class="footer-text">
                    Bringing light to every celebration with premium quality fireworks.
                    Safe, colorful and unforgettable moments.
                </p>
                <div class="social-icons">
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-whatsapp"></i></a>
                    <a href="#"><i class="bi bi-youtube"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="footer-title">Quick Links</h5>
                <ul class="footer-links">
                    <li><a href="index.html">Home</a></li>
                    <li><a href="Products.html">Products</a></li>
                    <li><a href="Cart.html">Cart</a></li>
                    <li><a href="Login.html">Login</a></li>
                </ul>
            </div>

            <!-- Categories -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="footer-title">Categories</h5>
                <ul class="footer-links">
                    <li><a href="#">Rockets</a></li>
                    <li><a href="#">Crackers</a></li>
                    <li><a href="#">Fountains</a></li>
                    <li><a href="#">Sparklers</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="footer-title">Newsletter</h5>
                <p class="footer-text">Subscribe to get special offers & festival discounts.</p>

                <form>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control footer-input" placeholder="Enter your email">
                        <button class="btn btn-warning" type="submit">Subscribe</button>
                    </div>
                </form>

                <p class="contact-info">
                    📍 Negombo, Sri Lanka <br>
                    📞 +94 77 123 4567 <br>
                    ✉ info@hapanafireworks.com
                </p>
            </div>

        </div>
    </div>


</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function addToCart(name, price){

let cart = JSON.parse(localStorage.getItem("cart_" + userId)) || [];

let found = cart.find(item => item.name === name);

if(found){
    found.quantity += 1;
}else{
    cart.push({
        name: name,
        price: parseInt(price),
        quantity: 1
    });
}

localStorage.setItem("cart_" + userId, JSON.stringify(cart));

alert("Product added to cart successfully!");
}
</script>

</body>
</html>