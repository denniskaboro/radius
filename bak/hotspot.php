<?php
include('config/data.php');
session_start();
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
    $in = "INSERT INTO `hotspot_auth`(`ip`,`mac`,`linklogin`,`linkorgin`,`chapid`,`chap_challange`,`linkloginonly`,`linkorginsc`,`macesc`)
VALUE('$ip','$mac','$linklogin','$linkorig','$chapid','$chapchallenge','$linkloginonly','$linkorigesc','$macesc')";
    $r = mysqli_query($con, $in);
    $success=1;
}
if (isset($_SESSION['mac'])) {
    $mac = $_SESSION['mac'];
    $sql = "SELECT * FROM `hotspot_auth` WHERE `mac`='$mac' ORDER BY `id` DESC ";
    $q = mysqli_query($con, $sql);
    $f = mysqli_fetch_array($q);
    $mac = $f['mac'];
    $ip = $f['ip'];
    $linklogin = $f['linklogin'];
    $chapid = $f['chapid'];
    $linkloginonly = $f['linkloginonly'];
    $macesc = $f['macesc'];
    $success=1;
}
if ($success) {

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
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i"
              rel="stylesheet">
        <link id="bootstrap_theme" rel="stylesheet" href="assets/css/theme-6-bootstrap.css">
        <link rel="stylesheet" href="assets/css/vendor.css">
        <link id="theme" rel="stylesheet" href="assets/css/theme-6.css">
        <link rel="stylesheet" href="assets/css/demo.css">
        <link rel="stylesheet" href="assets/css/custom.css">
        <script src="assets/js/modernizr.js"></script>
    </head>
    <body>
    <form name="sendin" action="$(link-login-only)" method="post">
        <input type="hidden" name="username"/>
        <input type="hidden" name="password"/>
        <input type="hidden" name="dst" value="$(link-orig)"/>
        <input type="hidden" name="popup" value="true"/>
    </form>
    <script type="text/javascript" src="assets/js/md5.js"></script>
    <script type="text/javascript">
        <!--
        function doLogin() {
            document.sendin.username.value = document.login.username.value;
            document.sendin.password.value = hexMD5('$(chap-id)' + document.login.password.value + '$(chap-challenge)');
            document.sendin.submit();
            return false;
        }

        //-->
    </script>
    <div class="site">
        <div id="site_loader" class="site-loader">
            <div class="spinner"><span class="bounce"></span><span class="bounce"></span><span
                        class="bounce"></span></div>
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
            <div id="site_content" class="site-content">
                <div id="page_intro_section" style="height:100vh;"
                     class="align align-middle page-intro-section page-intro-section-md section section-md">
                    <div data-bg-slideshow-src="assets/img/%.jpg" data-bg-slideshow-amount='6'
                         data-slideshow-delay='4000'
                         data-bg-slideshow-animation="kenburns" class="bg-slideshow"></div>
                    <div data-css-opacity=".5" class="bg-overlay bg-black"></div>
                    <div class="align-container">
                        <div class="container">
                            <div class="flexible-widgets row d-sm-flex flex-items-sm-middle">
                                <div class="widget-section widget widget_text wow fadeInUpShort col-sm-6 text-center text-sm-inherit">
                                    <div class="widget-wrap">
                                        <h1 class="widget-title primary-text-light">NETCOM INTERNET HOTSPOT</h1>
                                        <div class="textwidget">
                                            <p class="text-lead primary-text-light">High Speed Internet Access. <br>
                                                Get
                                                Smart with fast, convenient internet near you.With Netcom Internet
                                                hotspot,
                                                access unlimited internet on your Phone, Laptop / Desktop PC</p>
                                            <form class="md-form form-light" name="login"
                                                  action="<?php echo $linkloginonly; ?>" onSubmit="doLogin()">
                                                <input type="hidden" name="dst" value="$(link-orig)"/>
                                                <input type="hidden" name="popup" value="true"/>
                                                <input type="hidden" name="dst" value="<?php echo $linkorig; ?>"/>
                                                <input type="hidden" name="popup" value="true"/>
                                                <input type="hidden" name="mac" value="<?php echo $mac; ?>"/>
                                                <input type="hidden" name="ip" value="<?php echo $ip; ?>"/>
                                                <input type="hidden" name="link-login"
                                                       value="<?php echo $linklogin; ?>"/>
                                                <input type="hidden" name="link-orig"
                                                       value="<?php echo $linkorig; ?>"/>
                                                <input type="hidden" name="error" value="<?php echo $error; ?>"/>
                                                <input type="hidden" name="chap-id" value="<?php echo $chapid; ?>"/>
                                                <input type="hidden" name="link-login-only"
                                                       value="<?php echo $linkloginonly; ?>"/>
                                                <input type="hidden" name="chap-challenge"
                                                       value="<?php echo $chapchallenge; ?>"/>
                                                <input type="hidden" name="link-orig-esc"
                                                       value="<?php echo $linkorigesc; ?>"/>
                                                <input type="hidden" name="mac-esc" value="<?php echo $macesc; ?>"/>
						<div class="col-md-10">
                                                <h1 class="btn btn-warning"
                                                    style="font-weight: bold;"><a style="color:white;"  href="https://netcominternet.co.ke/Pricing.php">START BROWSING CLICK HERE</a></h1>
						</div>
                                               <?php if(isset($error)){?>
                                                <div class="col-lg-9 col-md-10">
                                                    <div class="alert alert-danger">
                                                        <strong>Error!</strong> <?php echo $error; ?>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="inputUser" class="form-label">Username<span
                                                                        class="asterisk-symbol">*</span></label>
                                                            <div class="md-form-line-wrap">
                                                                <input id="inputUser" type="text" name="username"
                                                                       placeholder="Username" class="form-control"
                                                                       autofocus
                                                                       required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="inputPassword"
                                                                   class="form-label">Password<span
                                                                        class="asterisk-symbol">*</span></label>
                                                            <div class="md-form-line-wrap">
                                                                <input id="inputPassword" type="password"
                                                                       name="password"
                                                                       placeholder="Password" class="form-control"
                                                                       required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group mb-0">
                                                            <button type="submit" name="login"
                                                                    class="btn btn-block btn-warning"><span
                                                                        class="btn-elem-wrap"> <span
                                                                            class="icon fa fa-send"></span> <span
                                                                            class="text">LOGIN WITH VOUCHER</span></span>
                                                            </button>
                                                            <span style="display: none;"
                                                                  class="form-notify help-block mb-0"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div style="color: #20b2d6; font-size: 10px" align=center>
                                                            Designed
                                                            by Netcom Networks
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
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
                                 class="site-footer-copyright secondary-text-light wow fadeInUpShort">Copyright ©
                                2019
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

        <script>
            $("#menu-toggle").click(function (e) {
                e.preventDefault();
                $("#wrapper").toggleClass("toggled");
            });
        </script>
        <script type="text/javascript">
            <!--
            document.login.username.focus();
            //-->
        </script>
    </body>
    </html>
    </html>
    <?php

}
?>
