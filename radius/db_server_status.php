<?php
include("includes/header.php");
?>

<?php
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "http://10.90.91.15/server_status.php",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
));
$response = curl_exec($curl);
curl_close($curl);
echo $response;
?>
