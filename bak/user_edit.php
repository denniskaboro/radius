<?php
include 'includes/header.php';
include 'config/data.php';

if (isset($_POST['create'])) {
    $id = $_POST['user_id'];
    $username = $_POST['username'];
    $sup_id = $_POST['sup_id'];
    $attribute = "Cleartext-Password";
    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $division = $_POST['division'];
    $area = $_POST['area'];
    $road = $_POST['road'];
    $house = $_POST['house'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $old_mobile = $_POST['old_mobile'];
    $nid = $_POST['nid'];
	if($old_mobile!=$mobile){
	$pen = "UPDATE clients SET mobile='$mobile',approval='Pending' WHERE username='$id'";
	mysqli_query($con,$pen);
	 $API->log($wh,$username." Mobile number has been changed.");
	}
	if($username == $id){
        	if ($division != null) {
        	$district = $_POST['district'];
        	$upazila = $_POST['upazila'];
        	$address = $area . ', ' . $upazila . ', ' . $district . ', ' . $division;
        	$up = "UPDATE clients SET full_name='$fullname', email='$email',
address='$address', house='$house', road='$road', area='$area', thana='$upazila',
district='$district', nid='$nid' WHERE username='$id'";
	$update = mysqli_query($con,$up);
	$msg = "Address has been updated without username.";
	 $API->log($wh,$username." ".$msg);
    }else {
        $up = "UPDATE clients SET full_name='$fullname', email='$email', house='$house', road='$road', area='$area', nid='$nid' WHERE username='$id'";
        $update = mysqli_query($con,$up);
        $msg = "Information has been updated without username.";
	$API->log($wh,$username." ".$msg);
        }
}else{
	$usr="SELECT * FROM radcheck WHERE username='$username'";
    	$chk=mysqli_query($con,$usr);
    	$row=mysqli_num_rows($chk);
    	if($row>0){
        echo "<h3 style='color:red;'>Username already taken..</h3>";
    	}else{
        $tab = ['radcheck', 'radreply', 'radacct', 'radusergroup', 'radpostauth', 'clients'];
        foreach ($tab as $table) {
            $sql = "UPDATE $table SET username='$username' WHERE username='$id'";
            $usr = mysqli_query($con,$sql);
        }
        $up = "UPDATE clients SET full_name='$fullname',username='$username', email='$email',  house='$house', road='$road', area='$area', nid='$nid' WHERE username='$id'";
        $update = mysqli_query($con,$up);
    	if ($update) {
        $msg = "<h3>Username has been Updated.</h3>";
	    $API->log($wh,$id." changed to ".$username);
    	}else {
        $msg = "<h3>Information has no changed.</h3>";
    	}
 }
}

}
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $sql = "SELECT * FROM clients WHERE id='$user_id'";
    $res = mysqli_query($con,$sql);
    $g = mysqli_fetch_array($res);

    ?>

    <div class="col-sm-6">
        <?php if (isset($msg)) {
            echo "<h4 style='color: #de4e97'>" . $msg . "</h4>";
        } ?>
        <h4>Client Details Information:</h4>
        <form data-toggle="validator" role="form" method="POST">
            <div class="form-group">
                <label for="inputName" class="control-label">Username </label>
                <input type="hidden" class="form-control" name="user_id" value="<?php echo $g['username']; ?>">
		<input type="hidden" class="form-control" name="sup_id" value="<?php echo $g['supplier_id']; ?>">
                <input type="text" class="form-control" id="inputName" name="username"
                       value="<?php echo $g['username']; ?>">
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
                    $rs = mysqli_query($con,$sql);
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
                <input type="text" class="form-control" id="inputArea" name="area" value="<?php if (isset($g['area'])) {
                    echo $g['area'];
                } ?>">
            </div>
            <div class="form-group">
                <label for="inputRoad" class="control-label">Road No.</label>
                <input type="text" class="form-control" id="inputRoad" name="road" value="<?php if (isset($g['road'])) {
                    echo $g['road'];
                } ?>">
            </div>
            <div class="form-group">
                <label for="inputHouse" class="control-label">House No.</label>
                <input type="text" class="form-control" id="inputHouse" name="house"
                       value="<?php if (isset($g['house'])) {
                           echo $g['house'];
                       } ?>">
            </div>
            <div class="form-group">
                <label for="inputMobile" class="control-label">Mobile </label>
                <input type="text" class="form-control" id="inputMobile" name="mobile"
                       value="<?php echo $g['mobile']; ?>">
		 <input type="hidden" class="form-control" id="inputMobile" name="old_mobile"
                       value="<?php echo $g['mobile']; ?>">
            </div>
            <div class="form-group">
                <label for="inputNid" class="control-label">NID</label>
                <input type="text" class="form-control" data-minlength="10" id="inputNid" name="nid"
                       value="<?php echo $g['nid']; ?>">
                <div class="help-block">Minimum of 13 characters</div>
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
        var r = confirm(" If you change username.User Will be disconnect. Are you sure ? ?");
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
        xmlhttp.open("GET", "sup_add.php?divi=" + str, true);
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
        xmlhttp.open("GET", "sup_add.php?dis=" + str, true);
        xmlhttp.send();
    }
</script>

<script type="text/javascript" src="component/validator.js"></script>
<script type="text/javascript" src="component/validator.min.js"></script>

<?php include 'includes/footer.php'; ?>

