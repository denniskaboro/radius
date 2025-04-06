<?php
include "header.php";
if (isset($_SESSION['mac'])) {
    $mac = $_SESSION['mac'];
    $sql = "SELECT * FROM `hotspot_auth` WHERE `mac`='$mac' ORDER BY `id` DESC ";
    $q = mysqli_query($con,$sql);
    $f = mysqli_fetch_array($q);
    $mac = $f['mac'];
    $ip = $f['ip'];
    $linklogin = $f['linklogin'];
    $linkorig = $f['linkorig'];
    $chapid = $f['chapid'];
    $chapchallenge = $f['chap_challenge'];
    $linkloginonly = $f['linkloginonly'];
    $linkorigesc = $f['linkorigsc'];
    $macesc = $f['macesc'];

}
if (isset($_POST['ip'])) {
    $mac = $_POST['mac'];
    $ip = $_POST['ip'];
    $username = $_POST['username'];
    $linklogin = $_POST['link-login'];
    $linkorig = $_POST['link-orig'];
    $error = $_POST['error'];
    $chapid = $_POST['chap-id'];
    $chapchallenge = $_POST['chap-challenge'];
    $linkloginonly = $_POST['link-login-only'];
    $linkorigesc = $_POST['link-orig-esc'];
    $macesc = $_POST['mac-esc'];
    $error = $_POST['error'];
}

?>
    <form name="sendin" action="<?php echo $linkloginonly; ?>" method="post">
        <input type="hidden" name="username"/>
        <input type="hidden" name="password"/>
        <input type="hidden" name="dst" value="<?php echo $linkorig; ?>"/>
        <input type="hidden" name="popup" value="true"/>
    </form>

    <script type="text/javascript" src="js/md5.js"></script>
    <script type="text/javascript">
        <!--
        function doLogin() {
            document.sendin.username.value = document.login.username.value;
            document.sendin.password.value = hexMD5('<?php echo $chapid; ?>' + document.login.password.value + '<?php echo $chapchallenge; ?>');
            document.sendin.submit();
            return false;
        }

        //-->
    </script>
    <!--/.Navbar -->
    <div class="container-fluid" style="background-image: url('img/t.jpg'); background-size: cover;">
        <div id="wrap">
        </div>
        <div class="d-flex justify-content-center h-100">
            <div class="card col-lg-6 col-md-6 px-md-0 px-3">
                <div class="card-header">
                    <h3>LogIn</h3>
                    <h6 style="color:red;"><?php if (isset($msg)) {
                            echo $msg;
                            echo $linklogin;
                        }
                        if (isset($_GET['OrderID'])) {
                            $succ = $_GET['OrderID'];
                            echo "<script>alert('$succ');</script>";
                        }
                        ?></h6>
                </div>
                <form name="login" action="<?php echo $linkloginonly; ?>" onSubmit="doLogin()">
                    <div class="card-body" data-aos="zoom-in"
                         data-aos-duration="3000">
                        <p>Please enter your username and password!</p>
                        <?php if (isset($error)) { ?>
                            <div class="alert alert-danger">
                                <strong>Error!</strong> <?php echo $error; ?>.
                            </div>
                        <?php } ?>
                        <input type="hidden" name="dst" value="<?php echo $linkorig; ?>"/>
                        <input type="hidden" name="popup" value="true"/>
                        <input type="hidden" name="mac" value="<?php echo $mac; ?>"/>
                        <input type="hidden" name="ip" value="<?php echo $ip; ?>"/>
                        <input type="hidden" name="link-login" value="<?php echo $linklogin; ?>"/>
                        <input type="hidden" name="link-orig" value="<?php echo $linkorig; ?>"/>
                        <input type="hidden" name="error" value="<?php echo $error; ?>"/>
                        <input type="hidden" name="chap-id" value="<?php echo $chapid; ?>"/>
                        <input type="hidden" name="link-login-only" value="<?php echo $linkloginonly; ?>"/>
                        <input type="hidden" name="chap-challenge" value="<?php echo $chapchallenge; ?>"/>
                        <input type="hidden" name="link-orig-esc" value="<?php echo $linkorigesc; ?>"/>
                        <input type="hidden" name="mac-esc" value="<?php echo $macesc; ?>"/>
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="username form-control"
                                   placeholder="Username"/>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="username form-control"
                                   placeholder="Password"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </div>
                    <hr>
                </form>
                <div class="card-footer">
                    <div class="justify-content-center links">
                        <h4 style="color:#100f33;"> Contact Us:</h4>
                        <hr>
                        <div><i class="fas fa-address-card"></i> Premium Connectivity Ltd.</div>
                        <div><i class="fas fa-at"></i> noc@pmcon.net</div>
                        <div><i class="fas fa-phone-square"></i>01847469555</div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid" style="background-image: url('img/t.jpg'); background-size: cover;">
        <div class="col-lg-12 col-md-12">
            <div class="row">
                <?php
                $sql = "SELECT * FROM `groups`  WHERE supplier_id='Admin'";
                $q = mysqli_query($con,$sql);
                while ($f = mysqli_fetch_array($q)) {
                    ?>
                    <div class="col-lg-3 col-md-3 col-sm-4" data-aos="zoom-out" style="color:white; margin-top:10px; ">
                        <div class="card-header" style="background-color: #38133b;">
                            <h6 class="text-uppercase m-0 font-weight-bold"><?php echo $f['groupname']; ?></h6>
                        </div>
                        <form method="POST" enctype="multipart/form-data" action="account.php">
                            <div class="card-body" style="background: rgba(39,33,79,0.38);">
                                <center>
                                    <h5 style="color:#ff7385;" class="font-weight-bold"></h5>
                                    <h5>Download Limit: <?php echo $f['data']; ?></h5>
                                    <h5>Duration: <?php echo $f['duration']; ?></h5>
                                    <h5>SPEED: <?php echo $f['speed']; ?></h5>
                                    <h5 style="color:#e7595f;" class="font-weight-bold">
                                        PRICE: <?php echo $f['price']; ?></h5>
                                    <input type="hidden" value="<?php echo $f['id']; ?>" name="pack_id">
                                    <button class="btn btn-danger" name="pack">Buy</button>
                                </center>
                            </div>
                        </form>
                        <!-- Card Body -->
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        document.login.username.focus();
    </script>

<?php
include "footer.php";
?>