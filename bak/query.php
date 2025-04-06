<?php
include('config/data.php');
if (isset($_GET['q'])) {
    $attribute = $_GET['q'];
    $sql = "SELECT * FROM dictionary WHERE  Attribute='$attribute'";
    $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
    $f = mysqli_fetch_array($r);
    $val = $f['RecommendedTooltip'];
    echo $val;
} ?>