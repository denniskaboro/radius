<?php
include 'includes/header.php';

if (isset($_POST['create'])) {
    if ($per == 'Full' || $per == 'Admin') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $shortcode = $_POST['shortcode'];
        $success = $_POST['success'];
        $refer = $_POST['refer'];
        $secret = $_POST['secret'];
        $payment_url = $_POST['payment_url'];
        $token_url = $_POST['token_url'];
        $queryURL = $_POST['queryURL'];
        $mode = $_POST['mode'];

        $in = "INSERT INTO `mpesa` (`customer_key`,`customer_secret`,`shortcode`,`success`,`refer`,`passKey`,`payment_url`,`token_url`,`queryURL`,`mode`) 
VALUES ('$username','$password','$shortcode','$success','$refer','$secret','$payment_url','$token_url','$queryURL','$mode')";
        $r = mysqli_query($con,$in);
        if ($r) {
            $API->log($wh, "New Payment gateway added");
            echo "<script>alert('New Payment gateway added')</script>";
        }

    }

}
?>
<div class="row">
    <!-- Pie Chart -->

    <div class="col-xl-8 col-lg-8" id="design" data-aos="fade-down">
        <!-- Basic Card Example -->
        <div class="card shadow mb-4 text-gray-900" style="margin-left: 2%; padding: 30px;">
            <?php if (isset($msg)) {
                echo $msg;
            } ?>
            <div class="card-header py-3">
                <h4 class="m-0 font-weight-bold text-secondary">Manage Your Payment Gateway</h4>
                <hr>
            </div>
            <div class="card-body">
                <form data-toggle="validator" role="form" method="POST" enctype="multipart/form-data">
                    <div class="form-group col-lg-8 col-md-8 col-sm-8">
                        <label class="control-label">Customer Key *</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <div class="form-group col-lg-8 col-md-8 col-sm-8">
                        <label class="control-label">Customer Secret *</label>
                        <input type="text" class="form-control" name="password" required>
                    </div>
                    <div class="form-group col-lg-8 col-md-8 col-sm-8">
                        <label class="control-label">Shortcode *</label>
                        <input type="number" class="form-control" name="shortcode" required>
                    </div>
                    <div class="form-group col-lg-8 col-md-8 col-sm-8">
                        <label class="control-label">Response URL</label>
                        <textarea rows="2" class="form-control" placeholder="http://180.210.181.218/api/success.php" name="success"></textarea>
                    </div>
                    <div class="form-group col-lg-8 col-md-8 col-sm-8">
                        <label class="control-label">Reference Code *</label>
                        <input type="text" class="form-control" name="refer" required>
                    </div>
                    <div class="form-group col-lg-8 col-md-8 col-sm-8">
                        <label class="control-label">Secret (PassKey) *</label>
                        <textarea rows="2" class="form-control" name="secret" required></textarea>
                    </div>
                    <div class="form-group col-lg-8 col-md-8 col-sm-8">
                        <label class="control-label">Payment URL *</label>
                        <textarea rows="2" class="form-control" name="payment_url" placeholder="https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest" required></textarea>
                    </div>
                    <div class="form-group col-lg-8 col-md-8 col-sm-8">
                        <label class="control-label">Token URL *</label>
                        <textarea rows="2" class="form-control" name="token_url" placeholder="https://api.safaricom.co.ke/oauth/v1/generate" required></textarea>
                    </div>
                    <div class="form-group col-lg-8 col-md-8 col-sm-8">
                        <label class="control-label">Query URL *</label>
                        <textarea rows="2" class="form-control" name="queryURL" placeholder="https://api.safaricom.co.ke/mpesa/stkpushquery/v1/query" required></textarea>
                    </div>
                    <div class="form-group col-lg-8 col-md-8 col-sm-8">
                        <label class="control-label">Mode *</label>
                        <select class="form-control" name="mode" required>
                            <option></option>
                            <option>SandBox</option>
                            <option>Live</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-8 col-md-8 col-sm-8">
                        <button class="btn btn-danger" type="submit" name="create">
                            SAVE
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>
