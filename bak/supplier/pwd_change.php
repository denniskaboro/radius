<?php
include('reseller.php');
if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $username = $_SESSION['username'];
}
if (isset($_POST['request'])) {
    $name = $_POST['name'];
    $old = $_POST['old'];
    $old_encry = md5($old);
    $password = $_POST['password'];
    $encry = md5($password);
    $client = "UPDATE supplier_login SET password='$encry' WHERE username='$name' && password='$old_encry' && supplier_id='$id'";
    $r = mysqli_query($con,$client);
    if ($r) {
        $msg = "Password Change Successfully... ";
	 $API->log($wh,"Supervidor ".$id." ".$msg);
    } else {
        $msg = "Old Password Wrong...";
    }
}

if (isset($msg)) {
    echo $msg;
}
?>
<div class="col-sm-5">
    <h3>Payment:</h3>
    <form data-toggle="validator" role="form" method="POST">

        <div class="form-group">
            <label for="inputSet">Your Name:</label>
            <input type="text" class="form-control" id="inputSet" name="name"
                   value="<?php echo $_SESSION['username']; ?>" readonly>
        </div>
        <div class="form-group">
            <label>OLD Password:</label>
            <input type="text" class="form-control" name="old" required>
        </div>
        <div class="form-group">
            <label for="inputPassword" class="control-label">New Password</label>
            <div class="row">
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
