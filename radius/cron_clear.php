<?php
include('config/data.php');
$psql = "SELECT * FROM radpostauth WHERE `reply`='Access-Reject' and `message`='You are already logged in - access denied' and `authdate` > DATE_SUB( NOW() , INTERVAL 5 MINUTE )  GROUP BY username";
$count = mysqli_query($con, $psql);
while ($n = mysqli_fetch_array($count)) {
    $dis = $n['username'];
    $ss = "SELECT * FROM `radacct` WHERE `username`='$dis' and `acctstoptime` IS NULL";
    $qs = mysqli_query($con, $ss);
    $ac = mysqli_num_rows($qs);
    if ($ac > 0) {
        $f = mysqli_fetch_array($qs);
        $id = $f['radacctid'];
        $username = $f['username'];
        $nas = $f['nasipaddress'];
        $n = "SELECT * FROM nas WHERE nasname='$nas'";
        $nu = mysqli_query($con, $n);
        $g = mysqli_fetch_array($nu);
        $secret = $g['secret'];
        shell_exec("echo User-Name={$username} | radclient -x {$nas}:1700 disconnect {$secret}");
        $sql = "UPDATE radacct SET  acctstoptime=NOW(), acctterminatecause='Stale-Session' WHERE
 acctstoptime IS NULL && radacctid='$id'";
        $r = mysqli_query($con, $sql);
        $del = "DELETE FROM radpostauth WHERE username='$dis'";
        mysqli_query($con, $del);
    }

}
$del = "DELETE FROM radpostauth WHERE authdate < DATE_SUB( NOW() , INTERVAL 10 MINUTE )";
mysqli_query($con, $del);

?>
