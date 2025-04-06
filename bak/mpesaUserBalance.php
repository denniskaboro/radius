<?php
include 'includes/header.php';
include('config/data.php');
if(isset($_GET['delete'])){
    $id=$_GET['delete'];
    $del="DELETE FROM `user_balance` WHERE `id`='$id'";
    $r=mysqli_query($con,$del);
    if($r){
        echo "<script>alert('Success!!!')</script>";
    }
}
?>
<h3>Mpesa Success Payment History</h3>
<table class="table table-bordered" id="btrc">
    <thead>
    <tr>
        <th>Username</th>
        <th>Debit</th>
        <th>Credit</th>
        <th>Transaction</th>
        <th>Date</th>
        <th>Delete</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $sum = 0;
    if (isset($_GET['show'])) {
        $user = $_GET['show'];
        $sql = "SELECT * FROM `user_balance` WHERE username ='$user'";
        $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
        while ($j = mysqli_fetch_array($r)) {
            $id = $j['id'];
            $fname = $j['username'];
            $date = $j['create_date'];
            $transaction = $j['transactionId'];
            $debit = $j['debit'];
            $sum = $debit + $sum;
            $credit= $j['credit'];

            ?>

            <tr>
                <td><?php echo $fname; ?></td>
                <td><?php echo $debit; ?></td>
                <td><?php echo $credit; ?></td>
                <td><?php echo strtoupper($transaction); ?></td>
                <td><?php echo $date; ?></td>
		  <td><a class="btn btn-danger" href="mpesaUserBalance.php?show=<?php echo $user; ?>&delete=<?php echo $id; ?>">
                        <i class="icon-edit"><span class="glyphicon glyphicon-trash"></span></i></a></td>
            </tr>
        <?php }
    }
    echo "<br> <br><h4>Total Payment Balance is: " . $sum . "</h4>";
    ?>
    </tbody>

</table>

<link rel="stylesheet" type="text/css" href="component/jquery.dataTables.min.css"/>
<script type="text/javascript" src="component/js/export/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#btrc').DataTable({
            pageLength: 15,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            order: [[2, 'DESC']]

        });

    });
</script>
<?php include 'includes/footer.php'; ?>


