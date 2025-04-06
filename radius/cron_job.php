<?php
include 'includes/header.php';
$per = $_SESSION['per'];
$supplier_id = "Admin";
if (isset($_POST['submit'])) {
    if ($per == "Full" || $per == "Admin") {
        $create = $_POST['create_message'];
        $exp = $_POST['exp_message'];
        $pay = $_POST['pay_message'];
        $days = $_POST['days'];
        $ckh = "SELECT * FROM `cron_job` WHERE supplier_id='$supplier_id'";
        $c = mysqli_query($con, $ckh);
        $row = mysqli_num_rows($c);
        if ($row > 0) {
            $up = "UPDATE `cron_job` SET `create_message`='$create',`expire_message`='$exp',`expire_time`='$days',`payment_message`='$pay' WHERE supplier_id='$supplier_id'";
            mysqli_query($con, $up);
            $msg = "<h4 style='color:#58cb4a'>Message Updated</h4>";
            $API->log($supplier_id, $wh, "Message settins updated");
        } else {
            $sql = "INSERT INTO `cron_job` (`supplier_id`,`create_message`,`payment_message`,`expire_message`,`expire_time`) VALUES('$supplier_id','$create','$pay','$exp','$days')";
            $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
            $msg = "<h4 style='color:#58cb4a'>Message Saved</h4>";
            $API->log($supplier_id, $wh, "New Message settins saved");
        }

    } else {
        $msg = "You have no permission...";
    }
}

if ($per == "Full" || $per == "Admin") {
    ?>
    <div class="row">
        <!-- Pie Chart -->

        <div class="col-xl-6 col-lg-6" id="design" data-aos="fade-down">
            <!-- Basic Card Example -->
            <div class="card shadow mb-4 text-gray-900" style="margin-left: 2%; padding: 30px;">
                <?php if (isset($msg)) {
                    echo $msg;
                }
                $sql = "SELECT * FROM cron_job WHERE supplier_id='$supplier_id'";
                $q = mysqli_query($con, $sql);
                $f = mysqli_fetch_array($q);
                ?>
                <div class="card-header py-3">
                    <h4 class="m-0 font-weight-bold text-secondary">Message Settings:</h4>
                    <p style="color: #ffe921; font-size: 1.3em">
                        You can use this variable for send message.
                    </p>
                    <p style="color: #ffac5e; font-size: 1.1em">
                        **username** = User Login Username,
                        **password** = User Login Password,
                        **full_name** = User Full Name,
                        **expiration** = User Expiration Date,
                        **date** = Current Date
                    </p>

                </div>
                <div class="card-body">
                    <form data-toggle="validator" role="form" method="POST">
                        <div class="form-group">
                            <label for="inputFullname" class="control-label">User Creation Message</label>
                            <textarea class="form-control" cols="4" rows="5" id="inputName" name="create_message"
                                      required><?php echo $f['create_message']; ?></textarea>

                        </div>
                        <div class="form-group">
                            <label for="inputName" class="control-label">User Expiration Message</label>
                            <textarea class="form-control" cols="4" rows="5" id="inputName" name="exp_message"
                                      required><?php echo $f['expire_message']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="control-label">User Payment Message</label>
                            <textarea class="form-control" cols="4" rows="5" id="inputName" name="pay_message"
                                      required><?php echo $f['payment_message']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword" class="control-label">Send Message before</label>
                            <input type="text" class="form-control" value="<?php echo $f['expire_time']; ?>"
                                   id="inputName" name="days"
                                   placeholder="3 days" required>

                        </div>
                        <div class="form-group">
                            <button type="submit" name="submit" class="btn btn-danger">SAVE</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="js/validator.js"></script>
    <script type="text/javascript" src="js/validator.min.js"></script>

<?php } else {
    echo "<h4 style='color: coral ;'>You have no permission...</h4>";
}
include 'includes/footer.php'; ?>

