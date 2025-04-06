<?php
use RadiusSpot\RadiusSpot;
require_once "class/radiusspot.php";
include('config/data.php');
include('session.php');
include 'log.php';
$API = new RadiusSpot($con);

$wh = $_SESSION['username'];
$per = $_SESSION['per'];
$dept = $_SESSION['dept'];

$ses = session_id();
$client = $_SERVER['REMOTE_ADDR'];
$current_time = time();
$sql = "SELECT * FROM `visitor` WHERE `session_id`='$ses'";
$qu = mysqli_query($con, $sql);
$row = mysqli_num_rows($qu);
if ($row < 1 && $ses != "") {
    $in = "INSERT INTO `visitor` (`user`,`session_id`,`ip`,`time`) VALUES('$wh','$ses','$client','$current_time')";
    mysqli_query($con, $in);
} else {
    $up = "UPDATE `visitor` set `user`='$wh',`ip`='$client',`time`='$current_time' WHERE `session_id`='$ses'";
    mysqli_query($con, $up);
}
$site_sql = "SELECT * FROM `site_setting` WHERE `status`='1'";
$site = mysqli_query($con, $site_sql);
$s = mysqli_fetch_array($site);
$site_name = $s['site_name'];
$site_logo = $s['site_logo'];
if ($dept == "clients") {
    header("location:clients/client_his.php");
}
if ($dept == "supplier") {
    header("location:supplier/reseller_statistics.php");
}
if ($dept == "Accounts") {
    header("location:accounts/accounts.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="component/logo.png"/>
    <title><?php echo $site_name; ?></title>

    <!-- Bootstrap -->
    <link href="component/css/bootstrap.min.css" rel="stylesheet">
    <link href="component/css/style.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="component/css/custom.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="component/js/jquery-1.8.3.min.js"></script>
    <link href="component/datetime/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <script id="myScript" src="https://scripts.pay.bka.sh/versions/1.2.0-beta/checkout/bKash-checkout.js"></script>
</head>

<body class="nav-md">
<div id="blur" style="background-image: url('bak/a.jpeg')">

</div>
<div class="container body" style=" position: relative;">
    <div class="main_container">
        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0; height: 80px !important; ">
                    <a href="index.php" class="site_title" style="height: 80px !important; ">
                        <img src="site/<?php echo $site_logo; ?>" height="80px" width="200px"/>
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
                            <li><a><i class="fa fa-user"></i> PPPOE<span class="fa fa-chevron-down"></span>
                                    <span style="float: right;margin-left: 5px;background-color: #3e5ec4;"
                                          class="badge">
                            <?php $q = "SELECT * FROM clients WHERE new_user='yes'";
                            $res = mysqli_query($con, $q);
                            $g = mysqli_num_rows($res);
                            if ($g > 0) {

                                echo "New " . $g;
                            }
                            ?></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="new_user.php">New Users<span style="float: right;margin-left: 5px;
                                    background-color: #3e5ec4;" class="badge"><?php if ($g > 0) {
                                                    echo "New " . $g;
                                                }

                                                ?></span></a></li>
                                    <?php if ($per == "Full" || $per == "Write" || $per == "Admin") { ?>
                                        <li><a href="user_create.php">Create User</a></li>
                                    <?php } ?>
                                    <li><a href="user_list.php">List Users</a></li>
                                    <li><a href="active_users.php"">Active User</a></li>
                                    <li><a href="deactive.php">Disable User <span style="float: right;margin-left: 5px;
                                    background-color: #8f635e;" class="badge">
                                            <?php
                                            $q = "SELECT * FROM radcheck WHERE attribute='Auth-Type' && value='reject' ";
                                            $res = mysqli_query($con, $q);
                                            $f = mysqli_num_rows($res);

                                            echo $f;


                                            ?></a></li>
                                    <li><a href="expired.php">Current Expired<span style="float: right;margin-left: 5px;
                                    background-color: #a52d1e;" class="badge">
                                            <?php
                                            $current = date("Y m d H:i", strtotime("-1 month"));
                                            $dateTime = new DateTime();
                                            $date = $dateTime->format('Y m d H:i');
                                            $f = 0;
                                            $sql1 = "SELECT * FROM radcheck WHERE attribute='Expiration'";
                                            $rs = mysqli_query($con, $sql1) or $msg = mysqli_error($con);
                                            while ($p = mysqli_fetch_array($rs)) {
                                                $expi = $p['value'];
                                                $d = strtotime($expi);
                                                $expiration = date("Y m d H:i", $d);
                                                if ($expiration < $date && $expiration > $current) {
                                                    $f = $f + 1;

                                                }
                                            }
                                            echo $f;

                                            ?></a></li>
                                    <li><a href="pre_expired.php">Previous Expired<span style="float: right;margin-left: 5px;
                                    background-color: #a52d1e;" class="badge">
                                            <?php
                                            $f = 0;
                                            $sql1 = "SELECT * FROM radcheck WHERE attribute='Expiration'";
                                            $rs = mysqli_query($con, $sql1) or $msg = mysqli_error($con);
                                            while ($p = mysqli_fetch_array($rs)) {
                                                $expi = $p['value'];
                                                $d = strtotime($expi);
                                                $expiration = date("Y m d H:i", $d);
                                                if ($expiration < $current) {
                                                    $f = $f + 1;

                                                }
                                            }
                                            echo $f;

                                            ?></a></li>

                                    <li><a href="upcomming_exp.php">Upcomming Expired <span style="float: right;margin-left: 5px;
                                    background-color: #a52d1e;" class="badge">
                                            <?php
                                            $f = 0;
                                            $current = date("Y m d H:i", strtotime("+4 days"));
                                            $sql1 = "SELECT * FROM radcheck WHERE attribute='Expiration'";
                                            $rs = mysqli_query($con, $sql1) or $msg = mysqli_error($con);
                                            while ($p = mysqli_fetch_array($rs)) {
                                                $expi = $p['value'];
                                                $d = strtotime($expi);
                                                $expiration = date("Y m d H:i", $d);
                                                if ($expiration > $date && $expiration < $current) {
                                                    $f = $f + 1;

                                                }
                                            }
                                            echo $f;

                                            ?></a></li>

                                </ul>
                            </li>
                            <li><a><i class="fa fa-paw"></i> COMPLAIN <span class="fa fa-chevron-down"></span><span
                                            style="float: right;margin-left: 5px;background-color: #ff001e;"
                                            class="badge">
                                        <?php $q = "SELECT * FROM complain WHERE status ='pending' || status='Working' ";
                                        $res = mysqli_query($con, $q);
                                        $found = mysqli_num_rows($res);
                                        echo "New " . $found;
                                        ?></span></a>
                                <ul class="nav child_menu">
                                    <?php if ($dept != "Visitor") { ?>
                                        <li><a href="com_create.php">Create Complain</a></li><?php } ?>
                                    <li><a href="com_pending.php">Pending Complain</a></li>
                                    <li><a href="com_resolve.php">Complain Resolved</a></li>
                                </ul>
                            </li>

                            <li><a><i class="fa fa-cubes"></i> PACKAGE <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="group_list.php">Package List</a></li>
                                    <?php if ($per == "Full" || $per == "Write" || $per == "Admin") { ?>
                                        <li><a href="group_create.php">Package Create</a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <li>
                                <a><i class="fa fa-user"></i>HOTSPOT<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="voucher_list.php">Manage Voucher</a></li>
                                    <li><a href="users.php">Voucher Users List</a></li>
                                    <li><a href="hotspot_active.php"">Active Users</a></li>
                                    <li><a href="hotspot_sell.php"">Hotspot Payment</a></li>
                                    <li><a href="hotspot_user.php"">Hotspot Customer</a></li>
                                </ul>
                            </li>
                            <?php if ($dept != "Visitor") { ?>
                                <li><a><i class="fa fa-user-circle"></i> RESELLER <span
                                            class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="supplier_list.php">Supervisor List</a></li>
                                    <?php if ($per == "Full" || $per == "Write" || $per == "Admin") { ?>
                                        <li><a href="supplier_account.php">Create Supervisor Account</a></li>
                                    <?php } ?>
                                </ul>
                                </li><?php } ?>
                            <li><a><i class="fa fa-cc-mastercard"></i> BILLING <span
                                            class="fa fa-chevron-down"></span><span style="float: right;
    margin-left: 5px;background-color: #ff518b;" class="badge"></span></a>
                                <ul class="nav child_menu">
                                    <?php if ($per == "Admin" || $per == "Full") { ?>
                                        <li><a href="debit.php">Recharge Supplier Account</a></li>
                                    <?php } ?>
                                    <li><a href="revenew.php">Total Transaction</a></li>
                                    <li><a href="advance_his.php">Payment Pending</a>
                                    <li><a href="mpesaResponse.php">Payment Success</a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-bar-chart-o"></i> REPORT <span
                                            class="fa fa-chevron-down"></span><span style="float: right;
    margin-left: 5px;background-color: #ff518b;" class="badge"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="chart.php">Monthly Report</a></li>
                                    <li><a href="UserBalance.php">User Account Status</a></li>
                                </ul>
                            </li>
                            <h2>System</h2>
                            <li><a><i class="fa fa-server"></i> NAS <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="nas_list.php">NAS List</a></li>
                                    <?php if ($per == "Full" || $per == "Write" || $per == "Admin") { ?>
                                        <li><a href="nas_create.php">NAS Create</a></li>
                                        <li><a href="vpn_create.php">VPN Create</a></li>
                                        <li><a href="vpn_list.php">VPN Lists</a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-envelope"></i>SETTINGS<span
                                            class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a class="collapse-item" href="smsGatewayList.php">SMS Gateway</a></li>
                                    <li><a class="collapse-item" href="setting.php">Portal Setting</a></li>
                                    <li><a class="collapse-item" href="cron_job.php">Message Settings</a></li>
                                    <li><a class="collapse-item" href="send_sms.php">SEND SMS</a></li>
                                    <li><a class="collapse-item" href="sent_list.php">Sent SMS List </a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-database"></i> LOG <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <!-- <li><a href="nas_log.php">NAS Connection Logs</a></li> -->
                                    <li><a href="con_status.php">Authentication Logs</a></li>
                                    <li><a href="radius_log.php">Radius Logs</a></li>
                                    <li><a href="server_status.php">Server Status</a></li>
                                    <li><a href="visitor.php">Visitor</a></li>
                                    <li><a href="system_log.php">System Log</a></li>
                                    <li><a href="accss_log.php">HTTP Access Log</a></li>
                                    <li><a href="httpd.php">HTTP Error Logs</a></li>
                                    <li><a href="sms_log.php">SMS Logs</a></li>
                                    <?php if ($per == "Admin") { ?>
                                        <li><a href="working_log.php">Working Logs</a></li>
                                    <?php } ?>
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
                                <?php if ($_SESSION['per'] == 'Full' || $_SESSION['per'] == 'Admin') { ?>
                                    <li><a href="show_user.php"><span class="glyphicon glyphicon-user"></span>Show All
                                            Users</a></li>
                                    <li><a href="account_create.php"><span class="glyphicon glyphicon-user"></span>
                                            Create Account</a></li>
                                <?php } ?>
                                <li><a href="recovery.php">Password Change</a></li>
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
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12" style="color: white;">


                        <!-- /page content -->


                        <!-- jQuery -->
                        <script src="component/js/jquery.min.js"></script>
                        <!-- Bootstrap -->
                        <script src="component/js/bootstrap.min.js"></script>

                        <!-- Custom Theme Scripts -->
                        <script src="component/js/custom.min.js"></script>
