<?php
include "header.php";

if (isset($_POST['otp'])){
    $code=$_POST['code'];
    $otp=$_SESSION['otp'];
    if($code==$otp){
        unset($_SESSION['otp']);
        unset($_SESSION['mobile']);
        echo "<script>window.location.assign('payment.php')</script>";
    }else{
        echo "<script>alert('You have Entered Wrong OTP');</script>";
    }
}
?>


<div class="container">
    <div id="wrap">
    </div>
    <div class="d-flex justify-content-center h-100">
        <div class="card">
            <div class="card-header">
                <h3>Sign In</h3>
                <h6 style="color:red;"><?php if (isset($msg)) {
                        echo $msg;
                    } ?></h6>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                        </div>
                        <input type="text" class="form-control" name="code" placeholder="Input code here.." >
                    </div>
                    <div class="form-group">
                        <button class="btn btn-danger" name="otp">Verify</button>
                    </div>
                    <button onclick="sendJSON()">Send Again</button>
                </form>

            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-center links">
                    <center><a href="https://www.pmcon.net/" style="color:white;" target="_blank">Premium Connectivity
                            Limited</a></center>
                </div>

            </div>
        </div>

    </div>
</div>
    <script type="text/javascript">
        function sendJSON() {

            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else { // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    alert(this.responseText);
                }
            }
            xmlhttp.open("GET", "send.php?otp", true);
            xmlhttp.send();

        }
    </script>
<?php  include "footer.php";?>
