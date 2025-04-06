<?php
include "includes/header.php";
if (isset($_SESSION['mac'])) {
    $mac = $_SESSION['mac'];
    $sql = "SELECT * FROM `hotspot_auth` WHERE `mac`='$mac' ORDER BY `id` DESC ";
    $q = mysqli_query($con, $sql);
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

    ?>
    <form name="sendin" action="<?php echo $linkloginonly; ?>" method="post">
        <input type="hidden" name="username"/>
        <input type="hidden" name="password"/>
        <input type="hidden" name="dst" value="<?php echo $linkorig; ?>"/>
        <input type="hidden" name="popup" value="true"/>
    </form>

    <script type="text/javascript" src="/assets/md5.js"></script>
    <script type="text/javascript">
        function doLogin() {
            document.sendin.username.value = document.login.username.value;
            document.sendin.password.value = hexMD5('<?php echo $chapid; ?>' + document.login.password.value + '<?php echo $chapchallenge; ?>');
            document.sendin.submit();
            return false;
        }
    </script>
    <div class="section-authentication-signin d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                <div class="col mx-auto">
                    <div class="card mt-5 mt-lg-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <img src="/site/logo.png" width="380px" height="100px">
                            </div>
                            <form name="login" action="<?php echo $linkloginonly; ?>" onSubmit="doLogin()">
                                <div class="card-body" data-aos="zoom-in"
                                     data-aos-duration="3000">
                                    <p>Please enter your username and password!</p>
                                    <?php if ($_SESSION['error'] != "" || $_SESSION['error'] != null) { ?>
                                        <div class="alert alert-danger">
                                            <strong>Error!</strong> <?php echo $_SESSION['error']; ?>.
                                        </div>
                                    <?php } ?>
                                    <input type="hidden" name="dst" value="<?php echo $linkorig; ?>"/>
                                    <input type="hidden" name="popup" value="true"/>
                                    <input type="hidden" name="mac" value="<?php echo $mac; ?>"/>
                                    <input type="hidden" name="ip" value="<?php echo $ip; ?>"/>
                                    <input type="hidden" name="link-login" value="<?php echo $linklogin; ?>"/>
                                    <input type="hidden" name="link-orig" value="<?php echo $linkorig; ?>"/>
                                    <input type="hidden" name="error" value="<?php echo $_SESSION['error']; ?>"/>
                                    <input type="hidden" name="chap-id" value="<?php echo $chapid; ?>"/>
                                    <input type="hidden" name="link-login-only" value="<?php echo $linkloginonly; ?>"/>
                                    <input type="hidden" name="chap-challenge" value="<?php echo $chapchallenge; ?>"/>
                                    <input type="hidden" name="link-orig-esc" value="<?php echo $linkorigesc; ?>"/>
                                    <input type="hidden" name="mac-esc" value="<?php echo $macesc; ?>"/>
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" name="username" onkeyup="setPassword(this.value)"
                                               onchange="setPassword(this.value)" required
                                               class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" id="password" name="password"
                                               class="form-control">
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
                                    <div><i class="fas fa-address-card"></i> <a
                                                href="https://netcominternet.co.ke/login.php">NETCOM INTERNET</a></div>
                                    <div><i class="fas fa-at"></i> support@netcominternet.co.ke</div>
                                    <div><i class="fas fa-phone-square"></i>0701250798</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
    </div>

    <script type="text/javascript">
        function setPassword(password) {
            document.getElementById("password").value = password;
        }
    </script>

    <?php

}
include "includes/footer.php";
?>

