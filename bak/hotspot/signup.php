<?php
include "includes/header.php";
if (isset($_POST['create'])) {
    unset($_POST['create']);
    $username = $_POST['username'];
    $mobile = $_POST['mobile'];
    $_POST['admin_id'] = $admin_id;
    $_POST['otp_limit'] = 1;
    $m = $API->Select("*", "hotspot", "mobile='$mobile' and status=1");
    $u = $API->Select("*", "hotspot", "username='$username'");

    if ($m->num_rows > 0) {
        $result = array("status" => "error", "message" => "Mobile number is already used");
    } elseif ($u->num_rows > 0) {
        $result = array("status" => "error", "message" => "Username is already used");
    } else {
        $sm = $API->Select("sms_template.message,cron_job.otp_gateway,cron_job.otp_masking",
            "cron_job INNER JOIN sms_template ON  cron_job.otp_message=sms_template.id",
            "cron_job.admin_id='$admin_id' and cron_job.otp_enable=1");

        if ($sm->num_rows > 0) {
            $data = [];
            $f = $sm->fetch_assoc();
            $otp = $API->RandomString(6, 5);
            $p_otp = "/[*][*]+[otp]+[*][*]/i";
            $message = $f['message'];
            $new_message = preg_replace(array($p_otp), array($otp), $message);
            $gateway_name = $f['otp_gateway'];
            $otp_masking = $f['otp_masking'];
            $data[$username] = $mobile;
            $mobileData = json_encode($data);
            $result = $API->$gateway_name("$admin_id", "$mobileData", "$message", $otp_masking);
            if ($result['status'] == "success") {
                $_POST['password'] = sha1($_POST['clear_text']);
                $API->dataInsert("hotspot", array($_POST));
                $_SESSION['otp'] = $otp;
                $_SESSION['username'] = $username;
                $result = array("status" => "success", "message" => "Your account has been created successfully");
                sleep(2);
                header("location:/hotspot/otp/");

            }
        } else {
            $result = array("status" => "error", "message" => "SMS Gateway not found!");
        }
    }

}

?>
    <div class="section-authentication-signin d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-2">
                <div class="col mx-auto">
                    <div class="card mt-5">
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <img src="/site/logo.png" width="380px" height="100px">
                            </div>
                            <div class="border p-4 rounded">
                                <div class="text-center">
                                    <h3 class="">Sign Up</h3>
                                    <p>Already have an account? <a href="/hotspot/signin/">Sign in here</a>
                                    </p>
                                </div>
                                <div class="login-separater text-center mb-4"><span>OR SIGN UP</span>
                                    <hr/>
                                </div>
                                <div class="form-body">
                                    <form class="row g-3" id="signup" method="post">
                                        <div class="col-sm-12">
                                            <label for="inputFirstName" class="form-label">Name *</label>
                                            <input type="text" name="name" class="form-control"
                                                   placeholder="Your name">
                                        </div>
                                        <div class="col-sm-12">
                                            <label for="inputLastName" class="form-label">Mobile *</label>
                                            <p class="text-danger">We will varify your mobile number.</p>
                                            <input type="text" name="mobile" class="form-control" id="inputLastName"
                                                   placeholder="Mobile">
                                        </div>
                                        <div class="col-12">
                                            <label for="inputEmailAddress" class="form-label">Address</label>
                                            <input type="text" name="address" class="form-control"
                                                   id="inputEmailAddress">
                                        </div>
                                        <div class="col-12">
                                            <label for="inputEmailAddress" class="form-label">Email Address</label>
                                            <input type="email" name="email" class="form-control"
                                                   id="inputEmailAddress"
                                                   placeholder="example@user.com">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="inputLastName" class="form-label">Password *</label>
                                            <input type="text" name="clear_text" required class="form-control"
                                                   placeholder="Login Password">
                                        </div>
                                        <div class="col-12">
                                            <div class="d-grid">
                                                <button type="submit" name="create" class="btn btn-dark"><i
                                                            class='bx bx-user'></i>Sign
                                                    up
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $("#show_hide_password a").on('click', function (event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bx-hide");
                    $('#show_hide_password i').removeClass("bx-show");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bx-hide");
                    $('#show_hide_password i').addClass("bx-show");
                }
            });
        });
    </script>
<?php
include "includes/footer.php";
?>