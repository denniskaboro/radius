<?php

use PEAR2\Net\RouterOS;

include '../PEAR2/Autoload.php';
include 'reseller.php';
if(isset($_GET['nasname'])){
$ip=$_GET['nasname'];
$sql = "SELECT * FROM nas WHERE nasname='$ip'";
$res = mysqli_query($con,$sql);
$f = mysqli_fetch_array($res);
$user = $f['login_user'];
$pass = $f['login_password'];
$port = $f['login_port'];

}
try {
    $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass, $port));

    ?>
    <h3>Interface List:</h3>
    <table class="table table-bordered" id="btrc">
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
                <td><a href="graph.php?phy=<?php echo $entry('name'); ?>&ip=<?php echo $ip;?>" style="color: #d6c7c2; font-size:15px;"><?php echo $entry('name'); ?></a></td>
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
<link rel="stylesheet" type="text/css" href="../component/jquery.dataTables.min.css"/>
<script type="text/javascript" src="../component/js/export/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#btrc').DataTable({
            pageLength: 15,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
	    order: [[ 4, "desc" ]]

        });

    });
</script>
<?php include '../includes/footer.php'; ?>
