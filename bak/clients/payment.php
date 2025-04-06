<?php
include('config.php');
if (isset($_GET['number'])) {
    $username=$_SESSION['username'];
    $part = $_GET['number'];
    $pack = $_GET['pack'];
    $sql = "SELECT * FROM groups WHERE id='$pack'";
    $gr = mysqli_query($con,$sql);
    $p = mysqli_fetch_array($gr);
    $price=$p['price'];
    $duration=$p['duration'];
    $newDate=date("d M Y H:i:s",strtotime("+".$duration));
    $pattern = '/^0/i';
    $replacement = '';
    $num=preg_replace($pattern, $replacement, $part);
    $partA='254'.$num;
    $basepass = base64_encode($shortcode.$passKey.$timestamp);
    $header = array(
        'Content-Type:application/json'
    );
    $url = curl_init();
    curl_setopt($url, CURLOPT_URL, $token_url."?grant_type=client_credentials");
    curl_setopt($url, CURLOPT_HTTPHEADER, $header);
    curl_setopt($url, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($url, CURLOPT_USERPWD, $mpea_user.':'.$mpea_pass);
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
        'TransactionType' => $pType,
        'Amount' =>(int)$price,
        'PartyA' => $partA,
        'PartyB' => $partB,
        'PhoneNumber' => $partA,
        'CallBackURL' => $callback."/success.php",
        'AccountReference' => $username,
        'TransactionDesc' => $desc
    );

   /*echo "<pre>";
    print_r($curl_post_data);
    exit;*/
    $data_string = json_encode($curl_post_data);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $payment_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $payheader);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

    $curl_response = curl_exec($curl);
    $resultdata = json_decode($curl_response);
    if(isset($resultdata->errorMessage)){
        unset($_SESSION['price']);
        $rep=["status"=>"error","message"=>$resultdata->errorMessage];
        echo json_encode($rep);
    }else {
        $succ = $resultdata->CustomerMessage;
        $code = $resultdata->ResponseCode;
        $transID = $resultdata->CheckoutRequestID;
        
        $insert="INSERT INTO `payment_processing` (`username`,`price`,`package`,`expiration`,`transactionID`,`password`,`shortcode`,`timestamp`) 
        VALUES('$username','$price','$pack','$newDate','$transID','$basepass','$shortcode','$timestamp')";
       $ss= mysqli_query($con,$insert);
        $rep=["status"=>"success","message"=>"$succ"];
        echo json_encode($rep);
    }

}
?>
