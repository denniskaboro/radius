<?php include 'includes/header.php'; ?>

<?php

$radiusLineCount = 66;
$logfile_loc = array();
echo date(Ymd) . " " . date(Hi);
$file = "/var/log/radius/radacct/103.132.92.10/detail-";
$logfile_loc[1] = $file . date(Ymd);

foreach ($logfile_loc as $tmp) {
    if (file_exists($tmp)) {
        $logfile = $tmp;
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
    if (file_get_contents($logfile)) {

        $counter = $radiusLineCount;
        $fileReversed = array_reverse(file($logfile));
        foreach ($fileReversed as $line) {
            if ($counter == 0) {
                break;
            }
            echo $line . "<br>";
            $counter--;
        }

    }
}

?>

<?php include 'includes/footer.php'; ?>
