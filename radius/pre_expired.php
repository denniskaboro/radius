<?php
include 'includes/header.php';
include('config/data.php');
$per = $_SESSION['per'];


?>
<div id="last"></div>

<h3>User List:</h3>

<table class="table table-bordered" id="btrc">

    <thead>
    <tr class="btn-success" style="background-color: rgba(21,53,74,0.75); color: #ceefde; font-size:16px;">
        <th>User Name</th>
        <th>Package</th>
        <th>Create Date</th>
        <th>Expiration</th><?php if ($dept != "Visitor") { ?>
            <th>Supervisor</th><?php } ?>
        <th>Address</th>
        <th>Created By</th>
        <th>Edit</th>
        <?php if ($per == "Full" || $per == "Write" || $per == "Admin") { ?>
            <th>Set Attribute</th>
            <th>Delete</th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
    <?php
    $band = 0;
    $sql = "SELECT * FROM clients ";
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    while ($f = mysqli_fetch_array($r)) {
        $name = $f['username'];
        $sup = $f['supplier_id'];
        $sql1 = "SELECT * FROM radcheck WHERE attribute='Expiration' && username='$name'";
        $rs = mysqli_query($con, $sql1) or $msg = mysqli_error($con);
        $p = mysqli_fetch_array($rs);
        $expi = $p['value'];
        $username = $p['username'];
        $current = date("Y m d H:i", strtotime("-1 month"));
        $d = strtotime($expi);
        $expiration = date("Y m d H:i", $d);
        $dateTime = new DateTime();
        $date = $dateTime->format('Y m d H:i');
        if ($expiration < $current) {

            ?>
            <tr style="background-color: rgba(89,177,159,0.21); color: #ffffff; font-size:16px;">

            <td><a style=" color: #e2b9db;" href="user_statistics.php?name=<?php echo $f['username']; ?>"
                   role="button" style="color: #ffffff;font-size:16px;"><?php echo $f['username']; ?></a>
            </td>
            <?php
            $sql2 = "SELECT groups.groupname FROM groups INNER JOIN radusergroup ON radusergroup.groupname=groups.id WHERE username='$name'";
            $rp = mysqli_query($con, $sql2) or $msg = mysqli_error($con);
            $q = mysqli_fetch_array($rp);
            $pro = $q['groupname'];
            $cx = strpos($pro, ' M');
            if ($cx) {
                $local = substr($pro, 0, $cx);
                $band = $local + $band;
            }
            ?>
            <td><?php echo $pro; ?></td>
            <td><?php echo $f['create_date'];; ?></td>
            <td>
                <?php echo $expi; ?>
                <a class="btn btn-primary"
                   href="extend.php?user=<?php echo $name; ?>&id=<?php echo $sup; ?>">Renew</a></td>
            <?php if ($dept != "Visitor") { ?>
                <td><?php
                $sql = "SELECT * FROM supplier WHERE supplier_id='$sup'";
                $rs = mysqli_query($con, $sql) or $msg = mysqli_error($con);
                $g = mysqli_fetch_array($rs);
                echo $g['full_name']; ?></td><?php } ?>
            <td><?php echo $f['address']; ?></td>
            <td><?php echo $f['reference']; ?></td>

            <td><a class="btn" href="user_edit.php?id=<?php echo $f['id']; ?>" style="color: #ffffff;">
                    <i class="icon-edit"><span class="glyphicon glyphicon-pencil"></span></i></a></td>
            <?php if ($per == "Full" || $per == "Write" || $per == "Admin") { ?>
                <td><a class="btn" href="user_attribute.php?val=<?php echo $f['username']; ?>"
                       style="color: #ffffff;">
                        <i class="icon-edit"><span class="glyphicon glyphicon-cog"></span></i></a></td>

                <td>
                    <button class="btn btn-danger" onclick="myFunction(this.id)" id="<?php echo $name; ?>">
                        <i class="icon-edit"><span class="glyphicon glyphicon-trash"></span></i></button>
                </td>
            <?php }
        } ?>
        </tr>

        <?php
    }
    echo "<h4> Total Bandwidth: " . $band . " Mbps</h4>"; ?>


    </tbody>

</table>
<script>
    function myFunction(str) {
        var r = confirm("Confirm to Delete " + str + "?");
        if (r == true) {
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
                    document.getElementById("last").innerHTML = this.responseText;
                }
            }
            xmlhttp.open("GET", "disable.php?usrdel=" + str, true);
            xmlhttp.send();
        }
    }
</script>
<script>
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
                document.getElementById("last").innerHTML = this.responseText;
            }
        }
        xmlhttp.open("GET", "disable.php?st=reject&value=" + str, true);
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
                document.getElementById("last").innerHTML = this.responseText;
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

<!-- end of col-lg-10 -->
<?php include 'includes/footer.php'; ?>
