<?php
include 'includes/header.php';
include('config/data.php');

if (isset($_POST['start'])) {
    $username = $_POST['username'];
    $id = $_POST['id'];
    $sql = "SELECT * FROM `mpesaresponses` WHERE `id`='$id'";
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    $j = mysqli_fetch_array($r);
    $TransID = $j['TransID'];
    $TransAmount = $j['TransAmount'];
    $userB = "INSERT INTO `user_balance` (`username`,`debit`,`transactionId`)
         VALUES('$username','$TransAmount','$TransID')";
    $ubil = mysqli_query($con, $userB);
    if ($ubil) {
        $up = "UPDATE mpesaresponses SET `BillRefNumber`='$username', `assigned`='1' WHERE `id`='$id'";
        mysqli_query($con, $up);
        echo "<script>alert('Success!!!')</script>";
    }
}
?>
<style>
    #endForm {
        display: none;
        font-size: 25px;
        border: 3px solid salmon;
        padding: 2em;
        width: 50%;
        height: 70%;
        text-align: center;
        background: rgba(29, 13, 46, 0.95);
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        -webkit-transform: translate(-50%, -50%)

    }

</style>
<h3>Mpesa Success Payment History</h3>
<table class="table table-bordered" id="btrc">
    <thead>
    <tr>
        <th>Username</th>
        <th>Full Name</th>
        <th>Mobile</th>
        <th>Transaction</th>
        <th>Amount</th>
        <th>Org. Balance</th>
        <th>Date</th>
        <th>Reference</th>
        <th>Edit</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $sum = 0;
    if (isset($_GET['show'])) {
        $sql = "SELECT * FROM `mpesaresponses` where DATE_FORMAT(TransTime,'%Y-%m-%d')=curdate() && TransAmount !=0 and assigned='0'";
    } else {
        $sql = "SELECT * FROM `mpesaresponses` WHERE TransAmount !=0  and assigned='0'";
    }
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    while ($j = mysqli_fetch_array($r)) {
        $fname = $j['FirstName'];
        $mname = $j['MiddleName'];
        $lname = $j['LastName'];
        $mobile = $j['MSISDN'];
        $date = $j['TransTime'];
        $transaction = $j['TransID'];
        $debit = $j['TransAmount'];
        $sum = $debit + $sum;
        $reference = $j['BillRefNumber'];

        ?>

        <tr>
            <td><?php echo $reference; ?></td>
            <td><?php echo $fname . " " . $mname . " " . $lname; ?></td>
            <td><?php echo $mobile; ?></td>
            <td><?php echo strtoupper($transaction); ?></td>
            <td><?php echo $debit; ?></td>
            <td><?php echo $j['OrgAccountBalance']; ?></td>
            <td><?php echo $date; ?></td>
            <td><?php echo $j['TransactionType']; ?></td>
            <td>
                <button class="btn btn-sm btn-primary" id="<?php echo $j['id']; ?>" onclick="End(this.id)"
                        style="color: #ffffff;">Edit
                </button>
            </td>
            </td>
        </tr>
    <?php }
    echo "<br> <br><h4>Total Payment Balance is: " . $sum . "</h4>";
    ?>
    </tbody>

</table>
<div class="form-popup" id="endForm">
    <form method="post" enctype="multipart/form-data" class="form-container">
        <h1>Assign Transaction to User.</h1>
        <label>Username</label>
        <div class="form-group">
            <input class="form-control" type="text" name="username" placeholder="username" required>
        </div>
        <div class="form-group">
            <input type="hidden" class="form-control" id="userid" value="" name="id">
        </div>

        <button class="btn btn-sm btn-danger" name='start' type="submit">SAVE</button>
        <input class="btn btn-sm btn-warning" type="reset"/>
    </form>
</div>
<script>
    function End(str) {
        $('#endForm').fadeToggle();
        document.getElementById("userid").value = str;
        $(document).mouseup(function (e) {
            var container = $("#endForm");

            if (!container.is(e.target) // if the target of the click isn't the container...
                && container.has(e.target).length === 0) // ... nor a descendant of the container
            {
                container.fadeOut();
            }
        });

    }
</script>
<link rel="stylesheet" type="text/css" href="component/jquery.dataTables.min.css"/>
<script type="text/javascript" src="component/js/export/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#btrc').DataTable({
            pageLength: 15,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            order: [[6, 'DESC']]

        });

    });
</script>
<?php include 'includes/footer.php'; ?>


