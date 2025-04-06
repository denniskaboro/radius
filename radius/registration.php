<?php
include('config/data.php');
session_start();
if (isset($_POST['create'])) {
    $username = $_POST['username'];
    $attribute = "Cleartext-Password";
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $sendmail = $_POST['send'];
    $gid = $_POST['gid'];
    $mobile = $_POST['mobile'];
    $p_m = "/^0|^254/i";
    $mobile = preg_replace($p_m, "", $mobile);

    $password = $_POST['password'];
    $encr = md5($password);
    $check = "SELECT * FROM `radcheck` WHERE username='$username'";
    $res = mysqli_query($con, $check);
    $found = mysqli_num_rows($res);
    if ($found > 0) {
        echo "<script>alert('Username has been already taken please use another username')</script>";
    } else {
        $cli = "INSERT INTO `clients` (`username`, `full_name`, `email`, `mobile`, `password`,`supplier_id`,`reference`,`approval`)
VALUES('$username','$fullname','$email','$mobile','$encr','Admin','Created By Self','Pending')";
        $client = mysqli_query($con, $cli) or $err = mysqli_error($con);
        if ($client) {
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['dept'] = "clients";
            $_SESSION['pass'] = $encr;
            $_SESSION['gid'] = $gid;
            $newdate = date("d M Y H:i:s", strtotime("-7 days"));
            $us = "INSERT INTO `radcheck` (`username`,`attribute`,`op`,`value`) VALUES('$username','Auth-Type',':=','PAP')";
            $u = mysqli_query($con, $us);
            $usr = "INSERT INTO `radcheck` (username,attribute,op,`value`) VALUES('$username','Cleartext-Password',':=','$password'),('$username','Expiration',':=','$newdate')";
            mysqli_query($con, $usr);
            $grp = "INSERT INTO `radusergroup` (`username`,`groupname`) VALUES('$username','$gid')";
            mysqli_query($con, $grp);
            $sql = "SELECT smsgateway.*, cron_job.* FROM `smsgateway` INNER JOIN cron_job ON  smsgateway.supplier_id=cron_job.supplier_id WHERE smsgateway.supplier_id='Admin'";
            $s = mysqli_query($con, $sql);
            $row = mysqli_num_rows($s);
            if ($row > 0) {
                $ss = mysqli_fetch_array($s);
                $api_url = $ss['url'];
                $SenderId = $ss['SenderId'];
                $ClientId = $ss['ClientId'];
                $ApiKey = $ss['ApiKey'];
                $AccessKey = $ss['AccessKey'];
                $message = $ss['create_message'];
                $p_user = "/[*][*]+[username]+[*][*]/i";
                $p_pass = "/[*][*]+[password]+[*][*]/i";
                $p_name = "/[*][*]+[full_name]+[*][*]/i";
                $p_exp = "/[*][*]+[expiration]+[*][*]/i";
                $p_date = "/[*][*]+[date]+[*][*]/i";
                $cur_date = date("d M Y H:i:s");
                $create_message = preg_replace(
                    array($p_name, $p_user, $p_pass, $p_exp, $p_date),
                    array($fullname, $username, $password, "", "$cur_date"),
                    $message
                );

                $total[] = array(
                    "Number" => "254" . $mobile,
                    "Text" => "$create_message"
                );
                $dat = date("Y-m-d H:i:s");
                $data = array(
                    "SenderId" => $SenderId,
                    "IsUnicode" => true,
                    "IsFlash" => true,
                    "MessageParameters" => $total,
                    "ApiKey" => $ApiKey,
                    "ClientId" => $ClientId
                );
                $postData = json_encode($data);
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => $api_url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $postData,
                    CURLOPT_HTTPHEADER => [
                        "AccessKey: " . $AccessKey,
                        "Content-Type: application/json"
                    ],
                ]);

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);
                $result = json_decode($response);
                if ($result->ErrorCode == 0) {
                    $in = "INSERT INTO sms (`supplier_id`,`mobile`,`message`,`sms_count`) VALUES('Admin','$mobile','$create_message','1')";
                    mysqli_query($con, $in);
                } else {
                    $d = $result->Data;
                    foreach ($d as $dd) {
                        $API->log($sup_id, $wh, $dd->MessageErrorDescription);
                    }
                }
                if ($sendmail) {
                    include "config/email.php";
                    require_once 'PHPMailer/PHPMailerAutoload.php';
                    $site_sql = "SELECT * FROM `site_setting` WHERE `status`='1'";
                    $site = mysqli_query($con, $site_sql);
                    $s = mysqli_fetch_array($site);
                    $site_name = $s['site_name'];
                    $site_logo = $s['site_logo'];

                    $subject = 'Account Created';
                    $mail = new PHPMailer;

                    $mail->SMTPDebug = 0;                                       // Enable verbose debug output
                    $mail->isSMTP();                                            // Set mailer to use SMTP
                    $mail->Host = $email_host; //'smtp.gmail.com', 'box5413.bluehost.com', 'mail.fingerthink.com';  // Specify main and backup SMTP servers
                    $mail->SMTPAuth = true;                                   // Enable SMTP authentication
                    $mail->Username = $email_user;                     // SMTP username
                    $mail->Password = $email_pass;                               // SMTP password
                    $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
                    $mail->Port = 587;                                    // TCP port to connect to
                    $mail->SMTPOptions = array(
                        'ssl' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        )
                    );
                    $mail->SetFrom($admin_email, $site_name);

                    //Recipients
                    $mail->addAddress($email);

                    // Content
                    $mail->isHTML(true);                                  // Set email format to HTML
                    $mail->Subject = $subject;
                    $mail->Body = $create_message;
                    $mail->addAttachment("site/$site_logo"); // attachment
                    $mail->send();
                }
            }
            header("location:clients/new_client.php");
        } else {
            echo "<h4 style='color: #ff1e1e ;'>{$err}</h4>";
        }
    }
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
                        <a href="login.html" class="navbar-brand navbar-brand-has-media"><span
                                    class="navbar-brand-elem-wrap"><span class="text">Brand</span><img
                                        src="assets\img\super-mi-slide-dark.png" alt=""
                                        class="navbar-brand-media-img navbar-brand-media-img-dark"><img
                                        src="assets\img\super-mi-slide-light.png" alt=""
                                        class="navbar-brand-media-img navbar-brand-media-img-light"></span></a>
                    </div>
                    <div id="site_header_navbar_collapse" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">

                            <Li><a href="login.php" class="current" style="color:#FF0000">Home</a></li>
                            <li><a href="Pricing.php" class="current" style="color:#FF0000">Pricing</a></li>
                            <li><a href="Contact.php" class="current" style="color:#FF0000">Contact</a></li>
                            <li><a href="Coverage.php" class="current" style="color:#FF0000">Coverage</a></li>


                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <div id="pricing_section" class="pricing-section section section-md bg-light">
        <div class="container">
            <div class="col-lg-6 col-md-6 col-sm-6" id="reg">
                <h3>Create Your Account </h3>
                <form data-toggle="validator" role="form" method="POST">
                    <div class="form-group">
                        <label for="inputName" class="control-label">Username *</label>
                        <input type="text" class="form-control" id="inputName" name="username" placeholder="Username"
                               required>
                    </div>
                    <div class="form-group">
                        <label for="inputFullname" class="control-label">Full Name *</label>
                        <input type="text" class="form-control" id="inputFullname" name="fullname"
                               placeholder="Full Name" required>
                        <input type="hidden" class="form-control" value="<?php if (isset($_GET['packid'])) {
                            echo $_GET['packid'];
                        } ?>" id="inputFullname" name="gid">
                    </div>
                    <div class="form-group">
                        <label for="inputMobile" class="control-label">Mobile *</label>
                        <input type="text"
                               class="form-control" id="inputMobile"
                               name="mobile" required>
                        <div class="help-block">Please start with 7***</div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail" class="control-label">Email</label>
                        <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Email"
                               data-error="That email address is invalid">
                        <div class="help-block with-errors"></div>
                    </div>
                    <input type="checkbox" style="width:20px;height:20px;" name="send"> Send Email Notification
                    <div class="form-group">
                        <label for="inputPassword" class="control-label">Password *</label>
                        <div class="form-inline row">
                            <div class="form-group col-sm-6">
                                <input type="password" data-minlength="4" class="form-control" id="inputPassword"
                                       name="password"
                                       placeholder="Password" required>
                                <div class="help-block">Minimum of 4 characters</div>
                            </div>
                            <div class="form-group col-sm-6">
                                <input type="password" class="form-control" id="inputPasswordConfirm"
                                       data-match="#inputPassword"
                                       data-match-error="Whoops, these don't match" placeholder="Confirm" required>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" class="form-control" id="inputSet" name="client" value="Home" readonly>
                    </div>
                    <div class="form-group">
                        <input type="hidden" class="form-control" id="inputCon" name="connectivity" value="Shared"
                               readonly>
                    </div>
                    <div class="form-group">
                        <input type="hidden" class="form-control" id="inputCon" name="connection" value="Wired"
                               readonly>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="" onClick='pay(this.id)' id="create" class="btn btn-primary">Create
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
    function pay(str) {
        var r = confirm("Confirm to Create ?");
        if (r == true) {
            document.getElementById("create").name = str;
        } else {
            document.getElementById("create").name = "";
        }
    }

</script>

<script src="assets\js\jquery.min.js"></script>
<script src="assets\js\bootstrap.min.js"></script>
<script src="assets\js\vendor.js"></script>
<script src="assets\js\main.js"></script>
<script src="assets\js\demo.js"></script>
$(if chap-id)
<script type="text/javascript" src="md5.js"></script>
<script type="text/javascript">
    $('#loginForm').submit(function () {
        var password = $('#inputPassword');
        password.val(hexMD5('$(chap-id)' + password.val() + '$(chap-challenge)'));
    });
</script>
$(endif)
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
