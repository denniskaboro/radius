<?php
include('clients.php');
if (isset($_SESSION['username']) and isset($_SESSION['gid'])) {
    $username = $_SESSION['username'];
    $groupId = $_SESSION['gid'];
    $sql = "SELECT * FROM clients WHERE username='$username'";
    $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
    $f = mysqli_fetch_array($r);
    $full = $f['full_name'];
    $id = $f['supplier_id'];
    $num= $f['mobile'];
    $sql = "SELECT * FROM groups WHERE id='$groupId'";
    $rs = mysqli_query($con,$sql) or $msg = mysqli_error($con);
    $p = mysqli_fetch_array($rs);
    $groupName = $p['groupname'];
    $price = $p['price'];
}

?>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <h3>Payment:</h3>

    <form method="POST"  data-toggle="validator" action="payment.php"  role="form">
        <div class="form-group col-sm-8 ">

            <label for="Username">Username:</label>
            <input type="text" class="form-control" name="username" value="<?php echo $_SESSION['username']; ?>" readonly>
            <label for="Username">Full Name:</label>
            <input type="text" class="form-control" name="full" value="<?php echo $full; ?>" readonly>
            <input type="hidden" class="form-control" name="id" value="Admin" readonly>
            <div class="form-group">
                <label for="inputPack" class="control-label">Package</label>
                <input type="hidden" class="form-control" id="inputPack" name="package" value="<?php echo $groupId; ?>" readonly>
                <input type="text" class="form-control" value="<?php echo $groupName; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="inputSet">Enter Number e.g 7**** or 2547***: </label>
                <input type="text" id="num" name="number" value="<?php echo $num; ?>" class="form-control" required>
            </div>
            <button type="button" name="pay" id="payment" onclick="Payment()" style="padding: 10px !important;" class="btn btn-danger">
                Payment with Mpesa
            </button>
    </form>
</div>
<script>
    function Payment() {
        document.getElementById('payment').innerText="Loading...";
        var number = document.getElementById('num').value;
        var pack = document.getElementById('inputPack').value;
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
                    document.getElementById('payment').innerText="Payment with Mpasa";
                } else {
                    alert(data.message);
                    window.location.href = "my_info.php?OrderID=" + data.message;
                }
            }
        }
        xmlhttp.open("GET", "payment.php?number=" + number+"&pack="+pack, true);
        xmlhttp.send();
    }
</script>
<script type="text/javascript" src="../component/validator.js"></script>
<script type="text/javascript" src="../component/validator.min.js"></script>

<?php include '../includes/footer.php'; ?>
