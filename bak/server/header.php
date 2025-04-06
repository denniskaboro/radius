<?php
include('session.php');
$site_sql = "SELECT * FROM `site_setting` WHERE `status`='1'";
$site = mysqli_query($con,$site_sql);
$s = mysqli_fetch_array($site);
$site_name = $s['site_name'];
$site_logo = $s['site_logo'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="pragma" content="no-cache"/>
    <meta http-equiv="expires" content="-1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title></title>
    <link rel="shortcut icon" type="image/png" href="../site/<?php echo $site_logo;?>">
    <link href="assets/fonts/font-awesome/font-awesome.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" media="all" href="assets/css/animate.css">
    <link rel="stylesheet" type="text/css" media="all" href="assets/packages/bootstrap/bootstrap.css">
    <link rel="stylesheet" type="text/css" media="all" href="assets/packages/aos/aos.css">
    <link rel="stylesheet" type="text/css" media="all" href="assets/css/theme.css">
    <script src="js/jquery-1.12.4.min.js"></script>

</head>
<body>
<nav class="mb-1 navbar navbar-expand-lg navbar-dark" style="background: rgba(0,0,0,0.4); color:white;">
    <a class="navbar-brand" href="index.php"><img src="../site/<?php echo $site_logo;?>" width="180px" height="60px"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" href="index.php">Home
                </a>
            </li>
            <li class="nav-item"><a class="nav-link" href="page.php">Packages</a></li>
            <li class="nav-item"><a class="nav-link" href="status.php">Status</a></li>
            <!--            <li class="nav-item"><a class="nav-link" href="#">Contact Us</a></li>-->
        </ul>
    </div>
</nav>
