<?php

use RadiusSpot\RadiusSpot;

require_once "../class/radiusspot.php";
require_once "../config/data.php";

$API = new RadiusSpot($con);

$vo = "SELECT * FROM hotspot_user";
$v = mysqli_query($con, $vo);
while ($e = mysqli_fetch_array($v)) {
    $username = $e['username'];
    $f = $API->RowFetch(
        "IFNULL( MAX(TIME_TO_SEC(TIMEDIFF(NOW(), acctstarttime))),0) as ttime",
        " radacct ",
        "username='$username' ORDER BY acctstarttime LIMIT 1"
    );
    $used_session = $f['ttime'];
    $dd = $API->Select(
        "value",
        "radcheck",
        "username='$username' and attribute='Expire-After'"
    );
    if ($dd->num_rows > 0) {
        $d = $dd->fetch_assoc();
        $assign_value = $d['value'];
        if ($used_session >= $assign_value) {
            $API->userRemove("$username");
        }
    }
}
?>