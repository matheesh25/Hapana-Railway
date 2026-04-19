<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Hapana Fireworks</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

/* =========================
   GENERAL
========================= */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

html{
    scroll-behavior:smooth;
}

body{
    background:#000;
    color:#fff;
    font-family:'Poppins',sans-serif;
    overflow-x:hidden;
    line-height:1.6;
}

a{
    text-decoration:none;
}

img{
    max-width:100%;
    display:block;
}

/* =========================
   FIREWORKS CANVAS
========================= */
#fireworksCanvas{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    z-index:-1;
}

/* =========================
   NAVBAR
========================= */
.navbar{
    background:rgba(0,0,0,0.80);
    backdrop-filter:blur(12px);
    -webkit-backdrop-filter:blur(12px);
    padding:12px 0;
    border-bottom:1px solid rgba(255,193,7,0.15);
    transition:0.3s ease;
}

.navbar-brand{
    display:flex;
    align-items:center;
    gap:10px;
    font-size:1.2rem;
    letter-spacing:0.4px;
}

.logo{
    width:42px;
    height:42px;
    border-radius:50%;
    object-fit:cover;
    border:2px solid rgba(255,193,7,0.7);
    box-shadow:0 0 12px rgba(255,193,7,0.35);
}

.navbar .nav-item{
    margin:0 5px;
}

.navbar .nav-link{
    color:#fff !important;
    font-weight:500;
    padding:8px 14px !important;
    border-radius:30px;
    transition:all 0.3s ease;
}

.navbar .nav-link:hover{
    color:#ffc107 !important;
    background:rgba(255,193,7,0.08);
}

.navbar .nav-link.active{
    background:linear-gradient(45deg,#ff9800,#ffc107);
    color:#000 !important;
    font-weight:600;
    box-shadow:0 0 15px rgba(255,193,7,0.35);
}

.navbar .nav-link.profile-active i{
    color:#ffc107 !important;
    text-shadow:0 0 10px #ffc107,
                0 0 20px #ffc107,
                0 0 30px #ff9800;
}

.navbar-toggler{
    border:1px solid rgba(255,193,7,0.4);
    padding:6px 10px;
}

.navbar-toggler:focus{
    box-shadow:none;
}

.navbar-toggler-icon{
    filter:brightness(10);
}

/* =========================
   HERO
========================= */
#hero{
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    text-align:center;
    position:relative;
    padding:120px 0 80px;
}

#hero::before{
    content:"";
    position:absolute;
    inset:0;
    background:linear-gradient(to bottom, rgba(0,0,0,0.45), rgba(0,0,0,0.68));
    z-index:0;
}

#hero .container,
#hero .container-fluid{
    position:relative;
    z-index:1;
}

.hero-subtitle{
    color:#ffd86b;
    font-size:1rem;
    letter-spacing:2px;
    text-transform:uppercase;
    margin-bottom:12px;
    font-weight:600;
}

#hero h1{
    font-size:clamp(2.4rem, 6vw, 4.8rem);
    font-weight:800;
    color:#fff;
    margin-bottom:18px;
    text-shadow:0 0 18px rgba(255,193,7,0.35);
}

#hero .lead{
    color:#d6d6d6;
    font-size:1.1rem;
    max-width:760px;
    margin:0 auto 28px;
}

.hero-btn{
    display:inline-block;
    background:linear-gradient(45deg,#ff9800,#ffc107);
    color:#000;
    font-weight:700;
    padding:14px 34px;
    border-radius:50px;
    transition:all 0.3s ease;
    box-shadow:0 0 20px rgba(255,193,7,0.25);
}

.hero-btn:hover{
    transform:translateY(-3px) scale(1.03);
    color:#000;
    box-shadow:0 0 25px rgba(255,193,7,0.45);
}

.hero-btn:active{
    transform:scale(0.97);
}

/* =========================
   SHARED SECTION TITLE
========================= */
.section-title{
    font-size:2.2rem;
    font-weight:700;
    margin-bottom:18px;
    color:#ffc107;
}

.section-text{
    max-width:850px;
    margin:0 auto;
    color:#d9d9d9;
    font-size:1rem;
}

/* =========================
   ABOUT
========================= */
#about{
    position:relative;
    background:linear-gradient(180deg, #101010 0%, #171717 100%);
    padding:100px 0;
    border-top:1px solid rgba(255,193,7,0.08);
    border-bottom:1px solid rgba(255,193,7,0.08);
}

#about .about-box{
    background:rgba(255,255,255,0.04);
    border:1px solid rgba(255,193,7,0.14);
    border-radius:22px;
    padding:40px 30px;
    box-shadow:0 0 25px rgba(0,0,0,0.25);
    backdrop-filter:blur(6px);
}

