<?php

use PEAR2\Net\RouterOS;

include 'PEAR2/Autoload.php';
include 'includes/router.php';
include('config/data.php');
?>
<form method="post">
    <div class="form-group col-lg-6 col-md-6 col-sm-6">
        <label>Type Your Command:</label>
        <textarea col="4" row="5" class="form-control" name="cmd"></textarea>
        <input type="submit" name="show" value="RUN" class="btn btn-danger">
    </div>
</form>

<?php
if(isset($_POST['show'])) {
    $cmd = $_POST['cmd'];
echo "<div class='col-lg-12 col-md-12 col-sm-12'>";
    try {
        $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass, $port));
        $util->exec($cmd); 

    } catch (Exception $e) {
        echo "Unable to connect to RouterOS.";
    }
}
echo "</div>";
?>

<?php include 'includes/footer.php'; ?>

