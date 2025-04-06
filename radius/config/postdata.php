<?php

use RadiusSpot\RadiusSpot;

include('../session.php');
include "../config/data.php";
require_once "../class/radiusspot.php";
$API = new RadiusSpot($con);

$wh = $_SESSION['username'];

if (isset($_POST['multipleSMSSend'])) {
    $message = $_POST['message'];
    $user = $_POST['multipleSMSSend'];
    $ext = $_POST['ext'];

    echo $API->SendSMS($message, $ext, $user);
}


