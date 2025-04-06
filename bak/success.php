<?php
include 'log.php';
$API = new log_api();
include "config/data.php";
$callbackJSONData = file_get_contents('php://input');

$callbackJSONData = file_get_contents('php://input');
$myfile = fopen("DOC/payment_log.txt", "w+") or die("Unable to open file!");

fwrite($myfile, $callbackJSONData);
fclose($myfile);
$callbackData = json_decode($callbackJSONData);
$phone = $callbackData->MSISDN;


//echo 2; exit;

/*

check if  TransID is already there

exit;

if($this->transactions->checktrasaction($callbackData->TransID)){


}*/


$TransactionType = $callbackData->TransactionType;
$TransID = $callbackData->TransID;
$TransTime = $callbackData->TransTime;
$TransAmount = $callbackData->TransAmount;
$BusinessShortCode = $callbackData->BusinessShortCode;
$BillRefNumber = $callbackData->BillRefNumber;
$InvoiceNumber = $callbackData->InvoiceNumber;
$OrgAccountBalance = $callbackData->OrgAccountBalance;
$ThirdPartyTransID = $callbackData->ThirdPartyTransID;
$MSISDN = $callbackData->MSISDN;
$FirstName = $callbackData->FirstName;
$MiddleName = $callbackData->MiddleName;
$LastName = $callbackData->LastName;
$date = date('Y-m-d H:i:s', strtotime($TransTime));
$Status = 1;
$assigned = 0;


$in = "INSERT INTO `mpesaresponses` (`TransactionType`,`TransID`,`TransTime`,`TransAmount`,`BusinessShortCode`,
`BillRefNumber`,`InvoiceNumber`,`OrgAccountBalance`,`ThirdPartyTransID`,`MSISDN`,`FirstName`,`MiddleName`,`LastName` )
VALUES ('$TransactionType','$TransID','$TransTime','$TransAmount','$BusinessShortCode','$BillRefNumber','$InvoiceNumber',
'$OrgAccountBalance','$ThirdPartyTransID','$MSISDN','$FirstName','$MiddleName','$LastName')";

