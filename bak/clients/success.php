<?php



		       	$callbackJSONData=file_get_contents('php://input');
		      
		        $callbackJSONData=file_get_contents('php://input');
		        $myfile = fopen("payment_log.txt", "w") or die("Unable to open file!");
            
                fwrite($myfile, $callbackJSONData);
                fclose($myfile);
		        $callbackData=json_decode($callbackJSONData);
				 $phone =$callbackData->MSISDN;

				/* 

				check if  TransID is already there

				exit;

			     if($this->transactions->checktrasaction($callbackData->TransID)){
		        	 			

		         }*/
	 
		       
		        $TransactionType 	=	$callbackData->TransactionType;
		        $TransID 			= 	$callbackData->TransID;
		        $TransTime 			=	$callbackData->TransTime;
		        $TransAmount 		=	$callbackData->TransAmount;
		        $BusinessShortCode 	=	$callbackData->BusinessShortCode;
		        $BillRefNumber 		=	$callbackData->BillRefNumber;
		        $InvoiceNumber 		=	$callbackData->InvoiceNumber;
		        $OrgAccountBalance 	=	$callbackData->OrgAccountBalance;
		        $ThirdPartyTransID 	=	$callbackData->ThirdPartyTransID;
		        $MSISDN 			=	$callbackData->MSISDN;
		        $FirstName 			=	$callbackData->FirstName;
		        $MiddleName 		= 	$callbackData->MiddleName;
		        $LastName 			=	$callbackData->LastName;
		        $date=	date('Y-m-d H:i:s',strtotime($TransTime));
		        $Status=1;
		        $assigned=0;
 
		

		             echo   $in = "INSERT INTO `mpesaresponses` ( 
		                               `TransactionType`,
                                       `TransID`, 
                                       `TransTime`,
                                       `TransAmount`, 
                                       `BusinessShortCode`, 
                                       `BillRefNumber`, 
                                       `InvoiceNumber`,
                                       `OrgAccountBalance`,
                                       `ThirdPartyTransID`,
                                       `MSISDN`, 
                                       `FirstName`, 
                                       `MiddleName`, 
                                       `LastName`,
                                       `Status`,
                                       `Datecreated`, 
                                       `DateModified`,
                                       `assigned` ) 
                                 VALUES ('$TransactionType',
                                          '$TransID',
                                          '$TransTime',
                                          '$TransAmount',
                                          '$BusinessShortCode',
                                          '$BillRefNumber',
                                          '$InvoiceNumber',
                                          '$OrgAccountBalance',
                                          '$ThirdPartyTransID',
                                          '$MSISDN',
                                          '$FirstName',
                                          '$MiddleName',
                                          '$LastName',
                                          '$Status',
                                          '$date',
                                          '$date',
                                          '$assigned'
                                       )";
                               $con=new mysqli ('localhost','root','Wh@t!$L!f3', 'radius') or die('connection error');
                               $r = mysqli_query($con,$in);

                             
		                       if($r){


		                    	    echo "save successfully";

		                    	    // proceed with user account
		                    	    // Use MSISDN to get user account
		      
		                        }

		       





?>
