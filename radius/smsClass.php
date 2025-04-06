<?php
function userCreate($mobile, $admin_id)
{
    include "config/data.php";
    $sql = "SELECT smsgateway.*, cron_job.* FROM smsgateway INNER JOIN cron_job ON smsgateway.admin_id=cron_job.admin_id WHERE smsgateway.admin_id='$admin_id'";
    $query = mysqli_query($conn, $sql);
    $row = mysqli_num_rows($query);
    if ($row > 0) {
        $f = mysqli_fetch_array($query);
        $url = $f['url'];
        $api_username = $f['username'];
        $api_password = $f['password'];
        $message = $f['create_message'];
        $data = array(
            "username" => "$api_username",
            "password" => "$api_password",
            "number" => "$mobile",
            "message" => "$message"
        );

        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("|", $smsresult);
        $sendstatus = $p[0];
        if ($sendstatus == '1101') {
            if ($mobile != '') {
                $sql = "INSERT INTO `sms` (`admin_id`,`mobile`,`message`) VALUES('$admin_id','$mobile','$message')";
                mysqli_query($conn, $sql);
                echo "success";
            }
        } else {
            echo "Error";
        }

    }

}

function userExpire($mobile, $admin_id)
{
    include "config/data.php";
    $sql = "SELECT smsgateway.*, cron_job.* FROM smsgateway INNER JOIN cron_job ON smsgateway.admin_id=cron_job.admin_id WHERE smsgateway.admin_id='$admin_id'";
    $query = mysqli_query($conn, $sql);
    $row = mysqli_num_rows($query);
    if ($row > 0) {
        $f = mysqli_fetch_array($query);
        $url = $f['url'];
        $api_username = $f['username'];
        $api_password = $f['password'];
        $message = $f['expire_message'];
        $data = array(
            "username" => "$api_username",
            "password" => "$api_password",
            "number" => "$mobile",
            "message" => "$message"
        );

        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("|", $smsresult);
        $sendstatus = $p[0];
        if ($sendstatus == '1101') {
            if ($mobile != '') {
                $sql = "INSERT INTO `sms` (`admin_id`,`mobile`,`message`) VALUES('$admin_id','$mobile','$message')";
                mysqli_query($conn, $sql);
                echo "success";
            }
        } else {
            echo "Error";
        }

    }

}

?>
