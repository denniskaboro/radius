<?php
include 'reseller.php';
include('../config/data.php');
$supplier = $_SESSION['id'];


?>
<div id="last">
</div>
<h3>User List:</h3>

<table class="table table-bordered" id="btrc">

    <thead>
    <tr class="btn-success" style="background-color: rgba(21,53,74,0.75); color: #ceefde; font-size:16px;">
        <th>User Name</th>
        <th>Package</th>
        <th>Create Date</th>
        <th>Expiration</th>
        <th>Supervisor</th>
        <th>Address</th>
        <th>Created By</th>
        <th>Edit</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $sql = "SELECT * FROM clients WHERE supplier_id='$supplier' ";
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    while ($f = mysqli_fetch_array($r)) {
        $name = $f['username'];
        $re = "SELECT * FROM re_clients WHERE username='$name'";
        $ro = mysqli_query($con, $re);
        $row = mysqli_num_rows($ro);
        if ($row <= 0) {
            $sup = $f['supplier_id'];
            $sql1 = "SELECT * FROM radcheck WHERE attribute='Expiration' && username='$name'";
            $rs = mysqli_query($con, $sql1) or $msg = mysqli_error($con);
            $p = mysqli_fetch_array($rs);
            $expi = $p['value'];
            $username = $p['username'];
            $d = strtotime($expi);
            $expiration = date("Y m d H:i", $d);
            $current = date("Y m d H:i", strtotime("+4 days"));
            $dateTime = new DateTime();
            $date = $dateTime->format('Y m d H:i');
            if ($expiration > $date && $expiration < $current) {

                ?>
                <tr style="background-color: rgba(89,177,159,0.21); color: #ffffff; font-size:16px;">
                <td><a style=" color: #e2b9db;" href="reseller_his.php?name=<?php echo $f['username']; ?>"
                       role="button" style="color: #ffffff;font-size:16px;"><?php echo $f['username']; ?></a>
                </td>
                <?php
                $sql2 = "SELECT * FROM radusergroup WHERE username='$name'";
                $rp = mysqli_query($con, $sql2) or $msg = mysqli_error($con);
                $q = mysqli_fetch_array($rp);
                $pro = $q['groupname'];
                ?>
                <td><?php echo $pro; ?></td>
                <td><?php echo $f['create_date'];; ?></td>
                <td>
                    <?php echo $expi; ?>
                    <a class="btn btn-primary"
                       href="extend.php?user=<?php echo $name; ?>&id=<?php echo $sup; ?>">Renew</a></td>
                <td><?php
                    $sql = "SELECT * FROM supplier WHERE supplier_id='$sup'";
                    $rs = mysqli_query($con, $sql) or $msg = mysqli_error($con);
                    $g = mysqli_fetch_array($rs);
                    echo $g['full_name']; ?></td>
                <td><?php echo $f['address']; ?></td>
                <td><?php echo $f['reference']; ?></td>

                <td><a class="btn" href="user_edit.php?id=<?php echo $f['id']; ?>" style="color: #ffffff;">
                        <i class="icon-edit"><span class="glyphicon glyphicon-pencil"></span></i></a></td>
            <?php }
        } ?>
        </tr>

        <?php
    } ?>


    </tbody>

</table>
<script>
    function showUser(str) {
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
        xmlhttp.open("GET", "multi.php?sup=<?php echo $supplier; ?>&extend=" + str, true);
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
<!-- end of col-lg-10 -->
<?php include '../includes/footer.php'; ?>
