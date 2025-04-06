<?php include 'includes/header.php';
if (isset($_GET['del'])) {
    $id = $_GET['del'];
    if ($per == "Admin") {
        $sq = "DELETE FROM log WHERE id='$id'  ";
        $res = mysqli_query($con,$sq);
        echo "Log Delete Successfully...";
	$API->log($wh,"Log Delete Successfully");
	}else{
	echo "<h4 style='color: coral ;'>You have no permission...</h4>";
	}
    }
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
        <th>Username</th>
        <th>Time</th>
	    <th>Remote Address</th>
        <th>Working History</th>
	    <th>Delete</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $d = time() - (86400 * 2);
    $date = date("Y-m-d H:i", $d);
    if (isset($_POST['sort'])) {
        $start = $_POST['start'];
        $end = $_POST['end'];
        $start=date("Y-m-d",strtotime($start));
        $end=date("Y-m-d",strtotime($end));
        $sql = "SELECT * FROM log WHERE DATE_FORMAT(time,'%Y-%m-%d') >='$start' && DATE_FORMAT(time,'%Y-%m-%d') <='$end' ORDER BY id DESC ";
    }else{
        $sql = "SELECT * FROM log WHERE time >='$date' ORDER BY id DESC ";
    }
    $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
    while ($f = mysqli_fetch_array($r)) {

        ?>
        <tr>

            <td><a style=" color: #fae2f4;" href="show_user.php?name=<?php echo $f['username']; ?>"
                   role="button" style="color: #ffffff;font-size:16px;"><?php echo $f['username']; ?></a></td>
            <td><?php echo $f['time']; ?></td>
	     <td><?php echo $f['remote_host']; ?></td>
            <td><?php echo $f['work']; ?></td>
		<td><a class="btn btn-danger" onclick="myFunction(this.id)" id="<?php echo $f['id']; ?>"
                       href="working_log.php">
                        <i class="icon-edit"><span class="glyphicon glyphicon-trash"></span></i></a></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<script type="text/javascript">
        function myFunction(str) {
            var r = confirm("Confirm to do this?");
            if (r == true) {
                document.getElementById(str).href = "working_log.php?del=" + str;
            } else {
                document.getElementById(str).href = "working_log.php";
            }
        }

    </script>
<link rel="stylesheet" type="text/css" href="component/jquery.dataTables.min.css"/>
<script type="text/javascript" src="component/js/export/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#btrc').DataTable({
            pageLength: 15,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            order: [[ 1, "desc" ]]
        });

    });
</script>
<?php include 'includes/footer.php'; ?>
