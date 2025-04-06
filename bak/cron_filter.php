<?php
use PEAR2\Net\RouterOS;

include 'PEAR2/Autoload.php';
include('config/data.php');
//Radius Old Data Transfer
 $dd = date("Y-m-d", strtotime("-7 days"));
    $in = "INSERT INTO radacct_old SELECT * FROM radacct WHERE acctstoptime <='$dd'";
    mysqli_query($con,$in);
    $del = "DELETE FROM radacct WHERE acctstoptime <='$dd'";
    $succ = mysqli_query($con,$del);


//MAC Filter Add
$sql = "SELECT * FROM `radpostauth` GROUP BY mac";
$que = mysqli_query($con,$sql);
while($f=mysqli_fetch_array($que)){
    $ip = $f['nas'];
    $user = $f['username'];
    $mac = $f['mac'];
    $sql = "SELECT * FROM nas WHERE nasname='$ip'";
	$res = mysqli_query($con,$sql);
	$f = mysqli_fetch_array($res);
	$user = $f['login_user'];
	$pass = $f['login_password'];
	$port=$f['login_port'];

    $con_id=$f['id'];
    $q="SELECT * FROM radcheck WHERE username='$user' and attribute='Expiration'";
    $query=mysqli_query($con,$q);
    $row=mysqli_num_rows($query);
    if($row>0){
        $g=mysqli_fetch_array($query);
        $da=$g['value'];
        $comp=strtotime($da);
        if($comp<$d){
            try {
                $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass,$port));
                $setRequest = new RouterOS\Request(
                    '/interface bridge filter add'
                );
                $setRequest
                    ->setArgument('action', "drop")
                    ->setArgument('chain', "input")
                    ->setArgument('src-mac-address', "$mac/FF:FF:FF:FF:FF:FF")
                    ->setArgument('comment', "$user");
                $client->sendSync($setRequest);
            } catch (Exception $e) {
                echo "Unable to connect to RouterOS.";
            }

        }
    }else{
        try {
            $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass));
            $setRequest = new RouterOS\Request(
                '/interface bridge filter add'
            );
            $setRequest
                ->setArgument('action', "drop")
                ->setArgument('chain', "input")
                ->setArgument('src-mac-address', "$mac/FF:FF:FF:FF:FF:FF")
                ->setArgument('comment', "$user");
            $client->sendSync($setRequest);
        } catch (Exception $e) {
            echo "Unable to connect to RouterOS.";
        }
    }

}
$del="DELETE FROM radpostauth WHERE mac='$mac'";
mysqli_query($con,$del);

?>
