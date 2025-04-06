<?php

use RadiusSpot\RadiusSpot;
require_once "../class/radiusspot.php";
include "../config/data.php";
$API = new RadiusSpot($con);

    header("Content-Type:application/json");
			    $resp = '{"ResultCode":0,"ResultDesc":"Confirmation recieved successfully"}';
			    //read incoming request
			    $postData = file_get_contents('php://input');
			    //log file
			    $filePath = "\opt\appLogs\messagesc.log";
			    //error log
			    $errorLog = "\opt\appLogs\errorsc.log";

			    	    //perform business operations on $jdata here
			    //open text file for logging messages by appending
			    $file = fopen($filePath,"a");
			    //log incoming request
			    fwrite($file, $postData);
			    fwrite($file,"\r\n");
			    //log response and close file
			    fwrite($file,$resp);
			    fclose($file);

$callbackJSONData = file_get_contents('php://input');
$myfile = fopen("payment_log.txt", "w+") or die("Unable to open file!");
fwrite($myfile, $callbackJSONData);
fwrite($myfile, "\n");
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

//added by deno
        $callbackJSONData=file_get_contents('php://input');
        			$callbackData=json_decode($callbackJSONData);
        			$resultCode=$callbackData->Body->stkCallback->ResultCode;
        			$resultDesc=$callbackData->Body->stkCallback->ResultDesc;
        			$merchantRequestID=$callbackData->Body->stkCallback->MerchantRequestID;
        			$checkoutRequestID=$callbackData->Body->stkCallback->CheckoutRequestID;
		        $amount=$callbackData->Body->stkCallback->CallbackMetadata->Item[0]->Value;
		        $mpesaReceiptNumber=$callbackData->Body->stkCallback->CallbackMetadata->Item[1]->Value;

    $balance=$callbackData->Body->stkCallback->CallbackMetadata->Item[2]->Name;
		        if($balance =='Balance'){		        
		        $transactionDate=$callbackData->Body->stkCallback->CallbackMetadata->Item[3]->Value;
		        $phoneNumber=$callbackData->Body->stkCallback->CallbackMetadata->Item[4]->Value;
		        }else{
		        	$transactionDate=$callbackData->Body->stkCallback->CallbackMetadata->Item[2]->Value;
		        $phoneNumber=$callbackData->Body->stkCallback->CallbackMetadata->Item[3]->Value;
		        	
		        	}

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
