<?php

use RadiusSpot\RadiusSpot;

require_once "../class/radiusspot.php";
require_once "../config/data.php";
session_start();
$API = new RadiusSpot($con);
if (isset($_POST['CheckoutRequestID'])) {

    $CheckoutRequestID = $_POST['CheckoutRequestID'];
    $result = $result = $API->checkPayment($CheckoutRequestID);
//    $result = array("status" => "success", "message" => "Test Redirection");

    echo json_encode($result);

}
