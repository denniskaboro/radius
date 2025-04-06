<?php
include 'includes/header.php';
$supplier_id = "Admin";
if (isset($_POST['send'])) {
    $sql = "SELECT * FROM `smsgateway` WHERE `supplier_id`='Admin'";
    $s = mysqli_query($con, $sql);
    $row = mysqli_num_rows($s);
    if ($row > 0) {
        $ss = mysqli_fetch_array($s);
        $api_url = $ss['url'];
        $SenderId = $ss['SenderId'];
        $ClientId = $ss['ClientId'];
        $ApiKey = $ss['ApiKey'];
        $AccessKey = $ss['AccessKey'];
    } else {
        exit("NO Gateway");
    }
    $message = $_POST['message'];
    $type = $_POST['type'];
    $l = strlen($message);
    $i = 0;
    $sms = 1;
    if ($l > 160) {
        $sms = 2;
    }
    if ($type != 'all') {
        $number = $_POST['number'];
	$p_m="/[^0|^254]/i";
        $mobile=preg_replace($p_m,"",$mobile);
    } else {
        $number = '';
        $sql = "SELECT * FROM `clients` WHERE `mobile` REGEXP '[^07]'";
        $query = mysqli_query($con, $sql);
        while ($f = mysqli_fetch_array($query)) {
            $num = $f['mobile'];
	     $p_m="/[^0]/i";
            $num=preg_replace($p_m,"",$num);

            $number = $number . $num . ",";
        }
    }
    $str_arr = explode(",", $number);
    foreach ($str_arr as $mobile) {
        if ($mobile != '') {
	$mobile=substr($mobile,1);
            $total[] = array(
                "Number" => "254" . $mobile,
                "Text" => "$message"
            );
        }
    }
    $dat=date("Y-m-d H:i:s");
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
            "AccessKey: ".$AccessKey,
            "Content-Type: application/json"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error =>" . $err;
    } else {
        $result = json_decode($response);
        if ($result->ErrorCode == 0) {
            $d = $result->Data;
            foreach ($d as $dd) {
                $ms=$dd->MessageErrorDescription;
	$in="INSERT INTO sms (`supplier_id`,`mobile`,`message`,`sms_count`) VALUES('Admin','$number','$message','1')";
	mysqli_query($con,$in);
                echo "<script>alert('$ms')</script>";
            }
        } else {
            echo $response;
        }

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
                    <label for="inputFullname" class="control-label">Select User</label>
                    <select onchange="Select(this.value)" class="form-control" name="type" required>
                        <option value="none">Select</option>
                        <option value="1">Individual</option>
                        <option value="all">All</option>
                    </select>
                </div>

                <div class="form-group" id="mobile" style="display: none;">
                    <label for="inputCom" class="control-label">Add Multiple</label>
                    <select class="form-control" id="num">
                        <option></option>
                        <?php
                        $sql = "SELECT * FROM `clients` WHERE  `mobile` REGEXP '^07'";
                        $query = mysqli_query($con, $sql);
                        while ($f = mysqli_fetch_array($query)) {
                            $pack = $f['mobile'];
                            $full_name = $f['full_name'];
                            echo "<option value='" . $pack . "'>{
                        $full_name
                        }- {
                        $pack
                        }</option>";
                        } ?>
                    </select>
                </div>
                <div class="form-group" id="result">

                </div>
                <div class="form-group">
                    <label class="control-label">Message</label>
                    <textarea class="form-control" name="message" cols="50" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" name="" onClick='pay(this.id)' id="send" style="padding: 7px !important;"
                            class="btn btn-primary">Send
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>
<script type="text/javascript">
    function pay(str) {
        var r = confirm("Money will be cutting for this SMS. Confirm to Create ?");
        if (r == true) {
            document.getElementById("send").name = str;
        } else {
            document.getElementById("send").name = "";
        }
    }

    function Select(str) {
        if (str == 1) {
            document.getElementById('mobile').style = "display:block";
            document.getElementById('result').innerHTML = '<textarea class="form-control" name="number" cols="50" rows="2" id="res" ></textarea><div class="form-group">\n' +
                '                    <button type="button" id="ex" onClick=\'Add()\' class="btn btn-sm btn-primary">Add</button>\n' +
                '                </div>'
        }
        if (str == 'none') {
            document.getElementById('mobile').style = "display:none";
            document.getElementById('res').remove();
            document.getElementById('ex').remove();
        }
        if (str == 'all') {
            document.getElementById('mobile').style = "display:none";
            document.getElementById('res').remove();
            document.getElementById('ex').remove();
        }
    }

    function Add() {
        var mob = document.getElementById('num').value;
        document.getElementById('res').innerHTML += mob + ",";
    }
</script>
<script type="text/javascript" src="js/validator.js"></script>
<script type="text/javascript" src="js/validator.min.js"></script>

<?php include 'includes/footer.php'; ?>

