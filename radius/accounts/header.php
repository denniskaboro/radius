<?php
include('../config/data.php');
include('session.php');
$per = $_SESSION['per'];
include("log.php");
$API = new log_api();
$wh = $_SESSION['username'];
$ses = session_id();
$client = $_SERVER['REMOTE_ADDR'];
$current_time = time();
$sql = "SELECT * FROM visitor WHERE session_id='$ses'";
$qu = mysqli_query($con, $sql);
$row = mysqli_num_rows($qu);
if ($row == 0 && $ses != "") {
    $in = "INSERT INTO visitor (user,session_id,ip,time) VALUES('$wh','$ses','$client','$current_time')";
    mysqli_query($con, $in);
} else {
    $up = "UPDATE visitor set user='$wh',ip='$client',time='$current_time' WHERE session_id='$ses'";
    mysqli_query($con, $up);
}
$site_sql = "SELECT * FROM `site_setting` WHERE `status`='1'";
$site = mysqli_query($con, $site_sql);
$s = mysqli_fetch_array($site);
$site_name = $s['site_name'];
$site_logo = $s['site_logo'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="../component/logo.png"/>
    <title><?php echo $site_name; ?></title>

    <!-- Bootstrap -->
    <link href="../component/css/bootstrap.min.css" rel="stylesheet">
    <link href="../component/css/style.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="../component/css/custom.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>

<body class="nav-md">
<div id="blur" style="background-image: url('../component/css/544.jpg')">

</div>
<div class="container body" style=" position: relative;">
    <div class="main_container">
        <div class="col-md-3 left_col" style="position: fixed;">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0; height: 80px !important; ">
                    <a href="accounts.php" class="site_title" style="height: 80px !important; ">
                        <img src="../site/<?php echo $site_logo; ?>" height="80px" width="140px"/>
                    </a>
                </div>

                <div class="clearfix"></div>

                <!-- menu profile quick info -->
                <div class="profile clearfix">
                    <div class="profile_info">
                        <span>Welcome,</span>
                        <h2><?php echo $site_name; ?></h2>
                    </div>
                </div>
                <!-- /menu profile quick info -->

                <br/>

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section">
                        <h2>Menu</h2>
                        <ul class="nav side-menu">
                            <li><a><i class="fa fa-user"></i> User<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="user_list.php">List User</a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-cubes"></i> Package <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="group_list.php">Package List</a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-bar-chart-o"></i> Supervisor <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="supplier_list.php">Supervisor List</a></li>
                                    <!--                                    <li><a href="payment_reseller.php">Supervisor Bill Payment</a></li>-->
                                </ul>
                            </li>
                            <li><a><i class="fa fa-cc-mastercard"></i> Billing <span
                                            class="fa fa-chevron-down"></span><span style="float: right;
    margin-left: 5px;background-color: #ff518b;" class="badge"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="mpesaExport.php">Mpesa History</a></li>
                                    <li><a href="advance.php">Payment History</a></li>
                                    <li><a href="revenew.php">Total Transaction</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                </div>
                <!-- /sidebar menu -->

                <!-- /menu footer buttons -->
                <div class="sidebar-footer hidden-small">
                    <a data-toggle="tooltip" data-placement="top" title="Settings">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Lock">
                        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Logout" href="../logout.php">
                        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                    </a>
                </div>
                <!-- /menu footer buttons -->
            </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
            <div class="nav_menu">
                <nav class="navbar" style="color:white;">
                    <div class="nav toggle">
                        <a id="menu_toggle"><i><span class="glyphicon glyphicon-list"></span> </i></a>
                    </div>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="">
                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown"
                               aria-expanded="false">
                                <?php echo $_SESSION['username']; ?>
                                <span class=" fa fa-angle-down"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu pull-right">
                                <li><a href="recovery.php">Password Change</a></li>
                                <li><a href="../logout.php"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- /top navigation -->

        <!-- page content -->

        <div class="right_col" role="main">
            <div class="">
                <div class="row">
                    <div class="col-md-12" style="color: white;">


                        <!-- /page content -->


                        <!-- jQuery -->
                        <script src="../component/js/jquery.min.js"></script>
                        <!-- Bootstrap -->
                        <script src="../component/js/bootstrap.min.js"></script>

                        <!-- Custom Theme Scripts -->
                        <script src="../component/js/custom.min.js"></script>
