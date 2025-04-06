<?php
include 'includes/header.php';
include('config/data.php');
$per = $_SESSION['per'];

//For Delete Attribute
if (isset($_GET['val'])) {
    $pro = $_GET['val'];
    if ($per == "Full" || $per == "Admin") {
        $sq = "DELETE FROM radcheck WHERE `username`='$pro'";
        $res = mysqli_query($con, $sq);
        $sq = "DELETE FROM radreply WHERE `username`='$pro'";
        $res = mysqli_query($con, $sq);
        $sq = "DELETE FROM radacct WHERE `username`='$pro'";
        $res = mysqli_query($con, $sq);
        $sq = "DELETE FROM vpn WHERE `username`='$pro'";
        $res = mysqli_query($con, $sq);

        $msg = "VPN user has been deleted Successfully.<br>";
        $API->log($wh, "VPN " . $pro . "has been deleted");
    } else {
        $msg = "You have no Permission for Delete this Item...<br>";
    }
}
?>
<div class="col-lg-12 ">
    <h3>VPN Lists</h3>
    <?php if (isset($msg)) {
        echo $msg;
    } ?>
    <table class="table table-bordered" id="btrc">
        <thead>
        <tr class="btn-primary" style="background-color: rgba(21,53,74,0.75); color: #ceefde; font-size:16px;">
            <th>UserName</th>
            <th>Password</th>
            <th>Duration</th>
            <th>Enable/Disable</th>
            <th>Set Attribute</th>
            <?php if ($per == "Full" || $per == "Admin") { ?>
                <th>Delete</th>
            <?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT * FROM vpn_user ORDER BY id DESC";
        $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
        while ($f = mysqli_fetch_array($r)) {
            $name = $f['id'];
            $username = $f['username'];
            ?>
            <tr>

                <td><?php echo $username; ?></td>
                <td><?php echo $f['password']; ?></td>
                <td><?php echo $f['duration']; ?></td>
                <td>
                    <button class="btn btn-success" id="enable<?php echo $username; ?>"
                            value="<?php echo $username; ?>"
                        <?php if ($f['status'] == "1") {
                            echo "disabled=\"true\"";
                        } ?> onclick="enableBtn(this.value)">
                        <span class="glyphicon glyphicon-ok"></span></button>
                    <button class="btn btn-danger" id="disable<?php echo $username; ?>"
                            value="<?php echo $username; ?>"
                        <?php if ($f['status'] == "0") {
                            echo "disabled=\"true\"";
                        } ?> onclick="disableBtn(this.value)">
                        <span class="glyphicon glyphicon-off"></span></button>
                </td>

                <td><a class="btn" href="user_attribute.php?val=<?php echo $username; ?>">
                        <i class="icon-edit"><span class="glyphicon glyphicon-cog"></span></i></a></td>
                <?php if ($per == "Full" || $per == "Write" || $per == "Admin") { ?>
                    <td><a class="btn btn-danger" onclick="myFunction('<?php echo $username; ?>')"
                           href="vpn_list.php">
                            <i class="icon-edit"><span class="glyphicon glyphicon-trash"></span></i></a></td>
                <?php } ?>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<script>
    function myFunction(str) {
        var r = confirm("Be Carefull !. All User will be deleted under this group.");
        if (r == true) {
            document.getElementById(str).href = "vpn_list.php?val=" + str;
        } else {
            document.getElementById(str).href = "vpn_list.php";
        }
    }

    function disableBtn(str) {
        document.getElementById("disable" + str).disabled = true;
        document.getElementById("enable" + str).disabled = false;
        if (str == "") {
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
                alert(this.responseText);
            }
        }
        xmlhttp.open("GET", "disable.php?st=reject&value=" + str, true);
        xmlhttp.send();
    }

    function enableBtn(str) {
        document.getElementById("disable" + str).disabled = false;
        document.getElementById("enable" + str).disabled = true;
        if (str == "") {
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
                alert(this.responseText);
            }
        }
        xmlhttp.open("GET", "disable.php?st=PAP&value=" + str, true);
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
