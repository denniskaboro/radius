<?php

use RadiusSpot\RadiusSpot;

session_start();
require_once "config.php";
require_once "../class/radiusspot.php";
require_once "../config/data.php";

$API = new RadiusSpot($con);

if (isset($_POST['mac']) && isset($_POST['ip'])) {
    $mac = $_POST['mac'];
    $supplier_id = $_POST['supplier_id'];
    $ip = $_POST['ip'];
    $supplier_id = $_POST['supplier_id'];
    $linklogin = $_POST['link-login'];
    $linkorig = $_POST['link-orig'];
    $error = $_POST['error'];
    $chapid = $_POST['chap-id'];
    $chapchallenge = $_POST['chap-challenge'];
    $linkloginonly = $_POST['link-login-only'];
    $linkorigesc = $_POST['link-orig-esc'];
    $macesc = $_POST['mac-esc'];
    $_SESSION['mac'] = $mac;
    $_SESSION['username'] = preg_replace("/:/", '', $_SESSION['mac']);
    $in = "INSERT INTO `hotspot_auth`(`ip`,`mac`,`linklogin`,`linkorgin`,`chapid`,`chap_challange`,`linkloginonly`,`linkorginsc`,`macesc`)
VALUE('$ip','$mac','$linklogin','$linkorig','$chapid','$chapchallenge','$linkloginonly','$linkorigesc','$macesc')";
    $r = mysqli_query($con, $in);
    if ($r) {
        if ($check = $API->userCheck($_POST['mac'])) {
            echo "<script>window.location.assign('/hotspot/transfer/')</script>";
        } else {
            echo "<script>window.location.assign('/hotspot/signin/')</script>";
        }
    }

}

?>