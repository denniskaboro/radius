<?php
include('reseller.php');
include('../config/data.php');
?>
<h3>My Transaction History</h3>
<table class="table table-bordered" id="btrc">
    <thead>
    <tr>
        <th>Name</th>
        <th>Date</th>
        <th>Transaction</th>
        <th>Initial Balance</th>
        <th>Deposit Amount</th>
        <th>Amount A</th>
        <th>Amount B</th>
        <th>Balance</th>
        <th>Remark</th>

    </tr>
    </thead>
    <tbody>
    <?php
    if (isset($_SESSION['id'])) {
        $id = $_SESSION['id'];
    }

    $sql = "SELECT * FROM transaction WHERE supplier_id='$id' ORDER BY id DESC";
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    while ($j = mysqli_fetch_array($r)) {
        $name = $j['supplier_name'];
        $date = $j['date'];
        $transaction = $j['transaction'];
        $balance = $j['balance'];
        $debit = $j['debit'];
        $deb = $deb + $debit;
        $credit = $j['credit'];
        $cre = $cre + $credit;
        $supplier_ammount = $j['supplier_ammount'];
        $reference = $j['reference'];

        ?>

        <tr>
            <td><?php echo $name; ?></td>
            <td><?php echo $date; ?></td>
            <td><?php echo $transaction; ?></td>
            <td><?php echo $balance; ?></td>
            <td><?php echo $debit; ?></td>
            <td><?php echo $credit; ?></td>
            <td><?php echo $supplier_ammount; ?></td>
            <td><?php echo $j['supplier_balance']; ?></td>
            <td><?php echo $reference; ?></td>
        </tr>
    <?php }

    $amm = "SELECT SUM(supplier_ammount) as ammount,SUM(supplier_payment) as pay,SUM(credit) as credit,
       SUM(debit) as debit FROM transaction WHERE supplier_id='$id'";
    $total = mysqli_query($con, $amm);
    $d = mysqli_fetch_array($total);
    $amm = $d['ammount'];
    $pay = $d['pay'];
    $cr = $d['credit'];
    $de = $d['debit'];
    $balance_total = $de - $cr;
    $sup_bala = $amm - $pay;
    echo "<h4>My Current Balance is: " . $balance_total . "Kes</h4>";
    echo "<h4>My Total Deposit Balance is: " . $de . "Kes</h4>";
    echo "<h4>My Total Usage Balance is: " . $cr . "Kes</h4>";
    echo "<h4>My Amount is: " . $sup_bala . "kes</h4>";

    ?>
    </tbody>

</table>
<link rel="stylesheet" type="text/css" href="component/jquery.dataTables.min.css"/>
<script type="text/javascript" src="component/js/export/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#btrc').DataTable({
            dom: 'Bfrtip',
            buttons: [],
        });

    });
</script>
<?php include '../includes/footer.php'; ?>

