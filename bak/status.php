<?php
session_start();
include "config/data.php";
if (isset($_POST['username'])) {
    $mac = $_POST['mac'];
    $ip = $_POST['ip'];
    $username = $_POST['username'];
    $linklogin = $_POST['link-login'];
    $linkorig = $_POST['link-orig'];
    $error = $_POST['error'];
    $trial = $_POST['trial'];
    $chapid = $_POST['chap-id'];
    $chapchallenge = $_POST['chap-challenge'];
    $linkloginonly = $_POST['link-login-only'];
    $linkorigesc = $_POST['link-orig-esc'];
    $macesc = $_POST['mac-esc'];
    $identity = $_POST['identity'];
    $bytesinnice = $_POST['bytes-in-nice'];
    $bytesoutnice = $_POST['bytes-out-nice'];
    $sessiontimeleft = $_POST['session-time-left'];
    $uptime = $_POST['uptime'];
    $refreshtimeout = $_POST['refresh-timeout'];
    $linkstatus = $_POST['link-status'];
    $del = "DELETE FROM hotspot_auth WHERE mac='$mac'";
    mysqli_query($con, $del);
}
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NETCOM INTERNET HOTSPOT</title>
    <link rel="icon" href="assets/img/favicon.png">
    <link rel="apple-touch-icon" href="assets/img/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i" rel="stylesheet">
    <link id="bootstrap_theme" rel="stylesheet" href="assets/css/theme-6-bootstrap.css">
    <link rel="stylesheet" href="assets/css/vendor.css">
    <link id="theme" rel="stylesheet" href="assets/css/theme-6.css">
    <link rel="stylesheet" href="assets/css/demo.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <script src="assets/js/modernizr.js"></script>
