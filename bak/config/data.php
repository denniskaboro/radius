<?php
    $con=new mysqli ('localhost','root','T@rg3tOp3N', 'radius') or die('connection error');
    date_default_timezone_set('Africa/Nairobi');
    mysqli_query($con,'SET CHARACTER SET utf8');
    mysqli_query($con,"SET SESSION collation_connection ='utf8_general_ci'");
?>
