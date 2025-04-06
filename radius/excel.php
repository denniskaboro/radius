<?php
include "config/data.php";
ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

require_once 'src/SimpleXLSX.php';

echo "<h1>Rows with header values as keys</h1>";

if ($xlsx = SimpleXLSX::parse('DOC/active.xlsx')) {

    // Produce array keys from the array values of 1st array element
    $header_values = $rows = [];

    foreach ($xlsx->rows() as $k => $r) {
        if ($k === 0) {
            $header_values = $r;
            continue;
        }
        $rows[] = array_combine($header_values, $r);
    }
    foreach ($rows as $ro) {
        $name = $ro['Name'];
        $username = $ro['User Name'];
        $password = $ro['Password'];
        $pack = $ro['Package Name'];
        $pass = md5($password);
        $sql = "INSERT INTO `clients`(`username`,`full_name`,`password`,`client_type`,`connectivity`,`connection`,`supplier_id`)VALUES('$username','$name','$pass','Home','Shared','Wired','P0011120')";
        mysqli_query($con, $sql);
        $in = "INSERT INTO `radcheck` (`username`,`attribute`,`op`,`value`) VALUES('$username','Cleartext-Password',':=','$password')";
        mysqli_query($con, $in);
        $inn = "INSERT INTO `radcheck` (`username`,`attribute`,`op`,`value`) VALUES('$username','Auth-Type',':=','PAP')";
        mysqli_query($con, $inn);
        $inn = "INSERT INTO `radcheck` (`username`,`attribute`,`op`,`value`) VALUES('$username','Expiration',':=','31 Nov 2020 23:59:00')";
        mysqli_query($con, $inn);
        $gr = "INSERT INTO `radusergroup` (`username`,`groupname`) VALUES('$username','$pack')";
        mysqli_query($con, $gr);
    }

}
?>
