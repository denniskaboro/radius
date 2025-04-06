<?php

use PEAR2\Net\RouterOS;

include 'PEAR2/Autoload.php';
include 'includes/router.php';
include('config/data.php');
if (isset($_GET['val'])) {
    $val = $_GET['val'];
    try {
        $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass,$port));
        $setRequest = new RouterOS\Request(
            '/interface vlan remove'
        );
        $setRequest
            ->setArgument('.id', $val);
        $client->sendSync($setRequest);
        if ($client) {
            echo "VLAN Remove Successfully.";
        }
    } catch (Exception $e) {
        echo "<div>Unable to connect to RouterOS.</div>";
    }
}
try {
    $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass,$port));

    ?>
    <h3>VLAN List:</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Interface Name</th>
            <th>VLAN ID</th>
            <th>Physical Interface</th>
            <th>State</th>
            <th>Comment</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($util->setMenu('/interface vlan')->getAll() as $entry) { ?>
            <tr>
                <td><?php echo $entry('name'); ?></td>
                <td>
                    <?php foreach (explode(',', $entry('vlan-id')) as $topic) { ?>
                        <span><?php echo $topic; ?></span>
                    <?php } ?>
                </td>
                <td><?php echo $entry('interface'); ?></td>
                <td><?php $run = $entry('running');
                    if ($run == 'true') {
                        echo 'Running';
                    } else {
                        echo 'Stop';
                    } ?></td>
                <td><?php echo $entry('comment'); ?></td>
                <td><a class="btn btn-danger" href="vlan_list.php?val=<?php echo $entry('.id'); ?>"
                       onClick='myFunction()' id="click">
                        <i class="icon-edit"><span class="glyphicon glyphicon-trash"></span></i></a></td>


            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } catch (Exception $e) { ?>
    <div>Unable to connect to RouterOS.</div>
<?php } ?>

<?php include 'includes/footer.php'; ?>
