<?php

use PEAR2\Net\RouterOS;

include 'PEAR2/Autoload.php';
include 'includes/header.php';
$per = $_SESSION['per'];

if (isset($_POST['submit'])) {
    if ($per == "Write" || $per == "Full" || $per == "Admin") {
        $nasip = $_POST['nasip'];
        $secret = $_POST['secret'];
		$des = $_POST['des'];
        $nasport = $_POST['nasport'];
        $login_name = $_POST['login_name'];
        $login_pass = $_POST['login_pass'];
        $nasname = $_POST['nasname'];
        $nastype = $_POST['nastype'];
        $port = $_POST['port'];

        $host = gethostname();
        $ip = gethostbyname($host);
	//$ip='10.90.91.11';
        $check = "SELECT * FROM nas WHERE nasname='$nasip'";
        $q = mysqli_query($con,$check);
        $row = mysqli_num_rows($q);
        if ($row > 0) {
            echo "NAS Already Added...";
        } else {
            if ($login_name != null) {
                try {
                    $util = new RouterOS\Util($client = new RouterOS\Client($nasip, $login_name, $login_pass,$port));
                    $setRequest = new RouterOS\Request(
                        '/radius add'
                    );
                    $setRequest
                        ->setArgument('service', 'ppp,hotspot')
                        ->setArgument('address', $ip)
                        ->setArgument('secret', $secret);
                    $client->sendSync($setRequest);

                    $sql = "INSERT INTO nas (nasname, shortname,location, type, ports, secret,login_user,login_password,login_port) VALUES('$nasip','$nasname','$des','$nastype','$nasport','$secret','$login_name','$login_pass','$port')";
                    $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
                    if ($r) {
                        $msg = "New NAS Added... ";
			shell_exec('sudo /opt/radius.sh');
                    } else {
                        $msg = "Unsuccessfully...";
                    }
                } catch (Exception $e) {
                    echo "Unable to connect to RouterOS.";
                }
                try {
                    $util = new RouterOS\Util($client = new RouterOS\Client($nasip, $login_name, $login_pass,$port));
                    $setRequest = new RouterOS\Request(
                        '/radius incoming set'
                    );
                    $setRequest
                        ->setArgument('accept', 'yes')
                        ->setArgument('port', '1700');

                    $client->sendSync($setRequest);

		$setRequest = new RouterOS\Request(
                        '/ppp aaa set'
                    );
                    $setRequest
                        ->setArgument('interim-update', '300');

                    $client->sendSync($setRequest);
                } catch (Exception $e) {
                    echo "Unable to connect to RouterOS.";
                }
            } else {
                $sql = "INSERT INTO nas (nasname, shortname,location, type, ports, secret,login_user,login_password,login_port) VALUES('$nasip','$nasname','$des','$nastype','$nasport','$secret','$login_name','$login_pass','$port')";
                $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
                if ($r) {
			shell_exec('sudo /opt/radius.sh');
                    $msg = "New NAS Added... ";
                } else {
                    $msg = "Unsuccessfully...";
                }
            }

        }

    } else {
        echo "<h4 style='color: coral ;'>You have no permission...</h4>";
    }
}
if (isset($msg)) {
    echo $msg;
}
?>
<div class="col-sm-5">
    <h3>Add Network Access Server</h3>
    <form method="POST" data-toggle="validator" role="form">
        <div class="form-group col-sm-8 ">
            <div class="form-group">
                <label for="inputName" class="control-label">NAS IP address:</label>
                <input type="text" class="form-control" id="inputName" name="nasip" placeholder="IP Address" required>
            </div>
            <div class="form-group">
                <label for="inputFullname" class="control-label">NAS Secret:</label>
                <input type="password" class="form-control" id="inputFullname" name="secret" placeholder="NAS Secret"
                       required>
            </div>
            <div class="form-group">
                <input type="hidden" class="form-control" name="nasport" value="0">
            </div>
            <div class="form-group">
                <label for="LoginUser" class="control-label">API Username:</label>
                <input type="text" class="form-control" placeholder="Router API Username" name="login_name" required>
            </div>
		<div class="form-group">
                <label for="LoginUser" class="control-label">API Port:</label>
                <input type="text" class="form-control" value='8728' name="port" required>
            </div>

            <div class="form-group">
                <label for="inputPassword" class="control-label">API Password</label>
                <div class="form-group">
                    <input type="password" data-minlength="6" class="form-control" id="inputPassword" name="login_pass"
                           placeholder="Router Login Password">
                    <div class="help-block">Minimum of 6 characters</div>
                </div>
                <label for="inputPassword" class="control-label">Confirm API Password:</label>
                <div class="form-group">
                    <input type="password" class="form-control" id="inputPasswordConfirm" data-match="#inputPassword"
                           data-match-error="Whoops, these don't match" placeholder="Confirm">
                    <div class="help-block with-errors"></div>
                </div>
            </div><?php if ($dept !="Visitor") { ?>
	     <div class="form-group">
                <label for="Name" class="control-label">Supervisor ID:</label>
				<select required class="form-control" name="nasname">
                <option></option>
                <option>Admin</option>
				<?php 
				$check = "SELECT * FROM supplier ORDER BY id DESC";
				$q = mysqli_query($con,$check);
				while($t=mysqli_fetch_array($q)){
				?>
                <option value="<?php echo $t['supplier_id'];?>"><?php echo $t['full_name']."-".$t['supplier_id'];?></option>
				<?php } ?>
				</select>
             
            </div><?php }?>
            <div class="form-group">
                <label for="Name" class="control-label">Location:</label>
                <input type="text" class="form-control" name="des">
            </div>
            <label>NAS Type:</label>
            <select required class="form-control" name="nastype">
                <option></option>
                <option>other</option>
            </select><br>

            <button type="submit" name="submit" class="btn btn-default">Submit</button>
        </div>
    </form>
</div>
<script type="text/javascript" src="component/validator.js"></script>
<script type="text/javascript" src="component/validator.min.js"></script>

<?php include 'includes/footer.php'; ?>
