<?php
include('includes/header.php');
include('config/data.php');
//For Delete Attribute
if (isset($_GET['val'])) {
    if ($per == "Full" || $per == "Admin") {
        $name = $_GET['val'];
        $table = ["radcheck", "radreply", "radacct", "clients", "radusergroup"];
        foreach ($table as $tab) {
            $sq = "DELETE FROM $tab WHERE username='$name'  ";
            $res = mysqli_query($con,$sq);
        }
    } else {
        echo "<h4 style='color: coral ;'>You have no permission...</h4>";
    }
}

?>

<h3>Deactivate User</h3>
<table class="table table-bordered" id="btrc">
    <thead>
    <tr>
        <th>Name</th>
        <th>Username</th>
        <th>Mobile</th>
        <th>Address</th>
        <th>Package</th>
        <th>Expiration</th><?php if ($dept !="Visitor") { ?>
	    <th>Supervisor ID</th><?php }?>
        <th>Activation Date</th>
        <th>Enable</th>
        <?php if ($per == "Full" || $per == "Write" || $per == "Admin") { ?>
            <th>Set Attribute</th>
            <th>Delete</th>
        <?php } ?>

    </tr>
    </thead>
    <tbody>
    <?php

        $sql = "SELECT * FROM clients WHERE new_user='yes' ORDER BY id DESC ";
        $res = mysqli_query($con,$sql);
        while($j = mysqli_fetch_array($res)){
        $name = $j['full_name'];
        $username = $j['username'];
        $mobile = $j['mobile'];
        $addr = $j['address'];
        $sup = $j['supplier_id'];
        $old_date = $j['create_date'];

        ?>
        <tr>

            <td><a href="user_statistics.php?name=<?php echo $username; ?>"
                   role="button" style="color:#87b5dc;font-size:16px;"><?php echo $name; ?></a></td>
            <td><?php echo $username; ?></td>
            <td><?php echo $mobile; ?></td>
            <td><?php echo $addr; ?></td>
            <?php
            $sql = "SELECT * FROM radusergroup WHERE username='$username'";
            $rs = mysqli_query($con,$sql) or $msg = mysqli_error($con);
            $p = mysqli_fetch_array($rs);
            $pro = $p['groupname'];
            ?>
            <td><?php echo $pro; ?></td>
            <?php
            $sql = "SELECT * FROM radcheck WHERE attribute='Expiration' && username='$username'";
            $rs = mysqli_query($con,$sql) or $msg = mysqli_error($con);
            $re = mysqli_num_rows($rs);
            if ($re) {
                $p = mysqli_fetch_array($rs);
                $expi = $p['value'];
                $d = strtotime($expi);
                $exp = date("Y m d H:i", $d);
                $dateTime = new DateTime();
                $date = $dateTime->format('Y m d H:i');
                if ($exp != null && $exp < $date) {
                    $expi = "<button class=\"btn btn-warning\">Expired</button>
                            <a class=\"btn btn-primary\"  href=\"extend.php?user=" . $username . "&id=" . $j['supplier_id'] . "\" onClick='myFunction()' >Renew</a>";
                }
            }

            ?>
            <td><?php echo $expi; ?></td><?php if ($dept !="Visitor") { ?>
	    <td><?php echo $sup; ?></td><?php }?>
            <td><?php echo $old_date; ?></td>
            <?php $sql = "SELECT * FROM radcheck WHERE attribute='Auth-Type' && username='$username'";
            $rj = mysqli_query($con,$sql) or $msg = mysqli_error($con);
            $ac = mysqli_fetch_array($rj);
            $type = $ac['value'];
            ?>
            <td>
                <button class="btn btn-success" id="enable<?php echo $username; ?>" value="<?php echo $username; ?>"
                    <?php if ($type == "PAP") {
                        echo "disabled=\"true\"";
                    } ?> onclick="enableBtn(this.value)">
                    <span class="glyphicon glyphicon-ok"></span></button>
                <button class="btn btn-danger" id="disable<?php echo $username; ?>" value="<?php echo $username; ?>"
                    <?php if ($type == "reject") {
                        echo "disabled=\"true\"";
                    } ?> onclick="disableBtn(this.value)">
                    <span class="glyphicon glyphicon-off"></span></button>
            </td>
            <?php if ($per == "Full" || $per == "Write" || $per == "Admin") { ?>
                <td><a class="btn" href="user_attribute.php?val=<?php echo $username; ?>" style="color: #ffffff;">
                        <i class="icon-edit"><span class="glyphicon glyphicon-cog"></span></i></a></td>

                <td><a class="btn btn-danger" onclick="delFunction(this.id)" id="<?php echo $username; ?>"
                       href="new_user.php">
                        <i class="icon-edit"><span class="glyphicon glyphicon-trash"></span></i></a></td>
            <?php } ?>
        </tr>
        <?php } ?>
    </tbody>
</table>
<div id="last"></div>
<script type="text/javascript">
    function myFunction() {
        alert('Payment First...');
    }

</script>
<script type="text/javascript">
    function delFunction(str) {
        var r = confirm("Confirm to Delete " + str + "?");
        if (r == true) {
            document.getElementById(str).href = "user_list.php?val=" + str;
        } else {
            document.getElementById(str).href = "user_list.php";
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
<?php include 'includes/footer.php'; ?>

