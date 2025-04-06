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
        <th>Ammount</th>
        <th>Remark</th>

    </tr>
    </thead>
    <tbody>
    <?php
    if (isset($_SESSION['id'])) {
        $id = $_SESSION['id'];
    }
    $amm = "SELECT SUM(credit) as credit,
       SUM(debit) as debit FROM `transaction` WHERE supplier_id='$id'";
    $total = mysqli_query($con,$amm);
    $d = mysqli_fetch_array($total);
    $cr = $d['credit'];
    $de = $d['debit'];
    $balance_total = $de - $cr;

    $sql = "SELECT * FROM `transaction` WHERE supplier_id='$id'";
    $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
    while ($j = mysqli_fetch_array($r)) {
        $name = $j['supplier_name'];
        $date = $j['create_date'];
        $transaction = $j['transaction'];
        $total = $j['credit'];
        $reference = $j['reference'];

        ?>

        <tr>
            <td><?php echo $reference; ?></td>
            <td><?php echo $date; ?></td>
            <td><?php echo $transaction; ?></td>
            <td><?php if($total ==''){ echo $j['debit']; }else{ echo $total; }  ?></td>
            <td><?php echo $name; ?></td>
        </tr>
    <?php }

    echo "<h4>My Current Balance is: " . $balance_total . "Kes</h4>";
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
	    order: [[ 1, "desc" ]]
        });

    });
</script>
<?php include '../includes/footer.php'; ?>

