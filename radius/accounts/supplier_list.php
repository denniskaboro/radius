<?php
include 'header.php';
$per = $_SESSION['per'];
?>
<form method="POST" enctype="multipart/form-data">
    <div class="form-group col-lg-12">
        <div class="col-lg-2">
            <label for="inputStart" class="control-label">From Date</label>
            <input type="date" class="form-control" id="inputStart" name="start">
        </div>
        <div class="col-lg-2">
            <label for="inputEnd" class="control-label">End Date</label>
            <input type="date" class="form-control" id="inputEnd" name="end">
        </div>
    </div>
    <div class="col-lg-2">
        <button type="submit" name="sort" style="padding: 6px 12px !important; font-size: 15px !important;"
                class="btn btn-primary">Sort
        </button>
    </div>
</form>
<table class="table table-bordered" id="btrc">
    <h3>Supervisor List</h3>
    <thead>
    <tr>
        <th>Supervisor Name</th>
        <th>Supervisor ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Mobile</th>
        <th>Address</th>
        <th>Create Date</th>
        <th>Deposit</th>
        <th>Revenue</th>
        <th>Balance(Availabe)</th>

    </tr>
    </thead>
    <tbody>
    <?php
    $sql = "SELECT * FROM supplier ";
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);

    while ($f = mysqli_fetch_array($r)) {
        $id = $f['supplier_id'];
        ?>
        <tr>

            <td><a style=" color: #e2b9db;" href="supplier_statis.php?id=<?php echo $id; ?>"
                   role="button"><?php echo $f['full_name']; ?></a></td>
            <td><?php echo $id; ?></td>
            <td><?php echo $f['username']; ?></td>
            <td><?php echo $f['email']; ?></td>
            <td><?php echo $f['mobile']; ?></td>
            <td><?php echo $f['address']; ?></td>
            <td><?php echo $f['create_date']; ?></td>
            <?php
            if (isset($_POST['sort'])) {
                $start = $_POST['start'];
                $end = $_POST['end'];
                $sql = "SELECT SUM(debit) as de, SUM(credit) as cr FROM `transaction` WHERE  supplier_id='$id' && `create_date`<='$end' && `create_date`>='$start'";
            } else {
                $sql = "SELECT SUM(debit) as de, SUM(credit) as cr FROM `transaction` WHERE  supplier_id='$id'";
            }

            $j = mysqli_query($con, $sql) or $msg = mysqli_error($con);
            $ac = mysqli_fetch_array($j);
            $deposit = $ac['de'];
            $credit = $ac['cr'];
            $net = $credit;
            $balance = $deposit - $credit;
            ?>
            <td><?php echo $deposit; ?></td>
            <td><?php echo $credit; ?></td>
            <td><?php echo $balance; ?></td>
        </tr>

    <?php } ?>
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
</script>
<?php include '../includes/footer.php'; ?>
