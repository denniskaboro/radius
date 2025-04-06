<?php
include('clients.php');
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $sql = "SELECT * FROM clients WHERE username='$username'";
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    $f = mysqli_fetch_array($r);
    $full = $f['full_name'];
    $id = $f['supplier_id'];
}
if (isset($_POST['request'])) {
    $tk = $_POST['tk'];
    $type = $_POST['type'];
    $package = $_POST['package'];
    $sup = "SELECT * FROM `pending` WHERE `username`='$username' ORDER BY `date` DESC ";
    $s = mysqli_query($con, $sup);
    $check = mysqli_num_rows($s);
    if ($check > 0) {
        echo "You already send payment request.";
    } else {
        $sql = "INSERT INTO `pending` (`supplier_id`,`username`,`full_name`,`package`,`debit`,`pay_method`,`reference`)
 VALUES('$id','$username','$full','$package','$tk','$type','Payment by User, $full')";
        $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
        if ($r) {
            $msg = "Payment Successful... ";
        } else {
            $msg = "Unsuccessfull...";
        }
    }

}

if (isset($msg)) {
    echo $msg;
}
?>
<div class="col-sm-4">
    <h3>Payment:</h3>
    <form data-toggle="validator" role="form" method="POST">

        <div class="form-group">
            <label for="inputSet">Service Provider Name:</label>
            <input type="text" class="form-control" id="inputSet" value="<?php echo $full; ?>" disabled>
        </div>
        <div class="form-group">
            <label for="inputPack" class="control-label">Existing Package</label>
            <?php $sql = "SELECT * FROM radusergroup WHERE username='$username'";
            $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
            $f = mysqli_fetch_array($r);
            $exist_group = $f['groupname']; ?>
            <input type="text" class="form-control" id="inputPack" name="package" value="<?php echo $exist_group; ?>"
                   readonly>
        </div>
        <div class="form-group">
            <?php $sql = "SELECT * FROM groups WHERE groupname='$exist_group'";
            $re = mysqli_query($con, $sql) or $msg = mysqli_error($con);
            $g = mysqli_fetch_array($re);
            $tk = $g['price']; ?>
            <label for="inputAmmount" class="control-label">Ammount</label>
            <input type="text" class="form-control" id="inputAmmount" name="tk"
                   value="<?php echo $tk; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="inputSet">Type:</label>
            <select class="form-control" id="inputSet" name="type" required>
                <option></option>
                <option>Cash</option>
                <option>Prime Bank</option>
                <option>Pubali Bank</option>
                <option>Brac Bank</option>
                <option>DBBL</option>
                <option>Bank Asia</option>
                <option>One Bank</option>
            </select>
        </div>
        <div class="form-group">
            <button type="submit" name="request" class="btn btn-primary">Payment</button>
        </div>
    </form>
</div>
<script>
    function showPro(str) {
        if (str == "") {
            document.getElementById("inputAmmount").innerHTML = "";
            return;
        } else {
            document.getElementById("inputAmmount").value = str;
        }

    }
</script>
<script type="text/javascript" src="../component/validator.js"></script>
<script type="text/javascript" src="../component/validator.min.js"></script>

<?php include '../includes/footer.php'; ?>
