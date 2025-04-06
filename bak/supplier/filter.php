<?php

use PEAR2\Net\RouterOS;

include '../PEAR2/Autoload.php';
include 'reseller.php';
include('../config/data.php');

    ?>
    <h3>MAC Filter List:</h3>
    <h4 id="option"></h4>
    <table class="table table-bordered" id="btrc">
        <thead>
        <tr>
            <th>Comment</th>
            <th>MAC Address</th>
            <th>Action</th>
            <th>Chain</th>
            <th>Remove Filter</th>

        </tr>
        </thead>
        <tbody>
		
        <?php 
		$supplier = $_SESSION['id'];
		$sql = "SELECT * FROM nas WHERE shortname='$supplier'";
		$res = mysqli_query($con,$sql);
		while($f = mysqli_fetch_array($res)){
		$ip = $f['nasname'];
		$user = $f['login_user'];
		$pass = $f['login_password'];
		$port = $f['login_port'];
		try {
		$util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass,$port));
		foreach ($util->setMenu('/interface bridge filter')->getAll() as $entry) { 
		 $idd=$entry('.id'); ?>
            <tr id="<?php echo $idd; ?>">
                <td><?php echo $entry('comment'); ?></td>
                <td>
                    <?php foreach (explode(',', $entry('src-mac-address')) as $topic) { ?>
                        <span><?php echo $topic; ?></span>
                    <?php } ?>
                </td>
                <td><?php echo $entry('action'); ?></td>
                <td><?php echo $entry('chain'); ?></td>
                <td><button class="btn btn-danger" onclick="MAC('<?php echo $idd;?>','<?php echo $ip;?>','<?php echo $user;?>','<?php echo $pass;?>')">
                        <i class="icon-edit"><span class="glyphicon glyphicon-trash"></span></i></button></td>
            </tr>
        <?php }
		}catch (Exception $e) { 
			echo "<div>Unable to connect to RouterOS.</div>";
		 } 
		 } ?>
        </tbody>
    </table>

<script>
    function MAC(str,ip,user,pass) {
		var r = confirm("Confirm to Delete ?");
		if (r == true) {
			document.getElementById(str).remove();
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
        xmlhttp.open("GET", "disable.php?filter="+str+"&ip="+ip+"&user="+user+"&password="+pass, true);
        xmlhttp.send();
		}
	}
</script>
<link rel="stylesheet" type="text/css" href="../component/jquery.dataTables.min.css"/>
<script type="text/javascript" src="../component/js/export/jquery.dataTables.min.js"></script>
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
