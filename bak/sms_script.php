<?php 
include "config/data.php";
$ss="SELECT * FROM clients";
$qs=mysqli_query($conn,$ss);
while($f=mysqli_fetch_array($qs)){
$username=$f['username']; 
$mobile=$f['mobile'];
$current = strtotime("+3 days");
$sql1 = "SELECT * FROM radcheck WHERE attribute='Expiration' and username='$username'";
$rs = mysqli_query($conn,$sql1) or $msg = mysqli_error($conn);
$p = mysqli_fetch_array($rs);
$expi = $p['value'];
$d = strtotime($expi);
$mon=date("m");
date_default_timezone_set("Asia/Dhaka");
if ($d <= $current) {
$verify="SELECT * FROM sms WHERE `mobile`='$mobile' and MONTH(`time`)='$mon' and `notification`='1'";
$mob=mysqli_query($conn,$verify);
$row=mysqli_num_rows($mob);
if($row<1){
        $url = "http://66.45.237.70/api.php";
        $number = "88" . $mobile;
        $text = "Dear User,
Your Package will be expire soon.
Please make Payment for interrupt service.
Regards
SP INTERNET";
        $data = array(
            'username' => "spmedia",
            'password' => "t@pu1989",
            'number' => "$number",
            'message' => "$text"
        );
        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("|",$smsresult);
        $sendstatus = $p[0];
        if($sendstatus=='1101'){
                $sms="INSERT INTO `sms` (`mobile`,`message`,`notification`) VALUES('$mobile','$text','1')";
                mysqli_query($conn,$sms);
          
        }

}
}
}

?>
