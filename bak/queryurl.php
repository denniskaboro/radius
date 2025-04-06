<?php
include ("session.php");
$strJsonFileContents = file_get_contents("config.json");
$array = json_decode($strJsonFileContents, true);
$id = $_GET['payid'];
$proxy = $array["proxy"];
$url=$array["queryURL"].$id;
$url = curl_init($url);
$header=array(
    'Content-Type:application/json',
    'authorization:'.$array["token"],
    'x-app-key:2m6rb6t73ms5li4oirl929sgih'
);

curl_setopt($url,CURLOPT_HTTPHEADER, $header);
curl_setopt($url,CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($url,CURLOPT_RETURNTRANSFER, true);
curl_setopt($url,CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($url,CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($url,CURLOPT_TIMEOUT, 30);

$resultdata = curl_exec($url);
curl_close($url);
echo $resultdata;

?>
