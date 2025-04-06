<?php
include 'includes/header.php';
include('config/data.php');
?>
<link rel="stylesheet" href="component/js/export/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="component/js/export/buttons.dataTables.min.css"/>
<script type="text/javascript" src="component/js/export/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="component/js/export/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="component/js/export/jszip.min.js"></script>
<script type="text/javascript" src="component/js/export/pdfmake.min.js"></script>
<script type="text/javascript" src="component/js/export/vfs_fonts.js"></script>
<script type="text/javascript" src="component/js/export/buttons.html5.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#btrc').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excel',
                text: 'Excel',
                className: 'exportExcel',
                filename: 'btrc_Excel',
                exportOptions: {modifier: {page: 'all'}}
            },
                {
                    extend: 'csv',
                    text: 'CSV',
                    className: 'exportExcel',
                    filename: 'btrc_Csv',
                    exportOptions: {modifier: {page: 'all'}}
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    className: 'exportExcel',
                    filename: 'btrc_Pdf',
                    exportOptions: {modifier: {page: 'all'}}
                }]
        });

    });
</script>

<h3>Transaction History</h3>
<table class="table table-bordered" id="btrc">
    <thead>
    <tr>
        <th>Name</th>
        <th>Date</th>
        <th>Transaction</th>
        <th>Amount</th>
        <?php if ($per == "Admin") { ?>
            <th>Edit</th>
        <?php } ?>
        <th>Remark</th>

    </tr>
    </thead>
    <tbody>
    <?php
    $id = $_GET['id'];
    $sql = "SELECT * FROM `transaction` WHERE supplier_id='$id' ORDER BY id DESC";
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    while ($j = mysqli_fetch_array($r)) {
        $name = $j['supplier_name'];
        $date = $j['create_date'];
        $transaction = $j['transaction'];
        $credit = $j['credit'];
        $reference = $j['reference'];

        ?>

        <tr>
            <td><?php echo $reference; ?></td>
            <td><?php echo $date; ?></td>
            <td><?php echo $transaction; ?></td>
            <td><?php echo $credit; ?></td>
            <?php if ($per == "Admin") { ?>
                <td><a class="btn" href="edit_tran.php?id=<?php echo $j['id']; ?>" style="color: #ffffff;">
                        <i class="icon-edit"><span class="glyphicon glyphicon-pencil"></span></i></a></td>
            <?php } ?>
            <td><?php echo $name; ?></td>
        </tr>
    <?php }

    $amm = "SELECT SUM(credit) as credit
       FROM `transaction` WHERE supplier_id='$id'";
    $total = mysqli_query($con, $amm);
    $d = mysqli_fetch_array($total);
    $cr = $d['credit'];
    echo "<h4>" . $name . " Total Revenew: " . $cr . "Kes</h4>";
    ?>
    </tbody>

</table>
<?php include 'includes/footer.php'; ?>

