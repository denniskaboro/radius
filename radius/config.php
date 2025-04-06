<?php
include('config/data.php');
include('session.php');
$mode = 'Live';  //payment mode Live or Sandbox
$sql = "SELECT * FROM bkash WHERE `mode`='$mode'";
$q = mysqli_query($con, $sql);
$array = mysqli_fetch_array($q);
$expire = $array["expiry"];
$username = $array["username"];
$password = $array["password"];
$app_key = $array["app_key"];
$app_secret = $array["app_secret"];
$token_url = $array["token_url"];
$refresh_url = $array["refresh_url"];
$refresh = $array["refresh_token"];
$token = $array["token"];
$script = $array["script"];
$search_url = $array["search_url"];
?>

