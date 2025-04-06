<?php
include('../config/data.php');
$sql = "SELECT * FROM mpesa WHERE status='1'";
$q = mysqli_query($con, $sql) or $g = mysqli_error($con);
$a = mysqli_fetch_array($q);
$customer_key = $a["customer_key"];
$customer_secret = $a["customer_secret"];
$shortcode = $a["shortcode"];
$timestamp = date('YmdHis');
$callback = $a["success"];
$refer = $a["refer"];
$passKey = $a["passKey"];


$payment_url = $a["payment_url"];
$token_url = $a["token_url"];
$queryURL = $a["queryURL"];

?>