#about p{
    color:#e4e4e4;
    font-size:1rem;
    margin-bottom:0;
}

/* =========================
   FEEDBACK / EVENTS
========================= */
.feedback-section{
    background:linear-gradient(180deg, #050505 0%, #111 100%);
    color:#fff;
    padding:100px 0;
}

.feedback-row{
    display:flex;
    flex-wrap:wrap;
    gap:30px;
    justify-content:center;
    margin-top:45px;
}

.feedback-card{
    background:rgba(255,255,255,0.05);
    border:1px solid rgba(255,193,7,0.16);
    padding:18px;
    border-radius:20px;
    width:100%;
    max-width:320px;
    text-align:center;
    transition:all 0.35s ease;
    box-shadow:0 10px 25px rgba(0,0,0,0.22);
    backdrop-filter:blur(8px);
}

.feedback-card:hover{
    transform:translateY(-10px);
    box-shadow:0 0 24px rgba(255,193,7,0.28);
    border-color:rgba(255,193,7,0.36);
}

.feedback-img{
    width:100%;
    height:210px;
    object-fit:cover;
    border-radius:14px;
    margin-bottom:18px;
}

.feedback-card p{
    color:#e7e7e7;
    font-size:0.97rem;
    min-height:72px;
    margin-bottom:10px;
}

.feedback-card h6{
    font-weight:600;
    margin-bottom:0;
}

/* =========================
   FOOTER
========================= */
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

/* =========================
   ANIMATIONS
========================= */
.fade-in{
    animation:fadeIn 1s ease;
}

@keyframes fadeIn{
    from{
        opacity:0;
        transform:translateY(20px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

/* =========================
   RESPONSIVE
========================= */
@media (max-width:991.98px){
    .navbar-collapse{
        background:rgba(0,0,0,0.96);
        margin-top:14px;
        padding:18px;
        border-radius:16px;
        border:1px solid rgba(255,193,7,0.12);
    }

    .navbar .nav-item{
        margin:6px 0;
    }

    .navbar .nav-link{
        display:inline-block;
        width:100%;
    }

    #hero{
        padding-top:140px;
    }
}

@media (max-width:767.98px){
    .logo{
        width:38px;
        height:38px;
    }

    .navbar-brand{
        font-size:1rem;
    }

    #hero{
        min-height:auto;
        padding:140px 0 90px;
    }

    #hero .lead{
        font-size:1rem;
        padding:0 8px;
    }

    .hero-btn{
        padding:12px 28px;
        font-size:0.95rem;
    }

    .section-title{
        font-size:1.8rem;
    }

    #about,
    .feedback-section,
    .footer-section{
        padding-top:70px;
        padding-bottom:70px;
    }

    #about .about-box{
        padding:28px 20px;
    }

    .feedback-img{
        height:190px;
    }
}

@media (max-width:575.98px){
    .feedback-card{
        max-width:100%;
    }

    .footer-section{
        text-align:center;
    }

    .social-icons{
        justify-content:center;
    }

    .social-icons a{
        margin:0 6px;
    }

    .contact-info{
        margin-top:15px;
    }
}
</style>


</style>
</head>

<body>

<!-- FIREWORKS BACKGROUND -->
<canvas id="fireworksCanvas"></canvas>

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
                    <a class="nav-link active" href="Index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Products.php">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cart.php">Cart</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Login.html">Login</a>
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

<!-- HERO -->
<section id="hero" class="fade-in">
    <div class="container-fluid px-3 px-md-5 mt-5">
        <p class="hero-subtitle">Celebrate Every Moment</p>
        <h1>Hapana Fireworks</h1>
        <p class="lead">Lighting up every celebration with color, safety, excitement, and unforgettable memories for every special occasion.</p>
        <a href="Products.php" class="hero-btn mt-3">View Products</a>
    </div>
