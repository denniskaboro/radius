<?php

use RadiusSpot\RadiusSpot;

require_once "../class/radiusspot.php";
include "../config/data.php";

$API = new RadiusSpot($con);

$callbackJSONData = file_get_contents('php://input');
$myfile = fopen("payment_log.txt", "w") or die("Unable to open file!");

fwrite($myfile, $callbackJSONData);
fclose($myfile);
$res = json_decode($callbackJSONData);

$CheckoutRequestID = $res->Body->stkCallback->CheckoutRequestID;
$MerchantRequestID = $res->Body->stkCallback->MerchantRequestID;
$resultCode = $res->Body->stkCallback->ResultCode;
$resultDes = $res->Body->stkCallback->ResultDesc;
if ($resultCode == 0) {
    $amount = $res->Body->stkCallback->CallbackMetadata->Item[0]->Value;
    $trxId = $res->Body->stkCallback->CallbackMetadata->Item[1]->Value;
    $trxDate = $res->Body->stkCallback->CallbackMetadata->Item[3]->Value;
    $number = $res->Body->stkCallback->CallbackMetadata->Item[4]->Value;

    $API->dataInsert("hotspot_payment", array(
            [
                "CheckoutRequestID" => "$CheckoutRequestID",
                "MerchantRequestID" => "$MerchantRequestID",
                "ResultCode" => "$resultCode",
                "ResultDesc" => "$resultDes",
                "amount" => "$amount",
                "trxId" => "$trxId",
                "trxDate" => "$trxDate",
                "number" => "$number"
            ]
        )
    );
} else {
    $API->dataInsert("hotspot_payment", array(
            [
                "CheckoutRequestID" => "$CheckoutRequestID",
                "MerchantRequestID" => "$MerchantRequestID",
                "ResultCode" => "$resultCode",
                "ResultDesc" => "$resultDes"
            ]
        )
    );
}