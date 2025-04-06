<?php
include 'includes/header.php';
include 'config/data.php';

if (isset($_POST['create'])) {
    $id = $_GET['id'];
    $pass = $_POST['pass'];
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $pop = $_POST['pop'];
    $division = $_POST['division'];
    if ($division != null) {
        $district = $_POST['district'];
        $area = $_POST['area'];
        $upazila = $_POST['upazila'];
        $address = $area . ', ' . $upazila . ', ' . $district . ', ' . $division;
    }
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $up = "UPDATE supplier SET username='$username',password='$pass', full_name='$fullname', email='$email', mobile='$mobile', 
address='$address',pop='$pop' WHERE supplier_id='$id'";

    $update = mysqli_query($con, $up);
    if ($update) {
        $msg = "Profile has been Updated.";
        $API->log($wh, "Supplier " . $msg);
    } else {
        $msg = "Username already used";
    }

}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM supplier WHERE supplier_id='$id'";
    $res = mysqli_query($con, $sql);
    $g = mysqli_fetch_array($res);

    ?>

    <div class="col-sm-6">
        <?php if (isset($msg)) {
            echo "<h4 style='color: #de4e97'>" . $msg . "</h4>";
        } ?>
        <h4>Update Supplier Information:</h4>
        <form data-toggle="validator" role="form" method="POST">
            <div class="form-group">
                <label for="inputName" class="control-label">Username </label>
                <input type="text" class="form-control" name="username" value="<?php echo $g['username']; ?>">
            </div>
            <div class="form-group">
                <label for="inputName" class="control-label">Password </label>
                <input type="text" class="form-control" name="pass" value="<?php echo $g['password']; ?>">
            </div>
            <div class="form-group">
                <label for="inputFullname" class="control-label">Full Name </label>
                <input type="text" class="form-control" id="inputFullname" name="fullname"
                       value="<?php echo $g['full_name']; ?>">
            </div>
            <div class="form-group">
                <label for="inputAdd" class="control-label">Address </label>
                <input type="text" class="form-control" id="inputAdd" name="address"
                       value="<?php echo $g['address']; ?>">
            </div>
            <br>
            <h4>Update Address (if require)</h4>
            <div class="form-group">

                <label for="inputDivision" class="control-label">Division </label>
                <select class="form-control" onchange="Division(this.value)" name="division">
                    <option></option>
                    <?php $sql = "SELECT * FROM divisions ";
                    $rs = mysqli_query($con, $sql);
                    while ($f = mysqli_fetch_array($rs)) {
                        $id = $f['id'];
                        echo "<option>" . $f['name'] . "</option>";
                    }

                    ?>
                </select>
            </div>
            <div class="form-group" id="inputDistrict">
            </div>
            <div class="form-group" id="inputThana">

            </div>
            <div class="form-group">
                <label for="inputArea" class="control-label">Area </label>
                <input type="text" class="form-control" id="inputArea" name="area"
                       value="<?php if (isset($g['address'])) {
                           $add = $g['address'];
                           $cx = strpos($add, ',');
                           if ($cx) {
                               //        $subnet = (int)(substr($address, $cx+1));
                               echo substr($add, 0, $cx);
                           }
                       } ?>">
            </div>
            <div class="form-group">
                <label for="inputMobile" class="control-label">Mobile </label>
                <input type="text" class="form-control" id="inputMobile" name="mobile"
                       value="<?php echo $g['mobile']; ?>">
            </div>
            <div class="form-group">
                <label for="inputPOP" class="control-label">POP Location </label>
                <input type="text" class="form-control" id="inputPOP" name="pop" value="<?php echo $g['pop']; ?>">
            </div>
            <div class="form-group">
                <label for="inputEmail" class="control-label">Email</label>
                <input type="email" class="form-control" id="inputEmail" name="email"
                       data-error="This Email address is invalid" value="<?php echo $g['email']; ?>">
                <div class="help-block with-errors"></div>
            </div>
            <div class="form-group">
                <button type="submit" name="" onClick='pay(this.id)' id="create" class="btn btn-primary">UPDATE</button>
            </div>
        </form>
    </div>
<?php } ?>
<script type="text/javascript">
    function pay(str) {
        var r = confirm("Are you Sure ?");
        if (r == true) {
            document.getElementById("create").name = str;
        } else {
            document.getElementById("create").name = "";
        }
    }

</script>
<script>
    function Division(str) {
        if (str == "") {
            document.getElementById("inputDistrict").innerHTML = "";
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
                document.getElementById("inputDistrict").innerHTML = this.responseText;
            }
        }
        xmlhttp.open("GET", "address.php?divi=" + str, true);
        xmlhttp.send();
    }
</script>
<script>
    function showDis(str) {
        if (str == "") {
            document.getElementById("inputThana").innerHTML = "";
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
                document.getElementById("inputThana").innerHTML = this.responseText;
            }
        }
        xmlhttp.open("GET", "address.php?dis=" + str, true);
        xmlhttp.send();
    }
</script>

<script type="text/javascript" src="component/validator.js"></script>
<script type="text/javascript" src="component/validator.min.js"></script>

<?php include 'includes/footer.php'; ?>

