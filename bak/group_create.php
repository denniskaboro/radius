<?php
include 'includes/header.php';
$per = $_SESSION['per'];
if (isset($_POST['create'])) {
    if ($per == "Write" || $per == "Full" || $per == "Admin") {
        $profile = $_POST['package'];
        $price = $_POST['price'];
        $for = $_POST['for'];
        $type = $_POST['type'];
        $dashboard = $_POST['dashboard'];
        $client = $_POST['client'];
        $bandwidth = $_POST['download'];
        $sp = $_POST['speed'];
        $device = $_POST['device'];
        if($device<=1){
            $device=1;
        }
        if ($for == 'Reseller') {
            $supplier_id = $_POST['reseller'];
            $query = "SELECT * FROM `groups` WHERE `groupname`='$profile' and `supplier_id`='$supplier_id'";
            $find = mysqli_query($con, $query);
            $row = mysqli_num_rows($find);
        } else {
            $supplier_id = "Admin";
            $query = "SELECT * FROM `groups` WHERE `groupname`='$profile' and `package_for`='Admin'";
            $find = mysqli_query($con, $query);
            $row = mysqli_num_rows($find);
        }

        if ($row > 0) {
            $msg = "<h4 style='color:#ff6376;'>This Package Already created for " . $for . "</h4>";
        } else {
            if ($sp == 'M') {
                $speed = $bandwidth . 'M';

            } else {
                $speed = $bandwidth . 'K';
            }
            if ($type == '1') {
                $day = $_POST['day'];
                $exp = $_POST['exp'];
                $duration = $day . " " . $exp;
//            if($exp=='Minute'){
//                $newDate=$day * 60;
//            }elseif ($exp=='Hour'){
//                $newDate=$day * 60 *60;
//            }elseif ($exp=='Days'){
//                $newDate=$day * 60 *60 * 24;
//            }else{
//                $newDate=$day * 60 *60 * 24 * 30;
//            }

                $sql = "INSERT INTO `groups` (`groupname`,`price`, `supplier_id`,`package_for`,`duration`,`speed`,`device`,`dashboard`,`client_portal`) VALUES('$profile','$price','$supplier_id','$for','$duration','$speed','$device','$dashboard','$client')";
                $r = mysqli_query($con, $sql);
                $q = "SELECT LAST_INSERT_ID() as i FROM `groups`";
                $s = mysqli_query($con, $q);
                $j = mysqli_fetch_array($s);
                $lastid = $j['i'];
            } else if ($type == '2') {
                $band = $_POST['band'];
                $limit = $_POST['limit'];
                $data = $band . " " . $limit;
                $sql = "INSERT INTO `groups` (`groupname`,`price`,`supplier_id`,`package_for`,`data`,`speed`,`device`,`dashboard`,`client_portal`) VALUES('$profile','$price','$supplier_id','$for','$data','$speed','$device','$dashboard','$client')";
                $r = mysqli_query($con, $sql);
                $q = "SELECT LAST_INSERT_ID() as i FROM groups";
                $s = mysqli_query($con, $q);
                $j = mysqli_fetch_array($s);
                $lastid = $j['i'];

                if ($limit == "MB") {
                    $value = $band * 1024 * 1024;
                    $sq = "INSERT INTO `radgroupreply` (`groupname`,`attribute`,`op`,`value`) VALUES('$lastid','Mikrotik-Xmit-Limit','=','$value')";
                    mysqli_query($con, $sq);
                    $sqq = "INSERT INTO `radgroupreply` (`groupname`,`attribute`,`op`,`value`) VALUES('$lastid','Mikrotik-Recv-Limit','=','$value')";
                    mysqli_query($con, $sqq);
                } else {
                    if ($band > 4) {
                        $value = $band * 1024 * 1024 * 1024;
                        $sq = "INSERT INTO `radgroupreply` (`groupname`,`attribute`,`op`,`value`) VALUES('$lastid','Mikrotik-Xmit-Limit-Gigawords','=','$band')";
                        mysqli_query($con, $sq);
                        $sqq = "INSERT INTO `radgroupreply` (`groupname`,`attribute`,`op`,`value`) VALUES('$lastid','Mikrotik-Recv-Limit-Gigawords','=','$band')";
                        mysqli_query($con, $sqq);
                    } else {
                        $value = $band * 1024 * 1024 * 1024;
                        $sq = "INSERT INTO `radgroupreply` (`groupname`,`attribute`,`op`,`value`) VALUES('$lastid','Mikrotik-Xmit-Limit','=','$value')";
                        mysqli_query($con, $sq);
                        $sqq = "INSERT INTO `radgroupreply` (`groupname`,`attribute`,`op`,`value`) VALUES('$lastid','Mikrotik-Recv-Limit','=','$value')";
                        mysqli_query($con, $sqq);
                    }

                }
                $sqq = "INSERT INTO `radgroupcheck` (`groupname`,`attribute`,`op`,`value`) VALUES('$lastid','Max-Data',':=','$value')";
                mysqli_query($con, $sqq);
            } else if ($type == 3) {
                $day = $_POST['day'];
                $exp = $_POST['exp'];
                $duration = $day . " " . $exp;

                $band = $_POST['band'];
                $limit = $_POST['limit'];
                $data = $band . " " . $limit;
                $sql = "INSERT INTO `groups` (`groupname`,`price`, `supplier_id`,`package_for`,`duration`,`data`,`speed`,`device`,`dashboard`,`client_portal`) VALUES('$profile','$price','$supplier_id','$for','$duration','$data','$speed','$device','$dashboard','$client')";
                $r = mysqli_query($con, $sql);
                $q = "SELECT LAST_INSERT_ID() as i FROM `groups`";
                $s = mysqli_query($con, $q);
                $j = mysqli_fetch_array($s);
                $lastid = $j['i'];

                if ($limit == "MB") {
                    $value = $band * 1024 * 1024;
                    $sq = "INSERT INTO `radgroupreply` (`groupname`,`attribute`,`op`,`value`) VALUES('$lastid','Mikrotik-Xmit-Limit','=','$value')";
                    mysqli_query($con, $sq);
                    $sqq = "INSERT INTO `radgroupreply` (`groupname`,`attribute`,`op`,`value`) VALUES('$lastid','Mikrotik-Recv-Limit','=','$value')";
                    mysqli_query($con, $sqq);
                } else {
                    if ($band > 4) {
                        $value = $band * 1024 * 1024 * 1024;
                        $sq = "INSERT INTO `radgroupreply` (`groupname`,`attribute`,`op`,`value`) VALUES('$lastid','Mikrotik-Xmit-Limit-Gigawords','=','$band')";
                        mysqli_query($con, $sq);
                        $sqq = "INSERT INTO `radgroupreply` (`groupname`,`attribute`,`op`,`value`) VALUES('$lastid','Mikrotik-Recv-Limit-Gigawords','=','$band')";
                        mysqli_query($con, $sqq);
                    } else {
                        $value = $band * 1024 * 1024 * 1024;
                        $sq = "INSERT INTO `radgroupreply` (`groupname`,`attribute`,`op`,`value`) VALUES('$lastid','Mikrotik-Xmit-Limit','=','$value')";
                        mysqli_query($con, $sq);
                        $sqq = "INSERT INTO `radgroupreply` (`groupname`,`attribute`,`op`,`value`) VALUES('$lastid','Mikrotik-Recv-Limit','=','$value')";
                        mysqli_query($con, $sqq);
                    }

                }

                $sqq = "INSERT INTO `radgroupcheck` (`groupname`,`attribute`,`op`,`value`) VALUES('$lastid','Max-Data',':=','$value')";
                mysqli_query($con, $sqq);

            } else {
                $sql = "INSERT INTO `groups` (`groupname`,`price`, `supplier_id`,`package_for`,`duration`,`data`,`speed`,`device`,`dashboard`,`client_portal`) VALUES('$profile','$price','$supplier_id','$for','Unlimited','Unlimited','$speed','$device','$dashboard','$client')";
                $r = mysqli_query($con, $sql);
                $q = "SELECT LAST_INSERT_ID() as i FROM groups";
                $s = mysqli_query($con, $q);
                $j = mysqli_fetch_array($s);
                $lastid = $j['i'];
            }

            $sq = "INSERT INTO `radgroupreply` (`groupname`,`attribute`,`op`,`value`) VALUES('$lastid','Mikrotik-Rate-Limit','=','$speed/$speed')";
            mysqli_query($con, $sq);

            $sq1 = "INSERT INTO `radgroupreply` (`groupname`,`attribute`,`op`,`value`) VALUES('$lastid','Acct-Interim-Interval','=','300')";
            mysqli_query($con, $sq1);

            $sql1 = "INSERT INTO `radgroupcheck` (`groupname`,`attribute`,`op`,`value`) VALUES('$lastid','Simultaneous-Use',':=','$device')";
            mysqli_query($con, $sql1);
            $sq = "INSERT INTO `radgroupreply` (`groupname`,`attribute`,`op`,`value`) VALUES('$lastid','Framed-Pool','=','radius_pool')";
            mysqli_query($con, $sq);
            if ($r) {
                $msg = "<h4 style='color:#50ee44;'>New Package Created for " . $for . "</h4>";
                $API->log($wh, "Package " . $lastid . $profile ." Created for " . $for);
            }
        }

    } else {
        echo "<h5 style='color: coral ;'>You have no permission...</h5>";
    }
}
?>

