<?php
include 'includes/header.php';
function secondsToTime($seconds)
{
    $dtF = new \DateTime('@0');
    $dtT = new \DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');
}

?>
<table class="table table-bordered" id="btrc">
    <thead>
    <tr>
        <th>Package Name</th>
        <th>Voucher Username</th>
        <th>Total Bandwidth</th>
        <th>Available Bandwidth</th>
        <th>Speed</th>
        <th>Duration</th>
        <th>Remaining Time Left</th>
        <th>Expired On</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if (isset($_GET['name'])) {
        $vou = $_GET['name'];
        $vo = "SELECT * FROM voucher WHERE supplier_id='Admin' and `voucher_name`='$vou'";
    } else {
        $vo = "SELECT * FROM voucher WHERE supplier_id='Admin'";
    }
    $v = mysqli_query($con, $vo);
    while ($e = mysqli_fetch_array($v)) {
        $voucher = $e['voucher_name'];
        $pack_name = $e['package_name'];
        $limit = $e['total_limit'];
        $exp = $e['expiration'];
        $speed = $e['mbps'];
        $dead = $e['deadline'];
        $sql = "SELECT * FROM radgroupreply WHERE groupname='$voucher' and attribute='Mikrotik-Recv-Limit' ORDER BY id ASC ";
        $rr = mysqli_query($con, $sql) or $msg = mysqli_error($con);
        $jj = mysqli_fetch_array($rr);
        $down = $jj['value'];

        $sq = "SELECT * FROM users WHERE voucher_name='$voucher'";
        $rr = mysqli_query($con, $sq);
        while ($f = mysqli_fetch_array($rr)) {
            $name = $f['username'];
            $give_session = $f['expire_date'];
            ?>
            <tr>
                <td><?php
                    echo $pack_name;

                    ?></td>
                <td><a style=" color: #1ce2f0;" href="user_statistics.php?name=<?php echo $f['username']; ?>"
                       role="button"><?php echo $f['username']; ?></a></td>
                <td><?php
                    echo $limit;

                    ?></td>
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
                <td><?php echo date("Y-m-d H:i", strtotime($dead)); ?></td>
                <td><?php echo $f['status']; ?></td>
            </tr>
        <?php }
    } ?>
    </tbody>
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
