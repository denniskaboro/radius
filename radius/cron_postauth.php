<?php
include('config/data.php');
$del = "DELETE FROM radpostauth WHERE authdate < DATE_SUB( NOW() , INTERVAL 1 HOUR )";
mysqli_query($con, $del);

?>
