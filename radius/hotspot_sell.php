<?php
include 'includes/header.php';
?>
<div class="server col-md-12">
    <form method="POST" data-toggle="validator" role="form">
        <div class="form-group col-sm-4">
            <label>Type</label>
            <select class="form-control" name="type">
                <option>All</option>
                <option value="0">Success Payment</option>
                <option>Unsuccessful Payment</option>
            </select>
        </div>
        <div class="form-group col-sm-4">
            <label>Start Date</label>
            <input type="date" class="form-control" name="start_date">
        </div>
        <div class="form-group col-sm-4">
            <label>End Date</label>
            <input type="date" class="form-control" name="end_date">
        </div>
        <button type="submit" name="search" class="btn btn-default">Search</button>
    </form>
</div>


<h3>Hotspot User's Payment History</h3>
<table class="table table-bordered" id="btrc">
    <thead>
    <tr>
        <th>Username</th>
        <th>Trx Id</th>
        <th>Amount</th>
        <th>Number</th>
        <th>CheckoutRequest ID</th>
        <th>Date</th>
        <th>Result Desc.</th>
        <th>Delete</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $sum = 0;
    if (isset($_GET['search'])) {
        if ($_POST['type'] != "") {
            $type = $_POST['type'];
            if ($type == "All") {
                $where = " resultCode IS NOT NULL";
            } else if ($type == 0) {
                $where = " resultCode='0'";
            } else {
                $where = " resultCode !='0'";
            }
        }
        if ($_POST['start_date'] != "") {
            $start = strtotime($_POST['start_date']);
            $where .= " and UNIX_TIMESTAMP(create_date)>='$start'";
        }
        if ($_POST['end_date'] != "") {
            $end = strtotime($_POST['end_date']);
            $where .= " and UNIX_TIMESTAMP(create_date)<='$end'";
        }
        $sql = "SELECT * FROM `hotspot_payment` WHERE $where";

    } else {
        $start = strtotime("-7days");
        $sql = "SELECT * FROM `hotspot_payment` WHERE UNIX_TIMESTAMP(create_date)>='$start'";
    }
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    while ($f = mysqli_fetch_array($r)) {
        $id = $f['id'];
        ?>

        <tr>
            <td><?php echo $f['username']; ?></td>
            <td><?php echo $f['trxId']; ?></td>
            <td><?php echo $f['amount'];
                $sum = $sum + $f['amount']; ?></td>
            <td><?php echo $f['number']; ?></td>
            <td><?php echo $f['checkoutRequestID']; ?></td>
            <td><?php echo $f['create_date']; ?></td>
            <td><?php echo $f['resultDesc']; ?></td>
            <td>
                <a class="btn btn-sm btn-primary" href="hotspot_sell.php?id=<?php echo $id; ?>">
                    <i class="fa fa-trash"></i>
                </a>
            </td>
        </tr>
    <?php }
    echo "<br> <br><h4>Total Payment Balance is: " . $sum . "</h4>";
    ?>
    </tbody>

</table>
<link rel="stylesheet" type="text/css" href="component/jquery.dataTables.min.css"/>
<script type="text/javascript" src="component/js/export/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#btrc').DataTable({
            pageLength: 15,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            order: [[5, 'DESC']]

        });

    });
</script>
<?php include 'includes/footer.php'; ?>


