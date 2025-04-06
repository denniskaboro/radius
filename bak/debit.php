<?php
include 'includes/header.php';
$per = $_SESSION['per'];
if (isset($_POST['create'])) {
    if ($per == "Admin") {
        $id = $_POST['id'];
        $debit = $_POST['debit'];
	    $otc = $_POST['otc'];
	    $date = $_POST['date'];
        $type = $_POST['type'];
        $nam = "SELECT * FROM `supplier` WHERE `supplier_id`='$id' ";
        $n = mysqli_query($con,$nam);
        $get = mysqli_fetch_array($n);
        $name = $get['full_name'];

        $sql = "INSERT INTO `transaction` (`supplier_id`,`supplier_name`,`create_date`,`debit`,`otc`,`reference`) VALUES('$id','$name','$date','$debit','$otc','$type')";
        $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
        if ($r) {
            $msg = "Recharge Successfull... ";
	$API->log($wh,"Supervisor ".$name." Account ".$debit." Kes has been debited. Supervisor ID: ".$id);
        } else {
            $msg = "Unsuccessfull...";
        }

    } else {
        $msg = "You have no permission...";
    }
}

if (isset($msg)) {
    echo $msg;
}
?>

<div class="col-sm-4">
    <h3>Fund Deposit:</h3>
    <form data-toggle="validator" role="form" method="POST">
        <div class="form-group">
            <label for="inputSet">Supplier Name:</label>
            <select class="form-control" id="inputSet"  required name="id">
                <option></option>
                <?php
                $sql = "SELECT * FROM supplier ORDER BY create_date DESC";
                $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
                while ($f = mysqli_fetch_array($r)) {
                    ?>
                    <option value="<?php echo $f['supplier_id']; ?>"><?php echo $f['full_name']; ?>
                        ( <?php echo $f['supplier_id']; ?> )
                    </option>

                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="inputAddress" class="control-label">Amount</label>
            <input type="text" class="form-control" id="inputAddress" name="debit" placeholder="Deposit Amount"
                   required>
        </div>
	 <div class="form-group">
            <label for="inputOtc" class="control-label">OTC</label>
            <input type="text" class="form-control" id="inputOtc" name="otc" placeholder="OTC Amount">
        </div>
	<div class="form-group">
            <label for="inputAddress" class="control-label">Date</label>
            <input type="datetime-local" class="form-control" id="inputAddress" name="date"
                   required>
        </div>
        <div class="form-group">
            <label for="inputSet">Type:</label>
            <select required class="form-control" id="inputSet" name="type">
                <option></option>
                <option>Work Order</option>
                <option>ADVANCE</option>
                <option>CASH</option>
            </select>
        </div>
        <div class="form-group">
            <button type="submit" name="" onClick='pay(this.id)' id="create" class="btn btn-primary">Debit</button>
        </div>
    </form>
</div>
<script type="text/javascript">
    function pay(str) {
        var r = confirm("Confirm to Payment ?");
        if (r == true) {
            document.getElementById(str).name = str;
        } else {
            document.getElementById(str).name = "";
        }
    }
</script>
<script type="text/javascript" src="component/validator.js"></script>
<script type="text/javascript" src="component/validator.min.js"></script>

<?php include 'includes/footer.php'; ?>
