<?php
include 'includes/header.php';
function secondsToTime($seconds)
{
    $dtF = new \DateTime('@0');
    $dtT = new \DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%ad, %hh %im and %ss');
}

if (isset($_GET['val'])) {
    $username = $_GET['val'];
    $sqlp = "SELECT * FROM radusergroup WHERE username='$username'";
    $resul = mysqli_query($con, $sqlp);
    $fi = mysqli_fetch_array($resul);
    $pro = $fi['groupname'];
    $tabb = ["hotspot_user", "radcheck", "radacct", "radreply", "radusergroup","sms_check"];
    foreach ($tabb as $item) {
        $sq = "DELETE FROM $item WHERE username='$username'";
        $res = mysqli_query($con, $sq);
    }

    $sqll = "DELETE FROM hotspot_auth WHERE mac='$username'";
    $res = mysqli_query($con, $sqll);
    $msg = "Package Delete Successfully...<br>";
    $API->log($wh, "Package " . $pro . " Deleted");
}
?>
<table class="table table-bordered" id="btrc">
    <thead>
    <tr>
        <th>Username</th>
        <th>Mobile</th>
        <th>Bandwidth</th>
        <th>Bandwidth(Avail.)</th>
        <th>Duration</th>
        <th>Time Left</th>
        <th>Activation Date</th>
        <th>Delete</th>
    </tr>
    </thead>
    <tbody>
    <?php

    $vo = "SELECT * FROM hotspot_user";
    $v = mysqli_query($con, $vo);
    while ($e = mysqli_fetch_array($v)) {
        $username = $e['username'];
        $mobile = $e['mobile'];
        $bandwidth = $e['bandwidth'];
        $duration = $e['duration'];

        ?>
        <tr>
            <td>
                <a style=" color: #1ce2f0;" href="user_statistics.php?name=<?php echo $username; ?>" role="button">
                    <?php echo $username; ?>
                </a>
            </td>
            <td><?php echo $mobile; ?></td>
            <td><?php echo $bandwidth; ?></td>
            <td><?php
                $f = $API->RowFetch(
                    "IFNULL(SUM(acctoutputoctets),0) as duse ",
                    " radacct ",
                    " username='$username'"
                );
                $used_session = $f['duse'];
                $dd = $API->Select(
                    "radgroupcheck.value",
                    "radgroupcheck INNER JOIN radusergroup ON radgroupcheck.groupname=radusergroup.groupname",
                    "radusergroup.username='$username' and radgroupcheck.attribute='Max-Data'"
                );

                if ($dd->num_rows > 0) {
                    $d = $dd->fetch_assoc();
                    $assign_value = $d['value'];
                    if($used_session >= $assign_value){
                        echo "<h5 class='text-danger'>Plan Expired</h5>";
                    }else{
                        $avail_bw = $assign_value - $used_session;
                        echo round($avail_bw / (1024 * 1024)) . " MB";
                    }

                } else {
                    echo "";
                }
                ?></td>

            <td><?php echo $duration; ?></td>
            <td><?php
                $f = $API->RowFetch(
                    "IFNULL( MAX(TIME_TO_SEC(TIMEDIFF(NOW(), acctstarttime))),0) as ttime",
                    " radacct ",
                    "username='$username' ORDER BY acctstarttime LIMIT 1"
                );
                $used_session = $f['ttime'];
                $dd = $API->Select(
                    "value",
                    "radcheck",
                    "username='$username' and attribute='Expire-After'"
                );
                if ($dd->num_rows > 0) {
                    $d = $dd->fetch_assoc();
                    $assign_value = $d['value'];
                    if($used_session >= $assign_value){
                        echo "<h5 class='text-danger'>Plan Expired</h5>";
                    }else{
                        $avail = $assign_value - $used_session;
                        echo secondsToTime($avail);
                    }

                } else {
                    echo "";
                }
                ?></td>
            <td><?php echo $e['active_date']; ?></td>
            <td><a class="btn btn-danger" onclick="myFunction('<?php echo $username; ?>')" id="<?php echo $username; ?>"
                   href="group_list.php">
                    <i class="icon-edit"><span class="glyphicon glyphicon-trash"></span></i></a></td>
        </tr>
        <?php
    } ?>
    </tbody>
    <script>
        function myFunction(str) {
            var r = confirm("Confirm to Delete?");
            if (r == true) {
                document.getElementById(str).href = "hotspot_user.php?val=" + str;
            } else {
                document.getElementById(str).href = "hotspot_user.php";
            }
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
