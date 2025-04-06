<?php

use PEAR2\Net\RouterOS;

include 'PEAR2/Autoload.php';
include 'includes/router.php';
include('config/data.php');
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $vlan_id = $_POST['vlan_id'];
    $comment = $_POST['comment'];
    $interface = $_POST['interface'];
    echo $interface;
    try {
        $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass,$port));
        $setRequest = new RouterOS\Request(
            '/interface vlan add'
        );
        $setRequest
            ->setArgument('name', $name)
            ->setArgument('vlan-id', $vlan_id)
            ->setArgument('comment', $comment)
            ->setArgument('interface', $interface);
        $client->sendSync($setRequest);
        if ($client) {
            echo "VLAN Create Successfully...";
        }
    } catch (Exception $e) {
        echo "<div>Unable to connect to RouterOS.</div>";
    }
}
try {
$util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass,$port));

?>
    <h3>New Interface VLAN Create:</h3>

<form method="POST" data-toggle="validator" role="form">
    <div class="form-group col-sm-4">
        <div class="form-group">
            <label for="inputName" class="control-label">VLAN Name:</label>
            <input type="text" class="form-control" id="inputName" name="name" placeholder="VLAN Name" required>
        </div>
        <div class="form-group">
            <label for="inputFullname" class="control-label">VLAN ID:</label>
            <input type="text" class="form-control" id="inputFullname" name="vlan_id" placeholder="VLAN ID: 2-4096"
                   required>
        </div>
        <div class="form-group">
            <label for="LoginUser" class="control-label">Comment:</label>
            <input type="text" class="form-control" placeholder="Description" name="comment">
        </div>
        <label>Interface:</label>
        <select required class="form-control" name="interface" required>
            <option>Select</option>
            <?php foreach ($util->setMenu('/interface')->getAll() as $entry) {
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
