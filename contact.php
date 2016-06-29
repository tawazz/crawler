<?php
    if( isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) ){
     $name = $_POST['name'];
     $email = $_POST['email'];
     $phone = $_POST['phone'];
     $msg = $_POST['msg'];   

     $M= "Name : {$name}". "Email: {$email} Phone : {$phone} "."Message : {$msg}";
     mail("tawanda.nyakudjga@gmail.com","tawazz.net/me",$M);
     header("location : index.php");
    }
?>
