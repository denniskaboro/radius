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
            '/ppp profile remove'
        );
        $setRequest
            ->setArgument('.id', $val);
        $client->sendSync($setRequest);
        if ($client) {
            echo "PPPoE Profile Removed Successfully.";
        }
    } catch (Exception $e) {
        echo "<div>Unable to connect to RouterOS.</div>";
    }
}
try {
    $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass,$port));

    ?>
    <h3>Profile List:</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Name</th>
            <th>Local Address</th>
            <th>Remote Address</th>
            <th>DNS Server</th>
            <th>Remove Profile</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($util->setMenu('/ppp profile')->getAll() as $entry) { ?>
            <tr>
                <td><?php echo $entry('name'); ?></td>
                <td>
                    <?php foreach (explode(',', $entry('local-address')) as $topic) { ?>
                        <span><?php echo $topic; ?></span>
                    <?php } ?>
                </td>
                <td><?php echo $entry('remote-address'); ?></td>
                <td><?php echo $entry('dns-server'); ?></td>
                <td><a class="btn btn-danger" href="profile_list.php?val=<?php echo $entry('.id'); ?>"
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
