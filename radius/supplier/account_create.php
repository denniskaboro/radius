<?php
include 'reseller.php';
$per = $_SESSION['per'];
if (isset($_POST['submit'])) {
    if ($per == "Admin") {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $password = md5($password);
        $fullname = $_POST['fullname'];
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];
        $permission = $_POST['permission'];
        $check = "SELECT * FROM supplier_login WHERE username='$username'";
        $r = mysqli_query($con, $check);
        $rs = mysqli_num_rows($r);
        if ($rs > 0) {
            echo "Username Already Exist..";
        } else {
            $sql = "INSERT INTO supplier_login (supplier_id,username,full_name, email,mobile, password,permission) 
VALUES('$sup_id','$username','$fullname','$email','$mobile','$password','$permission')";
            $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
            echo "New Account Created...";
            $API->log($wh, "New Reseller Account {$username} Created");
        }

    } else {
        $msg = "You have no permission...";
    }
}
if (isset($msg)) {
    echo $msg;
}
if ($per == 'Admin') {
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
                <label for="inputEmail" class="control-label">Mobile</label>
                <input type="text" class="form-control" id="inputEmail" name="mobile" placeholder="01911111111"
                       required>
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
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

    <script type="text/javascript" src="../component/validator.js"></script>
    <script type="text/javascript" src="../component/validator.min.js"></script>

<?php } else {
    echo "<h4 style='color: coral ;'>You have no permission...</h4>";
}
include '../includes/footer.php'; ?>
