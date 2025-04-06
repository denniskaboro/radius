<?php
function check_service($sname)
{
    if ($sname != '') {
        system("pgrep " . escapeshellarg($sname) . " >/dev/null 2>&1", $ret_service);
        if ($ret_service == 0) {
            return "Enabled";
        } else {
            return "Disabled";
        }
    } else {
        return "no service name";
    }
}

$radius = check_service("radius");
if ($radius != 'Enabled') {
    $msg = "Billing Radius Server Down Time: " . date("d M Y H:i:s");
    $mob = "01844526512,01911045914, 01847469555,01833103919";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://powersms.banglaphone.net.bd/httpapi/sendsms");
    curl_setopt($ch, CURLOPT_POST, 1);// set post data to true
    curl_setopt($ch, CURLOPT_POSTFIELDS, "userId=hotspot&password=PcLh0tsp0t321&smsText=" . $msg . "&commaSeperatedReceiverNumbers=" . $mob);   // Post data
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($ch);
    curl_close($ch);

}

?>
