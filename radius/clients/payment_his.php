<?php
include 'clients.php';
include('../config/data.php');
?>
<h3>Payment History</h3>
<table class="table table-bordered" id="btrc">
    <thead>
    <tr>
        <th>Name</th>
        <th>Transaction</th>
        <th>Debit</th>
        <th>Credit</th>
        <th>Date</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $usr = $_SESSION['username'];
    if (isset($_GET['show'])) {
        $sql = "SELECT * FROM `user_balance` where DATE_FORMAT(create_date,'%Y-%m-%d')=curdate() && `username`='$usr'";
    } else {
        $sql = "SELECT * FROM `user_balance` WHERE `username`='$usr'";
    }
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    while ($j = mysqli_fetch_array($r)) {
        $date = $j['create_date'];
        $transaction = $j['transactionId'];
        $debit = $j['debit'];
        $credit = $j['credit'];
        ?>

        <tr>
            <td><?php echo $usr; ?></td>
            <td><?php echo strtoupper($transaction); ?></td>
            <td><?php echo $debit; ?></td>
            <td><?php echo $credit; ?></td>
            <td><?php echo $date; ?></td>
        </tr>
    <?php }

    ?>
    </tbody>

</table>

<link rel="stylesheet" type="text/css" href="../component/jquery.dataTables.min.css"/>
<script type="text/javascript" src="../component/js/export/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#btrc').DataTable({
            pageLength: 15,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            order: [[2, 'DESC']]

        });

    });
</script>
<?php include '../includes/footer.php'; ?>

