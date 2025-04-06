<?php

use RadiusSpot\RadiusSpot;

require_once "../config/data.php";
require_once "../class/radiusspot.php";
session_start();
$API = new RadiusSpot($con);
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <title>NETCOM</title>
    <link rel="icon" href="/site/logo.png">
    <!--plugins-->
    <link rel="stylesheet" href="/hotspot/assets/plugins/notifications/css/lobibox.min.css"/>
    <link href="/hotspot/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet"/>
    <link href="/hotspot/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet"/>
    <link href="/hotspot/assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet"/>
    <link href="/hotspot/assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet"/>
    <!-- loader-->
    <!-- Bootstrap CSS -->
    <link href="/hotspot/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="/hotspot/assets/css/app.css" rel="stylesheet">
    <link href="/hotspot/assets/css/icons.css" rel="stylesheet">
    <link href="/hotspot/assets/css/loader.css" rel="stylesheet">
    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="/hotspot/assets/css/dark-theme.css"/>
    <link rel="stylesheet" href="/hotspot/assets/css/semi-dark.css"/>
    <link rel="stylesheet" href="/hotspot/assets/css/header-colors.css"/>
    <link rel="stylesheet" href="/hotspot/assets/css/msg.css"/>
    <script src="/hotspot/assets/js/jquery.min.js"></script>
    <script src="/hotspot/assets/plugins/notifications/js/lobibox.min.js"></script>
    <script src="/hotspot/assets/plugins/notifications/js/notifications.min.js"></script>

</head>

<body>

