<?php
include "clients.php";
if (isset($_GET['payment'])) {
    $user = $_SESSION['username'];
    $sql = "SELECT groups.* FROM groups INNER JOIN radusergroup ON radusergroup.groupname=groups.id WHERE username='$user'";
    $query = mysqli_query($con, $sql);
    $r = mysqli_fetch_array($query);
    $Amount = $r['price'];
    $groupname = $r['groupname'];
    $pack = $r['id'];
    $_SESSION['price'] = $Amount;
}
if (isset($_POST['price'])) {
    $Amount = $_POST['price'];
    $pack = $_POST['package'];
    $sql = "SELECT * FROM groups WHERE id='$pack'";
    $q = mysqli_query($con, $sql);
    $g = mysqli_fetch_array($q);
    $groupname = $g['groupname'];
    $_SESSION['price'] = $Amount;
}
if (isset($_POST['price']) || isset($_GET['payment'])) {
    ?>
    <div class="container-fluid">
        <div class="col-lg-8 col-md-12 col-sm-12">
            <h3>Payment</h3>
            <div class="form-group">
                <label for="inputSet">Username:</label>
                <input type="text" value="<?php echo $_SESSION['username']; ?>" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="inputSet">Package:</label>
                <input type="text" value="<?php echo $groupname; ?>" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="inputSet">Price:</label>
                <input type="text" name="amt" id="amt" value="<?php echo $Amount; ?>" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="inputSet">Enter Number e.g 0700******: </label>
                <p>(Number format: 7)</p>
                <input type="text" id="num" name="number" class="form-control" required>
            </div>
            <button type="button" name="pay" onclick="Payment()" style="padding:10px !important;"
                    class="btn  btn-danger">
                Payment with Mpesa
            </button>

        </div>
    </div>
    <script>
        function Payment() {
            var number = document.getElementById('num').value;
            if (number == '') {
                alert("You must have provide the mobile number.");
            } else {
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
                        var data = JSON.parse(this.responseText);
                        if (data.status == "error") {
                            alert(data.message);
                        } else {
                            window.location.href = "paymentStatus.php?OrderID=" + data.message;
                        }
                    }
                }
                xmlhttp.open("GET", "payment.php?number=" + number + "&pack=" +<?php echo $pack; ?>, true);
                xmlhttp.send();
            }
        }
    </script>
    <?php
}

include "../includes/footer.php"
?>
