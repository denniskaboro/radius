<?php
include "header.php";
if (isset($_SESSION['pack'])) {
    $id = $_SESSION['pack'];
    $username = $_SESSION['username'];
    $sql = "SELECT * FROM `groups` WHERE `id`='$id'";
    $q = mysqli_query($con,$sql);
    $f = mysqli_fetch_array($q);
    $price = $f['price'];
    $_SESSION['price']=$price;
    $speed = $f['speed'];
    $bandwidth = $f['data'];
    $duration = $f['duration'];
    $name = $f['groupname'];
    $sq = "SELECT * FROM `hotspot` WHERE username='$username'";
    $qq = mysqli_query($con,$sq);
    $r = mysqli_fetch_array($qq);
    $password = $r['password'];

    ?>

<div class="container-fluid" style="background-image: url('img/t.jpg'); background-size: cover;">
    <div id="wrap">
    </div>
    <div class="d-flex justify-content-center h-100">
        <div class="card col-lg-8 col-md-8 px-md-0 px-3">
            <div class="card-header">
                <h3>Payment</h3>
                <h6 style="color:red;"><?php if (isset($msg)) {
                        echo $msg;
                    } ?></h6>
            </div>
            <div class="card-body" data-aos="fade-up"
                 data-aos-duration="3000">
                <div class="form-group">
                    <label for="inputSet">Username:</label>
                    <input type="text" value="<?php echo $username;?>" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label for="inputSet">Package:</label>
                    <input type="text" value="<?php echo $name;?>" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label for="inputSet">Price:</label>
                    <input type="text" name="price" value="<?php echo $price;?>" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label for="inputSet">Bandwidth:</label>
                    <input type="text" name="band" value="<?php echo $bandwidth;?>" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label for="inputSet">Duration:</label>
                    <input type="text" name="band" value="<?php echo $duration;?>" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label for="inputSet">Speed:</label>
                    <input type="text" name="band" value="<?php echo $speed;?>" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label for="inputSet">Enter Number e.g 0700******: </label>
                    <p>(Number format: 7)</p>
                    <input type="text" id="num" name="number" class="form-control" required>
                </div>
                <button name="pay" id="payment" onclick="Payment()" class="btn btn-danger">
                   Payment with MPesa
                </button>
                <hr>
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
</div>
    <script>
        function Payment() {
            var number = document.getElementById('num').value;
            document.getElementById('payment').innerText="Loading...";
            document.getElementById('payment').disabled=true;
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
            }
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);
                    var data = this.responseText;
                    if (data.code == 1) {
                        alert(data.message);
                    } else {
                        var ajax = new XMLHttpRequest();
                        ajax.open("POST", "success.php", true);
                        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                        ajax.onreadystatechange = function () {
                            if (this.readyState == 4 && this.status == 200) {
                                console.log(this.responseText);
                                var responseData = JSON.parse(this.responseText);
                                if (responseData.status == "success") {
                                    window.location = "index.php?OrderID=" + responseData.message;
                                }
                            }
                        };

                        var pack = "<?php echo $id; ?>";
                        var data = "pack=" + pack;
                        ajax.send(data);
                    }
                }
            }
            xmlhttp.open("GET", "LipaNaMpesa.php?number=" + number, true);
            xmlhttp.send();
        }
    </script>
<?php }
include "footer.php"; ?>
