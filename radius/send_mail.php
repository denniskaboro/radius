<?php
include "config/data.php";
include "config/email.php";
require_once 'PHPMailer/PHPMailerAutoload.php';
$site_sql = "SELECT * FROM `site_setting` WHERE `status`='1'";
$site = mysqli_query($con, $site_sql);
$s = mysqli_fetch_array($site);
$site_name = $s['site_name'];
$site_logo = $s['site_logo'];
$email = "abuj.jeet@gmail.com";
$create_message = "Dear User,
Your account has been create in $site_name. Please login to your account Using below information.";
$subject = 'Account Created';
$mail = new PHPMailer;

$mail->SMTPDebug = 2;
$mail->isSMTP();                                            // Set mailer to use SMTP
$mail->Host = $email_host; //'smtp.gmail.com', 'box5413.bluehost.com', 'mail.fingerthink.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                                   // Enable SMTP authentication
$mail->Username = $email_user;                     // SMTP username
$mail->Password = $email_pass;                               // SMTP password
$mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465;                                    // TCP port to connect to
//$mail->SMTPOptions = array(
//    'ssl' => array(
//        'verify_peer' => false,
//        'verify_peer_name' => false,
//        'allow_self_signed' => true
//    )
//);
$mail->SetFrom($admin_email, $site_name);

//Recipients
//$mail->addAddress($email);
$mail->addAddress($email);

// Content
$mail->isHTML(true);                                  // Set email format to HTML
$mail->Subject = $subject;
$mail->Body = $create_message;
$mail->addAttachment("site/$site_logo"); // attachment
$mail->send();
//if($mail->Send()){
//    $mail->ClearAddresses();
//}

