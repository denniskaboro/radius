<?php
include 'includes/header.php';
include('config/data.php');
$API = new log_api();
$per = $_SESSION['per'];
$wh = $_SESSION['username'];
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $work = $_GET['name'];
    if ($per == "Admin") {
        $sq = "DELETE FROM transaction WHERE id='$id'";
        $res = mysqli_query($con, $sq);
        $msg = $work . " Delete Successfully ";
        $API->log($wh, $msg . " from transaction table");
        echo "<h3 style='color: red;'>Delete Successfully..</h3>";
    }
}
?>
<form method="POST" enctype="multipart/form-data">
    <div class="form-group col-md-2">

        <label for="inputStart" class="control-label">Search By Date:</label>
        <input type="text" class="form-control" id="inputStart" name="start"><br>
        <button type="submit" name="sort" style="padding: 6px 12px !important; font-size: 15px !important;"
                class="btn btn-primary">Search
        </button>
    </div>
</form>
<?php if (isset($_POST['sort'])) {
    $date = $_POST['start'];
    ?>
    <h3>Transaction History</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Name</th>
            <th>Date</th>
            <th>Transaction</th>
            <th>Ammount</th>
            <th>Remark</th>
            <?php if ($per == "Admin") { ?>
                <th>Delete</th>
            <?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT * FROM `transaction` WHERE `create_date`='$date' ORDER BY id DESC";
        $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
        while ($j = mysqli_fetch_array($r)) {
            $name = $j['supplier_name'];
            $date = $j['create_date'];
            $transaction = $j['transaction'];
            $credit = $j['credit'];
            $total = $credit + $de;
            $reference = $j['reference'];

            ?>

            <tr>
                <td><?php echo $reference; ?></td>
                <td><?php echo $date; ?></td>
                <td><?php echo $transaction; ?></td>
                <td><?php echo $total; ?></td>
                <td><?php echo $name; ?></td>
                <?php if ($per == "Full" || $per == "Admin") { ?>
                    <td><a class="btn btn-danger" id="link"
                           href="optional.php?id=<?php echo $j['id']; ?>&name=<?php echo $reference; ?>">
                            <i class="icon-edit"><span class="glyphicon glyphicon-trash"></span></i></a></td>
                <?php } ?>
            </tr>
        <?php }

        ?>
        </tbody>

    </table>
<?php }
include 'includes/footer.php'; ?>

