<?php
include('config.php');
if (isset($_GET['number'])) {
    $username = $_SESSION['username'];
    $part = $_GET['number'];
    $price = $_GET['amount'];
    $pattern = '/^0/i';
    $replacement = '';
    $num = preg_replace($pattern, $replacement, $part);
    $partA = '254' . $num;
    $basepass = base64_encode($shortcode . $passKey . $timestamp);
    $header = array(
        'Content-Type:application/json'
    );
    $url = curl_init();
    curl_setopt($url, CURLOPT_URL, $token_url);
    curl_setopt($url, CURLOPT_HTTPHEADER, $header);
    curl_setopt($url, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($url, CURLOPT_USERPWD, $mpea_user . ':' . $mpea_pass);
    curl_setopt($url, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($url, CURLOPT_TIMEOUT, 30);

    $resultdata = curl_exec($url);
    curl_close($url);
    $data = json_decode($resultdata);
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
        'CallBackURL' => $callback,
        'AccountReference' => $refer,
        'TransactionDesc' => $desc
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
        $response = array("code" => "1", "message" => $resultdata->errorMessage);
        echo json_encode($response);
    } else {
        $succ = $resultdata->CustomerMessage;
        $code = $resultdata->ResponseCode;
        $response = array("code" => $code, "message" => $succ);
        $in = "INSERT INTO `transaction` (`supplier_id`,`supplier_name`,`transaction`,`debit`,`reference`) VALUES ('Admin','Admin','$part','$price','$username')";
        mysqli_query($con, $in);
        echo json_encode($response);
    }

}
?>