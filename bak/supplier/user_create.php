<?php
include 'reseller.php';
$per = $_SESSION['per'];
$id = $_SESSION['id'];
$login_user = $_SESSION['username'];
if (isset($_POST['create'])) {
        $usrn = $_SESSION['username'];
        $username = $_POST['username'];
        $attribute = "Cleartext-Password";
        $fullname = $_POST['fullname'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
        $password = $_POST['password'];
        $encr = md5($password);
        $pack_id = $_POST['package'];
        $reseller = $_POST['sup_id'];
        $check = "SELECT * FROM clients WHERE username='$username'";
        $res = mysqli_query($con,$check);
        $found = mysqli_num_rows($res);
        if($found>0){
            $msg="<h4 style='color: #dea828'>Duplicate username found. Please use another name.</h4>";
        }else{
            if ($reseller != null && $pack_id != null) {
                $pack = "SELECT * FROM `groups` WHERE `id`='$pack_id'";
                $pack_query = mysqli_query($con,$pack);
                $pack_res = mysqli_fetch_array($pack_query);
                $profile = $pack_res['groupname'];
                $duration = $pack_res['duration'];
                $newDate = date("d M Y 23:59:00", strtotime('+' . $duration));
                $price = $pack_res['price'];
                $user = "SELECT SUM(`debit`) as de, SUM(`credit`) as cr,`supplier_name` FROM `transaction` WHERE `supplier_id`='$reseller'";
                $usr = mysqli_query($con,$user);
                $u = mysqli_fetch_array($usr);
                $full_name = $u['supplier_name'];
                $de = $u['de'];
                $cr = $u['cr'];
                $bal = $de - $cr;
                $balance = $bal - $price;

                if ($bal >= $price) {
                    echo $fullname;
                    $client = "INSERT INTO `clients` (`username`, `full_name`, `email`, `mobile`, `address`,`password`,`supplier_id`,`reference`)
VALUES('$username','$fullname','$email','$mobile','$address','$encr','$reseller','Create New User by Supervisor $login_user')";
                    $radcheck = "INSERT INTO `radcheck` (`username`, `attribute`, `op`, `value`) VALUES('$username','$attribute',':=','$password')";
                    $rj = "INSERT INTO `radcheck` (`username`, `attribute`, `op`, `value`) VALUES('$username','Auth-Type',':=','PAP')";
                    $day = "INSERT INTO `radcheck` (`username`, `attribute`, `op`, `value`) VALUES('$username','Expiration',':=','$newDate')";
                    mysqli_query($con,$day) or $msg = mysqli_error($con);
                    mysqli_query($con,$rj) or $msg = mysqli_error($con);
                    mysqli_query($con,$radcheck) or $msg = mysqli_error($con);
                    mysqli_query($con,$client) or $msg = mysqli_error($con);
                    $grp = "INSERT INTO `radusergroup` (`username`, `groupname`) VALUES('$username','$pack_id')";
                    mysqli_query($con,$grp) or $msg = mysqli_error($con);

                    $trans = "INSERT INTO `transaction` (`supplier_id`,`supplier_name`,`transaction`,`credit`,`reference`)VALUES('$reseller','$full_name','$profile','$price','$username Created')";
                    $create = mysqli_query($con,$trans);
                    if ($create) {
                        $msg = "<h4 style='color: #7dde2b'>Username Created under " . $full_name."</h4>";
                        $API->log($wh, $username . " " . $msg);
                    } else {
                        $msg = "Unknown Error...";
                    }

                } else {
                    $msg = "Supervisor Balance Low...";
                    $API->log($wh, $username . " can not created cause " . $msg);
                }

            }
        }
        echo "<script>alert('$msg')</script>";
}

?>
<div class="col-lg-8 col-md-8 col-sm-8">
    <form data-toggle="validator" role="form" method="POST">
        <div class="form-group">
            <label for="inputName" class="control-label">Username</label>
            <input type="text" class="form-control" id="inputName" name="username" placeholder="Username" required>
        </div>
        <div class="form-group">
            <label for="inputFullname" class="control-label">Full Name</label>
            <input type="text" class="form-control" id="inputFullname" name="fullname" placeholder="Full Name" required>
        </div>
        <div class="form-group">
            <label for="inputArea" class="control-label">Address *</label>
            <input type="text" class="form-control" id="inputArea" name="address" placeholder="Coustomer Area" required>
        </div>
        <div class="form-group">
            <label for="inputMobile" class="control-label">Mobile *</label>
            <input type="number"
                   class="form-control" id="inputMobile" name="mobile" required>
        </div>
        <div class="form-group">
            <label for="inputEmail" class="control-label">Email</label>
            <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Email"
                   data-error="That email address is invalid">
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
            <label for="inputPassword" class="control-label">Password</label>
            <div class="form-inline row">
                <div class="form-group col-sm-6">
                    <input type="password" data-minlength="3" class="form-control" id="inputPassword" name="password"
                           placeholder="Password" required>
                    <div class="help-block">Minimum of 4 characters</div>
                </div>
                <div class="form-group col-sm-6">
                    <input type="password" class="form-control" id="inputPasswordConfirm" data-match="#inputPassword"
                           data-match-error="Oops!, these don't match" placeholder="Confirm" required>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="inputPack">Package:</label>
            <select class="form-control" id="inputPack" name="package" required>
                <option></option>
                <?php
                $sql = "SELECT * FROM `groups` WHERE `supplier_id`='$sup_id' or `supplier_id`='COMMON'";
                $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
                while ($f = mysqli_fetch_array($r)) {
                    ?>
                    <option value="<?php echo $f['id']; ?>"><?php echo $f['groupname']; ?></option>
                <?php } ?>
            </select>
        </div>
        <input type="hidden" name="sup_id" value="<?php echo $_SESSION['id']; ?>"/>
        <div class="form-group">
            <button type="submit" name="" onClick='pay("create")' id="create" class="btn btn-sm btn-primary">Create</button>
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

<script type="text/javascript" src="../component/validator.js"></script>
<script type="text/javascript" src="../component/validator.min.js"></script>

<?php include '../includes/footer.php'; ?>
