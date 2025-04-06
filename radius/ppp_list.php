<?php

use PEAR2\Net\RouterOS;

include 'PEAR2/Autoload.php';
include 'includes/router.php';
include('config/data.php');


if (isset($_GET['val'])) {
    $val = $_GET['val'];
    try {
        $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass, $port));
        $setRequest = new RouterOS\Request(
            '/interface pppoe-server server remove'
        );
        $setRequest
            ->setArgument('.id', $val);
        $client->sendSync($setRequest);
        if ($client) {
            echo "PPPoE Server Remove Successfully.";
            $API->log($wh, 'PPPoE Server Remove Successfully.');
        }
    } catch (Exception $e) {
        echo "<div>Unable to connect to RouterOS.</div>";
    }
    try {
        $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass, $port));
        $setRequest = new RouterOS\Request(
            '/ppp aaa set'
        );
        $setRequest
            ->setArgument('use-radius', 'no')
            ->setArgument('accounting', 'no');
        $client->sendSync($setRequest);
        if ($client) {
            echo "PPPoE Authentication has been Changed Radius to Local..";
            $API->log($wh, 'PPPoE Authentication has been Changed Radius to Local..');
        }

    } catch (Exception $e) {
        echo "<div>Unable to connect to RouterOS.</div>";
    }
}

try {
    $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass, $port));

    ?>
    <h3>PPPoE Server List:</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Name</th>
            <th>Interface</th>
            <th>Authentication</th>
            <th>Default Profile</th>
            <th>Remove Server</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($util->setMenu('/interface pppoe-server server')->getAll() as $entry) { ?>
            <tr>
                <td><?php echo $entry('service-name'); ?></td>
                <td>
                    <?php foreach (explode(',', $entry('interface')) as $topic) { ?>
                        <span><?php echo $topic; ?></span>
                    <?php } ?>
                </td>
                <td><?php echo $entry('authentication'); ?></td>
                <td><?php echo $entry('default-profile'); ?></td>
                <td><a class="btn btn-danger" href="ppp_list.php?val=<?php echo $entry('.id'); ?>"
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
