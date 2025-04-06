<?php
include('../config/data.php');
include('session.php');
include 'log.php';
$sup_id = $_SESSION['id'];
$API = new log_api();
$wh = $_SESSION['username'];
$per=$_SESSION['per'];
$ses=session_id();
    $client = $_SERVER['REMOTE_ADDR'];
    $current_time=time();
    $sql="SELECT * FROM visitor WHERE session_id='$ses'";
    $qu=mysqli_query($con,$sql);
    $row=mysqli_num_rows($qu);
    if($row==0 && $ses!=""){
        $in="INSERT INTO visitor (`user`,`session_id`,`ip`,`time`) VALUES('$wh','$ses','$client','$current_time')";
        mysqli_query($con,$in);
    }else{
        $up="UPDATE visitor set `user`='$wh',`ip`='$client',`time`='$current_time' WHERE `session_id`='$ses'";
        mysqli_query($con,$up);
    }
$site_sql = "SELECT * FROM `site_setting` WHERE `status`='1'";
$site = mysqli_query($con,$site_sql);
$s = mysqli_fetch_array($site);
$site_name = $s['site_name'];
$site_logo = $s['site_logo'];
$supplier = $_SESSION['id'];
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
    <script src="../component/js/jquery-1.8.3.min.js"></script>

</head>

<body class="nav-md">
<div id="blur" style="background-image: url('../bak/a.jpeg')">

</div>
<div class="container body" style=" position: relative;">
    <div class="main_container">
        <div class="col-md-3 left_col" >
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0; height: 80px !important; ">
                    <a href="home.php" class="site_title" style="height: 80px !important; ">
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
				<li><a><i class="fa fa-desktop"></i> Movie Server <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="http://10.220.221.221:8096" target="_blank">Movie Server</a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-user"></i> PPPoE User <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="my_user.php">My User List</a></li>
                                    <li><a href="user_create.php">User Add</a></li>
                                    <li><a href="active_users.php">Active Users</a></li>
                                </ul>
                            </li>
				<li><a><i class="fa fa-paw"></i> Ticket <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="com_create.php">Ticket Create</a></li>
                                    <li><a href="com_pending.php">Ticket Status</a></li>
                                    <li><a href="com_resolve.php">Ticket Resolved</a></li>
                                </ul>
                            </li>
			     <li><a href="filter.php"><i class="fas fa-times"></i> MAC Filter</a></li>
                            <li><a><i class="fa fa-money"></i> Transaction History <span
                                            class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="reseller_statistics.php">Statistics</a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-bar-chart-o"></i> Bandwidth <span
                                            class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
				    <li><a href="nas_list.php">Router List</a></li>
                                    <!--<li><a href="phy.php">Physical Interface</a></li> -->
                                    <!-- <li><a href="pppoe.php">PPPoE Interface</a></li>-->
                                </ul>
                            </li>
                            <li><a><i class="fa fa-cc-mastercard"></i> Billing <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
				    <li><a href="mpesabilling.php">Payment via Mpesa</a></li>
                                </ul>
                            </li>
                            <?php
                            $f = 0;
                            $g = 0;
                            $sql = "SELECT * FROM clients WHERE supplier_id='$supplier'";
                            $query = mysqli_query($con,$sql);
                            while ($fs = mysqli_fetch_array($query)) {
                                $username = $fs['username'];
                                $sql1 = "SELECT * FROM radcheck WHERE attribute='Expiration' && username='$username'";
                                $rs = mysqli_query($con,$sql1) or $msg = mysqli_error($con);
                                $p = mysqli_fetch_array($rs);
                                $expi = $p['value'];
                                $d = strtotime($expi);
                                $expiration = date("Y m d H:i", $d);
				$current=date("Y m d H:i", strtotime("+4 days"));
                                $dateTime = new DateTime();
                                $date = $dateTime->format('Y m d H:i');
                                if ($expiration > $date && $expiration < $current) {
                                    $f = $f + 1;

                                }
                                $e = strtotime($expi);
                                $expiration = date("Y m d H:i", $e);
                                if ($expiration < $date) {
                                    $g = $g + 1;

                                }
                            } ?>
                            <li><a><i class="fa fa-user"></i> Manage User <span style="float: right;margin-left: 5px;
                                    background-color: #a52d1e;" class="badge">
                                            <?php if (isset($f) || isset($g)) {
                                                echo "New";
                                            }else {
                                                echo "<span class=\"fa fa-chevron-down\"></span>";
                                    }

                                            ?></a>
                                <ul class="nav child_menu">
                                    <li><a href="deactive.php">Deactivate User</a></li>
                                    <li><a href="upcomming_exp.php">Expire will be 3 Days<span style="float: right;margin-left: 5px;
                                    background-color: #a52d1e;" class="badge">
                                            <?php echo $f;
                                            ?></a></li>
                                    <li><a href="expired.php">Expired User<span style="float: right;margin-left: 5px;
                                    background-color: #a52d1e;" class="badge">
                                            <?php echo $g;
                                            ?></a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-cubes"></i> Packages <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="package.php">Packages Info.</a></li>
                                </ul>
                            </li>
				            <li><a href="con_status.php"><i class="fa fa-tachometer"></i> Connection Logs</a></li>
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
                    <a data-toggle="tooltip" data-placement="top" title="Logout" href="../supplier/logout.php">
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

                        <!-- jQuery -->
                        <script src="../component/js/jquery.min.js"></script>
                        <!-- Bootstrap -->
                        <script src="../component/js/bootstrap.min.js"></script>

                        <!-- Custom Theme Scripts -->
                        <script src="../component/js/custom.min.js"></script>

