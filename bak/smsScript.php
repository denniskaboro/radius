<?php 
include('config/data.php');
$month=date('m');
$sql="SELECT radcheck.value, clients.username,clients.mobile FROM `clients` INNER JOIN radcheck ON clients.username=radcheck.username WHERE radcheck.attribute='Expiration'";
$q=mysqli_query($conn,$sql);
while($r=mysqli_fetch_array($q)){
$expiration=$r['value'];
$username=$r['username'];
$mobile=$r['mobile'];
$time=strtotime('3days');
$exp=strtotime($expiration);
if($exp <$time){
$ss="SELECT * FROM `notification` WHERE username='$username' and MONTH(`create_date`)='$month'";
$check=mysqli_query($conn,$ss);
$found=mysqli_num_rows($check);
if($found<1){
$api_url = "http://66.45.237.70/api.php";
$api_username = "spmedia";
$api_password = "t@pu1989";
$message = "Dear {$username},
Your Package will be expired after {$expiration}. 
Please payment for uninterrupted service. 

Regards,
SP INTERNET SERVICE
Hot Line 01774078458";
		$data = array(
                "username" => "$api_username",
                "password" => "$api_password",
                "number" => "$mobile",
                "message" => "$message"
            );

            $ch = curl_init(); // Initialize cURL
            curl_setopt($ch, CURLOPT_URL, $api_url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $smsresult = curl_exec($ch);
            $p = explode("|", $smsresult);
            $sendstatus = $p[0];
            if ($sendstatus == '1101') {
                        if ($mobile != '') {
                        $sql = "INSERT INTO `sms` (`username`,`mobile`,`message`) VALUES('$username','$mobile','$message')";
                        mysqli_query($conn,$sql);
						$sql = "INSERT INTO `notification` (`username`) VALUES('$username')";
                        mysqli_query($conn,$sql);
                    }
                }

}

}
}

?>
