<?php
include 'reseller.php';
include('../config/data.php');
?>
<h3>Transaction History</h3>
<table class="table table-bordered" id="btrc">
    <thead>
    <tr>
        <th>Name</th>
        <th>Date</th>
        <th>Credit Ammount</th>
        <th>Deposit Ammount</th>
        <th>Balance</th>
        <th>Remark</th>

    </tr>
    </thead>
    <tbody>
    <?php
    $id = $_GET['id'];
    $sql = "SELECT * FROM re_transaction WHERE reseller_id='$id' ORDER BY id DESC";
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    while ($j = mysqli_fetch_array($r)) {
        $name = $j['supplier_name'];
        $date = $j['date'];
        $credit = $j['credit'];
        $reference = $j['reference'];

        ?>

        <tr>
            <td><?php echo $name; ?></td>
            <td><?php echo $date; ?></td>
            <td><?php echo $credit; ?></td>
            <td><?php echo $j['debit']; ?></td>
            <td><?php echo $j['balance']; ?></td>
            <td><?php echo $reference; ?></td>
        </tr>
    <?php }

    $amm = "SELECT supplier_name, SUM(credit) as credit,SUM(debit) as de FROM re_transaction WHERE reseller_id='$id'";
    $total = mysqli_query($con, $amm);
    $d = mysqli_fetch_array($total);
    $cr = $d['credit'];
    $de = $d['de'];
    $na = $d['supplier_name'];
    $balance_total = $de - $cr;
    echo "<h4>" . $na . " Current Balance: " . $balance_total . "Kes</h4>";
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
            order: [[1, 'desc']]
        });

    });
</script>
<?php include '../includes/footer.php'; ?>

