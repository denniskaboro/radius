<?php
include "config/data.php";
session_start();

?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NETCOM INTERNET HOTSPOT</title>
    <link rel="icon" href="assets\img\favicon.png">
    <link rel="apple-touch-icon" href="assets\img\favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i" rel="stylesheet">
    <link id="bootstrap_theme" rel="stylesheet" href="assets\css\theme-6-bootstrap.css">
    <link rel="stylesheet" href="assets\css\vendor.css">
    <link id="theme" rel="stylesheet" href="assets\css\theme-6.css">
    <link rel="stylesheet" href="assets\css\demo.css">
    <link rel="stylesheet" href="assets\css\custom.css">
    <script src="assets\js\modernizr.js"></script>
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
                                        src="assets\img\super-mi-slide-dark.png" alt=""
                                        class="navbar-brand-media-img navbar-brand-media-img-dark"><img
                                        src="assets\img\super-mi-slide-light.png" alt=""
                                        class="navbar-brand-media-img navbar-brand-media-img-light"></span></a>
                    </div>
                    <div id="site_header_navbar_collapse" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">

                                <li><a href="http://netcomhotspot.com/login" class="current">Home</a></li>
                            <li><a href="Pricing.php" class="current">Pricing</a></li>
                            <li><a href="Contact.php" class="current">Contact</a></li>
                            <li><a href="Coverage.php" class="current">Coverage</a></li>
                            <li><a href="login.php" class="current">Login Dashboard</a></li>

                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        <!-- .section -->
        <div id="pricing_section" class="pricing-section section section-md bg-light">
            <div class="container">
                <div class="flexible-widgets row">
                    <div class="widget-section widget widget_text wow fadeInUpShort col-md-8 col-md-offset-2 mb-xl text-center">
                        <div class="widget-wrap">
                            <h2 class="widget-title primary-text-dark">Our Packages</h2>
                            <div class="textwidget">
                                <p class="text-lead primary-text-dark">Netcom Unlimited Internet powered by Safaricom
                                    Business Internet</p>
                            </div>
                        </div>
                    </div>
                    <?php
                    $sql = "SELECT * FROM `groups` WHERE status =1 and dashboard=1";
                    $q = mysqli_query($con, $sql);
                    while ($f = mysqli_fetch_array($q)) {
                        ?>

                        <div data-wow-delay="100ms"
                             class="widget-section widget widget_text col-sm-6 col-md-4 mb-gg wow fadeInUpShort">
                            <div class="widget-wrap">
                                <div class="textwidget">
                                    <div class="card pricing-table-card card-light">
                                        <div class="card-media"><img src="assets\img\pricing-table-icon-1.png" alt=""
                                                                     width="72" height="72" class="img"></div>
                                        <div class="card-title">
                                            <div class="card-text"><span
                                                        class="title">INTERNET CONNECTION IN OUR NETWORK</span><span
                                                        class="price"><span class="currency">Ksh</span><span
                                                            class="value"><?php echo $f['price']; ?></span><span
                                                            class="per">/Month</span></span></div>
                                        </div>
                                        <div class="card-content">
                                            <ul>
                                                <li><span class="elem-wrap"><span
                                                                class="text">SPEED: <?php echo $f['speed']; ?></span></span>
                                                </li>
                                                <li><span class="elem-wrap"><span
                                                                class="text"><?php if ($f['data'] == "") {
                                                                echo "unlimited access";
                                                            } else {
                                                                echo $f['data'];
                                                            } ?></span></span></li>
                                                <li><span class="elem-wrap"><span
                                                                class="text">Number of Device: <?php echo $f['device']; ?></span></span>
                                                </li>
                                                <!-- <li><span class="elem-wrap"><span class="text">-</span></span></li> -->
                                                <li><span class="elem-wrap"><span class="text">-</span></span></li>
                                            </ul>
                                        </div>
                                        <div class="card-cta"><a href="registration.php?packid=<?php echo $f['id']; ?>"
                                                                 class="btn btn-wide btn-primary"><span
                                                        class="btn-elem-wrap"><span
                                                            class="text">Purchase</span></span></a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>

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
                                    href="https://www.facebook.com/" target="_blank" class="primary-text-light"><span>Facebook</span></a>
                        </li>
                        <li data-wow-delay="200ms" class="menu-item px-md wow fadeInUpShort"><a
                                    href="https://twitter.com/" target="_blank"
                                    class="primary-text-light"><span>Twitter</span></a></li>
                        <li data-wow-delay="300ms" class="menu-item px-md wow fadeInUpShort"><a
                                    href="https://plus.google.com/" target="_blank" class="primary-text-light"><span>Google+</span></a>
                        </li>
                        <li data-wow-delay="400ms" class="menu-item px-md wow fadeInUpShort"><a
                                    href="https://wa.me/6281211326207?text=ping" target="_blank"
                                    class="primary-text-light"><span>Whatsapp</span></a></li>
                        <li data-wow-delay="500ms" class="menu-item px-md wow fadeInUpShort"><a
                                    href="https://www.instagram.com/alpuketmerah" target="_blank"
                                    class="primary-text-light"><span>Instagram</span></a></li>
                    </ul>
                </nav>
                <div data-wow-delay="600ms" class="site-footer-copyright secondary-text-light wow fadeInUpShort">
                    Copyright © 2019 Netcom Networks All Rights Reserved.
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
