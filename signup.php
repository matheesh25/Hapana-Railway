
<?php
// Enable errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("config.php");
include("mail.php");

// Handle form submission
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if($password != $confirm){
        echo "<script>alert('Passwords do not match');</script>";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Check if email exists
        $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        if(mysqli_num_rows($check) > 0){
            echo "<script>alert('Email already exists');</script>";
        } else {
            // Insert user
            $sql = "INSERT INTO users(name,email,password,role,status)
                    VALUES('$name','$email','$password_hash','customer','active')";
            if(mysqli_query($conn, $sql)){
                $subject = "Welcome to Hapana Fireworks ";
                $message = "
                <h2>Hello $name 👋</h2>
                <p>Your account has been successfully created.</p>
                <p>Enjoy shopping with Hapana Fireworks 🎇</p>
                 ";

                sendMail($email, $subject, $message);

                echo "<script>alert('Registration Successful');window.location='login.html';</script>";
                exit();
            } else {
                echo "<script>alert('Database Error: ".mysqli_error($conn)."');</script>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Hapana Fireworks - Sign Up</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
/* GLOBAL */
body, html {
    margin: 0;
    padding: 0;
    font-family: 'Poppins', sans-serif;
    overflow-x: hidden;
    background: #000;
    color: #fff;
    height: 100%;
}

/* FIREWORKS CANVAS */
#fireworksCanvas {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;   
}

/* NAVBAR */
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
.navbar-brand img.logo {
    width: 50px;
    margin-right: 10px;
    border-radius: 50%;
}

/* AUTH BOX */
.auth-section {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 100px 15px 50px;
}
.auth-box {
    background: #111;
    padding: 40px 30px;
    border-radius: 15px;
    box-shadow: 0 0 30px rgba(255, 215, 0, 0.1);
    max-width: 400px;
    width: 100%;
    transition: 0.5s;
}
.auth-box:hover {
    box-shadow: 0 0 20px 5px rgba(255, 215, 0, 0.6);
    transform: translateY(-5px);
}
.auth-box h2 {
    color: #ffc107;
    font-weight: 700;
    margin-bottom: 30px;
    text-shadow: 0 0 10px #ffc107;
}

/* FORM */
.auth-box label {
    font-weight: 500;
    color: #fff;
}
.auth-box input {
    border-radius: 8px;
    border: none;
    padding: 12px 15px;
    background: rgba(255,255,255,0.05);
    color: #fff;
    margin-top: 5px;
}
.auth-box input:focus {
    outline: none;
    background: rgba(255,255,255,0.15);
    box-shadow: 0 0 10px #ffc107;
    color: #fff;
}
.auth-box button {
    border-radius: 50px;
    font-weight: 600;
    padding: 12px;
    transition: 0.3s;
    background: linear-gradient(45deg, #ff9800, #ffc107);
    border: none;
}
.auth-box button:hover {
    background: linear-gradient(45deg, #ffc107, #ff9800);
    transform: scale(1.05);
}
.auth-box a {
    text-decoration: none;
    font-weight: 500;
    color: #ffc107;
}
.auth-box a:hover {
    color: #fff !important;
}

/* FOOTER */
footer {
    text-align: center;
    padding: 15px 0;
    color: #fff;
    background: rgba(0,0,0,0.8);
    font-size: 0.9rem;
}

.auth-section,
.navbar,
footer {
    position: relative;
    z-index: 2;
}
</style>
</head>
<body>

<canvas id="fireworksCanvas"></canvas>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-warning" href="index.php">
            <img src="images/PHOTO-2026-02-12-17-21-53.jpg" class="logo">
            Hapana Fireworks
        </a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="Index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="products.html">Products</a></li>
                <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
                <li class="nav-item"><a class="nav-link active" href="signup.php">Sign Up</a></li>
            </ul>
        </div>
    </div>
</nav>

<section class="auth-section">
    <div class="auth-box">
        <h2 class="text-center mb-4">Sign Up</h2>
        <form method="POST" action="" onsubmit="return validateSignup()">
            <div class="mb-3">
                <label>Full Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-warning w-100">Sign Up</button>
            <p class="text-center mt-3">
                Already have an account? <a href="login.html">Login</a>
            </p>
        </form>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Fireworks JS (same as your original)
const canvas = document.getElementById("fireworksCanvas");
const ctx = canvas.getContext("2d");
function resize(){canvas.width=window.innerWidth;canvas.height=window.innerHeight;}
resize();window.addEventListener("resize", resize);
let fireworks=[], particles=[];
class Firework{constructor(){this.x=Math.random()*canvas.width;this.y=canvas.height;this.targetY=Math.random()*canvas.height*0.5;this.color=`hsl(${Math.random()*360},100%,60%)`;this.speed=5;this.exploded=false;}update(){if(!this.exploded){this.y-=this.speed;if(this.y<=this.targetY){this.exploded=true;for(let i=0;i<50;i++){particles.push(new Particle(this.x,this.y,this.color));}}}}draw(){if(!this.exploded){ctx.fillStyle=this.color;ctx.fillRect(this.x,this.y,3,8);}}}
class Particle{constructor(x,y,color){this.x=x;this.y=y;this.color=color;this.radius=2;this.speedX=(Math.random()-0.5)*6;this.speedY=(Math.random()-0.5)*6;this.gravity=0.05;this.alpha=1;}update(){this.x+=this.speedX;this.y+=this.speedY;this.speedY+=this.gravity;this.alpha-=0.01;}draw(){ctx.globalAlpha=this.alpha;ctx.fillStyle=this.color;ctx.beginPath();ctx.arc(this.x,this.y,this.radius,0,Math.PI*2);ctx.fill();ctx.globalAlpha=1;}}
function animate(){ctx.fillStyle="rgba(0,0,0,0.25)";ctx.fillRect(0,0,canvas.width,canvas.height);if(Math.random()<0.04){fireworks.push(new Firework());}fireworks.forEach((fw,i)=>{fw.update();fw.draw();if(fw.exploded)fireworks.splice(i,1);});particles.forEach((p,i)=>{p.update();p.draw();if(p.alpha<=0)particles.splice(i,1);});requestAnimationFrame(animate);}
animate();
</script>

<script>
function validateSignup() {
    let name = document.querySelector('input[name="name"]').value.trim();
    let email = document.querySelector('input[name="email"]').value.trim();
    let password = document.querySelector('input[name="password"]').value;
    let confirm = document.querySelector('input[name="confirm_password"]').value;

    let emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;

    if(name === ""){
        alert("Name required");
        return false;
    }

    if(!email.match(emailPattern)){
        alert("Invalid email");
        return false;
    }

    if(password.length < 6){
        alert("Password must be at least 6 characters");
        return false;
    }

    if(password !== confirm){
        alert("Passwords do not match");
        return false;
    }

    return true;
}
</script>

</body>
</html>