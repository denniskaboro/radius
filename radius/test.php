<?php

use RadiusSpot\RadiusSpot;

session_start();

require_once "config/data.php";
require_once "class/radiusspot.php";
$API = new RadiusSpot($con);

$result = $API->checkPayment("AJKSD");
echo json_encode($result);