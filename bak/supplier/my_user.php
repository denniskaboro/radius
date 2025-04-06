<?php
include('reseller.php');
include('../config/data.php');
?>
<h3>My Users List</h3>
<table class="table table-bordered" id="btrc">
    <thead>
    <tr>
        <th>Name</th>
        <th>Username</th>
        <th>Password</th>
        <th>Mobile</th>
        <th>Address</th>
        <th>Expiration</th>
        <th>Package</th>
        <th>MAC</th>
        <th>Status</th>
        <th>Activation Date</th><?php if($per=='Full' || $per=='Admin' || $per=='Write') {?>
        <th>Edit</th>
        <th>Enable</th>
        <th>Disable</th><?php }?>

    </tr>
    </thead>
    <tbody>
    <?php
    $id = $_SESSION['id'];
    $sql = "SELECT * FROM clients WHERE supplier_id='$id'  ORDER BY id DESC ";
    $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
    while ($j = mysqli_fetch_array($r)) {
        $name = $j['full_name'];
        $username = $j['username'];
        $mobile = $j['mobile'];
        $addr = $j['address'];
        $client_type = $j['client_type'];
        $con_type = $j['connectivity'];
        $old_date = $j['create_date'];

        ?>
        <tr>

            <td><a href="reseller_his.php?name=<?php echo $username; ?>"
                   role="button" style="color:#87b5dc;font-size:16px;"><?php echo $name; ?></a></td>
            <td><?php echo $username; ?></td>
            <td><?php $sql2 = "SELECT * FROM radcheck WHERE attribute='Cleartext-Password' && username='$username'";
                $pass = mysqli_query($con,$sql2) or $msg = mysqli_error($con);
                $p = mysqli_fetch_array($pass);
                echo $p['value']; ?></td>
            <td><?php echo $mobile; ?></td>
            <td><?php echo $addr; ?></td>
            <?php
            $sql = "SELECT * FROM radusergroup WHERE username='$username'";
            $rs = mysqli_query($con,$sql) or $msg = mysqli_error($con);
            $p = mysqli_fetch_array($rs);
            $pro = $p['groupname'];
            ?>
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
                            <a class=\"btn btn-primary\"  href=\"extend.php?user=" . $username . "&id=" . $id . "\" onClick='myFunction()' >Renew</a>";
                }
            }

            ?>
            <td><?php echo $expi; ?></td>
            <td><?php echo $pro; ?></td>
            <td><?php $mac = "SELECT * FROM radcheck WHERE attribute='Calling-Station-Id' && username='$username'";
                $ma = mysqli_query($con,$mac) or $msg = mysqli_error($con);
                $m = mysqli_num_rows($ma);
                if ($m > 0){
                $mm = mysqli_fetch_array($ma);
                $id = $mm['id'];
                ?>
                <button class="btn btn-danger" id="<?php echo $id; ?>"
                        value="<?php echo $id; ?>" onclick="Delete(this.value)">
                    <span class="glyphicon glyphicon-trash"></span></button>
            </td> <?php } ?>
            <td style="color:lightgreen !important;"><?php
                $acct = "SELECT * FROM radacct  WHERE username='$username' && acctstoptime IS NULL && acctterminatecause='' ORDER BY acctstarttime DESC";
                $q = mysqli_query($con,$acct);
                $row = mysqli_num_rows($q);
                if ($row > 0 && $row < 2) {
                    echo "Connected";
                } else if ($row > 1) {
                    echo "Multi Session";
                } else {
                    echo "Not Connected";
                }
                ?></td>

            <td><?php echo $old_date; ?></td>
            <?php $sql = "SELECT * FROM radcheck WHERE attribute='Auth-Type' && username='$username'";
            $rj = mysqli_query($con,$sql) or $msg = mysqli_error($con);
            $ac = mysqli_fetch_array($rj);
            $type = $ac['value'];
            $idd = $ac['id'];
            ?>
            <?php if($per=='Full' || $per=='Admin' || $per=='Write') {?>
            <td><a class="btn" href="user_edit.php?id=<?php echo $j['id']; ?>" style="color: #ffffff;">
                    <i class="icon-edit"><span class="glyphicon glyphicon-pencil"></span></i></a></td>
            <td>
                <button class="btn btn-success" id="enable<?php echo $p['username']; ?>"
                        value="<?php echo $p['username']; ?>"
                    <?php if ($type == "PAP" || empty($type)) {
                        echo "disabled=\"true\"";
                    } ?> onclick="enableBtn(this.value)">
                    <span class="glyphicon glyphicon-ok"></span></button>
            </td>
            <td>
                <button class="btn btn-danger" id="disable<?php echo $p['username']; ?>"
                        value="<?php echo $p['username']; ?>"
                    <?php if ($type == "reject") {
                        echo "disabled=\"true\"";
                    } ?> onclick="disableBtn(this.value)">
                    <span class="glyphicon glyphicon-off"></span></button>
            </td><?php } ?>
        </tr>
    <?php } ?>
    </tbody>
</table>

<script type="text/javascript">
    function myFunction() {
        alert('Payment First...');
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
<script>
    function Delete(str) {
        var r = confirm("Confirm to Delete " + str + "?");
        if (r == true) {
            document.getElementById(str).innerHTML = "";
            if (str == "") {
                document.getElementById(str).innerHTML = "";
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
                    document.getElementById("str").innerHTML = this.responseText;
                }
            }
            xmlhttp.open("GET", "disable.php?id=" + str, true);
            xmlhttp.send();
        }
    }
</script>
<link rel="stylesheet" type="text/css" href="../component/jquery.dataTables.min.css"/>
<script type="text/javascript" src="../component/js/export/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#btrc').DataTable({
            pageLength: 15,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
        });

    });
</script>
<?php include '../includes/footer.php'; ?>

