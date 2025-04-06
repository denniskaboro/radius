<?php
include "includes/header.php";
$mac = $_SESSION['mac'];
$ip = $_POST['ip'];
$username = $_SESSION['mac'];
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

?>
<div id="wrap">
    <div class="container">
        <div class="col-md-6 col-sm-12">
            <div class="row">
                <div class="alert alert-success">
                    You are logged in successfully. If nothing happens, click <a href="<?php echo $linkloginonly; ?>">here</a>.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.location = "<?php echo $linkloginonly; ?>/status";
</script>
<?php
include "includes/footer.php";
?>