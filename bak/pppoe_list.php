<?php
use PEAR2\Net\RouterOS;
include 'PEAR2/Autoload.php';
include 'includes/router.php';
include('config/data.php');

$ser='ppp,login';
try {
    $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass,$port));

    ?>
    <h3>Interface List:</h3>
    <table class="table table-bordered" id="btrc">
        <thead>
        <tr>
            <th>USER Name</th>
            <th>SERVER</th>
            <th>MAC</th>
            <th>UP TIME</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($util->setMenu('/interface pppoe-server')->getAll() as $entry) { ?>
            <tr>
                <td><a href="graph.php?ppp=<?php echo $entry('user'); ?>"><?php echo $entry('user'); ?></a></td>
                <td>
                    <?php foreach (explode(',', $entry('service')) as $topic) { ?>
                        <span><?php echo $topic; ?></span>
                    <?php } ?>
                </td>
                <td><?php echo $entry('remote-address'); ?></td>
                <td><?php echo $entry('uptime'); ?></td>

            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } catch (Exception $e) { ?>
    <div>Unable to connect to RouterOS.</div>
<?php } ?>
<link rel="stylesheet" type="text/css" href="component/jquery.dataTables.min.css"/>
    <script type="text/javascript" src="component/js/export/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#btrc').DataTable({
                pageLength: 15,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]

            });

        });
    </script>
<?php include 'includes/footer.php'; ?>
