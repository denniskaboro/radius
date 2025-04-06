<?php
include 'includes/header.php';
$per = $_SESSION['per'];
$usr = $_SESSION['username'];
$sup_id = 'Admin';
if (isset($_POST['create']) and $_POST['package'] != null) {
    if ($per == "Admin" || $per == "Full" || $per == "Write") {
        $username = $_POST['username'];
        $attribute = "Cleartext-Password";
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $mobile = $_POST['mobile'];
        $password = $_POST['password'];
        $encr = md5($password);
        $client = $_POST['client'];
        $send = $_POST['send'];
        $connection = $_POST['connection'];
        $connectivity = $_POST['connectivity'];
        $profile = $_POST['package'];
        $gr = "SELECT * FROM `groups` WHERE `id`='$profile'";
        $g_q = mysqli_query($con, $gr);
        $b_f = mysqli_fetch_array($g_q);
        $group = $b_f['groupname'];
        $duration = $b_f['duration'];
        $price = $b_f['price'];
        $p_m = "/^0|^254/i";
        $mobile = preg_replace($p_m, "", $mobile);

        $check = "SELECT * FROM `clients` WHERE username='$username'";
        $res = mysqli_query($con, $check);
        $found = mysqli_num_rows($res);
        if ($found > 0) {
            $msg = "<h4 style='color: #c41914 ;'>Username has been already used please use another name...</h4>";
        } else {
//Balance Check
            if (isset($_POST['reseller'])) {
                $supplier_id = $_POST['reseller'];
                $user = "SELECT SUM(debit) as de, SUM(credit) as cr,supplier_name FROM `transaction` WHERE supplier_id='$supplier_id'";
                $usr = mysqli_query($con, $user);
                $u = mysqli_fetch_array($usr);
                $full_name = $u['supplier_name'];
                $de = $u['de'];
                $cr = $u['cr'];
                $bal = $de - $cr;
                $balance = $bal - $price;
                if ($bal >= $price) {
                    $in = "INSERT INTO `transaction` (`supplier_id`,`supplier_name`,`credit`,`reference`) VALUES('$supplier_id','$full_name','$price','User $username Created')";
                    $r = mysqli_query($con, $in);
                    if ($r) {
                        $cli = "INSERT INTO `clients` (`username`, `full_name`, `email`, `mobile`, `address`, `password` ,`client_type`,`connectivity`,`connection`,`supplier_id`)
VALUES('$username','$fullname','$email','$mobile','$address','$encr','$client','$connectivity','$connection','$sup_id')";
                        if ($duration != null) {
                            $newDate = date("d M Y H:i:s", strtotime("+ " . $duration));
                            $day = "INSERT INTO `radcheck` (`username`, `attribute`, `op`, `value`) VALUES('$username','Expiration',':=','$newDate')";
                            mysqli_query($con, $day) or $msg = mysqli_error($con);
                        }

                        $radcheck = "INSERT INTO `radcheck` (`username`, `attribute`, `op`, `value`) VALUES('$username','$attribute',':=','$password')";
                        mysqli_query($con, $radcheck) or $msg = mysqli_error($con);

                        $grp = "INSERT INTO `radusergroup` (`username`, `groupname`) VALUES('$username','$profile')";
                        $create = mysqli_query($con, $grp) or $msg = mysqli_error($con);
                        $client = mysqli_query($con, $cli) or $msg = mysqli_error($con);
                        if ($create and $client) {
                            $msg = "<h4 style='color: #0eff10 ;'>Username Created & Profile has been set</h4>";
                            $API->log($wh, $msg);
                        } else {
                            $msg = "<h4 style='color: #ff1e1e ;'>Transaction Error...</h4>";
                        }

                        if ($send == "Yes") {
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
                                    array($fullname, $username, $password, "$newDate", "$cur_date"),
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
                                }
                            }
                        }
                    }
                }
            } else {
                $cli = "INSERT INTO `clients` (`username`, `full_name`, `email`, `mobile`, `address`, `password` ,`client_type`,`connectivity`,`connection`,`supplier_id`)
VALUES('$username','$fullname','$email','$mobile','$address','$encr','$client','$connectivity','$connection','Admin')";
                if ($duration != null) {
                    $newDate = date("d M Y H:i:s", strtotime("+ " . $duration));
                    $day = "INSERT INTO `radcheck` (`username`, `attribute`, `op`, `value`) VALUES('$username','Expiration',':=','$newDate')";
                    mysqli_query($con, $day) or $msg = mysqli_error($con);
                }

                $radcheck = "INSERT INTO `radcheck` (`username`, `attribute`, `op`, `value`) VALUES('$username','$attribute',':=','$password')";
                mysqli_query($con, $radcheck) or $msg = mysqli_error($con);
                $in = "INSERT INTO `transaction` (`supplier_id`,`supplier_name`,`credit`,`reference`) VALUES('Admin','Admin','$price','User $username Created')";
                $r = mysqli_query($con, $in);
                $grp = "INSERT INTO `radusergroup` (`username`, `groupname`) VALUES('$username','$profile')";
                $create = mysqli_query($con, $grp) or $msg = mysqli_error($con);
                $client = mysqli_query($con, $cli) or $msg = mysqli_error($con);
                if ($create and $client) {
                    $msg = "<h4 style='color: #0eff10 ;'>Username Created & Profile has been set</h4>";
                    $API->log($sup_id, $wh, $msg);
                } else {
                    $msg = "<h4 style='color: #ff1e1e ;'>Transaction Error...</h4>";
                }

                if ($send == "Yes") {
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
                            array($fullname, $username, $password, "$newDate", "$cur_date"),
                            $message
                        );
                        $p_m = "/[^0|^254]/i";
                        $mobile = preg_replace($p_m, "", $mobile);

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
                        }
                    }
                }
            }

        }
    } else {
        echo "<h4 style='color: coral ;'>You have no permission...</h4>";
    }
}
?>
<div class="row">
    <!-- Pie Chart -->

    <div class="col-xl-8 col-lg-8" id="design" data-aos="fade-down">
        <!-- Basic Card Example -->
        <div class="card shadow mb-4 text-gray-900" style="margin-left: 2%; padding: 30px;">
            <?php if (isset($msg)) {
                echo "<h4 style='color: #2cde43'>" . $msg . "</h4>";
            } ?>
            <div class="card-header py-3">
                <h4 class="m-0 font-weight-bold text-secondary">Client Details Information:</h4>
            </div>
            <form data-toggle="validator" role="form" method="POST">
                <div class="form-group">
                    <label for="inputName" class="control-label">Username *</label>
                    <input type="text" class="form-control" id="inputName" name="username" placeholder="Username"
                           required>
                </div>
                <div class="form-group">
                    <label for="inputFullname" class="control-label">Full Name *</label>
                    <input type="text" class="form-control" id="inputFullname" name="fullname" placeholder="Full Name"
                           required>
                </div>
                <div class="form-group">
                    <label for="inputEmail" class="control-label">Address</label>
                    <input type="text" class="form-control" id="inputEmail" name="address" placeholder="Address">

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
                <div class="form-group">
                    <label for="inputName" class="control-label">Send Message</label>
                    <select class="form-control" name="for" required>
                        <option></option>
                        <option>Yes</option>
                        <option>No</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="inputName" class="control-label">User For</label>
                    <select class="form-control" name="for" onchange="For(this.value)" required>
                        <option></option>
                        <option>Admin</option>
                        <option>Reseller</option>
                    </select>
                </div>
                <div id="sup_pack">
                </div>

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
                    <label for="inputSet">Type of Client:</label>
                    <input type="text" class="form-control" id="inputSet" name="client" value="Home" readonly>
                </div>
                <div class="form-group">
                    <label for="inputCon">Type of Connectivity:</label>
                    <input type="text" class="form-control" id="inputCon" name="connectivity" value="Shared" readonly>
                </div>
                <div class="form-group">
                    <label for="inputConn">Type of Connection:</label>
                    <select class="form-control" name="connection" required>
                        <option></option>
                        <option>Wired</option>
                        <option>Wireless</option>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" name="" onClick='pay(this.id)' id="create" class="btn btn-primary">Create
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>
<script type="text/javascript">
    function pay(str) {
        var r = confirm("Money will be cutting for this User. Confirm to Create ?");
        if (r == true) {
            document.getElementById("create").name = str;
        } else {
            document.getElementById("create").name = "";
        }
    }

</script>
<script>
    function For(val) {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("sup_pack").innerHTML = this.responseText;
            }
        }
        xmlhttp.open("GET", "disable.php?usertype=" + val, true);
        xmlhttp.send();

    }
</script>
<script type="text/javascript" src="component/validator.js"></script>
<script type="text/javascript" src="component/validator.min.js"></script>

<?php include 'includes/footer.php'; ?>

