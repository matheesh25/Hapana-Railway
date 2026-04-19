<?php
$servername = "localhost";
$username = "root";     // your DB username
$password = "";         // your DB password
$dbname = "hapana_fireworks";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}
?>