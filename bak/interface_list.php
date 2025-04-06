<?php

use PEAR2\Net\RouterOS;

include 'PEAR2/Autoload.php';
include 'includes/router.php';
include('config/data.php');

$ser = 'ppp,login';
try {
    $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass,$port));

    ?>
    <h3>Interface List:</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Interface Name</th>
            <th>Type</th>
            <th>State</th>
            <th>Comment</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($util->setMenu('/interface')->getAll() as $entry) { ?>
            <tr>
		<td><a href="graph.php?phy=<?php echo $entry('name'); ?>"><?php echo $entry('name'); ?></a></td>
                <td>
                    <?php foreach (explode(',', $entry('type')) as $topic) { ?>
                        <span><?php echo $topic; ?></span>
                    <?php } ?>
                </td>
                <td><?php $run = $entry('running');
                    if ($run == 'true') {
                        echo 'Running';
                    } else {
                        echo 'Stop';
                    } ?></td>
                <td><?php echo $entry('comment'); ?></td>

            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } catch (Exception $e) { ?>
    <div>Unable to connect to RouterOS.</div>
<?php } ?>

<?php include 'includes/footer.php'; ?>
