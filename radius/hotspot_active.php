<?php
include 'includes/header.php';
$per = $_SESSION['per'];
include "config/data.php";
function secondsToTime($seconds)
{
    $dtF = new \DateTime('@0');
    $dtT = new \DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');
}

?>
<div id="last"></div>
<div class="col-lg-12">
    <h3>Active User's</h3>
    <div class="table-responsive">
        <table class="table table-bordered" id="btrc">
            <thead>
            <tr>
                <th>Voucher Code</th>
                <th>RESELLER NAME</th>
                <th>NAS IP Address</th>
                <th>NAS Port</th>
                <th>Start Time</th>
                <th>Total Bandwidth</th>
                <th>Available Bandwidth</th>
                <th>Speed</th>
                <th>Duration</th>
                <th>Remaining Time Left</th>
                <th>MAC Address</th>
                <th>IP Address</th>
                <th>Disconnect</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sql = "SELECT * FROM radacct  WHERE acctstoptime IS NULL and nasporttype='Wireless-802.11' ";
            $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
            while ($f = mysqli_fetch_array($r)) {
                $name = $f['username'];
                $sql1 = "SELECT * FROM users WHERE username='$name'";
                $rr1 = mysqli_query($con, $sql1) or $msg = mysqli_error($con);
                $jp = mysqli_fetch_array($rr1);
                $voucher = $jp['voucher_name'];
                $supplier = $jp['supplier_id'];
                $give_session = $jp['expire_date'];
                $sup = "SELECT * FROM supplier WHERE supplier_id='$supplier'";
                $sup_qu = mysqli_query($con, $sup);
                $sup_f = mysqli_fetch_array($sup_qu);
                $sup_name = $sup_f['full_name'];
                $sql = "SELECT * FROM radgroupcheck WHERE groupname='$voucher' and attribute='Max-Data' ";
                $rr = mysqli_query($con, $sql) or $msg = mysqli_error($con);
                $jj = mysqli_fetch_array($rr);
                $down = $jj['value'];
                $sql = "SELECT * FROM voucher WHERE voucher_name='$voucher'";
                $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
                $j = mysqli_fetch_array($r);
                $limit = $j['total_limit'];
                $exp = $j['expiration'];
                $speed = $j['mbps'];
                ?>
                <tr>
                    <td><a style=" color: #fae2f4;" href="user_statistics.php?name=<?php echo $f['username']; ?>"
                           role="button" style="color: #ffffff;font-size:16px;"><?php echo $f['username']; ?></a></td>
                    <td><?php echo $sup_name; ?></td>
                    <td><?php echo $f['nasipaddress']; ?></td>
                    <td><?php echo $f['nasportid']; ?></td>
                    <td><?php echo $f['acctstarttime']; ?></td>
                    <td><?php echo $limit; ?></td>
                    <td><?php
                        $cou = "SELECT SUM(acctsessiontime) as sess, SUM(acctoutputoctets) as ou, acctstarttime  FROM `radacct` WHERE `username`='$name'";
                        $c = mysqli_query($con, $cou);
                        $g = mysqli_fetch_array($c);
                        $start_time = $g['acctstarttime'];
                        $downloaded = $g['ou'];
                        if ($start_time != NULL) {
                            $cur = time();
                            $start_time = strtotime($g['acctstarttime']);
                            $session = $cur - $start_time;
                            $available = $give_session - $session;
                            if ($available > 0) {
                                $expiration = secondsToTime($available);
                            } else {
                                $expiration = "<p style='color:red'>Expired</p>";
                            }
                            if ($limit != null) {
                                $sql = "SELECT * FROM radgroupcheck WHERE groupname='$voucher' and attribute='Max-Data' ";
                                $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
                                $jj = mysqli_fetch_array($r);
                                $download = $jj['value'];
                                $remaining = $download - $downloaded;
                                $avai = intval($remaining / (1024 * 1024));
                                echo $avai . " MB";

                            } else {
                                echo $limit;
                            }
                        } else {
                            $expiration = $exp;
                            echo $limit;

                        }
                        ?></td>
                    <td><?php echo $speed . 'bps'; ?></td>
                    <td><?php echo $exp; ?></td>
                    <td><?php
                        echo $expiration;

                        ?></td>
                    <td><?php echo $f['callingstationid']; ?></td>
                    <td><?php echo $f['framedipaddress']; ?></td>
                    <td>
                        <button class="btn btn-danger" id=<?php echo $f['radacctid'] ?>" onclick=" DisConnect(this.id)
                        ">
                        <i class="icon-edit"><span class="glyphicon glyphicon-remove"></span></i></button></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    function DisConnect(str) {
        var r = confirm("Confirm to Disconnect this User?");
        if (r == true) {
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
                    document.getElementById("last").innerHTML = this.responseText;
                }
            }
            xmlhttp.open("GET", "clear.php?disconnect=" + str, true);
            xmlhttp.send();
        }
    }
</script>

<link rel="stylesheet" type="text/css" href="component/jquery.dataTables.min.css"/>
<script type="text/javascript" src="component/js/export/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#btrc').DataTable({
            pageLength: 15,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            order: [[6, "desc"]]
        });

    });
</script>

<?php include 'includes/footer.php'; ?>
