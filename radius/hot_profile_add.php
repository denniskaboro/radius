<?php

use PEAR2\Net\RouterOS;

include 'PEAR2/Autoload.php';
include 'includes/router.php';
include('config/data.php');
if (isset($_POST['next'])) {
    $name = $_POST['name'];
    $interface = $_POST['interface'];
    $pool = $_POST['pool'];
    try {
        $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass, $port));
        $setRequest = new RouterOS\Request(
            '/ip  hotspot add'
        );
        $setRequest
            ->setArgument('name', $name)
            ->setArgument('disabled', 'no')
            ->setArgument('addresses-per-mac', '1')
            ->setArgument('address-pool', $pool)
            ->setArgument('interface', $interface);
        $client->sendSync($setRequest);
    } catch (Exception $e) {
        echo "<div>Unable to connect to RouterOS.</div>";
    }
}
if (isset($_POST['save'])) {
    $server = $_POST['server'];
    $pro = $_POST['name'];
    $address = $_POST['address'];
    $radius = $_POST['radius'];
    $cx = strpos($address, '/');
    if ($cx) {
//        $subnet = (int)(substr($address, $cx+1));
        $ipaddr = substr($address, 0, $cx);
    }
    try {
        $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass, $port));
        $setRequest = new RouterOS\Request(
            '/ip  hotspot profile add'
        );
        $setRequest
            ->setArgument('name', $pro)
            ->setArgument('hotspot-address', $ipaddr)
//            ->setArgument('disabled', 'no')
            ->setArgument('use-radius', $radius);
        $client->sendSync($setRequest);

    } catch (Exception $e) {
        echo "<div>Unable to connect to RouterOS.</div>";
    }
    try {
        $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass, $port));
        $setRequest = new RouterOS\Request(
            '/ip hotspot set'
        );
        $setRequest
            ->setArgument('numbers', $server)
            ->setArgument('profile', $pro);
        $client->sendSync($setRequest);
        if ($client) {
            echo "Hotspot Server Create Successfully..";
        }
    } catch (Exception $e) {
        echo "<div>Unable to connect to RouterOS.</div>";
    }
}

?>
<h3>Hotspot Profile Create:</h3>

<form method="POST" data-toggle="validator" role="form">
    <div class="form-group col-sm-4">
        <div class="form-group">
            <label for="LoginUser" class="control-label">Server Name:</label>
            <input type="text" class="form-control" value="<?php if (isset($name)) {
                echo $name;
            } ?>" name="server" readonly>
        </div>
        <div class="form-group">
            <label for="LoginUser" class="control-label">Profile Name:</label>
            <input type="text" class="form-control" placeholder="Profile Name" name="name">
        </div>
        <label>Local Address:</label>
        <select class="form-control" name="address" required>
            <option>Select</option>
            <?php
            try {
                $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass, $port));
                foreach ($util->setMenu('/ip address')->getAll() as $entry) {
                    echo "<option value='" . $entry('address') . "'>" . $entry('interface') . '- ' . $entry('address') . "</option>";
                }
            } catch (Exception $e) { ?>
                <div>Unable to connect to RouterOS.</div>
            <?php } ?>
        </select><br>

        <label class="control-label">Use Radius Authentication:</label>
        <select class="form-control" name="radius" required>
            <option></option>
            <option value="no">No</option>
            <option value="yes">Yes</option>
        </select><br>
        <button type="submit" name="save" class="btn btn-default">Submit</button>
    </div>
</form>
<script type="text/javascript" src="component/validator.js"></script>
<script type="text/javascript" src="component/validator.min.js"></script>
<?php include 'includes/footer.php'; ?>
