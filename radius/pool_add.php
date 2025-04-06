<?php

use PEAR2\Net\RouterOS;

include 'PEAR2/Autoload.php';
include 'includes/router.php';
include('config/data.php');
if (isset($_POST['add'])) {
    $address = $_POST['address'];
    $comment = $_POST['comment'];
    $name = $_POST['name'];
    try {
        $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass, $port));
        $setRequest = new RouterOS\Request(
            '/ip pool add'
        );
        $setRequest
            ->setArgument('name', $name)
            ->setArgument('ranges', $address)
            ->setArgument('comment', $comment);
        $client->sendSync($setRequest);
        if ($client) {
            echo "IP Pool Add Successfully.";
        }
    } catch (Exception $e) {
        echo "<div>Unable to connect to RouterOS.</div>";
    }
}

?>
<h3>New IP Pool Add:</h3>

<form method="POST" data-toggle="validator" role="form">
    <div class="form-group col-sm-4">
        <div class="form-group">
            <label for="inputName" class="control-label">IP Address:</label>
            <input type="text" class="form-control" id="inputName" name="name" placeholder="Pool Name" required>
        </div>
        <div class="form-group">
            <label for="inputAdd" class="control-label">IP Address Range:</label>
            <input type="text" class="form-control" id="inputAdd" name="address"
                   placeholder="Example: 10.10.10.1-10.10.10.254" required>
        </div>
        <div class="form-group">
            <label for="LoginUser" class="control-label">Comment:</label>
            <input type="text" class="form-control" placeholder="Description" name="comment">
        </div>
        <button type="submit" name="add" class="btn btn-default">Submit</button>
    </div>
</form>
<script type="text/javascript" src="component/validator.js"></script>
<script type="text/javascript" src="component/validator.min.js"></script>
<?php include 'includes/footer.php'; ?>
