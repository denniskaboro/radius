<?php require_once('api_mt_include2.php'); ?>
<?php
$ip = $_GET["ip"];
$username = $_GET["user"];
$pass = $_GET["pass"];
$port = $_GET["rport"];
$interface = $_GET["interface"]; //"<pppoe-nombreusuario>";


$API = new routeros_api();
$API->debug = false;
if ($API->connect($ip, $username, $pass, $port)) {
    $rows = array();
    $rows2 = array();
    $API->write("/interface/monitor-traffic", false);
    $API->write("=interface=" . $interface, false);
    $API->write("=once=", true);
    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);
    if (count($ARRAY) > 0) {
        $rx = $ARRAY[0]["rx-bits-per-second"] / 1024;
        $tx = $ARRAY[0]["tx-bits-per-second"] / 1024;
        if ($rx > 1024) {
            $rx = round($rx / 1024);
            $rows3['rxs'] = "Mbps";
        } else {
            $rx = round($rx);
            $rows3['rxs'] = 'Kbps';
        }
        if ($tx > 1024) {
            $tx = round($tx / 1024);
            $rows3['txs'] = "Mbps";
        } else {
            $tx = round($tx);
            $rows3['txs'] = 'Kbps';
        }
        $rows['name'] = 'Tx';
        $rows['data'][] = $tx;
        $rows2['name'] = 'Rx';
        $rows2['data'][] = $rx;
    } else {
        echo $ARRAY['!trap'][0]['message'];
    }
} else {
    echo "<font color='#ff0000'>La conexion ha fallado. Verifique si el Api esta activo.</font>";
}
$API->disconnect();

$result = array();
array_push($result, $rows);
array_push($result, $rows2);
array_push($result, $rows3);
print json_encode($result, JSON_NUMERIC_CHECK);

?>
