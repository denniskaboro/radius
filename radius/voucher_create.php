<?php
include('config/data.php');
include 'includes/header.php';
$per = $_SESSION['per'];
$profile = date('dyHs');
$admin_id = 'Admin';
function RandomString($length)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

if (isset($_POST['create'])) {
    $num = $_POST['num'];
    $name = $_POST['name'];
    $amt = $_POST['amount'];
    $dead = $_POST['dead'];
    $type = $_POST['type'];
    $pe = $_POST['per'];
    $sp = $_POST['speed'];
    if ($sp == 'M') {
        $speed = $pe . 'M';
        $sq = "INSERT INTO `radgroupreply` (`groupname`,`attribute`,`op`,`value`) VALUES('$profile','Mikrotik-Rate-Limit','=','$speed/$speed')";
        mysqli_query($con, $sq);
    } else {
        $speed = $pe . 'K';
        $sq = "INSERT INTO `radgroupreply` (`groupname`,`attribute`,`op`,`value`) VALUES('$profile','Mikrotik-Rate-Limit','=','$speed/$speed')";
        mysqli_query($con, $sq);
    }

    if ($type == '1') {
        $day = $_POST['day'];
        $exp = $_POST['exp'];
        $dd = $day . " " . $exp;
        if ($exp == 'Minute') {
            $newDate = $day * 60;
        } elseif ($exp == 'Hour') {
            $newDate = $day * 60 * 60;
        } elseif ($exp == 'Days') {
            $newDate = $day * 60 * 60 * 24;
        } else {
            $newDate = $day * 60 * 60 * 24 * 30;
        }

        $vou = "INSERT INTO `voucher` (`package_name`,`voucher_name`,`total_user`,`voucher_type`,`amount`,`mbps`,`expiration`,`deadline`,`supplier_id`) VALUES('$name','$profile','$num','Day Pack','$amt','$speed','$dd','$dead','$admin_id')";
        mysqli_query($con, $vou);
        $e = "INSERT INTO `radgroupcheck` (`groupname`,`attribute`,`op`,`value`) VALUES('$profile','Expire-After',':=','$newDate')";
        mysqli_query($con, $e);

    } else if ($type == '2') {
        $band = $_POST['band'];
        $limit = $_POST['limit'];
        if ($limit == "MB") {
            $value = $band * 1024 * 1024;
        } else {
            $value = $band * 1024 * 1024 * 1024;
        }
        $vou = "INSERT INTO `voucher` (`package_name`,`voucher_name`,`total_user`,`voucher_type`,`amount`,`mbps`,`total_limit`,`deadline`,`supplier_id`) VALUES('$name','$profile','$num','Bandwidth Pack','$amt','$speed','$band $limit','$dead','$admin_id')";
        mysqli_query($con, $vou);
        $sqq = "INSERT INTO `radgroupcheck` (`groupname`,`attribute`,`op`,`value`) VALUES('$profile','Max-Data',':=','$value')";
        mysqli_query($con, $sqq);
    } else {
        $day = $_POST['day'];
        $exp = $_POST['exp'];
        $dd = $day . " " . $exp;
        if ($exp == 'Minute') {
            $newDate = $day * 60;
        } elseif ($exp == 'Hour') {
            $newDate = $day * 60 * 60;
        } elseif ($exp == 'Days') {
            $newDate = $day * 60 * 60 * 24;
        } else {
            $newDate = $day * 60 * 60 * 24 * 30;
        }
        $e = "INSERT INTO `radgroupcheck` (`groupname`,`attribute`,`op`,`value`) VALUES('$profile','Expire-After',':=','$newDate')";
        mysqli_query($con, $e);
        $band = $_POST['band'];
        $limit = $_POST['limit'];
        if ($limit == "MB") {
            $value = $band * 1024 * 1024;
        } else {
            $value = $band * 1024 * 1024 * 1024;
        }
        $vou = "INSERT INTO `voucher` (`package_name`,`voucher_name`,`total_user`,`voucher_type`,`amount`,`mbps`,`total_limit`,`expiration`,`deadline`,`supplier_id`) VALUES('$name','$profile','$num','Day_Bandwidth Pack','$amt','$speed','$band $limit','$dd','$dead','$admin_id')";
        mysqli_query($con, $vou);
        $sqq = "INSERT INTO `radgroupcheck` (`groupname`,`attribute`,`op`,`value`) VALUES('$profile','Max-Data',':=','$value')";
        mysqli_query($con, $sqq);


    }
    //Genarate Voucher
    if (isset($value)) {
        for ($i = 1; $i <= $num; $i++) {
            $user = RandomString(6);
            $us = "INSERT INTO `users` (`supplier_id`,`voucher_name`,`username`,`status`,`expire_date`) VALUES('$admin_id','$profile','$user','FREE','$newDate')";
            mysqli_query($con, $us);
            $usr = "INSERT INTO `radcheck` (username,attribute,op,`value`) VALUES('$user','Cleartext-Password',':=','$user')";
            mysqli_query($con, $usr);
            $gr = "INSERT INTO `radusergroup` (`username`,`groupname`) VALUES('$user','$profile')";
            mysqli_query($con, $gr);
            $sq = "INSERT INTO `radreply` (`username`,`attribute`,`op`,`value`) VALUES('$user','Mikrotik-Xmit-Limit','=','$value')";
            mysqli_query($con, $sq);
            $sqq = "INSERT INTO `radreply` (`username`,`attribute`,`op`,`value`) VALUES('$user','Mikrotik-Recv-Limit','=','$value')";
            mysqli_query($con, $sqq);
            $us = "INSERT INTO `radcheck` (`username`,`attribute`,`op`,`value`) VALUES('$user','Auth-Type',':=','PAP')";
            $usr = mysqli_query($con, $us);
        }
    } else {

        for ($i = 1; $i <= $num; $i++) {
            $user = RandomString(6);
            $us = "INSERT INTO `users` (`supplier_id`,`voucher_name`,`username`,`status`) VALUES('$admin_id','$profile','$user','FREE')";
            mysqli_query($con, $us);
            $usr = "INSERT INTO `radcheck` (`username`,`attribute`,`op`,`value`) VALUES('$user','Cleartext-Password',':=','$user')";
            mysqli_query($con, $usr);
            $gr = "INSERT INTO `radusergroup` (`username`,`groupname`) VALUES('$user','$profile')";
            mysqli_query($con, $gr);
            $us = "INSERT INTO `radcheck` (`username`,`attribute`,`op`,`value`) VALUES('$user','Auth-Type',':=','PAP')";
            $usr = mysqli_query($con, $us);
        }
    }
    $sql1 = "INSERT INTO `radgroupcheck` (`groupname`,`attribute`,`op`,`value`) VALUES('$profile','Simultaneous-Use',':=','1')";
    mysqli_query($con, $sql1);
    $sq = "INSERT INTO `radgroupreply` (`groupname`,`attribute`,`op`,`value`) VALUES('$profile','Framed-Pool','=','radius_pool')";
    mysqli_query($con, $sq);
    $sq1 = "INSERT INTO `radgroupreply` (`groupname`,`attribute`,`op`,`value`) VALUES('$profile','Acct-Interim-Interval','=','300')";
    mysqli_query($con, $sq1);
    if ($usr) {
        $msg = "Voucher Created...";
        $API->log($wh, "Voucher $num created");
    }
}

