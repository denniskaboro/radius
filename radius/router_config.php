<?php

use PEAR2\Net\RouterOS;

include 'PEAR2/Autoload.php';
include 'includes/router.php';
include('config/data.php');
if (isset($_POST['add'])) {
    $dns = $_POST['dns'];
    $sql = "SELECT * FROM nas";
    $que = mysqli_query($con, $sql);
    while ($f = mysqli_fetch_array($que)) {
        $ip = $f['nasname'];
        $user = $f['login_user'];
        $pass = $f['login_password'];
        try {
            $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass));
            $setRequest = new RouterOS\Request(
                '/ppp aaa set'
            );
            $setRequest
                ->setArgument('interim-update', $dns);

            $client->sendSync($setRequest);

        } catch (Exception $e) {
            echo $ip . "<br>";
        }


    }
}

?>
<h3>New IP Pool Add:</h3>

<form method="POST" data-toggle="validator" role="form">
    <div class="form-group col-sm-4">

        <div class="form-group">
            <label for="inputAdd" class="control-label">DNS Server</label>
            <input type="text" class="form-control" id="inputAdd" name="dns"
                   placeholder="Example: 10.10.10.1,10.10.10.254">
        </div>
        <button type="submit" name="add" class="btn btn-default">Submit</button>
    </div>
</form>
<script type="text/javascript" src="component/validator.js"></script>
<script type="text/javascript" src="component/validator.min.js"></script>
<?php include 'includes/footer.php'; ?>
