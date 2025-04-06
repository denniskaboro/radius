<?php include 'header.php';
$per = $_SESSION['per'];

if (isset($_POST['change'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password = md5($password);
    $oldpass = $_POST['oldpass'];
    $oldpass = md5($oldpass);
    $sql = "UPDATE admin set password='$password' WHERE username='$username'";
    $change = mysqli_query($con,$sql);
    if ($change) {
        echo "Password Change Successfully..";
	$API->log($wh,$username." Password Change Successfully.");
    } else {
        echo "Old Password Wrong..";
	 $API->log($wh,$username." Password was wrong.");
    }

}

?>
<div class="col-sm-5">
    <h3>Password Change</h3>
    <form method="POST" data-toggle="validator" role="form">
        <div class="form-group col-sm-8 ">
            <div class="form-group">
                <label for="inputName" class="control-label">Username:</label>
                <input type="text" class="form-control" id="inputName" value="<?php echo $_SESSION['username']; ?>"
                       name="username" placeholder="Username" readonly>
            </div>
            <div class="form-group">
                <label for="inputPass" class="control-label">Old Password:</label>
                <input type="password" class="form-control" id="inputPass" name="oldpass" placeholder="Old Password"
                       required>
            </div>
            <div class="form-group">
                <label for="inputPassword" class="control-label">New Password:</label>
                <div class="form-inline row">
                    <div class="form-group col-sm-6">
                        <input type="password" data-minlength="6" class="form-control" id="inputPassword"
                               name="password" placeholder="New Password" required>
                        <div class="help-block">Minimum of 6 characters</div>
                    </div>
                    <div class="form-group col-sm-6">
                        <input type="password" class="form-control" id="inputPasswordConfirm"
                               data-match="#inputPassword" data-match-error="Whoops, these don't match"
                               placeholder="Confirm New Password" required>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
            </div>

            <button type="submit" name="change" class="btn btn-default">Change</button>
        </div>
    </form>
</div>
<script type="text/javascript" src="../component/validator.js"></script>
<script type="text/javascript" src="../component/validator.min.js"></script>

<?php include '../includes/footer.php'; ?>

