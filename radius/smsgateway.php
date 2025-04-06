<?php
include 'includes/header.php';
$per = $_SESSION['per'];
if (isset($_POST['submit'])) {
    if ($per == "Full" || $per == "Admin") {
        $senderId = $_POST['senderId'];
        $clientId = $_POST['clientId'];
        $ApiKey = $_POST['ApiKey'];
        $AccessKey = $_POST['AccessKey'];
        $url = $_POST['url'];
        $check = "SELECT * FROM `smsgateway` WHERE `SenderId`='$senderId'";
        $r = mysqli_query($conn, $check);
        $rs = mysqli_num_rows($r);
        if ($rs > 0) {
            $msg = "<h4 style='color:#cb154f'>This gateway is already added</h4>";
        } else {
            $sql = "INSERT INTO `smsgateway` (`supplier_id`,`url`,`SenderId`,`ClientId`,`ApiKey`,`AccessKey`) VALUES('Admin','$url','$senderId','$clientId','$ApiKey','$AccessKey')";
            $r = mysqli_query($conn, $sql) or $msg = mysql_error();
            echo "<script>alert('New SMS Gateway Added.')</script>";
            $API->log("Admin", $wh, "New SMS Gateway Added");
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
                } ?>
                <div class="card-header py-3">
                    <h4 class="m-2 font-weight-bold text-secondary">Add New Gateway:</h4>
                </div>
                <div class="card-body">
                    <form data-toggle="validator" role="form" method="POST">
                        <div class="form-group">
                            <label for="inputFullname" class="control-label">API URL</label>
                            <input type="text" class="form-control" id="inputFullname" name="url"
                                   placeholder="Enter the API url"
                                   required>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="control-label">SenderId</label>
                            <input type="text" class="form-control" id="inputName" name="senderId"
                                   placeholder="Sender ID" required>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="control-label">CLIENT ID</label>
                            <input type="text" class="form-control" id="inputName" name="clientId"
                                   placeholder="Client ID" required>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="control-label">API KEY</label>
                            <input type="text" class="form-control" id="inputName" name="ApiKey"
                                   placeholder="API key" required>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="control-label">ACCESS KEY</label>
                            <input type="text" class="form-control" id="inputName" name="AccessKey"
                                   placeholder="Access Key" required>
                        </div>

                        <div class="form-group">
                            <button type="submit" style="padding: 7px !important;" name="submit" class="btn btn-danger">
                                Create
                            </button>
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
