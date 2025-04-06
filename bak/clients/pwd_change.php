<?php
include('clients.php');
if (isset($_POST['request'])) {
    $name = $_POST['name'];
    $old = $_POST['old'];
    $old_encry = md5($old);
    $password = $_POST['password'];
    $encry = md5($password);

    $client = "UPDATE clients SET password='$encry' WHERE username='$name' && password='$old_encry'";
    $r = mysqli_query($con,$client);
    if ($r) {
        $update = "UPDATE radcheck SET value='$password' WHERE username='$name' && attribute='Cleartext-Password'";
        mysqli_query($con,$update);
        $msg = "Password Change Successfully... ";
    } else {
        $msg = "Old Password Wrong...";
    }
}

if (isset($msg)) {
    echo $msg;
}
?>
<div class="col-sm-5">
    <h3 style="color:#d63124;">Be Carefully.</h3>
    <h4 style="color:#d63124;">After changing your password you will lose your internet. Will need reconfiguration your
        router with New password.</h4>
    <h3 style="color:White;">Are you want to do this ???.</h3>
    <form data-toggle="validator" role="form" method="POST">
        <?php
        $username = $_SESSION['username'];
        $sql = "SELECT * FROM radcheck WHERE attribute='Cleartext-Password' && username='$username'";
        $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
        $f = mysqli_fetch_array($r);
        ?>

        <div class="form-group">
            <label for="inputSet">Your Name:</label>
            <input type="text" class="form-control" id="inputSet" name="name"
                   value="<?php echo $_SESSION['username']; ?>" readonly>
        </div>
        <div class="form-group">
            <label>OLD Password:</label>
            <input type="password" class="form-control" name="old" required>
        </div>
        <div class="form-group">
            <label for="inputPassword" class="control-label">New Password</label>
            <div class=" row">
                <div class="form-group col-sm-6">
                    <input type="password" data-minlength="6" class="form-control" id="inputPassword" name="password"
                           placeholder="Password" required>
                    <div class="help-block">Minimum of 6 characters</div>
                </div>
                <div class="form-group col-sm-6">
                    <input type="password" class="form-control" id="inputPasswordConfirm" data-match="#inputPassword"
                           data-match-error="Whoops, these don't match" placeholder="Confirm" required>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <button type="submit" name="request" class="btn btn-primary">Change</button>
        </div>
    </form>
</div>
<script type="text/javascript" src="../component/validator.js"></script>
<script type="text/javascript" src="../component/validator.min.js"></script>

<?php include '../includes/footer.php'; ?>
