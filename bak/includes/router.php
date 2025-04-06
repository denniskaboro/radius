<?php
include('config/data.php');
include('session.php');
include('log.php');
$wh = $_SESSION['username'];
$API=new log_api();
$per = $_SESSION['per'];
if ($per == "reseller") {
    header("location:supplier/reseller_statistics.php");
}
if ($per == "Accounts") {
    header("location:accounts/accounts.php");
}
if (isset($_GET['ip'])) {
    $ip_add = $_GET['ip'];
    $_SESSION['ip'] = $ip_add;
}
$ip = $_SESSION['ip'];
$sql = "SELECT * FROM nas WHERE nasname='$ip'";
$res = mysqli_query($con,$sql);
$f = mysqli_fetch_array($res);
$user = $f['login_user'];
$pass = $f['login_password'];
$port=$f['login_port'];

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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
          integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

</head>

<body class="nav-md">
<div id="blur" style="background-image: url('component/css/18.jpg')">

</div>
<div class="container body" style=" position: relative;">
    <div class="main_container">
        <div class="col-md-3 left_col">
            <div class="left_col scroll-view ">
                <div class="navbar nav_title" style="border: 0; height: 80px !important; ">
                    <a href="index.php" class="site_title" style="height: 80px !important; ">
                        <img src="site/<?php echo $site_logo; ?>" height="80px" width="140px"/>
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
                        <h2>Router Configuration</h2>
                        <ul class="nav side-menu">
                            <li><a><i class="fas fa-server"></i> System<span class="fas fa-angle-down"
                                                                           style="float: right;"></span></a>
				<ul class="nav child_menu">
                                    <li><a href="system_info.php">System Info</a></li>
                                    <li><a href="command.php">Show Config</a></li>
                                    <li><a href="command_run.php">RUN Config</a></li>
                                </ul>
				</li>
                            <li><a><i class="fas fa-broadcast-tower"></i> NAS <span class="fas fa-angle-down"
                                                                           style="float: right;"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="nas_list.php">NAS List</a></li>
                                    <li><a href="nas_create.php">NAS Create</a></li>
                                </ul>
                            </li>
                            <li><a href="filter.php"><i class="fas fa-times"></i> MAC Filter</a></li>
                            <li><a><i class="fas fa-tools"></i> Interface <span class="fas fa-angle-down"
                                                                               style="float: right;"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="interface_list.php">Interface List</a></li>
                                    <li><a href="pppoe_list.php">PPPoE Interface List</a></li>
                                    <li><a href="vlan_list.php">VLAN List</a></li>
                                    <?php if ($per == "Write" || $per == "Full" || $per == "Admin") { ?>
                                        <li><a href="vlan_create.php">VLAN Create</a></li><?php } ?>
                                </ul>
                            </li>
                            <li><a><i class="fas fa-database"></i> IP Address <span class="fas fa-angle-down"
                                                                                    style="float: right;"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="ip_address.php">IP Address List</a></li>
                                    <?php if ($per == "Write" || $per == "Full" || $per == "Admin") { ?>
                                        <li><a href="ip_address_add.php">IP Address Add</a></li><?php } ?>
                                </ul>
                            </li>
                            <li><a><i class="fas fa-satellite-dish"></i> IP Pool <span class="fas fa-angle-down"
                                                                                       style="float: right;"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="ip_pool.php">IP Pool List</a></li>
                                    <?php if ($per == "Write" || $per == "Full" || $per == "Admin") { ?>
                                        <li><a href="pool_add.php">IP Pool Add</a></li><?php } ?>
                                </ul>
                            </li>
                            <h2></h2>
                            <li><a><i class="fas fa-laptop"></i> PPPoE <span class="fas fa-angle-down"
                                                                                      style="float: right;"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="ppp_list.php">PPPoE Server List</a></li>
                                    <?php if ($per == "Write" || $per == "Full" || $per == "Admin") { ?>
                                        <li><a href="ppp_create.php">PPPoE Server Create</a></li><?php } ?>
                                    <li><a href="profile_list.php">PPPoE Profile List</a></li>
                                    <?php if ($per == "Write" || $per == "Full" || $per == "Admin") { ?>
                                        <li><a href="profile_add.php">PPPoE Profile Create</a></li><?php } ?>
                                </ul>
                            </li>
                            <li><a><i class="fas fa-wifi"></i> Hotspot <span class="fas fa-angle-down"
                                                                                        style="float: right;"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="hotspot_list.php">Hotspot Server List</a></li>
                                    <?php if ($per == "Write" || $per == "Full" || $per == "Admin") { ?>
                                        <li><a href="hotspot_create.php">Hotspot Server Create</a></li><?php } ?>
                                    <li><a href="hot_profile_list.php">Hotspot Profile List</a></li>
                                    <?php if ($per == "Write" || $per == "Full" || $per == "Admin") { ?>
                                        <li><a href="hot_profile_add.php">Hotspot Profile Create</a></li><?php } ?>
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
                                <?php if ($per == 'Full') { ?>
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
                    <div class="col-md-12" style="color: white;">


                        <!-- /page content -->


                        <!-- jQuery -->
                        <script src="component/js/jquery.min.js"></script>
                        <!-- Bootstrap -->
                        <script src="component/js/bootstrap.min.js"></script>

                        <!-- Custom Theme Scripts -->
                        <script src="component/js/custom.min.js"></script>
