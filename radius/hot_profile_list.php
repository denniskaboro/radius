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
            '/ip hotspot profile remove'
        );
        $setRequest
            ->setArgument('.id', $val);
        $client->sendSync($setRequest);
        if ($client) {
            echo "Hotspot Profile Remove Successfully.";
        }
    } catch (Exception $e) {
        echo "<div>Unable to connect to RouterOS.</div>";
    }
}
try {
    $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass, $port));

    ?>
    <h3>Hotspot Profile List:</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Name</th>
            <th>Hotspot Address</th>
            <th>Authentication</th>
            <th>Cookie</th>
            <th>Use Radius</th>
            <th>Radius Accounting</th>
            <th>NAS Port Type</th>
            <th>MAC Format</th>
            <th>Remove Server</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($util->setMenu('/ip hotspot profile')->getAll() as $entry) { ?>
            <tr>
                <td><?php echo $entry('name'); ?></td>
                <td>
                    <?php foreach (explode(',', $entry('hotspot-address')) as $topic) { ?>
                        <span><?php echo $topic; ?></span>
                    <?php } ?>
                </td>
                <td><?php echo $entry('login-by'); ?></td>
                <td><?php echo $entry('http-cookie-lifetime'); ?></td>
                <td><?php if ($entry('use-radius') == 'true') {
                        echo "Yes";
                    } else {
                        echo "No";
                    } ?></td>
                <td><?php if ($entry('radius-accounting') == 'true') {
                        echo "Yes";
                    } else {
                        echo "No";
                    } ?></td>
                <td><?php echo $entry('nas-port-type'); ?></td>
                <td><?php echo $entry('radius-mac-format'); ?></td>
                <td><a class="btn btn-danger" href="hot_profile_list.php?val=<?php echo $entry('.id'); ?>"' id="click">
                    <i class="icon-edit"><span class="glyphicon glyphicon-trash"></span></i></a></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } catch (Exception $e) { ?>
    <div>Unable to connect to RouterOS.</div>
<?php } ?>

<?php include 'includes/footer.php'; ?>
