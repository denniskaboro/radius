<?php

use PEAR2\Net\RouterOS;

include 'PEAR2/Autoload.php';
include('config/data.php');
if(isset($_GET['id'])) {
    $id=$_GET['id'];
    $sql = "SELECT * FROM radpostauth WHERE id='$id'";
    $que = mysqli_query($con,$sql);
    $f=mysqli_fetch_array($que);
    $ip = $f['nas'];
$mac = $f['mac'];
//    $ip = '103.132.94.142';
$sql = "SELECT * FROM nas WHERE nasname='$ip'";
$res = mysqli_query($con,$sql);
$f = mysqli_fetch_array($res);
$user = $f['login_user'];
$pass = $f['login_password'];
$port=$f['login_port'];
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
            if ($client) {
                echo "MAC Filter Add Successfully for user ".$user;
            }
        } catch (Exception $e) {
            echo "<div>Unable to connect to RouterOS.</div>";
        }
    }
$sql="DELETE FROM radpostauth  WHERE id='$id'";
mysqli_query($con,$sql);

?>
