<?php
/**
 * Created by PhpStorm.
 * User: JEET
 * Date: 02-Jul-19
 * Time: 10:03 AM
 */
include('config/data.php');
if (isset($_GET['divi'])) {
    $division_name = $_GET['divi'];
    $sql = "SELECT * FROM divisions WHERE name='$division_name'";
    $res = mysqli_query($con, $sql);
    $f = mysqli_fetch_array($res);
    $id = $f['id'];
    $sql = "SELECT * FROM districts WHERE division_id='$id'";
    $rs = mysqli_query($con, $sql);
    echo "<label for=\"inputDistrict\" class=\"control-label\">District</label>
    <select class=\"form-control\"  onchange=\"showDis(this.value)\" name=\"district\" required>
        <option></option>";
    while ($f = mysqli_fetch_array($rs)) {
        $name = $f['name'];
        echo "<option>" . $name . "</option>";
    }
    echo "</select>";
}

if (isset($_GET['dis'])) {
    $dis_name = $_GET['dis'];
    $sql = "SELECT * FROM districts WHERE name='$dis_name'";
    $res = mysqli_query($con, $sql);
    $f = mysqli_fetch_array($res);
    $id = $f['id'];
    $sql = "SELECT * FROM upazilas WHERE district_id='$id'";
    $rs = mysqli_query($con, $sql);
    echo "<label for=\"inputThana\" class=\"control-label\">Upazila</label>
    <select class=\"form-control\" name=\"upazila\" required>
        <option></option>";
    while ($f = mysqli_fetch_array($rs)) {
        $name = $f['name'];
        $lower = strtolower($name);
        echo "<option>" . ucfirst($lower) . "</option>";
    }
    echo "</select>";

}


?>
