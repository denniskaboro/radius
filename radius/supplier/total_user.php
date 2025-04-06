<?php
include 'reseller.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}
?>
<table class="table table-bordered" id="btrc">
    <thead>
    <tr>
        <th>Name</th>
        <th>Username</th>
        <th>Mobile</th>
        <th>Address</th>
        <th>NID</th>
        <th>Package</th>
        <th>Status</th>
        <th>Activation Date</th>

    </tr>
    </thead>
    <tbody>
    <?php
    $net = 0;
    $band = 0;
    $sql = "SELECT * FROM re_clients WHERE reseller_id='$id'  ORDER BY id DESC ";
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    $total = mysqli_num_rows($r);
    while ($j = mysqli_fetch_array($r)) {
        $user = $j['username'];
        ?>
        <tr>

            <td><?php echo $j['full_name']; ?></td>

            <td><a style=" color: #e2b9db;" href="reseller_his.php?name=<?php echo $user; ?>"
                   role="button" style="color: #ffffff;font-size:16px;"><?php echo $user; ?></a></td>
            <td><?php echo $j['mobile']; ?></td>
            <td><?php echo $j['address']; ?></td>
            <td><?php echo $j['nid']; ?></td>
            <td><?php
                $s = "SELECT * FROM radusergroup WHERE username='$user' ORDER BY id ASC ";
                $rr = mysqli_query($con, $s) or $msg = mysqli_error($con);
                $q = mysqli_fetch_array($rr);
                echo $q['groupname'];
                $pro = $q['groupname'];
                $cx = strpos($pro, ' M');
                if ($cx) {
                    $local = substr($pro, 0, $cx);
                    $band = $local + $band;
                }
                $price = "SELECT * FROM re_groups WHERE groupname='$pro'";
                $ff = mysqli_query($con, $price);
                $bill = mysqli_fetch_array($ff);
                $total = $bill['price'];
                $net = $total + $net;
                ?></td>
            <td style="color:lightgreen !important;"><?php
                $acct = "SELECT * FROM radacct  WHERE username='$user' && acctstoptime IS NULL && acctterminatecause='' ORDER BY acctstarttime DESC";
                $q = mysqli_query($con, $acct);
                $row = mysqli_num_rows($q);
                if ($row > 0 && $row < 2) {
                    echo "Connected";
                } else if ($row > 1) {
                    echo "Multi Session";
                } else {
                    echo "Not Connected";
                }
                ?></td>
            <td><?php echo $j['create_date']; ?></td>

        </tr>
    <?php }
    echo "<h3> Total Bandwidth: " . $band . " Mbps</h3> ";
    echo "<h3> Total Revenew: " . $net . " Kes</h3> "; ?>
    </tbody>
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
