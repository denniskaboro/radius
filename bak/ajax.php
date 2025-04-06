<?php
include "config/data.php";
$user=[];
$active_user=[];
$income=[];
for ($i=1; $i<=12; $i++){
    $y=date("Y");
    $date=$y."-0".$i;
    $active = 0;
    $client="SELECT radcheck.value FROM clients INNER JOIN radcheck ON clients.username=radcheck.username WHERE create_date LIKE '%$date%' and radcheck.attribute='Expiration'";
    $q=mysqli_query($con,$client);
    $total_user=mysqli_num_rows($q);
    while ($f=mysqli_fetch_array($q)){
        $expi=$f['value'];
        $ex=strtotime($expi);
        $time=time();
        if($ex > $time){
            $active = $active + 1 ;
        }
    }
    $tra="SELECT SUM(`debit`) as de FROM `transaction` WHERE `create_date` LIKE '%$date%'";
    $t=mysqli_query($con,$tra);
    $g=mysqli_fetch_array($t);
    $net_income=$g['de'];
    if($net_income== null){
        $net_income=0;
    }
    $user[]=$total_user;
    $active_user[]=$active;
    $income[]=intval($net_income);
}
echo json_encode(array("user_data"=>$user,"active_data"=>$active_user,"income_data"=>$income));

