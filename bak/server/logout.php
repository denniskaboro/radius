<?php
include ('session.php');
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
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta http-equiv="pragma" content="no-cache" />
		<meta http-equiv="expires" content="-1" />
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Circle Creative Office</title>
		<link rel="shortcut icon" type="image/png" href="favicon.png">
		<link href="assets/fonts/font-awesome/font-awesome.css" rel="stylesheet" type="text/css">
	    <link href="assets/fonts/maven-pro/maven-pro.css" rel="stylesheet" type="text/css">

	    <link rel="stylesheet" type="text/css" media="all" href="assets/css/animate.css">
	    <link rel="stylesheet" type="text/css" media="all" href="assets/packages/bootstrap/bootstrap.css">
	    <link rel="stylesheet" type="text/css" media="all" href="assets/packages/aos/aos.css">
	    <link rel="stylesheet" type="text/css" media="all" href="assets/css/theme.css">
	</head>
	<body class="d-flex align-items-center">
		<script language="JavaScript">
		<!--
			function openLogin() {
			if (window.name != 'hotspot_logout'){
				return true;
			open('<?php $linklogin ?>', '_blank', '');
			window.close();
			return false;
			}
		//-->
		</script>
		<main>
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-lg-4 col-md-6 text-center" data-aos="fade-down">
						<div class="box-white">
							<form action="<?php echo $linklogin;?>" name="login" onSubmit="return openLogin()">
								<div class="l-login">
									<i class="fas fa-check-circle fa-2x text-success"></i>
									<p class="user"><span class="name">Thanks <strong>$(username)..!</strong></span></p>
									<p>You have just logged out<br/>
									Please relogin to accsess the internet.</p>
								</div>
								<a href="<?php echo $linklogin;?>" class="btn btn-primary">Login</a>
							</form>
						</div>
					</div>
				</div>
			</div>
		</main>

		<script type="text/javascript" src="assets/js/jquery.js"></script>
		<script type="text/javascript" src="assets/js/waypoint.js"></script>
	    <script type="text/javascript" src="assets/packages/bootstrap/libraries/popper.js"></script>
	    <script type="text/javascript" src="assets/packages/bootstrap/bootstrap.js"></script>
	    <script type="text/javascript" src="assets/packages/aos/aos.js"></script>
	    <script type="text/javascript" src="assets/js/theme.js"></script>
	</body>
</html>
