<?php include 'includes/header.php';
if (ISSET($_GET['val'])) {
    $id = $_GET['val'];
    $sql = "DELETE FROM radpostauth WHERE id='$id'";
    mysqli_query($con,$sql);
}

$del = "DELETE FROM radpostauth WHERE authdate <= DATE_SUB(NOW(), INTERVAL 1 HOUR)";
$succ = mysqli_query($con,$del);
?>
<div id="log"></div>
<button class="btn-warning" style="color: #ffffff; padding: 5px;font-size:14px;" onclick='Auth()'>Clear</button>
<h3>User Authentication Log</h3>
<form method="POST" enctype="multipart/form-data">
    <div class="form-group col-lg-12">
        <div class="col-lg-2">
            <label for="inputStart" class="control-label">Change log Time</label>
            <select name="start">
                <option>Select</option>
                <option value="10">10 Min</option>
                <option value="30">30 Min</option>
                <option value="60">1 Hour</option>
            </select>
        </div>
    </div>
    <div class="col-lg-2">
        <button type="submit" name="sort" style="padding: 6px 12px !important; font-size: 15px !important;"
                class="btn btn-primary">Show
        </button>
    </div>
</form>
<table class="table table-bordered" id="btrc">
    <thead>
    <tr>
        <th>Username</th>
        <th>User Password</th>
	<th>Expire Date</th>
        <th>NAS IP</th>
        <th>MAC</th>
        <th>Reply</th>
        <th>Reason</th>
        <th>Authentication Date</th>
        <th>Disable</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if (isset($_POST['sort'])) {
        $time = $_POST['start'];
        if ($time == "Select") {
            $time = 5;
        }
    }else{
	$time=2;
}
    $sql = "SELECT * FROM radpostauth WHERE authdate >= DATE_SUB(NOW(), INTERVAL $time MINUTE) ORDER BY id DESC ";
    $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
    while ($f = mysqli_fetch_array($r)) {

        ?>
        <tr>

            <td><a style=" color: #fae2f4;" href="user_statistics.php?name=<?php echo $f['username']; ?>"
                   role="button" style="color: #ffffff;font-size:16px;"><?php echo $f['username']; ?></a></td>
            <td><?php echo $f['pass']; ?></td>
		<?php
                $nn=$f['username'];
                $sql1 = "SELECT * FROM radcheck WHERE username='$nn' && attribute='Expiration'";
                $rr = mysqli_query($con,$sql1);
                $row=mysqli_num_rows($rr);
                if($row>0){
                    $us=mysqli_fetch_array($rr);
                    $value=$us['value'];
                    echo "<td>".$value."</td>";
                }else{
                    echo "<td> </td>";
                }
                ?>
            <td><?php echo $f['nas']; ?></td>
            <td><?php echo $f['mac']; ?></td>
            <td><?php echo $f['reply']; ?></td>
            <td><?php echo $f['message']; ?></td>
            <td><?php echo $f['authdate']; ?></td>
            <td><btn class="btn btn-danger" onclick="Select(<?php echo $f['id'];?>)">
                    <i class="icon-edit"><span class="glyphicon glyphicon-off"></span></i></btn></td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<script>
    function Select(str) {
        if (str == "") {
            document.getElementById("log").innerHTML = "";
            return;
        }
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("log").innerHTML = this.responseText;
            }
        }
        xmlhttp.open("GET", "mac_filter.php?id=" + str, true);
        xmlhttp.send();
    }
</script>

<script>
    function Auth() {
        var r = confirm("Confirm to Clear ?");
        if (r == true) {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else { // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("log").innerHTML = this.responseText;
                }
            }
            xmlhttp.open("GET", "clear.php?auth", true);
            xmlhttp.send();
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
            order: [[5, "desc"]]
        });

    });
</script>
<?php include 'includes/footer.php'; ?>
