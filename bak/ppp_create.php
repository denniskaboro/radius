<?php

use PEAR2\Net\RouterOS;

include 'PEAR2/Autoload.php';
include 'includes/router.php';
include('config/data.php');
if (isset($_POST['add'])) {
    $profile = $_POST['profile'];
    $service = $_POST['service'];
    $interface = $_POST['interface'];
    try {
        $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass,$port));
        $setRequest = new RouterOS\Request(
            '/interface pppoe-server server add'
        );
        $setRequest
            ->setArgument('service-name', $service)
            ->setArgument('default-profile', $profile)
            ->setArgument('disabled', 'no')
            ->setArgument('authentication', 'pap')
            ->setArgument('interface', $interface);
        $client->sendSync($setRequest);
    } catch (Exception $e) {
        echo "<div>Unable to connect to RouterOS.</div>";
    }
    try {
        $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass,$port));
        $setRequest = new RouterOS\Request(
            '/ppp aaa set'
        );
        $setRequest
            ->setArgument('use-radius', 'yes')
            ->setArgument('accounting', 'yes');
        $client->sendSync($setRequest);
        if ($client) {
            echo "PPPoE Server Create Successfully..";
        }

    } catch (Exception $e) {
        echo "<div>Unable to connect to RouterOS.</div>";
    }
}

?>
<h3>PPPoE Server Create:</h3>

<form method="POST" data-toggle="validator" role="form">
    <div class="form-group col-sm-4">
        <div class="form-group">
            <label for="LoginUser" class="control-label">Service Name:</label>
            <input type="text" class="form-control" placeholder="Description" name="service">
        </div>
        <label>Profile:</label>
        <select required class="form-control" name="profile" required>
            <option>Select</option>
            <?php
            try {
                $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass,$port));
                foreach ($util->setMenu('/ppp profile')->getAll() as $entry) {
                    echo "<option>" . $entry('name') . "</option>";
                }
            } catch (Exception $e) { ?>
                <div>Unable to connect to RouterOS.</div>
            <?php } ?>
        </select><br>
        <label>Interface:</label>
        <select required class="form-control" name="interface" required>
            <option>Select</option>
            <?php
            try {
                $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass, $port));
                foreach ($util->setMenu('/interface')->getAll() as $entry) {
                    echo "<option>" . $entry('name') . "</option>";
                }
            } catch (Exception $e) { ?>
                <div>Unable to connect to RouterOS.</div>
            <?php } ?>
        </select><br>

        <button type="submit" name="add" class="btn btn-default">Submit</button>
    </div>
</form>
<script type="text/javascript" src="component/validator.js"></script>
<script type="text/javascript" src="component/validator.min.js"></script>
<?php include 'includes/footer.php'; ?>
