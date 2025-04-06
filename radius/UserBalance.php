<?php
include 'includes/header.php';
include('config/data.php');
?>
<h3>User Account History</h3>
<table class="table table-bordered" id="btrc">
    <thead>
    <tr>
        <th>Username</th>
        <th>Full Name</th>
        <th>Balance</th>
        <th>Email</th>
        <th>Mobile</th>
        <th>Address</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $deb = 0;
    $balance = 0;
    $sql = "SELECT * FROM `clients`";
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    while ($j = mysqli_fetch_array($r)) {
        $user = $j['username'];
        $fname = $j['full_name'];
        $em = $j['email'];
        $mo = $j['mobile'];
        $address = $j['address'];
        $sum = "SELECT SUM(`debit`) as de, SUM(`credit`) as cr FROM `user_balance` WHERE username='$user'";
        $q = mysqli_query($con, $sum);
        $s = mysqli_fetch_array($q);
        $balance = $s['de'] - $s['cr'];
        ?>

        <tr>
            <td><a href="mpesaUserBalance.php?show=<?php echo $user; ?>" style="color: #2cff7d"><?php echo $user; ?></a>
            </td>
            <td><?php echo $fname; ?></td>
            <td><?php echo $balance; ?></td>
            <td><?php echo $em; ?></td>
            <td><?php echo $mo; ?></td>
            <td><?php echo $address; ?></td>
        </tr>
    <?php }
    ?>
    </tbody>

</table>

<link rel="stylesheet" type="text/css" href="component/jquery.dataTables.min.css"/>
<script type="text/javascript" src="component/js/export/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#btrc').DataTable({
            pageLength: 15,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            order: [[2, 'DESC']]

        });

    });
</script>
<?php include 'includes/footer.php'; ?>

