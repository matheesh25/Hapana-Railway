<?php
include("config.php");

if(isset($_GET['token'])){
    $token = $_GET['token'];

    $res = mysqli_query($conn,"
    SELECT * FROM users 
    WHERE reset_token='$token'
    ");

    if(mysqli_num_rows($res) == 1){

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $newPass = password_hash($_POST['password'], PASSWORD_DEFAULT);

            mysqli_query($conn,"
                UPDATE users 
                SET password='$newPass',
                    reset_token=NULL,
                    token_expiry=NULL
                WHERE reset_token='$token'
            ");

            echo "<script>alert('Password updated');window.location='login.html';</script>";
        }

?>

<form method="POST">
    <h2>Reset Password</h2>
    <input type="password" name="password" placeholder="New Password" required>
    <button type="submit">Update Password</button>
</form>

<?php
    } else {
        echo "Invalid or expired link";
    }
}
?>