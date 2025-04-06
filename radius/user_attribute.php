<?php include 'includes/header.php';
$per = $_SESSION['per'];
//For Delete Attribute
if (isset($_GET['delete_tab'])) {
    if ($per == "Full" || $per == "Admin") {
        $del = $_GET['delete_tab'];
        $match = $_GET['id'];
        $name = $_GET['val'];
        $at = $_GET['at'];
        $del = "DELETE FROM $del WHERE id='$match'";
        mysqli_query($con, $del);
        $API->log($wh, $name . " User Attribute " . $at . " delete");
    } else {
        echo "<h4 style='color: coral ;'>You have no permission...</h4>";
    }
}
//For List Attribute
if (isset($_GET['val'])) {
    $name = $_GET['val'];
    $table = ["radcheck", "radreply"];
    foreach ($table as $tab) {
        $sq = "SELECT * FROM $tab WHERE username='$name'  ";

        $res = mysqli_query($con, $sq);
        echo "<div class=\"col-lg-6 \">
    <table class=\"table table-bordered\" style=\"color:black;\" >
        <thead>
        <tr>
            <th>Username</th>
            <th>Arrtibute</th>
            <th>OP</th>
            <th>Value</th>
            <th>Delete</th>

        </tr>
        </thead>
        <tbody>";
        while ($j = mysqli_fetch_array($res)) {
            $attri = $j['attribute'];
            $sr = $j['id'];
            $op = $j['op'];
            $val = $j['value'];
            echo "<tr>
                <td>$name</td>
                <td>$attri</td>
                <td>$op</td>
                <td>$val</td>
                <td><a class=\"btn btn-danger\" href=\"user_attribute.php?val=$name&at=$attri&delete_tab=$tab&id=$sr\">
                <i class=\"icon-edit\" ><span class=\"glyphicon glyphicon-trash\"></span></i> Delete</a></td>
            ";
        }
        echo "</tr>";
    }
    echo "
        </tbody>
    </table>
</div>";


}

?>
<div class="col-md-6">
    <?php if (isset($msg)) {
        echo $msg;
    } ?>
    <h2>Set New Attribute</h2>
    <form method="POST">
        <div class="form-group col-sm-3">
            <label for="ip">Attribute Name:</label>
            <select class="form-control" onchange="showAttr(this.value)">
                <option>Select</option>
                <?php if ($per == "Admin") { ?>
                    <option value='Group'>Change Package</option>
                <?php }
                $sql = "SELECT * FROM dictionary";
                $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
                while ($f = mysqli_fetch_array($r)) {
                    ?>
                    <option value='<?php echo $f['Attribute']; ?>'><?php echo $f['fakename']; ?></option>
                <?php } ?>
            </select><br>

            <!--            <button type="submit" name="find" class="btn btn-info">Show</button>-->
        </div>
    </form>
    <div id='attri' class="container col-sm-3" style="background-color: rgba(0,0,0,0.2) !important;">

    </div>

    <script>
        function showAttr(str) {
            if (str == "") {
                document.getElementById("attri").innerHTML = "";
                return;
            }
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else { // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("attri").innerHTML = this.responseText;
                }
            }
            if (str == "Framed-Pool") {
                xmlhttp.open("GET", "nas_query.php?j=" + str, true);
                xmlhttp.send();
            } else if (str == "Group") {
                xmlhttp.open("GET", "group_change.php?j=" + str, true);
                xmlhttp.send();
            } else {
                xmlhttp.open("GET", "list_query.php?j=" + str, true);
                xmlhttp.send();
            }

        }
    </script>

    <?php
    if (isset($_POST['save'])) {
        if ($per == "Full" || $per == "Admin" || $per == "Write") {
            $value = $_POST['value'];
            if (isset($_POST['bit'])) {
                $str = $_POST['bit'];
                $value = $_POST['value'];
                if ($str == "MB") {
                    $value = $value * 1024 * 1024;
                } else {
                    $value = $value * 1024 * 1024 * 1024;
                }
            }
            $table = $_POST['table'];
            if ($table == "radusergroup") {
                $che = "SELECT * FROM radusergroup  WHERE username='$name'";
                $gr = mysqli_query($con, $che);
                $g = mysqli_num_rows($gr);
                if ($g > 0) {
                    $sq = "UPDATE radusergroup SET groupname='$value' WHERE username='$name'";
                    mysqli_query($con, $sq);
                    $API->log($wh, $name . " Package " . $value . " Update");
                    $msg = "Attribute has been updated...";
                } else {
                    $sq = "INSERT INTO radusergroup (username,groupname) VALUES('$name','$value')";
                    $msg = "Attribute has been set...";
                    mysqli_query($con, $sq);
                    $API->log($wh, $name . " Package " . $value . " Set");
                }
            }
            $attribute = $_POST['attribute'];
            if ($attribute == 'Cleartext-Password') {
                $hash = md5($value);
                $sq = "UPDATE clients SET password='$hash' WHERE username='$name'";
                mysqli_query($con, $sq);
                $check = "UPDATE radcheck SET value='$value' WHERE attribute='$attribute' && username='$name'";
                $r = mysqli_query($con, $check);
                $API->log($wh, $name . " Password Changed");
            }
            $op = $_POST['op'];
            if ($table == "check") {
                $check = "SELECT * FROM radcheck WHERE username='$name' && attribute='$attribute'";
                $r = mysqli_query($con, $check);
                $rs = mysqli_num_rows($r);
                if ($rs > 0) {
                    echo "This Attribute already been set...";
                } else {
                    $sql = "INSERT INTO radcheck (username,attribute,op,value) VALUES('$name','$attribute','$op','$value')";
                    $r = mysqli_query($con, $sql);
                    $msg = "Attribute has been set...";
                    $API->log($wh, "User " . $name . " Attribute " . $attribute . " set");
                }

            } else if ($table == "reply") {
                $check = "SELECT * FROM radreply WHERE username='$name' && attribute='$attribute'";
                $r = mysqli_query($con, $check);
                $rs = mysqli_num_rows($r);
                if ($rs > 0) {
                    echo "This Attribute already been set...";
                } else {

                    $sql = "INSERT INTO radreply (username,attribute,op,value) VALUES('$name','$attribute','$op','$value')";
                    $r = mysqli_query($con, $sql);
                    $msg = "Attribute has been set...";
                    $API->log($wh, "User " . $name . " Attribute " . $attribute . " set");
                }
            } else {
                $msg = "Please select a table";
            }
        } else {
            echo "<h4 style='color: coral ;'>You have no permission...</h4>";
        }
    }


    ?>
</div>

<?php include 'includes/footer.php'; ?>

