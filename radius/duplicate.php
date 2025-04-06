<?php
include('config/data.php');
include('session.php');
include('log.php');
$API = new log_api();
$wh = $_SESSION['username'];
if (isset($_GET['sup_id'])) {
    $supplier = $_GET['sup_id'];

    $psql = "SELECT * FROM clients WHERE supplier_id='$supplier'";
    $count = mysqli_query($con, $psql);
    while ($n = mysqli_fetch_array($count)) {
        $dis = $n['username'];
        $sql = "SELECT * FROM radacct  WHERE acctstoptime IS NULL && username='$dis' ORDER BY radacctid DESC";
        $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
        $found = mysqli_num_rows($r);
        if ($found >= 2) {
            $f = mysqli_fetch_array($r);
            $sql = "UPDATE radacct SET  acctstoptime=NOW(), acctterminatecause='Stale-Session' WHERE
     AcctStopTime IS NULL && username='$dis'";
            $r = mysqli_query($con, $sql);
            if ($r) {
                echo "User has been disconnected...";
            }
            $session = $f['acctsessionid'];
            $name = $f['username'];
            $nas = $f['nasipaddress'];
            $myfile = fopen($_SERVER['DOCUMENT_ROOT'] . "/" . $wh . ".txt", "wb");
            $txt = "Acct-Session-Id=" . $session . "\n";
            fwrite($myfile, $txt);
            $txt = "User-Name=" . $name . "\n";
            fwrite($myfile, $txt);
            $txt = "NAS-IP-Address=" . $nas . "\n";
            fwrite($myfile, $txt);
            fclose($myfile);
            shell_exec("cat " . $wh . ".txt | radclient -x " . $nas . ":1700 disconnect ''PcL138536naS''");

        }
    }
}
if (isset($_GET['all'])) {
    $psql = "SELECT * FROM clients";
    $count = mysqli_query($con, $psql);
    while ($n = mysqli_fetch_array($count)) {
        $dis = $n['username'];
        $sql = "SELECT * FROM radacct  WHERE acctstoptime IS NULL && username='$dis' ORDER BY radacctid DESC";
        $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
        $found = mysqli_num_rows($r);
        if ($found >= 2) {
            $f = mysqli_fetch_array($r);
            $sql = "UPDATE radacct SET  acctstoptime=NOW(), acctterminatecause='Stale-Session' WHERE
     AcctStopTime IS NULL && username='$dis'";
            $r = mysqli_query($con, $sql);
            if ($r) {
                echo "User has been disconnected...";
            }
            $session = $f['acctsessionid'];
            $name = $f['username'];
            $nas = $f['nasipaddress'];
            $myfile = fopen($_SERVER['DOCUMENT_ROOT'] . "/" . $wh . ".txt", "wb");
            $txt = "Acct-Session-Id=" . $session . "\n";
            fwrite($myfile, $txt);
            $txt = "User-Name=" . $name . "\n";
            fwrite($myfile, $txt);
            $txt = "NAS-IP-Address=" . $nas . "\n";
            fwrite($myfile, $txt);
            fclose($myfile);
            shell_exec("cat " . $wh . ".txt | radclient -x " . $nas . ":1700 disconnect ''PcL138536naS''");
        }
    }

}
?>

