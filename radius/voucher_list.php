<?php
include 'includes/header.php';
include('config/data.php');
$per = $_SESSION['per'];

//For Delete Attribute
if (isset($_GET['val'])) {
    $groupname = $_GET['val'];
    if ($per == "Full" || $per == "Admin") {
        $groupname = $_GET['val'];
        $sqlp = "SELECT * FROM radusergroup WHERE groupname='$groupname'";
        $resul = mysqli_query($con, $sqlp);
        while ($fi = mysqli_fetch_array($resul)) {
            $pro = $fi['username'];
            $tabb = ["users", "radcheck", "radacct", "radreply"];
            foreach ($tabb as $item) {
                $sq = "DELETE FROM $item WHERE username='$pro'";
                $res = mysqli_query($con, $sq);
            }

            $tab = ["radgroupcheck", "radgroupreply", "radusergroup"];
            foreach ($tab as $table) {
                $sql = "DELETE FROM $table WHERE groupname='$groupname'  ";
                $res = mysqli_query($con, $sql);

            }
            $sqll = "DELETE FROM voucher WHERE voucher_name='$groupname'";
            $res = mysqli_query($con, $sqll);
            $msg = "Package Delete Successfully...<br>";
            $API->log($wh, "Package " . $groupname . " Deleted");
        }
    } else {
        $msg = "You have no Permission for Delete this Item...<br>";
    }
}
?>
<div class="col-lg-12 ">
    <h3>Voucher List</h3>
    <?php if (isset($msg)) {
        echo $msg;
    } ?>
    <div style="float: right;"><a href="voucher_create.php" class="btn btn-info btn-lg">
            <span class="glyphicon glyphicon-plus"></span> Add
        </a></div>
    <table class="table table-bordered" id="btrc">
        <thead>
        <tr class="btn-primary" style="background-color: rgba(21,53,74,0.75); color: #ceefde; font-size:16px;">
            <th>Package Name</th>
            <th>Voucher Type</th>
            <th>Total User</th>
            <th>Speed (Per Sec.)</th>
            <th>Duration</th>
            <th>Bandwidth</th>
            <th>Expiration</th>
            <th>Create Date</th>
            <th>Export</th>
            <th>Enable/Disable</th>
            <?php if ($per == "Full" || $per == "Write" || $per == "Admin") { ?>
                <th>Delete</th>
            <?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT * FROM voucher WHERE supplier_id='Admin' ORDER BY id DESC";
        $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
        while ($f = mysqli_fetch_array($r)) {
            $name = $f['voucher_name']; ?>
            <tr>

                <td><?php echo $f['package_name']; ?></td>
                <td><?php echo $f['voucher_type']; ?></td>
                <td><a style=" color: #1ce2f0;" href="users.php?name=<?php echo $f['voucher_name']; ?>"
                       role="button"><?php
                        $sql = "SELECT * FROM radusergroup WHERE groupname='$name'";
                        $re = mysqli_query($con, $sql) or $msg = mysqli_error($con);
                        $cou = mysqli_num_rows($re);
                        echo $cou; ?></a></td>
                <td><?php echo $f['mbps']; ?>
                <td><?php echo $f['expiration']; ?></td>
                <td><?php echo $f['total_limit']; ?></td>
                <td><?php echo $f['deadline']; ?></td>
                <td><?php echo $f['create_date']; ?></td>
                <?php $sql = "SELECT * FROM radgroupcheck WHERE attribute='Auth-Type' && groupname='$name'";
                $rj = mysqli_query($con, $sql) or $msg = mysqli_error($con);
                $ac = mysqli_fetch_array($rj);
                $type = $ac['value'];
                ?>
                <td><a style=" color: #dff0d8;" href="voucher_export.php?name=<?php echo $f['voucher_name']; ?>"
                       class="btn btn-warning"><?php echo 'Print'; ?></a></td>
                <td>
                    <button class="btn btn-success" id="enable<?php echo $f['voucher_name']; ?>"
                            value="<?php echo $f['voucher_name']; ?>" <?php if ($type == "PAP" || empty($type)) {
                        echo "disabled=\"true\"";
                    } ?> onclick="enableBtn(this.value)">
                        <span class="glyphicon glyphicon-ok"></span></button>
                    <button class="btn btn-danger" id="disable<?php echo $f['voucher_name']; ?>"
                            value="<?php echo $f['voucher_name']; ?>" <?php if ($type == "reject") {
                        echo "disabled=\"true\"";
                    } ?> onclick="disableBtn(this.value)">
                        <span class="glyphicon glyphicon-off"></span></button>
                </td>
                <?php if ($per == "Full" || $per == "Write" || $per == "Admin") { ?>
                    <td><a class="btn btn-danger" onclick="myFunction('<?php echo $name; ?>')" id="<?php echo $name; ?>"
                           href="group_list.php">
                            <i class="icon-edit"><span class="glyphicon glyphicon-trash"></span></i></a></td>
                <?php } ?>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<script>
    function myFunction(str) {
        var r = confirm("Confirm to Delete?");
        if (r == true) {
            document.getElementById(str).href = "voucher_list.php?val=" + str;
        } else {
            document.getElementById(str).href = "voucher_list.php";
        }
    }

    function disableBtn(str) {
        document.getElementById("disable" + str).disabled = true;
        document.getElementById("enable" + str).disabled = false;
        if (str == "") {
            document.getElementById("last").innerHTML = "";
            return;
        }
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                alert('All user has been disabled under this group');
            }
        }
        xmlhttp.open("GET", "disable.php?st=reject&group=" + str, true);
        xmlhttp.send();
    }
</script>
<script>
    function enableBtn(str) {
        document.getElementById("disable" + str).disabled = false;
        document.getElementById("enable" + str).disabled = true;
        if (str == "") {
            document.getElementById("last").innerHTML = "";
            return;
        }
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                alert('All user has been enable under this group');
            }
        }
        xmlhttp.open("GET", "disable.php?st=PAP&group=" + str, true);
        xmlhttp.send();
    }
</script>
<link rel="stylesheet" type="text/css" href="component/jquery.dataTables.min.css"/>
<script type="text/javascript" src="component/js/export/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#btrc').DataTable({
            pageLength: 15,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
        });

    });
</script>

<?php include 'includes/footer.php'; ?>
