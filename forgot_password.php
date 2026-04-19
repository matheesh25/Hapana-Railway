<?php
include("config.php");
include("mail.php");

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $res = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

    if(mysqli_num_rows($res) > 0){

        $token = bin2hex(random_bytes(50));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        mysqli_query($conn,"
            UPDATE users 
            SET reset_token='$token', token_expiry='$expiry'
            WHERE email='$email'
        ");

        $link = "http://localhost/Hapana_4/reset_password.php?token=$token";


        $subject = "Password Reset Request";
        $message = "
        <h3>Password Reset</h3>
        <p>Click below to reset your password:</p>
        <a href='$link'>Reset Password</a>
        <p>This link expires in 15 minutes.</p>
        ";

        sendMail($email, $subject, $message);

        echo "<script>alert('Reset link sent to your email');</script>";

    } else {
        echo "<script>alert('Email not found');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Forgot Password</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#000; color:white;">

<div class="container d-flex justify-content-center align-items-center vh-100">
<div class="card p-4" style="background:#111; border-radius:15px; width:400px;">
<h3 class="text-warning text-center mb-3">Forgot Password</h3>

<form method="POST">
<input type="email" name="email" class="form-control mb-3" placeholder="Enter Email" required>
<button class="btn btn-warning w-100">Send Reset Link</button>
</form>

</div>
</div>

</body>
</html>