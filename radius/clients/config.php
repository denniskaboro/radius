<?php
include('session.php');
include('../config/data.php');
$sql = "SELECT * FROM mpesa WHERE `status`='1'";
$q = mysqli_query($con, $sql);
$array = mysqli_fetch_array($q);

$mpea_user = $array["customer_key"];
$mpea_pass = $array["customer_secret"];
$shortcode = $array["shortcode"];
$timestamp = date('YmdHis');
$callback = $array["success"];
$pType = $array["transactionType"];
$partB = $array["partB"];
$refer = $array["refer"];
$passKey = $array["passKey"];
$desc = 'Bill Payment for online service';

$payment_url = $array["payment_url"];
$token_url = $array["token_url"];
$queryURL = $array["queryURL"];

?>

