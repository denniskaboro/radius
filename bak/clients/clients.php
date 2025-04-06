<?php
include('config.php');
$username=$_SESSION['username'];
$site_sql = "SELECT * FROM `site_setting` WHERE `status`='1'";
$site = mysqli_query($con,$site_sql);
$s = mysqli_fetch_array($site);
$site_name = $s['site_name'];
$site_logo = $s['site_logo'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
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
    <script src="js/jquery-1.8.3.min.js"></script>
<?php
//echo "<script src='".$script."'></script>";
?>
</head>

<body class="nav-md">
<div id="blur" style="background-image: url('../component/css/544.jpg')">

</div>
<div class="container body" style=" position: relative;">
    <div class="main_container">
        <div class="col-md-3 left_col" style="position: fixed;">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0; height: 80px !important; ">
                    <a href="my_info.php" class="site_title" style="height: 80px !important; ">
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
                        <h2>Genaral</h2>
                        <ul class="nav side-menu">
                            <li><a href="my_info.php"><i class="fa fa-bar-chart-o"></i> Client Info.<span
                                            class="fa fa-chevron-down"></span></a>
                            </li>
                            <li><a><i class="fa fa-paw"></i> Ticket <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="com_create.php">Ticket Create</a></li>
                                    <li><a href="com_pending.php">Ticket Status</a></li>
                                    <li><a href="com_resolve.php">Ticket Resolved</a></li>
                                </ul>
                            </li>
                            <li><a href="client_his.php"><i class="fa fa-bar-chart-o"></i>Usage History <span
                                            class="fa fa-chevron-down"></span></a>
                            </li>
                            <li><a><i class="fa fa-cc-mastercard"></i> Payment <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                   <!-- <li><a href="by_bkash.php">Payment By bKash</a></li> -->
                                    <li><a href="mpesabilling.php?payment">Payment By Mpesa</a></li>
                                    <!-- <li><a href="paymentStatus.php">Payment Status</a></li> -->
                                    
                                </ul>
                            </li>
                            <li><a href="package.php"><i class="fa fa-cubes"></i> Package Info. <span
                                            class="fa fa-chevron-down"></span></a>
                            </li>
                            <li><a href="payment_his.php"><i class="fa fa-cubes"></i>Payment History<span
                                            class="fa fa-chevron-down"></span></a>
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
                    <a data-toggle="tooltip" data-placement="top" title="Logout" href="logout.php">
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
                                <li><a href="pwd_change.php">Password Change</a></li>
                                <li><a href="logout.php"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
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

