<?php include 'includes/header.php'; ?>
<?php


$radiusLineCount = 40;
$logfile = '/var/log/httpd/error_log';


if (empty($logfile)) {
    echo "<br/><br/>
		error reading log file: <br/><br/>
		looked for log file in '" . implode(", ", $logfile_loc) . "' but couldn't find it.<br/>
		if you know where your freeradius log file is located, set it's location in " . $_SERVER['SCRIPT_NAME'];
    exit;
}


if (is_readable($logfile) == false) {
    echo "<br/><br/>
		error reading log file: <u>$logfile</u> <br/><br/>
		possible cause is file premissions or file doesn't exist.<br/>";
} else {
	$cmd = "tail  -$radiusLineCount  $logfile";
      	exec($cmd, $output);
	foreach($output as $result) {
    		echo $result, '<br>';
}
}

?>
</div>  <!-- end of col-lg-10 -->
<?php include 'includes/footer.php'; ?>
