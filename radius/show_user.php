<?php
include 'includes/header.php';
include('config/data.php');
$per = $_SESSION['per'];
//For Delete Attribute
if (isset($_GET['del'])) {
    $id = $_GET['del'];
    if ($per == "Admin") {
        $sq = "DELETE FROM admin WHERE id='$id'  ";
        $res = mysqli_query($con, $sq);
        echo "User Delete Successfully...";
    } else if ($per == "Full") {
        $sql = "SELECT * FROM admin WHERE id='$id'";
        $res = mysqli_query($con, $sql);
        $f = mysqli_fetch_array($res);
        $permission = $f['permission'];
        if ($permission != 'Admin') {
            $sq = "DELETE FROM admin WHERE id='$id'  ";
            $res = mysqli_query($con, $sq);
            echo "User Delete Successfully...";
        }
    }
}
if ($per == "Full" || $per == "Admin") {
    ?>
    <table class="table table-bordered" id="btrc">
        <h4>Al User List:</h4>
        <thead>
        <tr>
            <th>Full Name</th>
            <th>Username</th>
            <th>Email</th><?php if ($per == "Admin") { ?>
                <th>Permission</th><?php } ?>
            <th>Dept.</th>
            <th>Enable/Disable</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody>
        <?php

        $sql = "SELECT * FROM admin ";
        $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
        while ($f = mysqli_fetch_array($r)) {
            $type = $f['status'];
            $idd = $f['id'];
            ?>
            <tr>
                <td><?php echo $f['full_name']; ?></td>
                <td><?php echo $f['username']; ?></td>
                <td><?php echo $f['email']; ?></td><?php if ($per == "Admin") { ?>
                    <td><?php echo $f['permission']; ?></td><?php } ?>
                <td><?php echo $f['Dept']; ?></td>
                <td>
                    <button class="btn btn-success" id="enable<?php echo $f['id']; ?>"
                        <?php if ($type == "enable") {
                            echo "disabled=\"true\"";
                        } ?> onclick="enableBtn('<?php echo $idd; ?>','enable')">
                        <span class="glyphicon glyphicon-ok"></span></button>
                    <button class="btn btn-danger" id="disable<?php echo $f['id']; ?>"
                        <?php if ($type == "disable") {
                            echo "disabled=\"true\"";
                        } ?> onclick="disableBtn('<?php echo $idd; ?>','disable')">
                        <span class="glyphicon glyphicon-off"></span></button>
                </td>
                <td><a class="btn btn-danger" onclick="myFunction(this.id)" id="<?php echo $f['id']; ?>"
                       href="show_user.php">
                        <i class="icon-edit"><span class="glyphicon glyphicon-trash"></span></i></a></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <script type="text/javascript">
        function myFunction(str) {
            var r = confirm("Confirm to do this?");
            if (r == true) {
                document.getElementById(str).href = "show_user.php?del=" + str;
            } else {
                document.getElementById(str).href = "show_user.php";
            }
        }

    </script>

    <script>
        function enableBtn(id, val) {
            document.getElementById("disable" + id).disabled = false;
            document.getElementById("enable" + id).disabled = true;
            if (id == "") {
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
                    alert(this.responseText);
                }
            }
            xmlhttp.open("GET", "disable.php?admin_id=" + id + "&val=" + val, true);
            xmlhttp.send();
        }
    </script>
    <script>
        function disableBtn(id, val) {
            document.getElementById("disable" + id).disabled = true;
            document.getElementById("enable" + id).disabled = false;
            if (id == "") {
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
                    alert(this.responseText);
                }
            }
            xmlhttp.open("GET", "disable.php?admin_id=" + id + "&val=" + val, true);
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
<?php } else {
    echo "<h4 style='color: coral ;'>You have no permission...</h4>";
}
include 'includes/footer.php'; ?>
