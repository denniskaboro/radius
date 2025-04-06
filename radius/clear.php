<?php
include('config/data.php');
include('session.php');

use PEAR2\Net\RouterOS;

include 'PEAR2/Autoload.php';
$wh = $_SESSION['username'];
$per = $_SESSION['per'];

if (isset($_GET['filter'])) {
    $ip = $_SESSION['ip'];
    $sql = "SELECT * FROM nas WHERE nasname='$ip'";
    $res = mysqli_query($con, $sql);
    $f = mysqli_fetch_array($res);
    $user = $f['login_user'];
    $pass = $f['login_password'];

    $val = $_GET['filter'];
    try {
        $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass));
        $setRequest = new RouterOS\Request(
            '/interface bridge filter remove'
        );
        $setRequest
            ->setArgument('.id', $val);
        $client->sendSync($setRequest);
        if ($client) {
            echo "MAC Filter Remove Successfully.";
        }
    } catch (Exception $e) {
        echo "<div>Unable to connect to RouterOS.</div>";
    }
}


if (isset($_GET['sup_id'])) {
    $supplier = $_GET['sup_id'];
    $psql = "SELECT * FROM clients WHERE supplier_id='$supplier'";
    $count = mysqli_query($con, $psql);
    while ($n = mysqli_fetch_array($count)) {
        $dis = $n['username'];
        $sql = "UPDATE radacct SET  acctstoptime=NOW(), acctterminatecause='Stale-Session' WHERE 
     acctstoptime IS NULL && username='$dis'";
        $r = mysqli_query($con, $sql);

    }
    if ($r) {
        echo "All User has been disconnected from Radius Table.If needed Please disconnect all user from router..";
    }
}
//Disconnect Authentication User
if (isset($_GET['auth'])) {
    $psql = "SELECT * FROM radpostauth WHERE reply='Access-Reject' GROUP BY username";
    $count = mysqli_query($con, $psql);
    while ($n = mysqli_fetch_array($count)) {
        $dis = $n['username'];
        $sql = "SELECT * FROM radcheck WHERE username='$dis' && attribute='Expiration'";
        $qu = mysqli_query($con, $sql);
        $q = mysqli_num_rows($qu);
        if ($q > 0) {
            $f = mysqli_fetch_array($qu);
            $exp = $f['value'];
            $today = time();
            $pre = date(strtotime($exp));
            if ($pre > $today) {
                $sqll = "UPDATE radacct SET  acctstoptime=NOW(), acctterminatecause='Stale-Session' WHERE 
     acctstoptime IS NULL && username='$dis'";
                $r = mysqli_query($con, $sqll);

            }
        }
    }
    if ($r) {
        echo "All User has been disconnected from Radius Table.";
    }


}
//Disconnect User
if (isset($_GET['disconnect'])) {
    if ($per == "Full" || $per == "Write" || $per == "Admin") {
        $id = $_GET['disconnect'];
        $sql = "SELECT * FROM radacct  WHERE acctstoptime IS NULL && radacctid='$id' ORDER BY radacctid DESC";
        $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
        $f = mysqli_fetch_array($r);
        $session = $f['acctsessionid'];
        $name = $f['username'];
        $nas = $f['nasipaddress'];
        $frm = $f['nasipaddress'];
        $myfile = fopen($_SERVER['DOCUMENT_ROOT'] . "/" . $wh . ".txt", "wb");
        $txt = "Acct-Session-Id=" . $session . "\n";
        fwrite($myfile, $txt);
        $txt = "User-Name=" . $name . "\n";
        fwrite($myfile, $txt);
        $txt = "NAS-IP-Address=" . $nas . "\n";
        fwrite($myfile, $txt);
        fclose($myfile);
        $sql1 = "SELECT * FROM nas WHERE nasname='$nas'";
        $res = mysqli_query($con, $sql1);
        $g = mysqli_fetch_array($res);
        $secret = $g['secret'];
        shell_exec("cat " . $wh . ".txt | radclient -x " . $nas . ":1700 disconnect {$secret}");
        $sql = "UPDATE radacct SET  acctstoptime=NOW(), acctterminatecause='Stale-Session' WHERE
 AcctStopTime IS NULL && radacctid='$id'";
        $r = mysqli_query($con, $sql);
        if ($r) {
            echo "User has been disconnected...";
        }
    } else {
        echo "You have no permission";
    }
}


?>
