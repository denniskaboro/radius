<?php

use PEAR2\Net\RouterOS;

include 'PEAR2/Autoload.php';
include 'includes/router.php';
include('config/data.php');

?>
<h3>PPPoE Server Create:</h3>
<div class="server col-md-12">
    <form method="POST" data-toggle="validator" role="form" action="hot_profile_add.php">
        <div class="form-group col-sm-4">
            <div class="form-group">
                <label for="LoginUser" class="control-label">Service Name:</label>
                <input type="text" class="form-control" placeholder="Description" name="name" required>
            </div>
            <label>Interface:</label>
            <select class="form-control" name="interface">
                <option>Select</option>
                <?php
                try {
                    $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass,$port));
                    foreach ($util->setMenu('/interface')->getAll() as $entry) {
                        echo "<option>" . $entry('name') . "</option>";
                    }
                } catch (Exception $e) { ?>
                    <div>Unable to connect to RouterOS.</div>
                <?php } ?>
            </select><br>
            <label>Address Pool:</label>
            <select class="form-control" name="pool">
                <option>Select</option>
                <?php
                try {
                    $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass,$port));
                    foreach ($util->setMenu('/ip pool')->getAll() as $entry) {
                        echo "<option>" . $entry('name') . "</option>";
                    }
                } catch (Exception $e) { ?>
                    <div>Unable to connect to RouterOS.</div>
                <?php } ?>
            </select><br>
            <button type="submit" name="next" class="btn btn-default">Next</button>
        </div>
    </form>

</div>

<script type="text/javascript" src="component/validator.js"></script>
<script type="text/javascript" src="component/validator.min.js"></script>
<?php include 'includes/footer.php'; ?>
