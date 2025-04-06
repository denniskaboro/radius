<?php
include 'header.php';
$per = $_SESSION['per'];
//For User Update query
if (isset($_POST['user'])) {
    if ($per == "Write" || $per == "Full" || $per == "Admin") {
        $username = $_POST['username'];
        $old_user = $_POST['old_name'];
        $pack = $_POST['pack'];
        $old_pack = $_POST['old_pack'];
        $password = $_POST['password'];
        $old_pass = $_POST['old_pass'];
        $sup = $_POST['new_sup'];
        $old_sup = $_POST['old_sup'];
        if ($pack == "None" && $sup == "None") {
            $pack = "DELETE FROM radusergroup WHERE username='$old_user' ";
            mysqli_query($con, $pack);
            $sup = "UPDATE supplier SET supplier_id=null WHERE username='$old_user' ";
            mysqli_query($con, $sup);
            $trans = "DELETE FROM transaction WHERE reference='$username'";
            mysqli_query($con, $trans);
        } else if ($pack == "None") {
            $pack = "DELETE FROM radusergroup WHERE username='$old_user' ";
            mysqli_query($con, $pack);
            $trans = "DELETE FROM transaction WHERE reference='$username'";
            mysqli_query($con, $trans);
        } else if ($sup == "None") {
            $trans = "DELETE FROM transaction WHERE reference='$username' && supplier_id='$old_sup'";
            mysqli_query($con, $trans);
            $sup = "UPDATE supplier SET supplier_id=null WHERE username='$old_user' ";
            mysqli_query($con, $sup);
        } else {
            if ($username != $old_user) {
                $table = ['radcheck', 'radreply', 'supplier', 'radusergroup', 'radacct', 'clients'];
                foreach ($table as $tab) {
                    $sql = "UPDATE $tab set username='$username' WHERE username='$old_user'";
                    mysqli_query($con, $sql) or $msg = mysqli_error($con);
                }
            }
            if ($password != $old_pass) {
                $sql = "UPDATE radcheck set value='$password' WHERE id='$id'";
                mysqli_query($con, $sql) or $msg = mysqli_error($con);
            }
            if ($pack != $old_pack && $pack != null) {
                $sql = "SELECT * FROM radusergroup  WHERE username='$username'";
                $check = mysqli_query($con, $sql) or $msg = mysqli_error($con);
                $r = mysqli_num_rows($check);
                if ($r > 0) {
                    $sql = "UPDATE radusergroup SET groupname='$pack' WHERE username='$username'";
                    $check = mysqli_query($con, $sql) or $msg = mysqli_error($con);

                } else {
                    $sql = "INSERT INTO radusergroup (username,groupname) VALUES('$username','$pack'); ";
                    $check = mysqli_query($con, $sql) or $msg = mysqli_error($con);
                }
//                $sup_check="SELECT * FROM transaction WHERE reference='$old_user' ORDER BY date DESC";
//                $sup_query=mysqli_query($con,$sup_check);
//                $chk=mysqli_num_rows($sup_query);
//                if($chk>0){
                $trans = "DELETE FROM transaction WHERE reference='$username'";
                mysqli_query($con, $trans);
                $newtk = "SELECT * FROM groups WHERE groupname='$pack'";
                $new = mysqli_query($con, $newtk);
                $r = mysqli_fetch_array($new);
                $pcl_new = $r['pcl'];
                $supp_new = $r['supplier'];
                $sup_check = "SELECT * FROM `transaction` WHERE supplier_id='$sup' ORDER BY create_date DESC";
                $sup_query = mysqli_query($con, $sup_check);
                $sup_res = mysqli_fetch_array($sup_query);
                $full_name = $sup_res['supplier_name'];
                $balance = $sup_res['balance'];
                if ($pcl_new > $balance) {
                    echo "<h4>Low Balance</h4>";
                } else {
                    $balance = $balance - $pcl_new;
                    $trans = "INSERT INTO `transaction` (`supplier_id`,`supplier_name`,`transaction`,`credit`,`reference`)
 VALUES('$sup','$full_name','$pack','$pcl_new','$balance','$supp_new','$username')";
                    $create = mysqli_query($con, $trans);
                }
//                }

            }
            if ($sup != $old_sup && $sup != null) {
                $sup_check = "SELECT * FROM transaction WHERE reference='$old_user' ORDER BY create_date DESC";
                $sup_query = mysqli_query($con, $sup_check);
                $chk = mysqli_num_rows($sup_query);

                $newtk = "SELECT * FROM groups WHERE groupname='$pack'";
                $new = mysqli_query($con, $newtk);
                $r = mysqli_fetch_array($new);
                $pcl_new = $r['pcl'];
                $supp_new = $r['supplier'];
                $sup_check = "SELECT * FROM transaction WHERE supplier_id='$sup' ORDER BY create_date DESC";
                $sup_query = mysqli_query($con, $sup_check);
                $rs = mysqli_num_rows($sup_query);
                $sup_res = mysqli_fetch_array($sup_query);
                $full_name = $sup_res['supplier_name'];
                $bal = $sup_res['balance'];
                if ($chk > 0) {
                    $trans = "DELETE FROM transaction WHERE reference='$username' && supplier_id='$old_sup'";
                    mysqli_query($con, $trans);
                    if ($bal > $pcl_new) {
                        $balan = $bal - $pcl_new;
                        $trans = "INSERT INTO transaction (supplier_id,supplier_name,transaction,credit,reference)
                        VALUES('$sup','$full_name','$pack','$username')";
                        $create = mysqli_query($con, $trans);
                        $sql = "UPDATE supplier SET supplier_id='$sup' WHERE username='$username'";
                        $check = mysqli_query($con, $sql) or $msg = mysqli_error($con);
                    } else {
                        echo "<h4>Low Balance</h4>";
                    }
                } else {
                    if ($bal > $pcl_new) {
                        $balan = $bal - $pcl_new;
                        $trans = "INSERT INTO transaction (supplier_id,supplier_name,transaction,credit,reference)
                         VALUES('$sup','$full_name','$pack','$pcl_new','$username')";
                        $create = mysqli_query($con, $trans);
                        $sql = "UPDATE supplier SET supplier_id='$sup' WHERE username='$username'";
                        $check = mysqli_query($con, $sql) or $msg = mysqli_error($con);
                    } else {
                        echo "<h4>Low Balance</h4>";
                    }
                }

            } else {
                echo "No Change";
            }
        }

    } else {
        echo "<h4 style='color: #ff9a7d ;'>You have no permission...</h4>";
    }


}

