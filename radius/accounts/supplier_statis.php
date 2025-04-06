<?php
include 'header.php';
?>
<h3>Transaction History</h3>
<table class="table table-bordered" id="btrc">
    <thead>
    <tr>
        <th>Name</th>
        <th>Date</th>
        <th>Transaction</th>
        <th>Deposit</th>
        <th>Credit</th>
        <th>Remark</th>

    </tr>
    </thead>
    <tbody>
    <?php
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    }

    $balance = 0;
    $sql = "SELECT * FROM `transaction` WHERE supplier_id='$id' ORDER BY id DESC  ";
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    while ($j = mysqli_fetch_array($r)) {
        $date = $j['create_date'];
        $transaction = $j['transaction'];
        $debit = $j['debit'];
        $credit = $j['credit'];
        $name = $j['supplier_name'];

        ?>

        <tr>
            <td><?php echo $j['reference']; ?></td>
            <td><?php echo $date; ?></td>
            <td><?php echo $transaction; ?></td>
            <td><?php echo $debit; ?></td>
            <td><?php echo $credit; ?></td>
            <td><?php echo $j['supplier_name']; ?></td>
        </tr>
    <?php }


    $amm = "SELECT SUM(credit) as credit,SUM(debit) as debit FROM `transaction` WHERE supplier_id='$id'";
    $total = mysqli_query($con, $amm);
    $d = mysqli_fetch_array($total);
    $cr = $d['credit'];
    $de = $d['debit'];
    $balance_total = $de - $cr;
    echo "<h4>" . $name . " Current Available Balance is: " . $balance_total . "Kes</h4>";
    echo "<h4>" . $name . " Total Revenew: " . $cr . "Kes</h4>";
    echo "<h4>" . $name . " Total Payment: " . $de . "Kes</h4>";

    ?>
    </tbody>

</table>

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
            order: [[3, "desc"]],
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
</script><?php include '../includes/footer.php'; ?>

