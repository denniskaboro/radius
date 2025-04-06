<?php
include 'includes/header.php';
$per = $_SESSION['per'];
if (isset($_POST['submit'])) {
    if ($per == "Admin") {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $password = md5($password);
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $permission = $_POST['permission'];
        $dept = $_POST['dept'];
        $check = "SELECT * FROM admin WHERE username='$username'";
        $r = mysqli_query($con,$check);
        $rs = mysqli_num_rows($r);
        if ($rs > 0) {
            echo "Username Already Exist..";
        } else {
            $sql = "INSERT INTO admin (username, email, password, full_name, permission,Dept) VALUES('$username','$email','$password','$fullname','$permission','$dept')";
            $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
            echo "New Account Created...";
		$API->log($wh,"New User Account {$username} Created");
        }

    } else {
        $msg = "You have no permission...";
    }
}
if (isset($msg)) {
    echo $msg;
}
if ($per == 'Full' || $per == 'Admin') {
    ?>
    <div class="col-sm-5">
        <h3>Create An Account:</h3>
        <form data-toggle="validator" role="form" method="POST">
            <div class="form-group">
                <label for="inputName" class="control-label">Username</label>
                <input type="text" class="form-control" id="inputName" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <label for="inputFullname" class="control-label">Full Name</label>
                <input type="text" class="form-control" id="inputFullname" name="fullname" placeholder="Full Name"
                       required>
            </div>
            <div class="form-group">
                <label for="inputEmail" class="control-label">Email</label>
                <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Email"
                       data-error="Bruh, that email address is invalid" required>
                <div class="help-block with-errors"></div>
            </div>
            <div class="form-group">
                <label for="inputPassword" class="control-label">Password</label>
                <div class="form-inline row">
                    <div class="form-group col-sm-6">
                        <input type="password" data-minlength="6" class="form-control" id="inputPassword"
                               name="password" placeholder="Password" required>
                        <div class="help-block">Minimum of 6 characters</div>
                    </div>
                    <div class="form-group col-sm-6">
                        <input type="password" class="form-control" id="inputPasswordConfirm"
                               data-match="#inputPassword" data-match-error="Whoops, these don't match"
                               placeholder="Confirm" required>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPermission" class="control-label">Permission:</label>
                <select required class="form-control" id="inputPermission" name="permission">
                    <option></option>
                    <option>Read</option>
                    <option>Write</option>
                    <option>Full</option>
                    <?php if ($per == 'Admin') {
                        echo "<option>Admin</option>";
                    } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="inputDept" class="control-label">Department:</label>
                <select required class="form-control" id="inputDept" name="dept">
                    <option></option>
                    <option>Accounts</option>
                    <option value="Marketing">Sales & Marketing</option>
                    <option>Technical</option>
                    <option>Power</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

    <script type="text/javascript" src="component/validator.js"></script>
    <script type="text/javascript" src="component/validator.min.js"></script>

<?php } else {
    echo "<h4 style='color: coral ;'>You have no permission...</h4>";
}
include 'includes/footer.php'; ?>
