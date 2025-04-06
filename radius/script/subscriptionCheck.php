<?php

use RadiusSpot\RadiusSpot;

require_once "../class/radiusspot.php";
require_once "../config/data.php";

$API = new RadiusSpot($con);

$r = $API->AllRowFetch("*", "hotspot_user");
foreach ($r as $i) {
    $mobile = $i['mobile'];
    $username = $i['username'];
    $duration = $i['duration'];
    $bandwidth = $i['bandwidth'];
    $sms = false;

    if ($duration != "") {
        $f = $API->RowFetch(
            "IFNULL( MAX(TIME_TO_SEC(TIMEDIFF(NOW(), acctstarttime))),0) as ttime",
            " radacct ",
            "username='$username' ORDER BY acctstarttime LIMIT 1"
        );
        $used_session = $f['ttime'];

        $ee = $API->Select(
            "value",
            "radcheck",
            "username='$username' and attribute='Expire-After'"
        );
	if($ee->num_rows >0){
		$e=$ee->fetch_assoc();
		$assign_value = $e['value'];
	        $assign_value = $assign_value - 60;
		if ($used_session >= $assign_value) {
	        	$sms = true;
        	}

	}

    }

    if ($bandwidth != "") {
        $f = $API->RowFetch(
            "IFNULL(SUM(acctoutputoctets),0) as duse ",
            " radacct ",
            " username='$username'"
        );
        $used_session = $f['duse'];
        $ee = $API->Select(
            "radgroupcheck.value",
            "radgroupcheck INNER JOIN radusergroup ON radgroupcheck.groupname=radusergroup.groupname",
            "radusergroup.username='$username' and radgroupcheck.attribute='Max-Data'"
        );
	if($ee->num_rows >0){
                $e=$ee->fetch_assoc();
        	$assign_value = $e['value'];
        	$assign_value = $assign_value - (512 * 1024);
        	if ($used_session >= $assign_value) {
        	    $sms = true;
        	}
    	}
    }
    if ($sms) {
        $message = "Dear User, Your Current Internet Plan has been expired";
        $c = $API->Select("*", "sms_check", "username='$username'");
        if ($c->num_rows <= 0) {
            $res = $API->SendSMS("$message", "$mobile" . ",");
	$rr = json_decode($res);
            if ($rr->status == "success") {
                $API->dataInsert("sms_check",
                    array(
                        [
                            "username" => $username,
                            "date_send" => date("d M Y H:i:s"),
                            "month" => date("m"),
                            "year" => date("Y")
                        ]
                    )
                );
            }
        }
    }

}


