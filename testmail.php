<?php
include("mail.php");

if(sendMail("yourpersonalemail@gmail.com","Test Email","Hapana Email Working!")){
    echo "Email Sent Successfully!";
}else{
    echo "Email Failed!";
}
?>