<?php
include('config.php');

$cur = time();

if ($expire < $cur) {
    $new_expire = time() + 86400 * 5;
    $post_token = array(
        "app_key" => $app_key,
        "app_secret" => $app_secret

    );
    $url = curl_init($token_url);
    $posttoken = json_encode($post_token);
    $header = array(
        'Content-Type:application/json',
        'password:' . $password,
        'username:' . $username


    );

    curl_setopt($url, CURLOPT_HTTPHEADER, $header);
    curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($url, CURLOPT_POSTFIELDS, $posttoken);
    curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($url, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($url, CURLOPT_TIMEOUT, 30);
    //curl_setopt($url, CURLOPT_PROXY, $proxy);
    $resultdata = curl_exec($url);
    curl_close($url);
    $data = json_decode($resultdata);
    $token = $data->id_token;
    $refresh_token = $data->refresh_token;
    $sql = "UPDATE bkash SET `token`='$token', `refresh_token`='$refresh_token',`expiry`='$new_expire' WHERE `mode`='$mode'";
    mysqli_query($con, $sql);
} else {

    $post_token = array(
        "app_key" => $app_key,
        "app_secret" => $app_secret,
        "refresh_token" => $refresh

    );
    $url = curl_init($refresh_url);
    $posttoken = json_encode($post_token);
    $header = array(
        'Content-Type:application/json',
        'password:' . $password,
        'username:' . $username


    );

    curl_setopt($url, CURLOPT_HTTPHEADER, $header);
    curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($url, CURLOPT_POSTFIELDS, $posttoken);
    curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($url, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($url, CURLOPT_TIMEOUT, 30);
    //curl_setopt($url, CURLOPT_PROXY, $proxy);
    $resultdata = curl_exec($url);
    curl_close($url);
    $data = json_decode($resultdata);
    $token = $data->id_token;
    $sql = "UPDATE bkash SET `token`='$token' WHERE `mode`='$mode'";
    mysqli_query($con, $sql);
}


?>
