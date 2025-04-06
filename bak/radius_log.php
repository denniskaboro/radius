<?php include 'includes/header.php'; 


$radiusLineCount = 40;
$logfile_loc = array();
$logfile_loc[1] = '/var/log/freeradius/radius.log';
$logfile_loc[2] = '/usr/local/var/log/radius/radius.log';
$logfile_loc[3] = '/var/log/radius/radius.log';

foreach ($logfile_loc as $tmp) {
    if (file_exists($tmp)) {
        $logfile = $tmp;
	echo $logfile;
        break;
    }
}


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
	//$cmd ="tail -40 /var/log/radius/radius.log";
//	echo $cmd;
      	exec($cmd, $output);
// 	print_r( $output);
	foreach($output as $result) {
    		echo $result, '<br>';
}
/*    if (file_get_contents($logfile)) {

        $counter = $radiusLineCount;
        $fileReversed = array_reverse(file($logfile));
        foreach ($fileReversed as $line) {
            if ($counter == 0) {
                break;
            }
            echo $line . "<br>";
            $counter--;
        }

    }*/
}

?>
</div>  <!-- end of col-lg-10 -->
<?php include 'includes/footer.php'; ?>
