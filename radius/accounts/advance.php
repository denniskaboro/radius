<?php
include('header.php');
include('../config/data.php');
if (isset($_POST['submit'])) {
    $userid = $_POST['id'];
    $date = $_POST['date'];
    $trans = $_POST['trans'];
//    $newDate = date("d M Y H:i", strtotime($date));
    $sql = "UPDATE transaction SET date='$date',transaction='$trans' WHERE id='$userid'";
    $qu = mysqli_query($con, $sql);
    if ($qu) {
        echo "Data has Successfully Saved.";
        $API->log($wh, "Transaction ID has been updated");
    }

}
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
<style>

    #myForm {
        display: none;
        border: 3px solid salmon;
        padding: 2em;
        width: 550px;
        text-align: center;
        background: rgba(33, 12, 45, 0.93);
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        -webkit-transform: translate(-50%, -50%)

    }

    #bkash {
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

    #Show {
        display: none;
        border: 3px solid salmon;
        padding: 2em;
        width: 50%;
        height: 90%;
        text-align: center;
        background: rgba(111, 60, 73, 0.98);
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        -webkit-transform: translate(-50%, -50%)

    }

    .formBtn {
        width: 140px;
        display: inline-block;

        background: teal;
        color: #fff;
        font-weight: 100;
        font-size: 1.2em;
        border: none;
        height: 30px;
    }

    .loader {
        border: 16px solid rgba(107, 151, 192, 0.9); /* Light grey */
        border-top: 16px solid rgb(220, 11, 69); /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
</style>
<div id="last"></div>
<h3>User Payment History</h3>
<table class="table table-bordered" id="btrc">
    <thead>
    <tr>
        <th>Supervisor ID</th>
        <th>Name</th>
        <th>Mobile</th>
        <th>Address</th>
        <th>Amount</th>
        <th>OTC</th>
        <th>Transaction Number</th>
        <th>Date</th>
        <th>Payment ID</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $total = 0;
    if (isset($_POST['sort'])) {
        $start = $_POST['start'];
        $end = $_POST['end'];
        $sql = "SELECT * FROM transaction WHERE debit !=0 && create_date <='$end' && create_date>='$start'";
    } else {
        $sql = "SELECT * FROM transaction WHERE debit !=0";
    }

    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    while ($f = mysqli_fetch_array($r)) {
        $total = $f['debit'] + $total;
        ?>
        <tr class="bg-info">

            <td><?php echo $f['supplier_id']; ?></td>
            <td><?php echo $f['supplier_name']; ?></td>
            <?php
            $id = $f['supplier_id'];
            $name = $f['supplier_name'];
            $sql1 = "SELECT * FROM supplier WHERE supplier_id='$id'";
            $row = mysqli_query($con, $sql1);
            $g = mysqli_fetch_array($row);
            ?>
            <td><?php echo $g['mobile']; ?></td>
            <td><?php echo $g['address']; ?></td>
            <td><?php echo $f['debit']; ?></td>
            <td><?php echo $f['otc']; ?></td>
            <td><?php echo $f['transaction']; ?></td>
            <td><?php echo $f['create_date']; ?></td>
            <td><?php echo $f['reference']; ?></td>

        </tr>
    <?php } ?>
    <h2>Total payment is: <?php echo $total; ?></h2>
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
