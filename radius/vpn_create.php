<?php
include 'includes/header.php';
$per = $_SESSION['per'];
$usr = $_SESSION['username'];
if (isset($_POST['add'])) {
    if ($per == "Admin" || $per == "Full" || $per == "Write") {
        $username = $_POST['username'];
        $attribute = "Cleartext-Password";
        $password = $_POST['password'];
        $description = $_POST['description'];
        $duration = $_POST['duration'];

        $check = "SELECT * FROM `radcheck` WHERE username='$username'";
        $res = mysqli_query($con, $check);
        $found = mysqli_num_rows($res);
        if ($found > 0) {
            $msg = "<h4 style='color: #c41914 ;'>Username has been already used please use another name...</h4>";
        } else {
            $cli = "INSERT INTO `vpn_user` (`username`,`password`,`description`,`duration`)
VALUES('$username','$password','$description','$duration')";
            mysqli_query($con, $cli);

            $newDate = date("d M Y H:i:s", strtotime($duration));
            $day = "INSERT INTO `radcheck` (`username`, `attribute`, `op`, `value`) VALUES('$username','Expiration',':=','$newDate')";
            mysqli_query($con, $day) or $msg = mysqli_error($con);

            $radcheck = "INSERT INTO `radcheck` (`username`, `attribute`, `op`, `value`) VALUES('$username','$attribute',':=','$password'),('$username','Auth-Type',':=','PAP')";
            mysqli_query($con, $radcheck) or $msg = mysqli_error($con);

            $radcheck = "INSERT INTO `radcheck` (`username`, `attribute`, `op`, `value`) VALUES('$username','Service-Type','==','Outbound-User'),('$username','NAS-Port-Type','==','Virtual')";
            mysqli_query($con, $radcheck) or $msg = mysqli_error($con);
            $msg = "User has beed craeted successfully";
        }
    } else {
        $msg = "<h4 style='color: coral ;'>You have no permission...</h4>";
    }
}
?>
<div class="row">
    <!-- Pie Chart -->

    <div class="col-xl-8 col-lg-8" id="design" data-aos="fade-down">
        <!-- Basic Card Example -->
        <div class="card shadow mb-4 text-gray-900" style="margin-left: 2%; padding: 30px;">
            <?php if (isset($msg)) {
                echo "<h4 style='color: #2cde43'>" . $msg . "</h4>";
            } ?>
            <div class="card-header py-3">
                <h4 class="m-0 font-weight-bold text-secondary">VPN Information:</h4>
            </div>
            <form data-toggle="validator" role="form" method="POST">
                <div class="form-group">
                    <label for="inputName" class="control-label">Username *</label>
                    <input type="text" class="form-control" id="inputName" name="username" placeholder="Username"
                           required>
                </div>
                <div class="form-group">
                    <label for="inputPassword" class="control-label">Password *</label>
                    <input type="password" data-minlength="4" class="form-control"
                           name="password"
                           placeholder="Password" required>
                    <div class="help-block">Minimum of 4 characters</div>
                </div>
                <div class="form-group">
                    <label for="inputName" class="control-label">Expiration *</label>
                    <input type="datetime-local" class="form-control" id="inputName" name="duration"
                           required>
                </div>
                <div class="form-group">
                    <label for="inputName" class="control-label">Description *</label>
                    <input type="text" class="form-control" name="description" placeholder="Comments"
                           required>
                </div>
                <div class="form-group">
                    <button type="submit" name="add" class="btn btn-primary">Create
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>
<script type="text/javascript" src="component/validator.js"></script>
<script type="text/javascript" src="component/validator.min.js"></script>

<?php include 'includes/footer.php'; ?>

