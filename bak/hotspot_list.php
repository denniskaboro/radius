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
            '/ip  hotspot remove'
        );
        $setRequest
            ->setArgument('.id', $val);
        $client->sendSync($setRequest);
        if ($client) {
            echo "Hotspot Server Remove Successfully.";
        }
    } catch (Exception $e) {
        echo "<div>Unable to connect to RouterOS.</div>";
    }
}
try {
    $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass,$port));

    ?>
    <h3>Hotspot Server List:</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Name</th>
            <th>Interface</th>
            <th>Address Pool</th>
            <th>Profile</th>
            <th>Idle Timeout</th>
            <th>Remove Server</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($util->setMenu('/ip  hotspot')->getAll() as $entry) { ?>
            <tr>
                <td><?php echo $entry('name'); ?></td>
                <td>
                    <?php foreach (explode(',', $entry('interface')) as $topic) { ?>
                        <span><?php echo $topic; ?></span>
                    <?php } ?>
                </td>
                <td><?php echo $entry('address-pool'); ?></td>
                <td><?php echo $entry('profile'); ?></td>
                <td><?php echo $entry('idle-timeout'); ?></td>
                <td><a class="btn btn-danger" href="hotspot_list.php?val=<?php echo $entry('.id'); ?>"' id="click">
                    <i class="icon-edit"><span class="glyphicon glyphicon-trash"></span></i></a></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } catch (Exception $e) { ?>
    <div>Unable to connect to RouterOS.</div>
<?php } ?>

<?php include 'includes/footer.php'; ?>
