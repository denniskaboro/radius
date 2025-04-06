<?php

include 'includes/header.php';
include "config/data.php";
if (isset($_GET['id'])) {
    $supplier = $_GET['id'];
    $user = $_GET['user'];
    $sql = "SELECT groups.groupname, groups.id FROM groups INNER JOIN radusergroup ON radusergroup.groupname=groups.id WHERE username='$user'";
    $r = mysqli_query($con, $sql);
    $result = mysqli_fetch_array($r);
    $pack = $result['groupname'];
    $pack_id = $result['id'];
    $sql = "SELECT * FROM radcheck WHERE attribute='Expiration' && username='$user'";
    $rs = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    $p = mysqli_fetch_array($rs);
    $exp = $p['value'];
}
?>
<link href="component/datetime/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<div id="last">

</div>
<div class="col-sm-5">
    <form method="POST" data-toggle="validator" role="form">
        <div class="form-group col-sm-8 ">

            <label for="ip">Username:</label>
            <input type="text" class="form-control" id="name" value="<?php echo $user; ?>" readonly>

            <label for="pack">Existing Package:</label>
            <input type="text" class="form-control" id="pack_name" name="pack_name" value="<?php echo $pack; ?>"
                   readonly required>
            <input type="hidden" class="form-control" id="pack_id" name="pack" value="<?php echo $pack_id; ?>" readonly
                   required>

            <label for="Package">Set New Package:</label>
            <select class="form-control" onchange="showAttr(this.value)" name="new_pack">
                <option>None</option>
                <?php
                if (isset($_GET['id'])) {
                    $uname = $_GET['user'];
                    $id = $_GET['id'];
                    if ($id == 'Admin') {
                        $sql = "SELECT * FROM `groups` WHERE `package_for`='Admin'";

                    } else {
                        $sql = "SELECT * FROM `groups` WHERE `supplier_id`='$id' of `supplier_id`='COMMON'";
                    }

                    $gr = mysqli_query($con, $sql);
                    while ($res = mysqli_fetch_array($gr)) {
                        $name = $res['groupname'];
                        $pack_id = $res['id'] . "/" . $name;
                        echo "<option value='{$pack_id}'>" . $name . "</option>";
                    }
                }
                ?>
            </select>

            <label for="date">Expiration Date:</label>
            <input type="text" class="form-control" id="old" value="<?php echo $exp;
            $today = time();
            $pre = date(strtotime($exp));
            if ($pre > $today) {
                $newDate = date("d M Y H:i", strtotime($exp . " +30 days"));
            } else {
                $newDate = date("d M Y 23:59:00", strtotime("+30 days"));
            } ?>" disabled>
            <label for="date">New Expiration Date:</label>

            <div class="input-group date form_datetime" data-date-format="dd MM yyyy H:i" data-link-field="dtp_input1">
                <input class="form-control" type="text" id="expiry" value="<?php echo $newDate; ?>" required>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
            </div>
            <input type="hidden" id="dtp_input1" name="expiry" value="<?php echo $newDate; ?>"/><br/>
            <label>Supplier ID:</label>
            <input type="text" class="form-control" id="sup_id" value="<?php echo $supplier; ?>" readonly>
            <br>
            <button id="sub_btn" <?php $dis = date(strtotime("+5 days"));
            if ($pre > $dis) {
                echo "disabled";
            } ?> onclick="ExtentFun()" class="btn btn-success">Renew
            </button>
        </div>
    </form>
</div>
<script type="text/javascript">
    function showAttr(str) {
        if (str == "None") {
            var name = '<?php echo $pack; ?>';
            var id = '<?php echo $pack_id; ?>';
        } else {
            var i = str.search("/");
            var name = str.substr(i + 1);
            var id = str.substr(0, i);
        }
        document.getElementById("pack_name").value = name;
        document.getElementById("pack_id").value = id;
    }
</script>
<script type="text/javascript">
    function ExtentFun() {
        var r = confirm("Money will be cutting for this User. Confirm to Create ?");
        if (r == true) {
            $('#sub_btn').attr("disabled", true);
            var name = document.getElementById("name").value;
            var pack = document.getElementById("pack_id").value;
            var expiry = document.getElementById("expiry").value;
            var sup_id = document.getElementById("sup_id").value;
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else { // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("last").innerHTML += this.responseText;
                }
            }
            xmlhttp.open("GET", "disable.php?name=" + name + "&pack=" + pack + "&expiry=" + expiry + "&sup_id=" + sup_id, true);
            xmlhttp.send();
        } else {
            document.getElementById("last").innerHTML = "";
        }

    }
</script>

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

<!-- end of col-lg-10 -->
<?php include 'includes/footer.php'; ?>
