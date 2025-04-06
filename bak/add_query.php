<?php
/**
 * Created by PhpStorm.
 * User: JEET
 * Date: 15-Jun-19
 * Time: 1:56 PM
 */
include('config/data.php');

use PEAR2\Net\RouterOS;

include 'PEAR2/Autoload.php';

include('config/data.php');
if (isset($_GET['nas'])) {
    $attr = $_GET['nas'];
    $sql = "SELECT * FROM nas WHERE  nasname='$attr'";
    $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
    $f = mysqli_fetch_array($r);
    $user = $f['login_user'];
    $pass = $f['login_password'];
    $port = $f['login_port'];
    try {
        $util = new RouterOS\Util($client = new RouterOS\Client($attr, $user, $pass,$port));
        echo "<br><label for=\"inputValue\">Select IP Pool:</label><select class=\"form-control\" name=\"value\" >";
        foreach ($util->setMenu('/ip pool')->getAll() as $entry) {
            echo "<option>" . $entry('name') . "</option>";
        }
        echo "</select><br>";
    } catch (Exception $e) {

    }
}
?>
