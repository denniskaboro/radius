<?php
/**
 * Created by PhpStorm.
 * User: arman
 * Date: 5/11/2019
 * Time: 1:05 AM
 */
include('config/data.php');
if (isset($_GET['accept'])) {
    $username = $_GET['accept'];
    $sql = "SELECT * FROM pending WHERE username='$username'";
    $qu = mysqli_query($con,$sql);
    $f = mysqli_fetch_array($qu);
    $name = $f['full_name'];
    $id = $f['supplier_id'];
    $package = $f['package'];
    $send = $f['debit'];
    $tran = $f['transaction'];
    $refer = $f['reference'];
    $price_query = "SELECT * FROM groups WHERE groupname='$package'";
    $qu = mysqli_query($con,$price_query);
    $fi = mysqli_fetch_array($qu);
    $sup_price = $fi['supplier'];
    //Balance adjustment
    $user = "SELECT SUM(debit) as de, SUM(credit) as cr,SUM(supplier_ammount) as am,SUM(supplier_payment) as pm FROM transaction WHERE supplier_id='$id'";
    $usr = mysqli_query($con,$user);
    $u = mysqli_fetch_array($usr);
    $de = $u['de'];
    $cr = $u['cr'];
    $balance = $de - $cr;
    $bala = $balance + $send;
    $sup_am = $u['am'];
    $sup_pay = $u['pm'];
    $sup_bal = $sup_am - $sup_pay;
    $sup_ba = $sup_bal + $sup_price;
    //Insert Transaction Table
    $add = "INSERT INTO transaction (supplier_id,supplier_name,transaction,debit,balance,reference) 
VALUES('$id','$name','$tran','$send','$bala','$refer')";
    $end = mysqli_query($con,$add);

    $create = mysqli_query($con,$trans);
    //New Expiration Set an User
    $exp = "SELECT * FROM radcheck WHERE username='$username' && attribute='Expiration'";
    $chk = mysqli_query($con,$exp);
    $row = mysqli_num_rows($chk);
    if ($row > 0) {
        $f = mysqli_fetch_array($chk);
        $date = $f['value'];
        $exis_month = date('m', strtotime($date));
        $new_year = date('Y', strtotime($date));
        $new_month = $exis_month + 1;
        if ($exis_month >= 12) {
            $new_year = $new_year + 1;
            $new_month = 1;
        }

        $number = cal_days_in_month(CAL_GREGORIAN, $new_month, $new_year);

        $new_date = date('d M Y', strtotime($date . "+$number days"));
        $sql = "UPDATE radcheck set value='$new_date 23:59' WHERE username='$username' && attribute='Expiration'";
        $mysql = mysqli_query($con,$sql);
        if ($mysql) {
            echo "New Expiration Set: " . $new_date . " 23:59";
        }
        $trans = "INSERT INTO transaction (supplier_id,supplier_name,transaction,credit,balance,supplier_ammount,supplier_balance,reference)
 VALUES('$id','$name','$package','$send','$balance','$sup_price','$sup_ba','$username Renew')";

    }
    //Delete Pending List
    $del = "DELETE  FROM pending WHERE supplier_id='$id' && username='$username'";
    mysqli_query($con,$del);
}
if (isset($_GET['del'])) {
    $del = "DELETE  FROM pending WHERE supplier_id='$id'";
    $end = mysqli_query($con,$del);
    if ($end) {
        echo "Request Deleted.";
    } else {
        echo "Error...";
    }
}
?>