</section>

<!-- ABOUT -->
<section id="about" class="text-center fade-in">
    <div class="container">
        <div class="about-box">
            <h2 class="section-title">About Us</h2>
            <p class="section-text">
                Hapana Fireworks is a trusted fireworks business located in Negombo, offering a wide range of safe and high-quality fireworks for many kinds of celebrations. We proudly serve customers for festivals, weddings, parties, and special events by providing reliable products at affordable prices while focusing on safety, quality, and customer satisfaction.
            </p>
        </div>
    </div>
</section>

<!-- FEEDBACK -->
<section class="feedback-section fade-in">
    <div class="container text-center">
        <h2 class="section-title">Events & Customer Feedback</h2>
        <p class="section-text">See how Hapana Fireworks brings color, joy, and unforgettable memories to every celebration.</p>

<div class="feedback-row">

<div class="feedback-card">
<img src="images/event1.jpg" class="feedback-img">
<p>“Hapana Fireworks made our New Year celebration unforgettable.”</p>
<h6 class="text-warning">- New Year Event</h6>
</div>

<div class="feedback-card">
<img src="images/event2.jpg" class="feedback-img">
<p>“Best fireworks supplier for wedding events. Highly recommended.”</p>
<h6 class="text-warning">- Wedding Ceremony</h6>
</div>

<div class="feedback-card">
<img src="images/event3.jpg" class="feedback-img">
<p>“Safe products and excellent customer support. Will order again.”</p>
<h6 class="text-warning">- Festival Customer</h6>
</div>

</div>
</div>
</section>

<!-- FOOTER -->

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
                    <li><a href="index.php">Home</a></li>
                    <li><a href="Products.php">Products</a></li>
                    <li><a href="Cart.php">Cart</a></li>
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
<!-- FOOTER END -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
const canvas=document.getElementById("fireworksCanvas");
const ctx=canvas.getContext("2d");

function resize(){
canvas.width=window.innerWidth;
canvas.height=window.innerHeight;
}
resize();
window.addEventListener("resize",resize);

let fireworks=[];
let particles=[];

class Firework{
constructor(){
this.x=Math.random()*canvas.width;
this.y=canvas.height;
this.targetY=Math.random()*canvas.height*0.5;
this.color=`hsl(${Math.random()*360},100%,60%)`;
this.speed=5;
this.exploded=false;
}
update(){
if(!this.exploded){
this.y-=this.speed;
if(this.y<=this.targetY){
this.exploded=true;
for(let i=0;i<50;i++){
particles.push(new Particle(this.x,this.y,this.color));
}
}
}
}
draw(){
if(!this.exploded){
ctx.fillStyle=this.color;
ctx.fillRect(this.x,this.y,3,8);
}
}
}

class Particle{
constructor(x,y,color){
this.x=x;
this.y=y;
this.color=color;
this.radius=2;
this.speedX=(Math.random()-0.5)*6;
this.speedY=(Math.random()-0.5)*6;
this.gravity=0.05;
this.alpha=1;
}
update(){
this.x+=this.speedX;
this.y+=this.speedY;
this.speedY+=this.gravity;
this.alpha-=0.01;
}
draw(){
ctx.globalAlpha=this.alpha;
ctx.fillStyle=this.color;
ctx.beginPath();
ctx.arc(this.x,this.y,this.radius,0,Math.PI*2);
ctx.fill();
ctx.globalAlpha=1;
}
}

function animate(){
ctx.fillStyle="rgba(0,0,0,0.2)";
ctx.fillRect(0,0,canvas.width,canvas.height);

if(Math.random()<0.04){
fireworks.push(new Firework());
}

fireworks.forEach((fw,i)=>{
fw.update();
fw.draw();
if(fw.exploded)fireworks.splice(i,1);
});

particles.forEach((p,i)=>{
p.update();
p.draw();
if(p.alpha<=0)particles.splice(i,1);
});

requestAnimationFrame(animate);
}

animate();
</script>

</body>
</html>