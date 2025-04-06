<?php
include('../config/data.php');
include('session.php');
include 'log.php';
$API = new log_api();
$wh = $_SESSION['username'];
if(isset($_GET['tra_id'])){
    $id=$_GET['tra_id'];
    if($id==null){
        echo 'Please input valid Transaction ID';
    }else{
        $sql = "SELECT * FROM `transaction` WHERE `transaction`='$id' ";
        $query = mysqli_query($con,$sql);
        $row = mysqli_num_rows($query);
        if ($row > 0) {
            $API->log($wh,"Trying to Payment with Duplicate Transaction ID ".$id);
            echo 'Sorry, This Transaction ID is already used.';
        } else {
            $_SESSION['trax']=$id;
            echo 'True';

        }
    }

}
?>
