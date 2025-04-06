<?php
include "config/data.php";
class log_api
{
    function log($user, $work)
    {	
	include "config/data.php";
	$client = $_SERVER['REMOTE_ADDR'];
        $sql = "INSERT INTO log (username,type,remote_host,work)VALUES('$user','Admin','$client','$work')";
        mysqli_query($con,$sql);
    }
}
?>