$r = mysqli_query($con, $in);
$newId=$con->insert_id;
$newDate = date("d M Y H:i", strtotime("+1 month"));
$sql="SELECT clients.mobile,groups.*, radcheck.value FROM radusergroup INNER JOIN groups ON radusergroup.groupname=groups.id INNER JOIN radcheck ON radusergroup.username=radcheck.username INNER JOIN clients ON radusergroup.username=clients.username WHERE radusergroup.username='$BillRefNumber' and radcheck.attribute='Cleartext-Password'";
$qq = mysqli_query($con, $sql);
$row = mysqli_num_rows($qq);
if ($row > 0) {
    $g=mysqli_fetch_array($qq);
    $price=$g['price'];
    $du=$g['duration'];
    $password=$g['value'];
    $package=$g['groupname'];
    $mobile=$g['mobile'];
    $userB = "INSERT INTO `user_balance` (`username`,`debit`,`transactionId`)
	 VALUES('$BillRefNumber','$TransAmount','$TransID')";
    $ubil=mysqli_query($con, $userB) or $errLog=mysqli_error($con);
    if($ubil){
	$upd="UPDATE `mpesaresponses` SET `assigned`='1' WHERE `id`='$newId'";
	mysqli_query($con,$upd);
        $ckh="SELECT SUM(`debit`) as de , SUM(`credit`) as cr FROM user_balance WHERE username='$BillRefNumber'";
        $cq=mysqli_query($con,$ckh);
        $f=mysqli_fetch_array($cq);
        $debit=$f['de'];
        $credit=$f['cr'];
        $balance=$debit-$credit;
        if($balance >=$price){
			 $sql = "SELECT * FROM radcheck WHERE attribute='Expiration' && username='$BillRefNumber'";
			$rs = mysqli_query($con,$sql) or $msg = mysqli_error($con);
			$row=mysqli_num_rows($rs);
	if($row>0){
		$p = mysqli_fetch_array($rs);
		$exp = $p['value'];
		$today=time();
		$pre=strtotime($exp);
		if($pre > $today){
                	$newDate=date("d M Y H:i:s",strtotime($exp."+".$du));
            	}else{
                	$newDate = date("d M Y H:i:s",strtotime("+".$du));
            	}
		$update = "UPDATE radcheck set `value`='$newDate' WHERE attribute='Expiration' && username='$BillRefNumber'";
        	$up = mysqli_query($con,$update);

	}else{
		$newDate = date("d M Y H:i:s",strtotime("+".$du));
		$ss="INSERT INTO radcheck(`username`,`attribute`,`op`,`value`) VALUES('$BillRefNumber','Expiration',':=','$newDate')";
		$up=mysqli_query($con,$ss);
	}
	if($up){
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
                                $message = $ss['payment_message'];
				$fullname=$FirstName." ".$MiddleName." ".$LastName;
                                $p_user ="/[*][*]+[username]+[*][*]/i";
                                $p_pass ="/[*][*]+[password]+[*][*]/i";
                                $p_name ="/[*][*]+[full_name]+[*][*]/i";
                                $p_exp ="/[*][*]+[expiration]+[*][*]/i";
                                $p_date ="/[*][*]+[date]+[*][*]/i";
                                $cur_date=date("d M Y H:i:s");
                                $create_message = preg_replace(
                                    array($p_name,$p_user,$p_pass,$p_exp,$p_date),
                                    array($fullname, $BillRefNumber, $password,"$newDate","$cur_date"),
                                    $message
                                );
                         	$p_m="/^0/i";
                                $mobile=preg_replace($p_m,"",$mobile);
   
                            $total[] = array(
                                "Number" => "254".$mobile,
                                "Text" => "$create_message"
                            );
                            $dat = date("Y-m-d H:i:s");
                            $data = array(
                                "SenderId" => $SenderId,
                                "IsUnicode" => true,
                                "IsFlash" => true,
                                "MessageParameters" => $total,
                                "ApiKey" => $ApiKey,
                                "ClientId" => $ClientId
                            );
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
                            if ($result->ErrorCode == 0) {
                                $in = "INSERT INTO sms (`supplier_id`,`mobile`,`message`,`sms_count`) VALUES('Admin','$mobile','$create_message','1')";
                             	mysqli_query($con, $in);
                            }else{
                        $d=$result->Data;
                        foreach($d as $dd){
                                $API->log("Admin", $BillRefNumber, $dd->MessageErrorDescription);
                                }
                        }
		}
            		$trans = "INSERT INTO `transaction` (`supplier_id`,`supplier_name`,`transaction`,`debit`,`reference`)
	 VALUES('Admin','Admin','$TransID','$price','Online Payment Received for $BillRefNumber')";
            		mysqli_query($con,$trans);
            		$API->log("Admin","Online Payment Received for $BillRefNumber");
            		$userB = "INSERT INTO `user_balance` (`username`,`credit`,`transactionId`)
	 VALUES('$BillRefNumber','$price','$package')";
			mysqli_query($con,$userB);
            		$API->log("Admin","Date Extend to User $BillRefNumber");
			$scl="UPDATE clients set `approval`='Approved By PayBill' WHERE  username='$BillRefNumber'";
	                mysqli_query($con,$scl);
		require_once 'PHPMailer/PHPMailerAutoload.php';
		require_once 'config/email.php';
                $site_sql = "SELECT * FROM `site_setting` WHERE `status`='1'";
                $site = mysqli_query($con,$site_sql);
                $s = mysqli_fetch_array($site);
                $site_name = $s['site_name'];
                $site_logo = $s['site_logo'];
		$user_sql = "SELECT email FROM `clients` WHERE `username`='$BillRefNumber'";
                $usr = mysqli_query($con,$user_sql);
                $u = mysqli_fetch_array($usr);
                $email = $u['email'];

                $subject = 'Payment Successful';
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
                $mail->SetFrom($admin_email, $site_name);

                //Recipients
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = $subject;
                $mail->Body = $create_message;
                $mail->addAttachment("site/$site_logo"); // attachment
                $mail->send();
	}
        }
    }else{
$API->log("Admin",$errLog);
}
}
?>
