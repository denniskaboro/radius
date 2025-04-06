<?php
include 'includes/header.php';
include('config/data.php');
$per = $_SESSION['per'];

if (isset($_POST['grp'])) {
    if ($per == "Full" || $per == "Admin") {
        $group = $_POST['group'];
        $supplier = $_POST['supplier'];
        $price = $_POST['price'];
        $duration = $_POST['duration'];
        $data = $_POST['data'];
        $speed = $_POST['speed'];
        $gid = $_POST['gid'];
        $sql1 = "UPDATE groups SET `price`='$price',`duration`='$duration',`data`='$data', `speed`='$speed', `groupname`='$group' WHERE  id='$gid'";
        mysqli_query($con, $sql1);
        $API->log($wh, "Package " . $gid . " Updated");

    } else {
        echo "<h4 style='color: coral ;'>You have no permission...</h4>";
    }
}
//For Delete Attribute
if (isset($_GET['val'])) {
    $groupname = $_GET['val'];
    if ($per == "Full" || $per == "Admin") {
        $sqlp = "SELECT * FROM radusergroup WHERE id='$groupname'";
        $resul = mysqli_query($con, $sqlp);
        while ($fi = mysqli_fetch_array($resul)) {
            $pro = $fi['username'];
            $sq = "DELETE FROM radcheck WHERE `username`='$pro'";
            $res = mysqli_query($con, $sq);
            $sq = "DELETE FROM radreply WHERE `username`='$pro'";
            $res = mysqli_query($con, $sq);
            $sq = "DELETE FROM radacct WHERE `username`='$pro'";
            $res = mysqli_query($con, $sq);
        }

        $tab = ["radgroupcheck", "radgroupreply", "radusergroup"];
        foreach ($tab as $table) {
            $sql = "DELETE FROM $table WHERE groupname='$groupname'";
            $res = mysqli_query($con, $sql);

        }
        $sql = "DELETE FROM groups WHERE id='$groupname'";
        $res = mysqli_query($con, $sql);
        $msg = "Package Delete Successfully...<br>";
        $API->log($wh, "Package " . $groupname . " Deleted");
    } else {
        $msg = "You have no Permission for Delete this Item...<br>";
    }
}
?>
<div class="col-lg-12 ">
    <h3>Package List</h3>
    <?php if (isset($msg)) {
        echo $msg;
    } ?>
    <table class="table table-bordered" id="btrc">
        <thead>
        <tr class="btn-primary" style="background-color: rgba(21,53,74,0.75); color: #ceefde; font-size:16px;">
            <th>Package Name</th>
            <th>Total User This Package</th>
            <th>Duration</th>
            <th>Bandwidth</th>
            <th>Speed</th>
            <th>Customer Price</th>
            <th>Edit Package</th>
            <th>Show/Hide Dashboard</th>
            <th>Show/Hide Client</th>
            <th>Enable/Disable</th>
            <th>Set Attribute</th>
            <?php if ($per == "Full" || $per == "Admin") { ?>
                <th>Delete</th>
            <?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT * FROM groups ORDER BY price DESC";
        $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
        while ($f = mysqli_fetch_array($r)) {
            $name = $f['id'];
            ?>
            <tr>

                <td><a style=" color: #dff0d8;" href="group_statistics.php?name=<?php echo $f['id']; ?>"
                       role="button"><?php echo $f['groupname']; ?></a></td>
                <td><?php
                    $sql = "SELECT * FROM radusergroup WHERE groupname='$name'";
                    $re = mysqli_query($con, $sql) or $msg = mysqli_error($con);
                    $cou = mysqli_num_rows($re);
                    echo $cou; ?></td>
                <td><?php echo $f['duration']; ?></td>
                <td><?php echo $f['data']; ?></td>
                <td><?php echo $f['speed']; ?></td>
                <td><?php echo $f['price']; ?></td>
                <td><a class="btn" href="edit.php?val=groups&id=<?php echo $f['id']; ?>">
                        <i class="icon-edit"><span class="glyphicon glyphicon-pencil"></span></i></a></td>
                <td>
                    <div style="float:left; padding:5px;" id='dashboard<?php echo $name; ?>'>
                        <?php if ($f['dashboard'] == "1") {
                            echo "show";
                        } else {
                            echo "hide";
                        } ?>
                    </div>
                    <button class="btn-danger" onclick="SendData('0','dashboard','<?php echo $name; ?>')">Hide</button>
                    <button class="btn-success" onclick="SendData('1','dashboard','<?php echo $name; ?>')">Show</button>
                </td>
                <td>
                    <div style="float:left; padding:5px;" id='client_portal<?php echo $name; ?>'>
                        <?php if ($f['client_portal'] == "1") {
                            echo "show";
                        } else {
                            echo "hide";
                        } ?>
                    </div>
                    <button class="btn-danger" onclick="SendData('0','client_portal','<?php echo $name; ?>')">Hide
                    </button>
                    <button class="btn-success" onclick="SendData('1','client_portal','<?php echo $name; ?>')">Show
                    </button>
                </td>
                <td>
                    <button class="btn btn-success" id="enable<?php echo $f['id']; ?>"
                            value="<?php echo $f['id']; ?>"
                        <?php if ($f['status'] == "1") {
                            echo "disabled=\"true\"";
                        } ?> onclick="enableBtn(this.value)">
                        <span class="glyphicon glyphicon-ok"></span></button>
                    <button class="btn btn-danger" id="disable<?php echo $f['id']; ?>"
                            value="<?php echo $f['id']; ?>"
                        <?php if ($f['status'] == "0") {
                            echo "disabled=\"true\"";
                        } ?> onclick="disableBtn(this.value)">
                        <span class="glyphicon glyphicon-off"></span></button>
                </td>

                <td><a class="btn" href="add_attribute.php?val=<?php echo $f['id']; ?>">
                        <i class="icon-edit"><span class="glyphicon glyphicon-cog"></span></i></a></td>
                <?php if ($per == "Full" || $per == "Write" || $per == "Admin") { ?>
                    <td><a class="btn btn-danger" onclick="myFunction(this.id)" id="<?php echo $name; ?>"
                           href="group_list.php">
                            <i class="icon-edit"><span class="glyphicon glyphicon-trash"></span></i></a></td>
                <?php } ?>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<script>
    function SendData(str, clmn, gid) {
        if (str == 1) {
            val = "show";
        } else {
            val = "hide";
        }
        document.getElementById(clmn + gid).innerHTML = val;
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
        xmlhttp.open("GET", "disable.php?show=" + str + "&grpid=" + gid + "&column=" + clmn, true);
        xmlhttp.send();

    }

    function myFunction(str) {
        var r = confirm("Be Carefull !. All User will be deleted under this group.");
        if (r == true) {
            document.getElementById(str).href = "group_list.php?val=" + str;
        } else {
            document.getElementById(str).href = "group_list.php";
        }
    }

    function disableBtn(str) {
        document.getElementById("disable" + str).disabled = true;
        document.getElementById("enable" + str).disabled = false;
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
        xmlhttp.open("GET", "disable.php?group=reject&gid=" + str, true);
        xmlhttp.send();
    }

    function enableBtn(str) {
        document.getElementById("disable" + str).disabled = false;
        document.getElementById("enable" + str).disabled = true;

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
        xmlhttp.open("GET", "disable.php?group=PAP&gid=" + str, true);
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
