<?php
include('reseller.php');
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $sql = "SELECT * FROM supplier WHERE username='$username'";
    $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
    $f = mysqli_fetch_array($r);
    $full = $f['full_name'];
    $id = $f['supplier_id'];
}
if (isset($_POST['request'])) {

    $tk = $_POST['tk'];
    $type = $_POST['type'];
    $trans = $_POST['trans'];
    $file = $_FILES['upload']['name'];
    $target_file="../attachment/".$_FILES['upload']['name'];
    if ($_FILES["upload"]["size"] > 20000000) {
        echo "Sorry, your file is too large. File less than 20MB";
    }else{

    $sup = "SELECT * FROM pending WHERE transaction='$trans' ORDER BY date DESC ";
    $s = mysqli_query($con,$sup);
    $check = mysqli_num_rows($s);
    if ($check > 0) {
        echo "You already payment this Transaction ID.";
	 $API->log($wh,$username." payment request cancel for duplicate Transaction ID");
    } else {

        $sql = "INSERT INTO pending (supplier_id,username,full_name,debit,transaction,pay_method,reference,file) VALUES('$id','$username','$full','$tk','$trans','$type','Payment by Supervisor, $wh','$file')";
        $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
        if ($r) {
            move_uploaded_file($_FILES['upload']['tmp_name'],"../attachment/".$_FILES['upload']['name']);
            $msg = "Payment Successful... ";
	 $API->log($wh,$username." payment request successful for ".$tk." Kes");
        } else {
            $msg = "Unsuccessfull...";
        }
    }

}
}

if (isset($msg)) {
    echo $msg;
}
?>
<div class="col-sm-4">
    <h3>Payment:</h3>
    <form data-toggle="validator" role="form" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="inputSet">Your Name:</label>
            <input type="text" class="form-control" id="inputSet" name="name" value="<?php echo $full; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="inputAddress" class="control-label">Ammount</label>
            <input type="text" class="form-control" id="inputAddress" name="tk" placeholder="Payment Ammount" required>
        </div>
        <div class="form-group">
            <label for="inputTrans" class="control-label">Transaction</label>
            <input type="text" class="form-control" id="inputTrans" name="trans" placeholder="bKash Transaction Number" required>
        </div>
        <div class="form-group">
            <label for="inputSet">Type:</label>
            <select class="form-control" id="inputSet" name="type">
                <option></option>
		<option>bKash</option>
                <option>Prime Bank Ltd.</option>
                <option>UCBL</option>
		<option>IBBL</option>
		<option>DBBL</option>
		<option>Pubali Bank Ltd.</option>
                <option>EBL</option>
            </select>
        </div>
        <div class="form-group">
            <label for="upload" class="control-label">Attachment</label>
            <input type="file" class="form-control" id="upload" name="upload">
        </div>
        <div class="form-group">
            <button type="submit" name="request" class="btn btn-primary">Payment</button>
        </div>
    </form>
</div>
<script type="text/javascript" src="../component/validator.js"></script>
<script type="text/javascript" src="../component/validator.min.js"></script>

<?php include '../includes/footer.php'; ?>
