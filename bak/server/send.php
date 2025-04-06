<?php
include "config/data.php";
if(isset($_GET['otp'])) {
    $mobile = $_SESSION['mobile'];
    $rand = rand(1, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
    $_SESSION['otp'] = $rand;
    $ERROR_MESSAGE = '';

// ************* Call API:
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://powersms.banglaphone.net.bd/httpapi/sendsms");
    curl_setopt($ch, CURLOPT_POST, 1);// set post data to true
    curl_setopt($ch, CURLOPT_POSTFIELDS, "userId=hotspot&password=PcLh0tsp0t321&smsText=Your verification code is: " . $rand . "&commaSeperatedReceiverNumbers=" . $mobile);   // post data
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($ch);
    curl_close($ch);
    $obj = json_decode($json);
    $sql = "INSERT INTO sms (supplier_id,mobile,message) VALUES('Hotspot System','$mobile','Your verification code is $rand')";
    mysqli_query($con,$sql);
    if ($obj->{'message'} == 'Success!') {
        echo "OTP send successfully";
    } else {
        $ERROR_MESSAGE = $obj->{'isError'};
    }
}



?>