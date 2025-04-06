<?php 
session_start();
if ($_SESSION['id']==null || (!isset($_SESSION['id']))) {
    session_destroy();
	header("location:../login.php");
}
if ($_SESSION['per']==null || (!isset($_SESSION['per']))) {
        session_destroy();
         header("location:../login.php");
}

if ($_SESSION['username']==null || (!isset($_SESSION['username']))) {
   	session_destroy();
	 header("location:../login.php");
}else{
    $user=$_SESSION['username'];
    $pass=$_SESSION['pass'];
    $check = "SELECT * FROM `supplier` WHERE `username`='$user' and `password`='$pass'";
    $r = mysqli_query($con,$check);
    $rs = mysqli_num_rows($r);
    if($rs<1){
        session_destroy();
        header("location:../login.php");
    }
}

$uname=$_SESSION['id'];
?>
