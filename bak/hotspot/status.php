<?php
include "includes/header.php";

$mac = $_POST['mac'];
$ip = $_POST['ip'];
$username = $_POST['username'];
$linklogin = $_POST['link-login'];
$linkorig = $_POST['link-orig'];
$error = $_POST['error'];
$trial = $_POST['trial'];
$loginby = $_POST['login-by'];
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

$t = $API->RowFetch("value", "radcheck", "username='$mac' and attribute='Expire-After'");
$time = $t['value'];
$left = $API->secondsToTime($time);
?>

    <div class="section-authentication-signin d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                <div class="col mx-auto">
                    <div class="card mt-5 mt-lg-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <img src="/site/logo.png" width="380px" height="100px">
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <?php if ($error) { ?>
                                        <div class="alert alert-danger"><?php echo $error; ?></div>
                                    <?php } ?>
                                    <?php if ($loginby != 'mac') { ?>
                                        <div class="alert alert-primary">Successfully logged in</div>
                                    <?php } ?>
                                </div>
                            </div>
                            <form name="logout" onSubmit="return openLogout()">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tbody>
                                        <tr class="table-info">
                                            <td>Total Time/Time Left:</td>
                                            <td><?php echo $left . "/" . $sessiontimeleft; ?></td>
                                        </tr>
                                        <tr>
                                            <td>IP address:</td>
                                            <td><?php echo $ip; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Bytes Up/Down</td>
                                            <td><?php echo $bytesinnice; ?> / <?php echo $bytesoutnice; ?></td>
                                        </tr>

                                            <tr>
                                                <td>Uptime:</td>
                                                <td><?php echo $uptime; ?></td>
                                            </tr>
                                        <?php
                                        if ($refreshtimeout) { ?>
                                            <tr>
                                                <td>Status Refresh:</td>
                                                <td><?php echo $refreshtimeout; ?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
include "includes/footer.php";
?>