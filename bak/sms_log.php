<?php include 'includes/header.php';
?>
<form method="POST" enctype="multipart/form-data">
    <div class="form-group col-lg-12">
        <div class="col-lg-2">
            <label for="inputStart" class="control-label">From Date</label>
            <input type="date" class="form-control" id="inputStart" name="start" required>
        </div>
        <div class="col-lg-2">
            <label for="inputEnd" class="control-label">End Date</label>
            <input type="date" class="form-control" id="inputEnd" name="end" required>
        </div>
        <div class="col-lg-2" style="margin-top: 30px !important;width: 50px !important;height: 40px !important;">
            <input type="submit" value="Submit" name="sort" class="btn btn-primary"/>
        </div>
    </div>
</form>
<h3>User Working Log</h3>
<table class="table table-bordered" id="btrc">
    <thead>
    <tr>
        <th>Mobile</th>
        <th>Message</th>
        <th>Send Time</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $d = time() - (86400 * 3);
    $date = date("Y-m-d H:i", $d);
    if (isset($_POST['sort'])) {
        $start = $_POST['start'];
        $end = $_POST['end'];
        $start=date("Y-m-d",strtotime($start));
        $end=date("Y-m-d",strtotime($end));
        $sql = "SELECT * FROM sms WHERE DATE_FORMAT(time,'%Y-%m-%d') >='$start' && DATE_FORMAT(time,'%Y-%m-%d') <='$end' ORDER BY id DESC ";
    }else{
        $sql = "SELECT * FROM sms WHERE time >='$date' ORDER BY id DESC ";
    }
    $r = mysqli_query($conn,$sql) or $msg = mysqli_error($conn);
    while ($f = mysqli_fetch_array($r)) {

        ?>
        <tr>
            <td><?php echo $f['mobile']; ?></td>
            <td><?php echo $f['otp']; ?></td>
            <td><?php echo $f['time']; ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<link rel="stylesheet" type="text/css" href="component/jquery.dataTables.min.css"/>
<script type="text/javascript" src="component/js/export/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#btrc').DataTable({
            pageLength: 15,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            order: [[ 2, "desc" ]]
        });

    });
</script>
<?php include 'includes/footer.php'; ?>