<div class="row">
    <!-- Pie Chart -->

    <div class="col-xl-6 col-lg-6" id="design" data-aos="fade-down">
        <!-- Basic Card Example -->
        <div class="card shadow mb-4 text-gray-900" style="margin-left: 2%; padding: 30px;">
            <?php if (isset($msg)) {
                echo $msg;
            } ?>
            <div class="card-header py-3">
                <h4 class="m-0 font-weight-bold text-secondary">Create New Package</h4>
                <hr>
            </div>
            <div class="card-body">
                <form data-toggle="validator" role="form" method="POST">
                    <div class="form-group">
                        <label for="inputName" class="control-label">Package Name</label>
                        <input type="text" class="form-control" id="inputName" name="package" placeholder="Package Name"
                               required>
                    </div>
                    <div class="form-group">
                        <label for="inputName" class="control-label">Package Price</label>
                        <input type="text" class="form-control" id="inputName" name="price" placeholder="Package Price"
                               required>
                    </div>
                    <div class="form-group">
                        <label for="inputName" class="control-label">Package For</label>
                        <select class="form-control" name="for" onchange="For(this.value)" required>
                            <option></option>
                            <option>Admin</option>
                            <option>Reseller</option>
                        </select>
                    </div>
                    <div id="sup_pack">
                    </div>

                    <div class="form-group">
                        <label for="inputName" class="control-label">Package Type</label>
                        <select class="form-control" id="inputType" onchange="Type(this.value)" name="type" required>
                            <option></option>
                            <option>None</option>
                            <option value="1">Duration</option>
                            <option value="2">Bandwidth</option>
                            <option value="3">Duration + Bandwidth</option>

                        </select>
                    </div>
                    <div id="type">

                    </div>
                    <div class="form-group">
                        <label for="inputType" class="control-label">Allowed Device</label>
                        <input type="number" class="form-control" id="inputVou" name="device"
                               placeholder="Hou many device allow for one username" required>
                    </div>
                    <div class="form-group">
                        <label for="inputType" class="control-label">Speed</label>
                        <input type="text" class="form-control" id="inputVou" name="download"
                               placeholder="Integer Number" required>
                    </div>
                    <div class="form-group">
                        <select class="form-control" id="inputType" name="speed" required>
                            <option value="M">Mbps</option>
                            <option value="K">Kbps</option>

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="inputType" class="control-label">Show On Dashboard</label>
                        <select class="form-control" id="inputType" name="dashboard" required>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="inputType" class="control-label">Show On Client Portal</label>
                        <select class="form-control" id="inputType" name="client" required>
                            <option value="1">Yes</option>
                            <option value="0">No</option>

                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="create" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>


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
                '                <option>Year</option>\n' +
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
        } else if (str == '3') {
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
                '                <option>Year</option>\n' +
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

        }
    }
</script>

<script>
    function For(val) {
        if (val == 'Reseller') {
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
            xmlhttp.open("GET", "disable.php?for", true);
            xmlhttp.send();
        } else {
            document.getElementById("sup_pack").innerHTML = "";
        }
    }
</script>

<?php include 'includes/footer.php'; ?>
