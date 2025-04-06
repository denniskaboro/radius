<?php
include 'reseller.php';
include '../config/data.php';
if (isset($_POST['create'])) {
    $id = $_POST['user_id'];
    $username = $_POST['username'];
    $sup_id = $_POST['sup_id'];
    $pass = $_POST['password'];
    $encr = md5($pass);
    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    if ($username == $id) {
        $pa = "UPDATE radcheck SET `value`='$pass' WHERE `username`='$username' && `attribute`='Cleartext-Password'";
        mysqli_query($con,$pa);
        $up = "UPDATE clients SET full_name='$fullname',password='$encr', email='$email', 
address='$address',mobile='$mobile' WHERE username='$id'";
        $update = mysqli_query($con,$up);
        $msg = "Address has been Updated.";
        $API->log($wh, $username . " " . $msg);
    } else {
        $usr = "SELECT * FROM radcheck WHERE username='$username'";
        $chk = mysqli_query($con,$usr);
        $row = mysqli_num_rows($chk);
        if ($row > 0) {
            echo "<h3>Username already taken..</h3>";
            $API->log($wh, $username . " username can not changed cause duplicate username found");
        } else {
            $tab = ['radcheck', 'radreply', 'clients', 'radusergroup', 'radpostauth', 'radacct'];
            foreach ($tab as $table) {
                $sql = "UPDATE $table SET username='$username' WHERE username='$id'";
                $usr = mysqli_query($con,$sql);
            }
            $pa = "UPDATE radcheck SET `value`='$pass' WHERE `username`='$username' && `attribute`='Cleartext-Password'";
            mysqli_query($con,$pa);
            $up = "UPDATE clients SET full_name='$fullname',password='$encr', email='$email', 
address='$address',mobile='$mobile' WHERE username='$id'";
            if ($up) {
                $API->log($wh, $id . " changed to " . $username);
            } else {
                $msg = "<h3>Information has no changed.</h3>";
            }
        }
    }
}
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $sql = "SELECT * FROM clients WHERE id='$user_id'";
}
$res = mysqli_query($con,$sql);
$g = mysqli_fetch_array($res);
$page = "Modify Information";
include "../includes/form_header.php";
?>


<form data-toggle="validator" role="form" method="POST">
    <div class="form-group">
        <label for="inputName" class="control-label">Username </label>
        <input type="hidden" class="form-control" name="user_id" value="<?php echo $g['username']; ?>">
        <input type="hidden" class="form-control" name="sup_id" value="<?php echo $g['supplier_id']; ?>">
        <input type="text" class="form-control" id="inputName" name="username"
               value="<?php echo $g['username']; ?>">
    </div>
    <div class="form-group">
        <label for="inputName" class="control-label">Password:</label>
        <input type="text" class="form-control" id="inputPass" name="password"
               value="<?php
               $name = $g['username'];
               $sql1 = "SELECT * FROM radcheck WHERE username='$name' && attribute='Cleartext-Password'";
               $qu = mysqli_query($con,$sql1);
               $h = mysqli_fetch_array($qu);
               echo $h['value']; ?>">
    </div>
    <input type="hidden" class="form-control" id="inputMobile" name="old_pass"
           value="<?php echo $h['value']; ?>">
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

    <div class="form-group">
        <label for="inputMobile" class="control-label">Mobile </label>
        <input type="text" class="form-control" id="inputMobile" name="mobile"
               value="<?php echo $g['mobile']; ?>">
    </div>
    <div class="form-group">
        <label for="inputEmail" class="control-label">Email</label>
        <input type="email" class="form-control" id="inputEmail" name="email"
               data-error="This Email address is invalid" value="<?php echo $g['email']; ?>">
        <div class="help-block with-errors"></div>
    </div>
    <div class="form-group">
        <button type="submit" name="" onClick='pay(this.id)' id="create" class="btn btn-sm btn-primary">UPDATE</button>
    </div>
</form>
<?php include "../includes/form_footer.php"; ?>
<script type="text/javascript">
    function pay(str) {
        var r = confirm("User Will be disconnect. Are you sure ?");
        if (r == true) {
            document.getElementById("create").name = str;
        } else {
            document.getElementById("create").name = "";
        }
    }

</script>


<?php include 'footer.php'; ?>