?>
<div id="last"></div>
<h3>User List</h3>
<table class="table table-bordered" id="btrc">

    <thead>
    <tr class="btn-success" style="background-color: rgba(21,53,74,0.75); color: #ceefde; font-size:16px;">
        <th>User Name</th>
        <th>Full Name</th>
        <th>Mobile</th>
        <th>Address</th>
        <th>Package</th>
        <th>Create Date</th>
        <th>Expiration</th>
        <th>Supervisor</th>
        <th>Created By</th>

    </tr>
    </thead>
    <tbody>
    <?php
    $sql = "SELECT * FROM clients ORDER BY id DESC ";
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);

    while ($f = mysqli_fetch_array($r)) {
        $name = $f['username'];
        $sup = $f['supplier_id'];
        ?>
        <tr style="background-color: rgba(89,177,159,0.21); color: #ffffff; font-size:16px;">

            <td><?php echo $f['username']; ?></td>
            <?php
            $sql = "SELECT * FROM radusergroup WHERE username='$name'";
            $rs = mysqli_query($con, $sql) or $msg = mysqli_error($con);
            $p = mysqli_fetch_array($rs);
            $pro = $p['groupname'];
            ?>
            <td><?php echo $f['full_name']; ?></td>
            <td><?php echo $f['mobile']; ?></td>
            <td><?php echo $f['address']; ?></td>
            <td><?php echo $pro; ?></td>
            <td><?php echo $f['create_date'];; ?></td>
            <?php
            $sql = "SELECT * FROM radcheck WHERE attribute='Expiration' && username='$name'";
            $rs = mysqli_query($con, $sql) or $msg = mysqli_error($con);
            $re = mysqli_num_rows($rs);
            if ($re) {
                $p = mysqli_fetch_array($rs);
                $expi = $p['value'];
                $d = strtotime($expi);
                $exp = date("Y m d H:i", $d);
                date_default_timezone_set("Asia/Dhaka");
                $dateTime = new DateTime();
                $date = $dateTime->format('Y m d H:i');
                if ($exp < $date) {
                    $expi = "<button class=\"btn btn-warning\">Expired</button>";
                }
            } else {
                $expi = "No Expiratioin";
            }

            ?>
            <td><?php echo $expi; ?></td>
            <td><?php
                $sql = "SELECT * FROM supplier WHERE supplier_id='$sup'";
                $rs = mysqli_query($con, $sql) or $msg = mysqli_error($con);
                $g = mysqli_fetch_array($rs);
                echo $g['full_name']; ?></td>
            <td><?php echo $f['reference']; ?></td>

        </tr>

    <?php } ?>
    </tbody>

</table>
<script type="text/javascript">
    function myFunction(str) {
        var r = confirm("Confirm to Delete " + str + "?");
        if (r == true) {
            document.getElementById(str).href = "user_list.php?val=" + str;
        } else {
            document.getElementById(str).href = "user_list.php";
        }
    }

</script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://Cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#btrc').DataTable({
            dom: 'Bfrtip',
            pageLength: 15,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            order: [[8, "desc"]],
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
</script>


<!-- end of col-lg-10 -->
<?php include '../includes/footer.php'; ?>
