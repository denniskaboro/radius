<?php
include 'includes/header.php';
$per = $_SESSION['per'];
if (isset($_POST['create'])) {
    if ($per == "Full" || $per == "Admin") {
        $id = $_POST['id'];
        $username = $_POST['username'];
        $fullname = $_POST['fullname'];
        $mobile = $_POST['mobile'];
        $address = $_POST['address'];
        $pop = $_POST['pop'];
        $password = $_POST['password'];
        $password = md5($password);
        $email = $_POST['email'];

        $sql = "INSERT INTO `supplier` (`supplier_id`,`username`,`full_name`, `email`,`mobile`,`address`,`pop`, `password`) 
VALUES('$id','$username','$fullname','$email','$mobile','$address','$pop','$password')";
        $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
        if ($r) {
            $msg = "New User Added... ";
	$API->log($wh,"New Supervisor ".$fullname." Account created" );
        } else {
            $msg = "Unsuccessfully...";
        }

    } else {
        $msg = "You have no permission...";
    }
}

if (isset($msg)) {
    echo $msg;
}
?>
<div class="col-sm-5">
    <h3>Create An Account:</h3>
    <form data-toggle="validator" role="form" method="POST">
        <div class="form-group">
            <label for="inputName" class="control-label">Supplier ID</label>
            <input type="text" class="form-control" id="inputName" name="id" placeholder="S000101" required>
        </div>
        <div class="form-group">
            <label for="inputName" class="control-label">Username</label>
            <input type="text" class="form-control" id="inputName" name="username" placeholder="shortname" required>
        </div>
        <div class="form-group">
            <label for="inputFullname" class="control-label">Full Name</label>
            <input type="text" class="form-control" id="inputFullname" name="fullname" placeholder="Full Name" required>
        </div>
        <div class="form-group">
            <label for="inputMobile" class="control-label">Mobile</label>
            <input type="text" class="form-control" id="inputMobile" name="mobile" placeholder="Mobile">
        </div>
        <div class="form-group">
            <label for="inputArea" class="control-label">Address *</label>
            <input type="text" class="form-control" id="inputArea" name="address" placeholder="Coustomer Area" required>
        </div>
        <div class="form-group">
            <label for="inputAddress" class="control-label">Distribution Location Point</label>
            <input type="text" class="form-control" id="inputAddress" name="pop" placeholder="Distribution POP">
        </div>
        <div class="form-group">
            <label for="inputEmail" class="control-label">Email</label>
            <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Email"
                   data-error="This email address is invalid">
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
            <label for="inputPassword" class="control-label">Password</label>
            <div class="form-inline row">
                <div class="form-group col-sm-6">
                    <input type="password" data-minlength="6" class="form-control" id="inputPassword" name="password"
                           placeholder="Password" required>
                    <div class="help-block">Minimum of 6 characters</div>
                </div>
                <div class="form-group col-sm-6">
                    <input type="password" class="form-control" id="inputPasswordConfirm" data-match="#inputPassword"
                           data-match-error="Opps! Password doesn't match" placeholder="Confirm" required>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" name="" onClick='pay(this.id)' id="create" class="btn btn-primary">Create</button>
        </div>
    </form>
</div>
<script type="text/javascript">
    function pay(str) {
        var r = confirm("Confirm to Create ?");
        if (r == true) {
            document.getElementById(str).name = str;
        } else {
            document.getElementById(str).name = "";
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