?>
<?php
if (isset($msg)) {
    echo $msg;
} ?>
<div class="col-sm-5">
    <form data-toggle="validator" role="form" method="POST">
        <div class="form-group">
            <label for="inputName" class="control-label">Package Name</label>
            <input type="text" class="form-control" id="inputVou" name="name" placeholder="Package Name" required>
        </div>
        <div class="form-group">
            <label for="inputName" class="control-label">Total quantity to be generated</label>
            <input type="number" class="form-control" id="inputVou" name="num"
                   placeholder="How many voucher you want to genarate" required>
        </div>
        <div class="form-group">
            <label for="inputName" class="control-label">Financial Value</label>
            <input type="number" class="form-control" name="amount" placeholder="Price for per voucher" required>
        </div>
        <label for="date">Expired On:</label>
        <div class="input-group date form_datetime" data-date-format="dd MM yyyy H:i" data-link-field="dtp_input1">
            <input class="form-control" type="text" required>
            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
            <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
        </div>
        <input type="hidden" id="dtp_input1" name="dead" value=""/><br/>
        <div class="form-group">
            <label for="inputName" class="control-label">Package Type</label>
            <select class="form-control" id="inputType" onchange="Type(this.value)" name="type" required>
                <option></option>
                <option value="1">Duration</option>
                <option value="2">Bandwidth</option>
                <option value="0">Both</option>

            </select>
        </div>
        <div id="type">

        </div>
        <div class="form-group">
            <label for="inputType" class="control-label">Speed</label>
            <input type="text" class="form-control" id="inputVou" name="per" placeholder="Integer Number" required>
        </div>
        <div class="form-group">
            <select class="form-control" id="inputType" name="speed" required>
                <option value="M">MBPS</option>
                <option value="K">KBPS</option>

            </select>
        </div>
        <div class="form-group">
            <button type="submit" name="create" class="btn btn-primary">Create</button>
        </div>
    </form>
