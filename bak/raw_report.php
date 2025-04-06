<?php
include "config/data.php";
    if (isset($_POST['month'])) {
    $month = $_POST['month'];
    $sql = "SELECT `op_name`, `client_type`, `conn_type`, `client_name`, `pop`, `cont_type`, `ac_date`, `bw`, `ip`, `address`, `house`, `road`, `area`, `district`, `thana`, `mobile`, `email`, `price`, `create_date` FROM report WHERE  MONTH(`month`)='$month' GROUP BY `username`";
    
//execute query
    $setRec = @mysqli_query($con,$sql) or die("Couldn't execute query:<br>" . mysqli_error($con) . "<br>" . mysql_errno());

    $filename = 'btrc_report_' . date("M_Y").".xls";


    $columnHeader = '';
    $columnHeader = "Operator Name" . "\t" . "Type of Client" . "\t" . "Type of Connection" . "\t" . "Name of Client" . "\t" .
                "Distribution Location point" . "\t" . 
                "Type of Connectivity" . "\t" . 
                "Activation Date" . "\t" . 
                "Bandwidth Allocation (Mbps)" . "\t" . 
                "Allowcated IP(s)/MAC" . "\t" . 
                "Address" . "\t" . 
                "House No." . "\t" . 
                "Road" . "\t" . 
                "Area" . "\t" . 
                "District" . "\t" . 
                "Thana" . "\t" . 
                "Client Phone" . "\t" . 
                "Email" . "\t" . 
                "Selling Bandwidth BDT excluding VAT" . "\t";

    $setData = '';

    while ($rec = mysql_fetch_row($setRec)) {
        $rowData = '';
        foreach ($rec as $value) {
            $value = '"' . $value . '"' . "\t";
            $rowData .= $value;
        }
        $setData .= trim($rowData) . "\n";
    }


    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=" . $filename);
    header("Pragma: no-cache");
    header("Expires: 0");

    //echo ucwords($columnHeader) . "\n" . $setData . "\n";
    print( ucwords($columnHeader) . "\n" . $setData . "\n");
}
?>
