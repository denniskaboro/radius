<?php
include "../config/data.php";

class log_api
{
    function log($username, $work)
    {
        $client = $_SERVER['REMOTE_ADDR'];
        $sql = "INSERT INTO log (username,type,remote_host,work)VALUES('$username','Supervisor','$client','$work')";
        mysqli_query($con, $sql);
    }
}

?>
