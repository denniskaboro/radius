<?php
include 'includes/header.php';
include('config/data.php');
$per = $_SESSION['per'];
$usr = $_SESSION['username'];
if (isset($_POST['create'])) {
    if ($per == "Admin") {
        $id = $_POST['id'];
        $debit = $_POST['debit'];
        $credi = $_POST['credit'];
        $tra = $_POST['tra'];
        $otc = $_POST['otc'];
        $balance = $_POST['balance'];
        $sup_am = $_POST['sup_am'];
        $sup_bl = $_POST['sup_bl'];
        $sql = "UPDATE transaction set transaction='$tra',debit='$debit',credit='$credi',otc='$otc',balance='$balance',
supplier_ammount='$sup_am',supplier_balance='$sup_bl' WHERE id='$id'";
        $q = mysqli_query($con, $sql);
        if ($q) {
            echo "Data saved Successfully";
        }
    } else {
        echo "<h4 style='color: coral ;'>You have no permission...</h4>";
    }
}
?>
<h3>Supervisor Transaction Edit:</h3>
<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM transaction WHERE id='$id'";
    $res = mysqli_query($con, $sql);
    $g = mysqli_fetch_array($res);

    ?>

    <div class="col-sm-6">
        <?php if (isset($msg)) {
            echo "<h4 style='color: #de4e97'>" . $msg . "</h4>";
        } ?>
        <form data-toggle="validator" role="form" method="POST">
            <div class="form-group">
                <label for="inputName" class="control-label">Supplier ID </label>
                <input type="text" class="form-control" value="<?php echo $g['supplier_id']; ?>" readonly>
                <input type="hidden" class="form-control" name="id" value="<?php echo $g['id']; ?>">
            </div>
            <div class="form-group">
                <label for="inputFullname" class="control-label">Name </label>
                <input type="text" class="form-control" value="<?php echo $g['supplier_name']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="inputFullname" class="control-label">Date </label>
                <input type="text" class="form-control" value="<?php echo $g['date']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="inputAdd" class="control-label">Transaction</label>
                <input type="text" class="form-control" name="tra"
                       value="<?php echo $g['transaction']; ?>" required>
            </div>
            <div class="form-group">
                <label for="inputFullname" class="control-label">Debit </label>
                <input type="text" class="form-control" name="debit"
                       value="<?php echo $g['debit']; ?>" required>
            </div>
            <div class="form-group">
                <label for="inputAdd" class="control-label">OTC </label>
                <input type="text" class="form-control" name="otc"
                       value="<?php echo $g['otc']; ?>" required>
            </div>
            <div class="form-group">
                <label class="control-label">Credit </label>
                <input type="text" class="form-control" name="credit" value="<?php echo $g['credit']; ?>" required>
            </div>
            <div class="form-group">
                <label for="inputRoad" class="control-label">Balance</label>
                <input type="text" class="form-control" id="inputRoad" name="balance"
                       value="<?php echo $g['balance']; ?>" required>
            </div>
            <div class="form-group">
                <label for="inputHouse" class="control-label">Supervisor Amount.</label>
                <input type="text" class="form-control" id="inputHouse" name="sup_am"
                       value="<?php echo $g['supplier_ammount']; ?>" required>
            </div>
            <div class="form-group">
                <label for="inputMobile" class="control-label">Supervisor Balance </label>
                <input type="text" class="form-control" id="inputMobile" name="sup_bl"
                       value="<?php echo $g['supplier_balance']; ?>" required>
            </div>
            <div class="form-group">
                <button type="submit" name="" onClick='pay(this.id)' id="create" class="btn btn-primary">UPDATE</button>
            </div>
        </form>
    </div>
    <script type="text/javascript">
        function pay(str) {
            var r = confirm("Money will be Change for this User. Confirm to Update ?");
            if (r == true) {
                document.getElementById("create").name = str;
            } else {
                document.getElementById("create").name = "";
            }
        }

    </script>
<?php }
include 'includes/footer.php'; ?>
