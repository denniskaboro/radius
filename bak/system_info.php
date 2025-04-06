<?php

use PEAR2\Net\RouterOS;

include 'PEAR2/Autoload.php';
include 'includes/router.php';
include('config/data.php');
if (isset($_POST['reboot'])) {
    try {
        $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass,$port));
        $setRequest = new RouterOS\Request(
            '/system reboot'
        );
        $client->sendSync($setRequest);
        if ($client) {
            echo $API->alert("Router Reboot Successfull!");
            $API->log($admin_id, $wh, $ip . "Router Reboot");
        }
    } catch (Exception $e) {
        echo $API->alert("Unable to connect to RouterOS.");
    }
}
try {
    $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass,$port));

    ?>
<style>
    .table-bordered>tbody>tr{
        font-size: 17px !important;
    }

</style>
    <div class="col-md-8">
        <h3>RouterBoard Information:</h3>
	<form method="post">
        <div class="form-group float-right">
            <button class="btn btn-danger" name="reboot" type="submit">Reboot</button>
        </div>
    </form>
        <table class="table table-bordered">
            <tbody>
            <?php foreach ($util->setMenu('/system resource')->getAll() as $entry) {?>
                <tr>
                    <td>Router Board</td>
                    <td><?php echo $entry('platform').': '.$entry('board-name'); ?></td>
                </tr>
                <tr>
                    <td>Uptime</td>
                    <td><?php echo $entry('uptime'); ?></td>
                </tr>
                <tr>
                    <td>Architecture</td>
                    <td><?php echo $entry('architecture-name'); ?></td>
                </tr>
                <tr>
                    <td>Version</td>
                    <td><?php echo $entry('version'); ?></td>
                </tr>
                <tr>
                    <td>Total Memory</td>
                    <td><?php echo sprintf("%.2f", $entry('total-memory')/(1024*1024))."MB"; ?></td>
                </tr>
                <tr>
                    <td>Free Memory</td>
                    <td><?php echo sprintf("%.2f", $entry('free-memory')/(1024*1024))."MB";?></td>
                </tr>
                <tr>
                    <td>CPU</td>
                    <td><?php echo $entry('cpu'); ?></td>
                </tr>
                <tr>
                    <td>CPU Core</td>
                    <td><?php echo $entry('cpu-count'); ?></td>
                </tr>
                <tr>
                    <td>CPU Frequency</td>
                    <td><?php echo $entry('cpu-frequency')."MHz"; ?></td>
                </tr>
                <tr>
                    <td>CPU load</td>
                    <td><?php echo $entry('cpu-load')."%"; ?></td>
                </tr>
                <tr>
                    <td>Total HDD</td>
                    <td><?php echo sprintf("%.2f", $entry('total-hdd-space')/(1024*1024))."MB"; ?></td>
                </tr>
                <tr>
                    <td>Free HDD</td>
                    <td><?php echo sprintf("%.2f", $entry('free-hdd-space')/(1024*1024))."MB"; ?></td>
                </tr>
                <tr>
                    <td>Bad Blocks</td>
                    <td><?php echo $entry('bad-blocks'); ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <?php } catch (Exception $e) { ?>
            <div>Unable to connect to RouterOS.</div>
        <?php } ?>
    </div>


<?php include 'includes/footer.php'; ?>
