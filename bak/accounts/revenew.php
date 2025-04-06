<?php
include 'header.php';
include('../config/data.php');
?>

<h3>Transaction History</h3>
<form method="POST" enctype="multipart/form-data">
    <div class="form-group col-lg-12">
        <div class="col-lg-2">
            <label for="inputStart" class="control-label">From create_date</label>
            <input type="date" class="form-control" id="inputStart" name="start">
        </div>
        <div class="col-lg-2">
            <label for="inputEnd" class="control-label">End create_date</label>
            <input type="date" class="form-control" id="inputEnd" name="end">
        </div>
        <div class="col-lg-2">
            <label for="ID" class="control-label">Supervisor ID</label>
            <input type="text" class="form-control" id="ID" name="id" placeholder="Supervisor ID">
        </div>
    </div>
    <div class="col-lg-2">
        <button type="submit" name="sort" class="btn btn-primary">Sort</button>
    </div>
</form>
<table class="table table-bordered" id="btrc">
    <thead>
    <tr>
        <th>Name</th>
        <th>Supervisor ID</th>
        <th>create_date</th>
        <th>Transaction</th>
        <th>Deposit</th>
        <th>Revenue</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if (isset($_POST['sort'])) {
        $start = $_POST['start'];
        $end = $_POST['end'];
        $id = $_POST['id'];
        if ($start != null && $end != null && $id != null) {
            $amm = "SELECT SUM(debit) as debit,SUM(credit) as credit
       FROM `transaction` WHERE supplier_id='$id' && create_date>='$start' && create_date<='$end'";
            $sql = "SELECT * FROM `transaction` WHERE supplier_id='$id' && create_date>='$start' && create_date<='$end' 
ORDER BY id DESC ";
        } else if ($start != null && $end != null) {
            $amm = "SELECT SUM(debit) as debit,SUM(credit) as credit
       FROM `transaction` WHERE create_date>='$start' && create_date<='$end'";
            $sql = "SELECT * FROM `transaction` WHERE create_date>='$start' && create_date<='$end' 
ORDER BY id DESC ";
        } else {
            $amm = "SELECT SUM(debit) as debit,SUM(credit) as credit
       FROM `transaction` WHERE supplier_id='$id'";
            $sql = "SELECT * FROM `transaction` WHERE supplier_id='$id' 
ORDER BY id DESC ";
        }
    } else {
        $amm = "SELECT SUM(debit) as debit,SUM(credit) as credit
       FROM `transaction`";
        $sql = "SELECT * FROM `transaction` ";
    }

    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);

    while ($j = mysqli_fetch_array($r)) {
        $name = $j['supplier_name'];
        $create_date = $j['create_date'];
        $transaction = $j['transaction'];
        $credit = $j['credit'];
        $reference = $j['reference'];

        ?>

        <tr>
            <td><?php echo $name; ?></td>
            <td><?php echo $j['supplier_id']; ?></td>
            <td><?php echo $create_date; ?></td>
            <td><?php echo $transaction; ?></td>
            <td><?php echo $j['debit']; ?></td>
            <td><?php echo $credit; ?></td>
        </tr>
    <?php }
    $total = mysqli_query($con, $amm);
    $d = mysqli_fetch_array($total);
    $de = $d['debit'];
    $cr = $d['credit'];
    echo "<h4>Total Revenue: " . $cr . "Kes</h4>";
    echo "<h4>Total Payments: " . $de . "Kes</h4>";
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
    $(document).ready(function() {
        $('#btrc').DataTable( {
            dom: 'Bfrtip',
            pageLength: 15,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            order: [[3, "desc"]],
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        } );
    } );
</script>

<?php include '../includes/footer.php'; ?>

