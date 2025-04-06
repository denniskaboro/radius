<?php
include "../config/data.php";
include "../config/email.php";
$sql = "SELECT smsgateway.*, cron_job.* FROM `smsgateway` INNER JOIN cron_job ON  smsgateway.supplier_id=cron_job.supplier_id WHERE smsgateway.supplier_id='Admin'";
$s = mysqli_query($con, $sql);
$row = mysqli_num_rows($s);
if ($row > 0) {
    $ss = mysqli_fetch_array($s);
    $api_url = $ss['url'];
    $SenderId = $ss['SenderId'];
    $ClientId = $ss['ClientId'];
    $ApiKey = $ss['ApiKey'];
    $AccessKey = $ss['AccessKey'];
    $expire_message = $ss['expire_message'];
    $send_date = $ss['expire_time'];
    $p_user = "/[*][*]+[username]+[*][*]/i";
    $p_pass = "/[*][*]+[password]+[*][*]/i";
    $p_name = "/[*][*]+[full_name]+[*][*]/i";
    $p_exp = "/[*][*]+[expiration]+[*][*]/i";
    $p_date = "/[*][*]+[date]+[*][*]/i";
    $cur_date = date("d M Y H:i:s");
}
$month = date("m");
$year = date("Y");
$sql = "SELECT radcheck.value, clients.* FROM clients
 INNER JOIN radcheck ON clients.username=radcheck.username
 WHERE radcheck.attribute='Expiration' and ( clients.sms_send !=0 or clients.email_send !=0 )";
$q = mysqli_query($con, $sql);
while ($f = mysqli_fetch_array($q)) {
    $username = $f['username'];
    $sms_sql = "SELECT * FROM `sms_check` WHERE `username`='$username' and `month`='$month' and `year`='$year'";
    $sm = mysqli_query($con, $sms_sql);
    $ss = mysqli_num_rows($sm);
    if ($ss <= 0) {
        $email = $f['email'];
        $exp = $f['value'];
        $full_name = $f['full_name'];
        $mobile = $f['mobile'];
        $sms = $f['sms_send'];
        $mail = $f['email_send'];
        $cur = time();
        $expiration = strtotime($exp . "- $send_date");
        if ($expiration <= $cur) {
            if ($sms == "1") {
                $message = preg_replace(
                    array($p_name, $p_user, $p_exp, $p_date),
                    array($full_name, $username, "$exp", "$cur_date"),
                    $expire_message
                );
                $p_m = "/^0|^254/i";
                $mobile = preg_replace($p_m, "", $mobile);
                $total[] = array(
                    "Number" => "254" . $mobile,
                    "Text" => "$message"
                );
                $data = array(
                    "SenderId" => $SenderId,
                    "IsUnicode" => true,
                    "IsFlash" => true,
                    "MessageParameters" => $total,
                    "ApiKey" => $ApiKey,
                    "ClientId" => $ClientId
                );

                $in = "INSERT INTO sms (`supplier_id`,`username`,`mobile`,`message`,`sms_count`) VALUES('Admin','$username','$mobile','$message','1')";
                mysqli_query($con, $in);
                $sin = "INSERT INTO sms_check (`username`,`date_send`,`month`,`year`) VALUES('$username','$cur_date','$month','$year')";
                mysqli_query($con, $sin);
            }

            if ($mail == "1") {
                require_once '../PHPMailer/PHPMailerAutoload.php';
                $site_sql = "SELECT * FROM `site_setting` WHERE `status`='1'";
                $site = mysqli_query($con, $site_sql);
                $s = mysqli_fetch_array($site);
                $site_name = $s['site_name'];
                $site_logo = $s['site_logo'];

                $subject = 'Account Expired';
                $mail = new PHPMailer;

                $mail->SMTPDebug = 0;                                       // Enable verbose debug output
                $mail->isSMTP();                                            // Set mailer to use SMTP
                $mail->Host = $email_host; //'smtp.gmail.com', 'box5413.bluehost.com', 'mail.fingerthink.com';  // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                                   // Enable SMTP authentication
                $mail->Username = $email_user;                     // SMTP username
                $mail->Password = $email_pass;                               // SMTP password
                $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587;                                    // TCP port to connect to
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
                $mail->SetFrom($email_user, $site_name);

                //Recipients
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = $subject;
                $mail->Body = $expire_message;
                $mail->addAttachment("../site/$site_logo"); // attachment
                $mail->send();
            }
        }
    }

}
if(!isset($data)){
$data=null;
}
if($data !=null){
$postData = json_encode($data);
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $api_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $postData,
    CURLOPT_HTTPHEADER => [
        "AccessKey: " . $AccessKey,
        "Content-Type: application/json"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);
$result = json_decode($response);
}
?>