</div>
<script type="text/javascript" src="component/datetime/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="component/datetime/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
<script type="text/javascript">
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });
</script>
<script>
    function Type(str) {
        if (str == '1') {
            document.getElementById('type').innerHTML = '<div class="form-group">\n' +
                '            <label for="inputFullname" class="control-label">Duration</label>\n' +
                '            <input type="text" class="form-control"  name="day" placeholder="1,7,12 etc"\n' +
                '                   required>\n' +
                '        </div>\n' +
                '        <div class="form-group">\n' +
                '            <select class="form-control" id="inputType" name="exp" required>\n' +
                '                <option></option>\n' +
                '                <option>Minute</option>\n' +
                '                <option>Hour</option>\n' +
                '                <option>Days</option>\n' +
                '                <option>Month</option>\n' +
                '\n' +
                '            </select>\n' +
                '        </div>';
        } else if (str == '2') {
            document.getElementById('type').innerHTML = '<div class="form-group">\n' +
                '            <label for="inputFullname" class="control-label">Total Internet Data (Bandwidth)</label>\n' +
                '            <input type="text" class="form-control"  name="band" placeholder="1,7,12 etc"\n' +
                '                   required>\n' +
                '        </div>\n' +
                '        <div class="form-group">\n' +
                '            <select class="form-control" id="inputType" name="limit" required>\n' +
                '                <option></option>\n' +
                '                <option>MB</option>\n' +
                '                <option>GB</option>\n' +
                '\n' +
                '            </select>\n' +
                '        </div>';
        } else if (str == '0') {
            document.getElementById('type').innerHTML = '<div class="form-group">\n' +
                '            <label for="inputFullname" class="control-label">Duration</label>\n' +
                '            <input type="text" class="form-control"  name="day" placeholder="1,7,12 etc"\n' +
                '                   required>\n' +
                '        </div>\n' +
                '        <div class="form-group">\n' +
                '            <select class="form-control" id="inputType" name="exp" required>\n' +
                '                <option></option>\n' +
                '                <option>Minute</option>\n' +
                '                <option>Hour</option>\n' +
                '                <option>Days</option>\n' +
                '                <option>Month</option>\n' +
                '\n' +
                '            </select>\n' +
                '        </div><div class="form-group">\n' +
                '            <label for="inputFullname" class="control-label">Total Internet Data (Bandwidth)</label>\n' +
                '            <input type="text" class="form-control"  name="band" placeholder="1,7,12 etc"\n' +
                '                   required>\n' +
                '        </div>\n' +
                '        <div class="form-group">\n' +
                '            <select class="form-control" id="inputType" name="limit" required>\n' +
                '                <option></option>\n' +
                '                <option>MB</option>\n' +
                '                <option>GB</option>\n' +
                '\n' +
                '            </select>\n' +
                '        </div>';
        } else {
            document.getElementById('type').innerHTML = '';
        }
    }
</script>

<?php include 'includes/footer.php'; ?>
