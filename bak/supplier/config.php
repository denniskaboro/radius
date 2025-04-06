<?php
include "../config/data.php";
include('session.php');
$sql="SELECT * FROM mpesa WHERE `status`='1'";
$q=mysqli_query($con,$sql);
$array=mysqli_fetch_array($q);

$mpea_user=$array["user"];
$mpea_pass=$array["pass"];
$shortcode=$array["shortcode"];
$timestamp=date('YmdHis');
$callback=$array["success"];
$refer=$array["refer"];
$passKey=$array["secret"];
$desc='Bill Payment for online service';

$payment_url = $array["payment_url"];
$token_url = $array["token_url"];

?>

