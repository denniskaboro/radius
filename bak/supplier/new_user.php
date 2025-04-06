<?php
include('reseller.php');
include('../config/data.php');
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
        <th>Expiration</th>
        <th>Cilent Type</th>
        <th>Con. Type</th>
        <th>Activation Date</th>
        <th>Edit</th>
       <th>Enable/Disable</th> 

    </tr>
    </thead>
    <tbody>
    <?php
    $id = $_SESSION['id'];
    $sql = "SELECT * FROM clients WHERE supplier_id='$id' && new_user='yes' ORDER BY id DESC ";
    $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
    while ($f = mysqli_fetch_array($r)) {
        $name = $f['full_name'];
        $username = $f['username'];
        $mobile = $f['mobile'];
        $addr = $f['address'];
        $client_type = $f['client_type'];
        $con_type = $f['connectivity'];
        $old_date = $f['create_date'];
        ?>
            <tr>

                <td><a href="reseller_his.php?name=<?php echo $username; ?>"
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
                }

                ?>
                <td><?php echo $exp; ?></td>
                <td><?php echo $client_type; ?></td>
                <td><?php echo $con_type; ?></td>
                <td><?php echo $old_date; ?></td>
                <td><a class="btn" href="user_edit.php?id=<?php echo $f['id']; ?>" style="color: #ffffff;">
                        <i class="icon-edit"><span class="glyphicon glyphicon-pencil"></span></i></a></td>
		 <td>
                    <button class="btn btn-success" id="enable<?php echo $username; ?>"
                            value="<?php echo $username; ?>"
                        <?php if ($type == "PAP") {
                            echo "disabled=\"true\"";
                        } ?> onclick="enableBtn(this.value)">
                        <span class="glyphicon glyphicon-ok"></span></button>
                    <button class="btn btn-danger" id="disable<?php echo $username; ?>"
                            value="<?php echo $username; ?>"
                        <?php if ($type == "reject") {
                            echo "disabled=\"true\"";
                        } ?> onclick="disableBtn(this.value)">
                        <span class="glyphicon glyphicon-off"></span></button>
                </td>
            </tr>
        <?php }?>
    </tbody>
</table>
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
        xmlhttp.open("GET", "../disable.php?st=reject&value=" + str, true);
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
        xmlhttp.open("GET", "../disable.php?st=PAP&value=" + str, true);
        xmlhttp.send();
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

