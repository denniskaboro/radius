<?php
include 'includes/header.php';
$per = $_SESSION['per'];
if (isset($_GET['delete_tab'])) {
    if ($per == "Full" || $per == "Admin") {
        $del = $_GET['delete_tab'];
        $match = $_GET['id'];
        $del = "DELETE FROM $del WHERE id='$match'";
        mysqli_query($con, $del);
        $API->log($wh, "Attribute  Deleted");
    } else {
        echo "<h4 style='color: coral ;'>You have no permission...</h4>";
    }
}
if (isset($_GET['val'])) {
    $name = $_GET['val'];
    $table = ["radgroupcheck", "radgroupreply"];
    foreach ($table as $tab) {
        $sq = "SELECT * FROM $tab WHERE groupname='$name'  ";

        $res = mysqli_query($con, $sq);
        echo "<div class=\"col-lg-6 \">
    <table class=\"table\" style=\"color:black;\" >
        <thead>
        <tr>
            <th>Package Name</th>
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
                <td><a class=\"btn btn-danger\" href=\"add_attribute.php?val=$name&delete_tab=$tab&id=$sr\">
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
        <div class="form-group col-sm-6 ">
            <label for="ip">Attribute Name:</label>
            <select class="form-control" onchange="showAttr(this.value)">
                <option>Select</option>
                <?php
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
    <div id='attri' class="container col-sm-6" style="background-color: rgba(0,0,0,0.2) !important;">

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
            } else {
                xmlhttp.open("GET", "list_query.php?j=" + str, true);
                xmlhttp.send();
            }

        }
    </script>

    <script>
        function showTest(str) {
            if (str == "") {
                document.getElementById("tst").innerHTML = "";
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
                    document.getElementById("tst").innerHTML = this.responseText;
                }
            }
            xmlhttp.open("GET", "add_query.php?nas=" + str, true);
            xmlhttp.send();
        }
    </script>
    <?php
    include('config/data.php');
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
            $attribute = $_POST['attribute'];
            $op = $_POST['op'];

            if ($table == "check") {
                $check = "SELECT * FROM radgroupcheck WHERE groupname='$name' && attribute='$attribute'";
                $r = mysqli_query($con, $check);
                $rs = mysqli_num_rows($r);
                if ($rs > 0) {
                    echo "<h4>This Attribute already been set...</h4>";
                } else {
                    $sql = "INSERT INTO radgroupcheck (groupname,attribute,op,value) VALUES('$name','$attribute','$op','$value')";
                    $r = mysqli_query($con, $sql);
                    $msg = " Attribute has been set...";
                    $API->log($wh, "Group " . $name . $msg);
                }
            } else if ($table == "reply") {
                $check = "SELECT * FROM radgroupreply WHERE groupname='$name' && attribute='$attribute'";
                $r = mysqli_query($con, $check);
                $rs = mysqli_num_rows($r);
                if ($rs > 0) {
                    echo "<h4>This Attribute already been set...</h4>";
                } else {
                    $sql = "INSERT INTO radgroupreply (groupname,attribute,op,value) VALUES('$name','$attribute','$op','$value')";
                    $r = mysqli_query($con, $sql);
                    $msg = " Attribute has been set...";
                    $API->log($wh, "Group " . $name . $msg);
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

