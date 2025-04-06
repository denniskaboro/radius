<?php
include('config/data.php');
if (isset($_GET['j'])) {
    $attr = $_GET['j'];
    $sql = "SELECT * FROM dictionary WHERE  Attribute='$attr'";
    $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
    $f = mysqli_fetch_array($r);
    $val = $f['Value'];
    $op = $f['RecommendedOP'];
    $tab = $f['RecommendedTable'];
    $hints = $f['Format'];
    echo "<form method=\"POST\" role=\"form\" data-toggle=\"validator\">
        <div class=\"form-group col-sm-12\">
            <input type=\"hidden\" class=\"form-control\" id='inputAttribute' value=\"$attr\" readonly name=\"attribute\">
            <input type=\"hidden\" class=\"form-control\" value=\"$op\" name=\"op\" readonly required > <label for=\"inputValue\">Value:</label>
            <input type=\"text\"  class=\"form-control\" id='inputValue' value=\"$val\" name=\"value\"><br>";

    if ($attr == "Mikrotik-Recv-Limit" || $attr == "Mikrotik-Xmit-Limit") {
        echo "<select  class=\"form-control\" name=\"bit\"><option>MB</option><option>GB</option></select>";
    }
    echo "<input type=\"hidden\"  class=\"form - control\" value=\"$tab\" name=\"table\" readonly >
<button type=\"submit\" name=\"save\" class=\"btn btn-success\">Save</button><br><br>
<div class='col-sm-12'>Value Format is:$hints</div></form>";
} ?>