</head>
<body>
<div class="site">
    <div id="site_loader" class="site-loader">
        <div class="spinner"><span class="bounce"></span><span class="bounce"></span><span class="bounce"></span></div>
    </div>
    <div class="site-canvas">
        <div id="site_header" class="site-header">
            <nav id="site_header_navbar"
                 class="site-header-navbar navbar navbar-fixed-top navbar-lg navbar-bg-from-transparent navbar-fg-from-light navbar-light">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" data-toggle="collapse" data-target="#site_header_navbar_collapse"
                                class="navbar-toggle collapsed"><span class="icon-bar"></span><span
                                    class="icon-bar"></span><span class="icon-bar"></span></button>
                        <a href="hotspot.php" class="navbar-brand navbar-brand-has-media"><span
                                    class="navbar-brand-elem-wrap"><span class="text">Brand</span><img
                                        src="assets/img/super-mi-slide-dark.png" alt=""
                                        class="navbar-brand-media-img navbar-brand-media-img-dark"><img
                                        src="assets/img/super-mi-slide-light.png" alt=""
                                        class="navbar-brand-media-img navbar-brand-media-img-light"></span></a>
                    </div>
                    <div id="site_header_navbar_collapse" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="https://netcominternet.co.ke/login" class="current">Home</a></li>

                            <li><a href="Pricing.php" class="current">Pricing</a></li>

                            <li><a href="Contact.php" class="current">Contact</a></li>
                            <li><a href="Coverage.php" class="current">Coverage</a></li>
                            <li><a href="login.php" class="current">Login Dashboard</a></li>

                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        <div id="site_content" class="site-content">
            <div id="page_intro_section" style="height:100vh;"
                 class="align align-middle page-intro-section page-intro-section-md section section-md">
                <div data-bg-slideshow-src="assets/img/%.jpg" data-bg-slideshow-amount='6' data-slideshow-delay='4000'
                     data-bg-slideshow-animation="kenburns" class="bg-slideshow"></div>
                <div data-css-opacity=".5" class="bg-overlay bg-black"></div>
                <div class="align-container">
                    <div class="container">
                        <div class="flexible-widgets row d-sm-flex flex-items-sm-middle">
                            <div class="widget-section widget widget_text wow fadeInUpShort col-sm-6 text-center text-sm-inherit">
                                <div class="widget-wrap">
                                    <h1 class="widget-title primary-text-light">NETCOM INTERNET HOTSPOT</h1>
                                    <div class="textwidget">
                                        <p class="text-lead primary-text-light">High Speed Internet Access. <br> Get
                                            Smart with fast, convenient internet near you.With Netcom Internet hotspot,
                                            access unlimited internet on your Phone, Laptop / Desktop PC</p>
                                        <div class="container"
                                             style="min-height: 100vh; height: auto; overflow: visible; padding: 5% 0;">
                                            <div class="row justify-content-center">
                                                <div class="col-lg-5 col-md-6 text-center" data-aos="fade-down">
                                                    <div style="color:white;background-color: rgba(0, 0, 0, 0.5) !important;">
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 text-center">
                                                                <?php if ($error) { ?>
                                                                    <div class="alert alert-danger"><?php echo $error; ?></div>
                                                                <?php } ?>
                                                                <div class="alert alert-info">Successfully logged in</div>
                                                            </div>
                                                        </div>
                                                        <form name="logout" onSubmit="return openLogout()">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered">
                                                                    <tbody style="color: black !important;">
                                                                    <tr>
                                                                        <td>IP address:</td>
                                                                        <td><?php echo $ip; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>bytes up/down</td>
                                                                        <td><?php echo $bytesinnice; ?>
                                                                            / <?php echo $bytesoutnice; ?></td>
                                                                    </tr>
                                                                    <?php if ($sessiontimeleft) { ?>
                                                                        <tr>
                                                                            <td>connected / left:</td>
                                                                            <td><?php echo $uptime; ?>
                                                                                / <?php echo $sessiontimeleft; ?></td>
                                                                        </tr>
                                                                    <?php } else { ?>
                                                                        <tr>
                                                                            <td>connected:</td>
                                                                            <td><?php echo $uptime; ?></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    <?php if ($refreshtimeout) { ?>
                                                                        <tr>
                                                                            <td>status refresh:</td>
                                                                            <td><?php echo $refreshtimeout; ?></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </form>
                                                        <div class="col-lg-12 col-md-12 text-center">
                                                           <a href="https://netcominternet.co.ke/logout" class="alert alert-danger">LOGOUT</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div data-wow-delay="100ms"
                                 class="widget-section widget widget_text col-xs-8 col-xs-offset-2 col-sm-6 col-sm-offset-0 col-md-5 col-md-offset-1 mt-vr mt-sm-0 wow fadeInUpShort">
                                <div class="widget-wrap">
                                    <div class="textwidget"><img src="assets/img/page-intro-shopping.png" alt=""
                                                                 class="img-responsive mx-auto"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- .site-content-->
            <div id="site_footer">
                <div id="site_footer_info" class="site-footer-info section section-sm text-center bg-dark">
                    <div class="container">
                        <nav class="site-footer-social-nav mb-lg">
                            <ul class="site-footer-social-menu list-inline ml-0">
                                <li data-wow-delay="100ms" class="menu-item px-md wow fadeInUpShort"><a
                                            href="https://www.facebook.com/" target="_blank"
                                            class="primary-text-light"><span>Facebook</span></a></li>
                                <li data-wow-delay="200ms" class="menu-item px-md wow fadeInUpShort"><a
                                            href="https://twitter.com/" target="_blank"
                                            class="primary-text-light"><span>Twitter</span></a>
                                </li>
                                <li data-wow-delay="300ms" class="menu-item px-md wow fadeInUpShort"><a
                                            href="https://plus.google.com/" target="_blank"
                                            class="primary-text-light"><span>Google+</span></a></li>
                                <li data-wow-delay="400ms" class="menu-item px-md wow fadeInUpShort"><a
                                            href="https://wa.me/6281211326207?text=ping" target="_blank"
                                            class="primary-text-light"><span>Whatsapp</span></a></li>
                                <li data-wow-delay="500ms" class="menu-item px-md wow fadeInUpShort"><a
                                            href="https://www.instagram.com/alpuketmerah" target="_blank"
                                            class="primary-text-light"><span>Instagram</span></a></li>
                            </ul>
                        </nav>
                        <div data-wow-delay="600ms"
                             class="site-footer-copyright secondary-text-light wow fadeInUpShort">Copyright © 2019
                            Netcom Networks All Rights Reserved.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/vendor.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/demo.js"></script>

</body>
</html>
</html>
