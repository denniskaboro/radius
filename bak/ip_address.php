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
            '/ip address remove'
        );
        $setRequest
            ->setArgument('.id', $val);
        $client->sendSync($setRequest);
        if ($client) {
            echo "IP Address Remove Successfully.";
        }
    } catch (Exception $e) {
        echo "<div>Unable to connect to RouterOS.</div>";
    }
}
try {
    $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass,$port));

    ?>
    <h3>IP Address List:</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Interface</th>
            <th>IP Address</th>
            <th>Network Address</th>
            <th>Comment for Address</th>
            <th>Remove Address</th>

        </tr>
        </thead>
        <tbody>
        <?php foreach ($util->setMenu('/ip address')->getAll() as $entry) { ?>
            <tr>
                <td><?php echo $entry('interface'); ?></td>
                <td>
                    <?php foreach (explode(',', $entry('address')) as $topic) { ?>
                        <span><?php echo $topic; ?></span>
                    <?php } ?>
                </td>
                <td><?php echo $entry('network'); ?></td>
                <td><?php echo $entry('comment'); ?></td>
                <td><a class="btn btn-danger" href="ip_address.php?val=<?php echo $entry('.id'); ?>"
                       onClick='myFunction()' id="click">
                        <i class="icon-edit"><span class="glyphicon glyphicon-trash"></span></i></a></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } catch (Exception $e) { ?>
    <div>Unable to connect to RouterOS.</div>
<?php } ?>
<script type="text/javascript">
    function myFunction() {
        var r = confirm("Confirm to do this?");
        if (r == true) {
            document.getElementById("click").href = "ip_address.php?val=<?php echo $name; ?>";
        } else {
            document.getElementById("click").href = "ip_address.php";
        }
    }
</script>
<?php include 'includes/footer.php'; ?>
