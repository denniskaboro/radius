<?php
$mac=$_POST['mac'];
$ip=$_POST['ip'];
$username=$_POST['username'];
$linklogin=$_POST['link-login'];
$linkorig=$_POST['link-orig'];
$error=$_POST['error'];
$trial=$_POST['trial'];
$loginby=$_POST['login-by'];
$chapid=$_POST['chap-id'];
$chapchallenge=$_POST['chap-challenge'];
$linkloginonly=$_POST['link-login-only'];
$linkorigesc=$_POST['link-orig-esc'];
$macesc=$_POST['mac-esc'];
$identity=$_POST['identity'];
$bytesinnice=$_POST['bytes-in-nice'];
$bytesoutnice=$_POST['bytes-out-nice'];
$sessiontimeleft=$_POST['session-time-left'];
$uptime=$_POST['uptime'];
$refreshtimeout=$_POST['refresh-timeout'];
$linkstatus=$_POST['link-status'];
include "header.php";
$del="DELETE FROM hotspot_auth WHERE mac='$mac'";
mysqli_query($con,$del);
?>


			<div class="container"  style="min-height: 100vh; height: auto; overflow: visible; padding: 5% 0;">
				<div class="row justify-content-center" >
					<div class="col-lg-6 col-md-8 text-center" data-aos="fade-down" >
						<div class="box-white" style="color:white;background-color: rgba(0, 0, 0, 0.5) !important;">
							<div class="logo-black">
				                <a href="<?php echo $linkloginonly;?>" class="text-center d-block mb-3">
				                    <img src="assets/img/logo.png" alt="Circle Creative" style="height: 120px !important;"/>
				                </a>
				            </div>
				            <div class="row">
				            	<div class="col-md-12 text-center">
									<?php if($error) { ?>
									<div class="alert alert-danger"><?php echo $error; ?></div>
									<?php } ?>
									<?php if($loginby != 'mac') { ?>
									<div class="alert alert-info">Successfully logged in</div>
									<?php } ?>
						        </div>
				            </div>
				            <form  name="logout" onSubmit="return openLogout()">
					            <div class="table-responsive">
						            <table class="table table-striped">
						                <tbody>
							                <tr>
							                    <td>IP address:</td>
							                    <td><?php echo $ip; ?></td>
							                </tr>
							                <tr>
							                    <td>bytes up/down</td>
							                    <td><?php echo $bytesinnice; ?> / <?php echo $bytesoutnice; ?></td>
							                </tr>
											<?php if($sessiontimeleft) { ?>
							                <tr>
							                    <td>connected / left:</td>
							                    <td><?php echo $uptime; ?> / <?php echo $sessiontimeleft; ?></td>
							                </tr>
											<?php }else{ ?>
							                <tr>
							                    <td>connected:</td>
							                    <td><?php echo $uptime; ?></td>
							                </tr>
											<?php } ?>
											<?php if($refreshtimeout) { ?>
											<tr>
												<td>status refresh:</td>
												<td><?php echo $refreshtimeout; ?></td>
											</tr>
											<?php } ?>
						                </tbody>
						            </table>
						        </div>
					        </form>
						</div>
					</div>
				</div>
			</div>

		<script type="text/javascript" src="assets/js/jquery.js"></script>
		<script type="text/javascript" src="assets/js/waypoint.js"></script>
	    <script type="text/javascript" src="assets/packages/bootstrap/libraries/popper.js"></script>
	    <script type="text/javascript" src="assets/packages/bootstrap/bootstrap.js"></script>
	    <script type="text/javascript" src="assets/packages/aos/aos.js"></script>
	    <script type="text/javascript" src="assets/js/theme.js"></script>
	</body>
</html>
