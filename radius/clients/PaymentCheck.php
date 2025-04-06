<?php
include('../config/data.php');
$sql = "SELECT * FROM mpesa WHERE `status`='1'";
$q = mysqli_query($con, $sql);
$array = mysqli_fetch_array($q);

$mpea_user = $array["customer_key"];
$mpea_pass = $array["customer_secret"];
$shortcode = $array["shortcode"];
$timestamp = date('YmdHis');
$callback = $array["success"];
$refer = $array["refer"];
$passKey = $array["passKey"];
$desc = 'Bill Payment for online service';

$payment_url = $array["payment_url"];
$token_url = $array["token_url"];
$queryURL = $array["queryURL"];

$header = array(
    'Content-Type:application/json'
);
$url = curl_init();
curl_setopt($url, CURLOPT_URL, $token_url . "?grant_type=client_credentials");
curl_setopt($url, CURLOPT_HTTPHEADER, $header);
curl_setopt($url, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($url, CURLOPT_USERPWD, $mpea_user . ':' . $mpea_pass);
curl_setopt($url, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($url, CURLOPT_TIMEOUT, 30);

$resultdata = curl_exec($url);
curl_close($url);
$resultdata = json_decode($resultdata);
$access_token = $resultdata->access_token;
$payheader = array(
    'Content-Type:application/json',
    'Authorization:Bearer ' . $access_token
);
$que = "SELECT * FROM `payment_processing`";
$q = mysqli_query($con, $que);
while ($f = mysqli_fetch_array($q)) {
    $username = $f['username'];
    $shortcode = $f['shortcode'];
    $apiPassword = base64_encode($shortcode . $passKey . $timestamp);
    $transID = $f['transactionID'];
    $pack = $f['package'];
    $newDate = $f['expiration'];
    $price = $f['price'];
    $postdata = array(
        "BusinessShortCode" => "$shortcode",
        "Password" => "$apiPassword",
        "Timestamp" => "$timestamp",
        "CheckoutRequestID" => "$transID"
    );
    $postdata = json_encode($postdata);
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $queryURL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "$postdata",
        CURLOPT_HTTPHEADER => $payheader,
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        $rep = ["status" => "error", "message" => "$err"];
    } else {
        $response = json_decode($response);
        $response;
        if ($response->ResultCode == 0) {
            $in = "INSERT INTO `transaction` (`supplier_id`,`supplier_name`,`transaction`,`debit`,`reference`) VALUES ('Admin','Admin','$transID','$price','$username')";
            mysqli_query($con, $in);
            $sql = "SELECT * FROM radcheck WHERE username='$username' and `attribute`='Expiration'";
            $q = mysqli_query($con, $sql);
            $cl = mysqli_num_rows($q);
            if ($cl > 0) {
                $up = "UPDATE radusergroup SET `groupname`='$pack' WHERE `username`='$username'";
                $u = mysqli_query($con, $up);
                $up = "UPDATE radcheck SET `value`='$newDate' WHERE `username`='$username' and `attribute`='Expiration'";
                $u = mysqli_query($con, $up);
                $que = "DELETE FROM `payment_processing` WHERE username='$username'";
                mysqli_query($con, $que);

                $rep = ["status" => "success", "message" => $response->ResultDesc];
            } else {

                $usr = "INSERT INTO `radcheck` (`username`,`attribute`,`op`,`value`) VALUES('$username','Expiration',':=','$newDate')";
                mysqli_query($con, $usr);
                $gr = "INSERT INTO `radusergroup` (`username`,`groupname`) VALUES('$username','$pack')";
                $suc = mysqli_query($con, $gr);
                $que = "DELETE FROM `payment_processing` WHERE username='$username'";
                mysqli_query($con, $que);

                $rep = ["status" => "success", "message" => $response->ResultDesc];
            }
        } elseif ($response->ResultCode == 1032) {
            $que = "DELETE FROM `payment_processing` WHERE username='$username'";
            mysqli_query($con, $que);
            $rep = ["status" => "error", "message" => $response->ResultDesc];

        } else {
            $rep = ["status" => "error", "message" => $response->ResultDesc];
        }

    }

}
?>
