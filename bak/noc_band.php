<?php

use PEAR2\Net\RouterOS;

include 'PEAR2/Autoload.php';
include('config/data.php');
include('includes/header.php');
if(isset($_POST['add'])) {
	$raw=$_POST['ip'];
    $user = 'radius';
    $pass = 'bandAPIr@d!us';
    $sup=$_POST['sup'];
        $nsql = "SELECT * FROM nas WHERE shortname='$sup'";
        $nque = mysqli_query($con,$nsql);
        $n = mysqli_fetch_array($nque);
        $ip = $n['nasname'];
            $ch = ip2long($ip);
            $c = $ch - 1;
            $ip = long2ip($c);
            try {
                $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass));
                $util->setMenu('/queue simple');
                $util->edit($sup, 'max-limit', $raw . "M/" . $raw . "M");
		echo "<h4 style='Color:lightgreen;'>Queue has been Changed</h4>";
            } catch (Exception $e) {
                echo "<div>Unable to connect to RouterOS.".$ip."</div>";
            }
}
?>
<h3>Dynamic Bandwidth Set:</h3>
<form method="POST" data-toggle="validator" role="form">
    <div class="form-group col-sm-4">
       
	<div class="form-group">
            <label for="inputAdd" class="control-label">Supplier ID</label>
            <input type="text" class="form-control" id="inputAdd" name="sup" required >
        </div>
	<div class="form-group">
            <label for="inputAdd" class="control-label">IP Bandwidth</label>
            <input type="number" class="form-control" id="inputAdd" name="ip" required >
        </div>
        <button type="submit" name="add" class="btn btn-default">Submit</button>
    </div>
</form>
<script type="text/javascript" src="component/validator.js"></script>
<script type="text/javascript" src="component/validator.min.js"></script>
<?php include 'includes/footer.php'; ?>
