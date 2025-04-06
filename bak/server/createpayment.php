<?php
include('config.php');
if(isset($_SESSION['username'])) {
    $pack = $_POST['pack'];
    $username=$_SESSION['username'];
    $sql="SELECT * FROM radcheck WHERE username='$username'";
    $q=mysqli_query($con,$sql);
    $row=mysqli_num_rows($q);
    if($row>0){
        $up="UPDATE radusergroup SET groupname='$pack' WHERE username='$username'";
        $u=mysqli_query($con,$up);
        if($u){
            $rep=["status"=>"success","message"=>"Your package {$pack} has been Update successfully"];
            echo json_encode($rep);
        }else{
            $rep=["status"=>"error","message"=>"Transaction Error"];
            echo json_encode($rep);
        }
    }else{
        $us = "INSERT INTO `radcheck` (`username`,`attribute`,`op`,`value`) VALUES('$username','Auth-Type',':=','PAP')";
        $u=mysqli_query($con,$us);
        $usr = "INSERT INTO `radcheck` (username,attribute,op,`value`) VALUES('$username','Cleartext-Password',':=','$username')";
        mysqli_query($con,$usr);
        $gr = "INSERT INTO `radusergroup` (`username`,`groupname`) VALUES('$username','$pack')";
        $suc=mysqli_query($con,$gr);
        if($suc){
            $rep=["status"=>"success","message"=>"You have purchased {$pack} successfully"];
            echo json_encode($rep);
        }else{
            $rep=["status"=>"error","message"=>"Transaction Error"];
            echo json_encode($rep);
        }
    }

}
?>
