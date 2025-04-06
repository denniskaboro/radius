<?php
/**
 * Created by PhpStorm.
 * User: JEET
 * Date: 15-Jun-19
 * Time: 1:56 PM
 */
include('config/data.php');
if (isset($_GET['j'])) {
    $attr = $_GET['j'];
    $sql = "SELECT * FROM dictionary WHERE  Attribute='$attr'";
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    $f = mysqli_fetch_array($r);
    $op = $f['RecommendedOP'];
    $tab = $f['RecommendedTable'];
    $hints = $f['Format'];
    echo "<form method=\"POST\" role=\"form\" data-toggle=\"validator\">
        <div class=\"form-group col-sm-12\">
            <input type=\"hidden\" class=\"form-control\" id='inputAttribute' value=\"$attr\" readonly name=\"attribute\">
            <input type=\"hidden\" class=\"form-control\" value=\"$op\" name=\"op\" readonly required >";
    echo "<label for=\"inputValue\">Select NAS:</label><select class=\"form-control\" onchange=\"showTest(this.value)\">";
    echo "<option>Select</option>";
    $qu = "SELECT * FROM nas";
    $r = mysqli_query($con, $qu);
    while ($f = mysqli_fetch_array($r)) {
        echo "<option>" . $f['nasname'] . "</option>";
    }
    echo "</select><div id='tst'></div><br>
<input type=\"hidden\"  class=\"form - control\" value=\"$tab\" name=\"table\" readonly >
<button type=\"submit\" name=\"save\" class=\"btn btn-success\">Save</button><br><br>
Value Format is:.$hints</form>";
} ?>


