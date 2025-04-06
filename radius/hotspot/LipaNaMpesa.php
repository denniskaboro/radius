<?php

use RadiusSpot\RadiusSpot;

session_start();
require_once "config.php";
require_once "../class/radiusspot.php";
require_once "../config/data.php";

$API = new RadiusSpot($con);

if (isset($_GET['number']) and isset($_GET['group_id'])) {
    $username = $_SESSION['mac'];
    $check = $API->userCheck($username);
    if ($check) {
        $group_id = $_GET['group_id'];
        $_SESSION['group_id'] = $group_id;
        $part = $_GET['number'];
        $pattern = '/^0/i';
        $replacement = '';
        $num = preg_replace($pattern, $replacement, $part);
        $partA = '254' . $num;
        ///$partA ='254'.substr($num,-9,9);

        $sql = "SELECT * FROM groups WHERE id='$group_id'";
        $r = mysqli_query($con, $sql);
        $f = mysqli_fetch_array($r);
        $name = $f['groupname'];
        $price = $f['price'];

        $basepass = base64_encode($shortcode . $passKey . $timestamp);
        $header = array(
            'Content-Type:application/json'
        );
        $url = curl_init();
        curl_setopt($url, CURLOPT_URL, $token_url . "?grant_type=client_credentials");
        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_USERPWD, $customer_key . ':' . $customer_secret);
        curl_setopt($url, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($url, CURLOPT_TIMEOUT, 50);

        $resultdata = curl_exec($url);
        curl_close($url);
        $data = json_decode($resultdata);
        if (isset($data->access_token)) {
            $access_token = $data->access_token;

            $payheader = array(
                'Content-Type:application/json',
                'Authorization:Bearer ' . $access_token
            );

            $curl_post_data = array(
                'BusinessShortCode' => $shortcode,
                'Password' => $basepass,
                'Timestamp' => $timestamp,
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => $price,
                'PartyA' => $partA,
                'PartyB' => $shortcode,
                'PhoneNumber' => $partA,
                'CallBackURL' => $callback . "/hotspot/success.php",
                'AccountReference' => $_SESSION['username'],
                'TransactionDesc' => $_SESSION['username'],
            );
            $data_string = json_encode($curl_post_data);

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $payment_url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $payheader);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

            $curl_response = curl_exec($curl);
            $resultdata = json_decode($curl_response);
            if (isset($resultdata->errorMessage)) {
                $response = array("status" => "error", "err_code" => "1", "message" => $resultdata->errorMessage);
            } else {
                $succ = $resultdata->CustomerMessage;
                $code = $resultdata->ResponseCode;
                $trans = $resultdata->CheckoutRequestID;
                $insert = "INSERT INTO `payment_processing` (`username`,`price`,`package`,`transactionID`,`password`,`shortcode`,`timestamp`)
        VALUES('$username','$price','$group_id','$trans','$basepass','$shortcode','$timestamp')";
                $ss = mysqli_query($con, $insert) or $succ = mysqli_error($con);
                $response = array("status" => "success", "err_code" => "0", "message" => "$succ", "CheckoutRequestID" => $trans);
            }
        } else {
            $response = array("status" => "error", "err_code" => "1", "message" => $data->errorMessage);
        }
    } else {
        $response = array("status" => "error", "err_code" => "1", "message" => "You have some unused data. Please contact with Administrator");
    }

    echo json_encode($response);
}


?>