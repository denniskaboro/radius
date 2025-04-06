<?php
include('config.php');
if(isset($_SESSION['paymentID'])) {
    $paymentID = $_SESSION['paymentID'];

    $url = curl_init($exe_url . $paymentID);

    $header = array(
        'Content-Type:application/json',
        'authorization:' . $token,
        'x-app-key:' . $app_key
    );

    curl_setopt($url, CURLOPT_HTTPHEADER, $header);
    curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($url, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($url, CURLOPT_TIMEOUT, 30);
//curl_setopt($url, CURLOPT_PROXY, $proxy);

    $resultdatax = curl_exec($url);
    curl_close($url);
    echo $resultdatax;
    unset($_SESSION['paymentID']);
}
?>
