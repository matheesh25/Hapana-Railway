<?php
include("config.php");

if(!isset($_GET['id']) || empty($_GET['id'])){
    die("Product not found.");
}

$id = intval($_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM products WHERE id='$id'");

if(mysqli_num_rows($query) == 0){
    die("Product not found.");
}


$product = mysqli_fetch_assoc($query);
$image = !empty($product['image']) ? "admin/uploads/" . $product['image'] : "https://via.placeholder.com/500x500?text=No+Image";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Hapana Fireworks - Product View</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body, html {
    background: black;
    color: white;
    font-family: Arial, sans-serif;
    position: relative;
    margin:0;
    padding:0;
}

#fireworkCanvas {
    position: fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    z-index:-1;
}

.navbar{
    background:rgba(0,0,0,0.9);
}

.navbar .nav-item{
    margin:0 6px;
}

.navbar .nav-link{
    color:white !important;
    font-weight:500;
    transition:0.3s;
}

.navbar .nav-link:hover{
    color:#ffc107 !important;
}

.navbar .nav-link.active{
    background:linear-gradient(45deg,#ff9800,#ffc107);
    color:black !important;
    padding:8px 18px;
    border-radius:30px;
    font-weight:600;
}

.logo {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
}

.product-section {
    padding-top: 130px;
    padding-bottom: 80px;
    min-height:100vh;
}

.main-img {
    width:100%;
    height:450px;
    object-fit:cover;
    border-radius:18px;
    box-shadow:0 0 25px rgba(255,193,7,0.3);
    transition:0.4s;
    border:1px solid rgba(255,193,7,0.2);
}

.product-card {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(12px);
    padding:40px;
    border-radius:20px;
    border:1px solid rgba(255,193,7,0.2);
    box-shadow:0 0 40px rgba(255,193,7,0.15);
}

.product-card h2 {
    font-weight:bold;
    margin-bottom:15px;
}

.price {
    color:#ffc107;
    font-size:28px;
    font-weight:bold;
    margin-top:10px;
}

.info-box{
    background:rgba(255,255,255,0.04);
    border:1px solid rgba(255,193,7,0.12);
    border-radius:14px;
    padding:15px 18px;
    margin-top:18px;
}

.qty-box {
    display:flex;
    align-items:center;
    gap:15px;
    margin-top:25px;
}

.qty-btn {
    background:#ffc107;
    border:none;
    width:38px;
    height:38px;
    border-radius:50%;
    font-weight:bold;
    transition:0.3s;
}

.qty-btn:hover {
    box-shadow:0 0 15px #ffc107;
    transform:scale(1.1);
}

.btn-add {
    background:linear-gradient(45deg,#ff9800,#ffc107);
    border:none;
    font-weight:bold;
    padding:12px;
    border-radius:30px;
    transition:0.3s;
    color:black;
    text-decoration:none;
    text-align:center;
}

.btn-add:hover {
    transform:translateY(-3px);
    box-shadow:0 0 20px #ffc107;
    color:black;
}

.btn-buy {
    background:black;
    border:2px solid #ffc107;
    color:#ffc107;
    font-weight:bold;
    padding:12px;
    border-radius:30px;
    transition:0.3s;
}

.btn-buy:hover {
    background:#ffc107;
    color:black;
    box-shadow:0 0 25px #ffc107;
}

.footer-section {
    background: #000;
    padding: 80px 0 30px;
    color: #ccc;
}

.footer-logo {
    color: #ffc107;
    font-weight: bold;
}

.footer-title {
    color: #ffc107;
    margin-bottom: 20px;
}

.footer-text {
    font-size: 14px;
    line-height: 1.7;
}

.footer-links {
    list-style: none;
    padding: 0;
}

.footer-links li {
    margin-bottom: 10px;
}

.footer-links a {
    color: #ccc;
    text-decoration: none;
    transition: 0.3s;
}

.footer-links a:hover {
    color: #ffc107;
    padding-left: 5px;
}

.social-icons a {
    color: #ffc107;
    margin-right: 12px;
    font-size: 18px;
    transition: 0.3s;
}

.social-icons a:hover {
    color: white;
}

.footer-input {
    background: #111;
    border: 1px solid #333;
    color: white;
}
</style>
</head>
<body>

<canvas id="fireworkCanvas"></canvas>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-warning" href="index.php">
            <img src="images/logo.jpg" class="logo" alt="Logo">
            Hapana Fireworks
        </a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="Index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link active" href="products.php">Products</a></li>
                <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
                <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                <li class="nav-item ms-3">
                    <a class="nav-link text-warning" href="profile.php">
                        <i class="bi bi-person-circle fs-4"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<section class="product-section container">
<div class="row align-items-center g-4">

    <div class="col-md-6">
        <img id="mainImage" src="<?php echo $image; ?>" class="main-img" alt="<?php echo htmlspecialchars($product['name']); ?>">
    </div>

    <div class="col-md-6">
        <div class="product-card">
            <div class="mb-2 text-warning"><?php echo htmlspecialchars($product['category']); ?></div>

            <h2><?php echo htmlspecialchars($product['name']); ?></h2>

            <p>
                Premium quality firework item from Hapana Fireworks.
                Safe, colorful and suitable for your celebrations and special events.
            </p>

            <div class="price">LKR <?php echo number_format($product['price'],2); ?></div>

            <div class="info-box">
                <strong>Available Stock:</strong> <?php echo $product['stock']; ?>
            </div>

            <div class="qty-box">
                <button class="qty-btn" onclick="changeQty(-1)">-</button>
                <span id="quantity">1</span>
                <button class="qty-btn" onclick="changeQty(1)">+</button>
            </div>

            <div class="d-grid gap-3 mt-4">
            <button class="btn-add" onclick="addToCart(
'<?php echo addslashes($product['name']); ?>',
<?php echo $product['price']; ?>,
<?php echo $product['id']; ?>
)">
Add to Cart
</button>

    <button class="btn btn-buy" onclick="buyNow()">
        Buy Now
    </button>
</div>
        </div>
    </div>

</div>
</section>

<footer class="footer-section">
  <div class="container">
      <div class="row">

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

          <div class="col-lg-3 col-md-6 mb-4">
              <h5 class="footer-title">Quick Links</h5>
              <ul class="footer-links">
                  <li><a href="index.php">Home</a></li>
                  <li><a href="products.php">Products</a></li>
                  <li><a href="cart.php">Cart</a></li>
                  <li><a href="login.php">Login</a></li>
              </ul>
          </div>

          <div class="col-lg-3 col-md-6 mb-4">
              <h5 class="footer-title">Categories</h5>
              <ul class="footer-links">
                  <li><a href="#">Rockets</a></li>
                  <li><a href="#">Crackers</a></li>
                  <li><a href="#">Fountains</a></li>
                  <li><a href="#">Sparklers</a></li>
              </ul>
          </div>

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
function changeQty(value){
    let qty = document.getElementById("quantity");
    let current = parseInt(qty.innerText);
    let stock = <?php echo (int)$product['stock']; ?>;

    if(current + value >= 1 && current + value <= stock){
        qty.innerText = current + value;
        updateLinks();
    }
}

function updateLinks(){
    let qty = document.getElementById("quantity").innerText;
    document.getElementById("addToCartBtn").href = "cart.php?id=<?php echo $product['id']; ?>&qty=" + qty;
    document.getElementById("buyNowBtn").href = "cart.php?id=<?php echo $product['id']; ?>&qty=" + qty;
}

window.onload = function() {
    updateLinks();

    const canvas = document.getElementById("fireworkCanvas");
    const ctx = canvas.getContext("2d");

    function resize(){
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }

    resize();
    window.addEventListener("resize", resize);

    let particles = [];

    class Particle{
        constructor(x,y,color){
            this.x = x;
            this.y = y;
            this.radius = Math.random()*4+2;
            this.color = color;
            const angle = Math.random()*Math.PI*2;
            const speed = Math.random()*10+5;
            this.speedX = Math.cos(angle)*speed;
            this.speedY = Math.sin(angle)*speed;
            this.gravity = 0.08;
            this.alpha = 1;
            this.decay = Math.random()*0.015+0.01;
        }
        update(){
            this.x += this.speedX;
            this.y += this.speedY;
            this.speedY += this.gravity;
            this.alpha -= this.decay;
        }
        draw(){
            ctx.save();
            ctx.globalAlpha = this.alpha;
            ctx.beginPath();
            ctx.arc(this.x,this.y,this.radius,0,Math.PI*2);
            ctx.fillStyle = this.color;
            ctx.shadowBlur = 25;
            ctx.shadowColor = this.color;
            ctx.fill();
            ctx.restore();
        }
    }

    function createFirework(x,y){
        const color = `hsl(${Math.random()*360},100%,60%)`;
        for(let i=0;i<120;i++){
            particles.push(new Particle(x,y,color));
        }
    }

    setInterval(()=>{
        createFirework(Math.random()*canvas.width, Math.random()*canvas.height*0.5);
    }, 1800);

    function animate(){
        ctx.fillStyle = "rgba(0,0,0,0.25)";
        ctx.fillRect(0,0,canvas.width,canvas.height);

        for(let i=particles.length-1;i>=0;i--){
            particles[i].update();
            particles[i].draw();
            if(particles[i].alpha<=0){
                particles.splice(i,1);
            }
        }
        requestAnimationFrame(animate);
    }
    animate();
};
</script>
<script>

function buyNow(){

    function buyNow(){

let qty = parseInt(document.getElementById("quantity").innerText);

let item = {
    id: "<?php echo $product['id']; ?>",
    name: "<?php echo htmlspecialchars($product['name']); ?>",
    price: <?php echo $product['price']; ?>,
    quantity: qty
};

localStorage.setItem("checkoutItem", JSON.stringify(item));
localStorage.removeItem("checkoutAll"); // optional clean

window.location.href = "checkout.html"; // or checkout.php
}

localStorage.setItem("checkoutItem", JSON.stringify(item));
window.location.href = "checkout.html";
}

</script>
<script>
function addToCart(name, price, id){

let cart = JSON.parse(localStorage.getItem("cart_" + userId)) || [];

let found = cart.find(item => item.id == id);

if(found){
    found.quantity += 1;
}else{
    cart.push({
        id: id,
        name: name,
        price: price,
        quantity: 1
    });
}

localStorage.setItem("cart_" + userId, JSON.stringify(cart));

alert("✅ Added to cart!");
}
</script>

</body>
</html>