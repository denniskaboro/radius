<?php

use PEAR2\Net\RouterOS;

include 'PEAR2/Autoload.php';
include 'includes/router.php';
include('config/data.php');

try {
    $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass, $port));

    ?>
    <h3>IP Address List:</h3>
    <h4 id="option"></h4>
    <table class="table table-bordered" id="btrc">
        <thead>
        <tr>
            <th>Comment</th>
            <th>MAC Address</th>
            <th>Action</th>
            <th>Chain</th>
            <th>Bytes</th>
            <th>Packet</th>
            <th>Remove Filter</th>

        </tr>
        </thead>
        <tbody>
        <?php foreach ($util->setMenu('/interface bridge filter')->getAll() as $entry) { ?>
            <tr>
                <td><?php echo $entry('comment'); ?></td>
                <td>
                    <?php foreach (explode(',', $entry('src-mac-address')) as $topic) { ?>
                        <span><?php echo $topic; ?></span>
                    <?php } ?>
                </td>
                <td><?php echo $entry('action'); ?></td>
                <td><?php echo $entry('chain'); ?></td>
                <td><?php echo $entry('bytes'); ?></td>
                <td><?php echo $entry('packets'); ?></td>
                <td>
                    <button class="btn btn-danger" <?php $idd = $entry('.id'); ?> onclick="MAC('<?php echo $idd; ?>')">
                        <i class="icon-edit"><span class="glyphicon glyphicon-trash"></span></i></button>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } catch (Exception $e) { ?>
    <div>Unable to connect to RouterOS.</div>
<?php } ?>
<script>
    function MAC(str) {
        if (str == "") {
            document.getElementById("option").innerHTML = "";
            return;
        }
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                alert(this.responseText);
            }
        }
        xmlhttp.open("GET", "clear.php?filter=" + str, true);
        xmlhttp.send();
    }
</script>
<link rel="stylesheet" type="text/css" href="component/jquery.dataTables.min.css"/>
<script type="text/javascript" src="component/js/export/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#btrc').DataTable({
            pageLength: 15,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            order: [[3, "desc"]]
        });

    });
</script>
<?php include 'includes/footer.php'; ?>
