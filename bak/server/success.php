<?php
include "session.php";
date_default_timezone_set("Asia/Dhaka");
if (isset($_SESSION['created']) and $_SESSION['username']) {
    $id = $_SESSION['pack'];
    $username = $_SESSION['username'];
    $sql = "SELECT * FROM `groups` WHERE `id`='$id'";
    $q = mysqli_query($con,$sql);
    $f = mysqli_fetch_array($q);
    $price = $f['price'];
    $speed = $f['speed'];
    $bandwidth = $f['data'];
    $duration = $f['duration'];
    $pack_name = $f['groupname'];
    $sq = "SELECT * FROM `hotspot` WHERE username='$username'";
    $qq = mysqli_query($con,$sq);
    $r = mysqli_fetch_array($qq);
    $password = $r['password'];
    $mobile = $r['mobile'];
    if ($bandwidth != null) {
        $gbb = strpos($bandwidth, 'GB');
        $gb=substr($bandwidth,0,$gbb);
        if ($gb) {
            $down = $gb * 1024 * 1024 * 1024;
        }
        $mbb = strpos($bandwidth, 'GB');
        $mb=substr($bandwidth,0,$mbb);
        if ($mb) {
            $down = $gb * 1024 * 1024;
        }
        $sqq = "INSERT INTO `radcheck` (`username`,`attribute`,`op`,`value`) VALUES('$username','Max-Data',':=','$down')";
        mysqli_query($con,$sqq);
        $sq = "INSERT INTO `radreply` (`username`,`attribute`,`op`,`value`) VALUES('$username','Mikrotik-Xmit-Limit','=','$down')";
        mysqli_query($con,$sq);
        $sqq = "INSERT INTO `radreply` (`username`,`attribute`,`op`,`value`) VALUES('$username','Mikrotik-Recv-Limit','=','$down')";
        mysqli_query($con,$sqq);
    }
    if ($duration != null) {
//        $mm = strpos($duration, 'Month');
//        $m=substr($duration,0,$mm);
//        if ($m) {
            $exp=date("d M Y 23:59:00",strtotime("+$duration"));
            $e = "INSERT INTO `radcheck` (`username`,`attribute`,`op`,`value`) VALUES('$username','Expiration',':=','$exp')";
            $s = mysqli_query($con,$e);
//        }
//        $dd = strpos($duration, 'Days');
//        $d=substr($duration,0,$dd);
//        if ($d) {
//            $exp = $d * 24 * 60 * 60;
//        }
//        $hh = strpos($duration, 'Hour');
//        $h=substr($duration,0,$hh);
//        if ($h) {
//            $exp = $h * 60 * 60;
//        }
//        $e = "INSERT INTO `radcheck` (`username`,`attribute`,`op`,`value`) VALUES('$username','Expire-After',':=','$exp')";
//        $s = mysqli_query($con,$e);
    }
    if ($speed != null) {
        $mbps = strpos($speed, 'M');
        if ($mbps) {
            $sp = $mbps . "M";
        }
        $kbps = strpos($speed, 'K');
        if ($kbps) {
            $sp = $kbps . "K";
        }
        $sq = "INSERT INTO `radreply` (`username`,`attribute`,`op`,`value`) VALUES('$username','Mikrotik-Rate-Limit','=','$sp/$sp')";
        mysqli_query($con,$sq);
    }

    $usr = "INSERT INTO `radcheck` (`username`,`attribute`,`op`,`value`) VALUES('$username','Cleartext-Password',':=','$password')";
    $cre=mysqli_query($con,$usr);
    $us = "INSERT INTO `radcheck` (`username`,`attribute`,`op`,`value`) VALUES('$username','Auth-Type',':=','PAP')";
    $usr = mysqli_query($con,$us);
    $sq = "INSERT INTO `radreply` (`username`,`attribute`,`op`,`value`) VALUES('$username','Framed-Pool','=','radius_pool')";
    mysqli_query($con,$sq);
    $crea="INSERT INTO clients ( `username`, `full_name`, `mobile`, `password`,`reference`)
SELECT  `username`, `full_name`, `mobile`, `encr`,'Created By Hotspot System'
FROM    hotspot
WHERE   username = '$username'";
    $cc=mysqli_query($con,$crea);
    if($cc){
        $upda="UPDATE hotspot set status='approved' WHERE username='$username'";
        $s=mysqli_query($con,$upda);
    }
    if ($cre and $s) {
            $rep=["status"=>"success","message"=>"Your package {$pack_name} has been Update successfully"];
            echo json_encode($rep);
            unset($_SESSION['created']);
            unset($_SESSION['username']);
        }else{
            $rep=["status"=>"error","message"=>"Transaction Error"];
            echo json_encode($rep);
            unset($_SESSION['created']);
            unset($_SESSION['username']);
        }
}
if (isset($_SESSION['update']) and $_SESSION['username']) {
    $id = $_SESSION['pack'];
    $username = $_SESSION['username'];
    $sql = "SELECT * FROM `groups` WHERE `id`='$id'";
    $q = mysqli_query($con,$sql);
    $f = mysqli_fetch_array($q);
    $price = $f['price'];
    $speed = $f['speed'];
    $bandwidth = $f['data'];
    $duration = $f['duration'];
    $pack_name = $f['groupname'];
    $sq = "SELECT * FROM `hotspot` WHERE username='$username'";
    $qq = mysqli_query($con,$sq);
    $r = mysqli_fetch_array($qq);
    $password = $r['password'];
    $mobile = $r['mobile'];
    if ($bandwidth != null) {
        $gbb = strpos($bandwidth, 'GB');
        $gb=substr($bandwidth,0,$gbb);
        if ($gb) {
            $down = $gb * 1024 * 1024 * 1024;
        }
        $mbb = strpos($bandwidth, 'GB');
        $mb=substr($bandwidth,0,$mbb);
        if ($mb) {
            $down = $gb * 1024 * 1024;
        }
        $sqq = "UPDATE `radcheck` SET `value`='$down' WHERE `username`='$username' and `attribute`='Max-Data'";
        mysqli_query($con,$sqq);
        $sq = "UPDATE `radreply` SET `value`='$down' WHERE `username`='$username' and `attribute`='Mikrotik-Xmit-Limit'";
        mysqli_query($con,$sq);
        $sqq = "UPDATE `radreply` SET `value`='$down' WHERE `username`='$username' and `attribute`='Mikrotik-Recv-Limit'";
        mysqli_query($con,$sqq);
    }
    if ($duration != null) {
        $exp=date("d M Y 23:59:00",strtotime("+$duration"));
        $e = "UPDATE `radcheck` SET `value`='$exp' WHERE `username`='$username' and `attribute`='Expiration'";
        $s = mysqli_query($con,$e);
    }
    if ($speed != null) {
        $mbps = strpos($speed, 'M');
        if ($mbps) {
            $sp = $mbps . "M";
        }
        $kbps = strpos($speed, 'K');
        if ($kbps) {
            $sp = $kbps . "K";
        }
        $sq = "UPDATE `radreply` SET `value`='$sp/$sp' WHERE `username`='$username' and `attribute`='Mikrotik-Rate-Limit'";
        mysqli_query($con,$sq);
    }
    $upda="UPDATE hotspot set package_id='$id' WHERE username='$username'";
    $s=mysqli_query($con,$upda);
    if($s){
        $rep=["status"=>"success","message"=>"Your package {$pack_name} has been Update successfully"];
        unset($_SESSION['username']);
        unset($_SESSION['update']);
        echo json_encode($rep);
    }else{
        $rep=["status"=>"error","message"=>"Transaction Error"];
        echo json_encode($rep);
        unset($_SESSION['username']);
        unset($_SESSION['update']);
    }
}
?>