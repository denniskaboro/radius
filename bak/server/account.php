<?php
include "header.php";
if(isset($_POST['verify'])){
    $user=$_POST['username'];
    $pack_id=$_POST['pack_id'];
    $fullname=$_POST['fullname'];
    $password=$_POST['password'];
    $encr=md5($password);
    $mobile=$_POST['mobile'];
    $ss="SELECT * FROM clients WHERE username='$user'";
    $q=mysqli_query($con,$ss);
    $row=mysqli_num_rows($q);
    if($row>0){
        $msg="<h4 style='color: red;'>Username Already Used. Please use another username.</h4>";
    }else{
        $sql="INSERT INTO hotspot (`full_name`,`username`,`mobile`,`password`,`encr`,`package_id`,`status`) VALUES('$fullname','$user','$mobile','$password','$encr','$pack_id','Pending')";
        $in=mysqli_query($con,$sql);
        if ($in){
            $_SESSION['created']=$user;
            $_SESSION['username']=$user;
            echo "<script>window.location.assign('payment.php')</script>";
        }else{
            $msg="Error Try Again";
        }

    }
}
if(isset($_POST['login'])){
    $username=$_POST['username'];
    $password=$_POST['password'];
    $encr=md5($password);
    $ss="SELECT * FROM `hotspot` WHERE `username`='$username' and `password`='$password' and `status`='approved'";
    $q=mysqli_query($con,$ss);
    $row=mysqli_num_rows($q);
    if($row>0){
        $_SESSION['username']=$username;
        $_SESSION['update']=$username;
        echo "<script>window.location.assign('payment.php')</script>";
    }else{
        $ss="SELECT * FROM hotspot WHERE username='$username' and password='$password'";
        $q=mysqli_query($con,$ss);
        if($q>0){
            $upda="DELETE FROM hotspot  WHERE username='$username'";
            $s=mysqli_query($con,$upda);
            if($s){
                $msg="<h4 style='color: red;'>User not Varified. Please Create New User. </h4>";
            }else{
                $msg="<h4 style='color: red;'>Wrong Username or Password.</h4>";
            }
        }
    }
}
if (isset($_POST['pack'])) {
    $id = $_POST['pack_id'];
    $_SESSION['pack']=$id;
   }
if (isset($_SESSION['pack'])){
    $id = $_SESSION['pack'];
}
    ?>
<div class="container-fluid" style="background-image: url('img/t.jpg'); background-size: cover;">
    <div id="wrap">
    </div>
    <div class="d-flex justify-content-center h-100">
        <div class="card col-lg-6 col-md-6 px-md-0 px-3">
            <div class="card-header">
                <h3>Sign In</h3>
                <h6 style="color:red;"><?php if (isset($msg)) {
                        echo $msg;
                    } ?></h6>
            </div>
            <div class="card-body" data-aos="fade-up"
                 data-aos-duration="3000">
                <div id="create" style="display: none;">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="inputSet">Username:</label>
                            <input type="text" name="username" class="form-control"  required>
                            <input type="hidden" name="pack_id" value="<?php echo $id; ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="inputSet">Full Name:</label>
                            <input type="text" name="fullname" class="form-control"  required>
                        </div>
                        <div class="form-group">
                            <label for="inputSet">Password:</label>
                            <input type="text" name="password" class="form-control"  required>
                        </div>
                        <div class="form-group">
                            <label for="inputSet">Mobile Number:</label>
                            <input type="text" name="mobile" class="form-control" required>
                        </div>
                        <button class="btn btn-success" name="verify">Create</button>
                    </form>
                    <hr>
                    <button class="btn btn-danger" name="login" onclick="Login()">Login</button>
                </div>

                <div id="login" style="display: block;">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="inputSet">Username:</label>
                            <input type="text" name="username" class="form-control"  required>
                        </div>
                        <div class="form-group">
                            <label for="inputSet">Password:</label>
                            <input type="text" name="password" class="form-control"  required>
                        </div>
                        <button class="btn btn-danger" name="login">Login</button>
                        <hr>
                        <p style="font-size:15px; color:#6689ff;">Create New Account</p>
                    </form>
                    <button class="btn btn-success" name="verify" onclick="Create()">Create</button>
                </div>
                <div id="txt">

                </div>

            </div>
            <div class="card-footer">
                <div class="justify-content-center links">
                    <h4  style="color:#100f33;"> Contact Us:</h4>
                    <hr>
                    <div><i class="fas fa-address-card"></i> Premium Connectivity Ltd.</div>
                    <div><i class="fas fa-at"></i> noc@pmcon.net</div>
                    <div><i class="fas fa-phone-square"></i>01847469555</div>
                </div>

            </div>
        </div>

    </div>

    </div>
<script>
    function Division(str) {
        if (str == "") {
            document.getElementById("inputDistrict").innerHTML = "";
            return;
        }
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("inputDistrict").innerHTML = this.responseText;
            }
        }
        xmlhttp.open("GET", "address.php?divi=" + str, true);
        xmlhttp.send();
    }
</script>
<script>
    function showDis(str) {
        if (str == "") {
            document.getElementById("inputThana").innerHTML = "";
            return;
        }
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("inputThana").innerHTML = this.responseText;
            }
        }
        xmlhttp.open("GET", "address.php?dis=" + str, true);
        xmlhttp.send();
    }
</script>
<script>
    function Login() {
        document.getElementById('create').style='display:none;';
        document.getElementById('login').style='display:block;';
    }
    function Create() {
        document.getElementById('login').style='display:none;';
        document.getElementById('create').style='display:block;';
    }
</script>
<?php
include "footer.php";?>
