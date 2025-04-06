<?php

use PEAR2\Net\RouterOS;

include 'PEAR2/Autoload.php';
include 'includes/router.php';
include('config/data.php');
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $local = $_POST['local'];
    $remote = $_POST['remote'];
    $dns = $_POST['dns'];
    $cx = strpos($local, '/');
    if ($cx) {
//        $subnet = (int)(substr($address, $cx+1));
        $local = substr($local, 0, $cx);
    }
    try {
        $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass,$port));
        $setRequest = new RouterOS\Request(
            '/ppp profile add'
        );
        if ($remote != null) {
            $setRequest
                ->setArgument('name', $name)
                ->setArgument('local-address', $local)
                ->setArgument('remote-address', $remote)
                ->setArgument('dns-server', $dns);
            $client->sendSync($setRequest);
        } else {
            $setRequest
                ->setArgument('name', $name)
                ->setArgument('local-address', $local)
                ->setArgument('dns-server', $dns);
            $client->sendSync($setRequest);
        }

        if ($client) {
            echo "PPPoE Profile Created Successfully..";
        }
    } catch (Exception $e) {
        echo "<div>Unable to connect to RouterOS.</div>";
    }
}

?>
<h3>PPPoE Profile Create:</h3>

<form method="POST" data-toggle="validator" role="form">
    <div class="form-group col-sm-4">
        <div class="form-group">
            <label for="LoginUser" class="control-label">Profile Name:</label>
            <input type="text" class="form-control" placeholder="Description" name="name">
        </div>
        <label>Local Address:</label>
        <select class="form-control" name="local">
            <option></option>
            <?php
            try {
                $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass,$port));
                foreach ($util->setMenu('/ip address')->getAll() as $entry) {
                    echo "<option value=" . $entry('address') . ">" . $entry('interface') . "  " . $entry('address') . "</option>";
                }
            } catch (Exception $e) { ?>
                <div>Unable to connect to RouterOS.</div>
            <?php } ?>
        </select><br>
        <label>Remote Address:</label>
        <select class="form-control" name="remote">
            <option></option>
            <?php
            try {
                $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass,$port));
                foreach ($util->setMenu('/ip pool')->getAll() as $entry) {
                    echo "<option>" . $entry('name') . "</option>";
                }
            } catch (Exception $e) { ?>
                <div>Unable to connect to RouterOS.</div>
            <?php } ?>
        </select><br>
        <div class="form-group">
            <label for="DNS" class="control-label">DNS Server(Optional):</label>
            <input type="text" class="form-control" placeholder="DNS Server Address " name="dns">
        </div>

        <button type="submit" name="add" class="btn btn-default">Submit</button>
    </div>
</form>
<script type="text/javascript" src="component/validator.js"></script>
<script type="text/javascript" src="component/validator.min.js"></script>
<?php include 'includes/footer.php'; ?>
