<?php
include 'header.php';
include('../config/data.php');

?>
<style>
    #endForm {
        display: none;
        font-size: 25px;
        border: 3px solid salmon;
        padding: 2em;
        width: 50%;
        height: 70%;
        text-align: center;
        background: rgba(29, 13, 46, 0.95);
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        -webkit-transform: translate(-50%, -50%)

    }

</style>
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

<h3>Mpesa Success Payment History</h3>
<table class="table table-bordered" id="btrc">
    <thead>
    <tr>
        <th>Username</th>
        <th>Full Name</th>
        <th>Mobile</th>
        <th>Transaction</th>
        <th>Amount</th>
        <th>Org. Balance</th>
        <th>Date</th>
        <th>Reference</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $sum = 0;
    if (isset($_POST['sort'])) {
        $start = $_POST['start'];
        $end = $_POST['end'];
        $sql = "SELECT * FROM `mpesaresponses` where DATE_FORMAT(TransTime,'%Y-%m-%d') > '$start' and DATE_FORMAT(TransTime,'%Y-%m-%d')< '$end' && TransAmount !=0 and assigned='1'";
    } else {
        $sql = "SELECT * FROM `mpesaresponses` WHERE TransAmount !=0  and assigned='1'";
    }
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    while ($j = mysqli_fetch_array($r)) {
        $fname = $j['FirstName'];
        $mname = $j['MiddleName'];
        $lname = $j['LastName'];
        $mobile = $j['MSISDN'];
        $date = $j['TransTime'];
        $transaction = $j['TransID'];
        $debit = $j['TransAmount'];
        $sum = $debit + $sum;
        $reference = $j['BillRefNumber'];

        ?>

        <tr>
            <td><?php echo $reference; ?></td>
            <td><?php echo $fname . " " . $mname . " " . $lname; ?></td>
            <td><?php echo $mobile; ?></td>
            <td><?php echo strtoupper($transaction); ?></td>
            <td><?php echo $debit; ?></td>
            <td><?php echo $j['OrgAccountBalance']; ?></td>
            <td><?php echo $date; ?></td>
            <td><?php echo $j['TransactionType']; ?></td>
        </tr>
    <?php }
    echo "<br> <br><h4>Total Payment Balance is: " . $sum . "</h4>";
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
            order: [[8, "desc"]],
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
</script>

<?php include '../includes/footer.php'; ?>


