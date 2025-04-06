<?php
include 'clients.php';
include('../config/data.php');
if(isset($_GET['OrderID'])){
    $msg="Please wait, we will update your package after verify the payment status within 5 Minutes.";
    echo "<script>alert('{$msg}')</script>";
}
?>
<h3>Payment in Processing</h3>
<h4 style="color: red">Please wait, we will update your package after verify the payment status within 5 Minutes or you can check manually if you payment</h4>
<table class="table table-bordered" id="btrc">
    <thead>
    <tr>
        <th>Username</th>
        <th>Price</th>
        <th>Transaction ID</th>
        <th>Expiration</th>
        <th>Package</th>
        <th>Payment Date</th>
        <th>Status</th>
        <th>Check Status</th>

    </tr>
    </thead>
    <tbody>
    <?php
    $usr=$_SESSION['username'];
    $sql = "SELECT payment_processing.*, groups.groupname FROM `payment_processing` INNER JOIN `groups` ON payment_processing.package=groups.id WHERE payment_processing.username='$usr'";
    
    $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
    while ($j = mysqli_fetch_array($r)) {
        $transaction = $j['transactionID'];
        $price = $j['price'];
        $expiration = $j['expiration'];
        $groupname = $j['groupname'];
        $date = $j['create_date'];
        $id = $j['id'];

        ?>

        <tr id="<?php echo $id; ?>">
            <td><?php echo $usr; ?></td>
            <td><?php echo $price; ?></td>
            <td><?php echo $transaction; ?></td>
            <td><?php echo $expiration; ?></td>
            <td><?php echo $groupname; ?></td>
            <td><?php echo $date; ?></td>
            <td><?php echo "Processing"; ?></td>
            <td><button onclick="Payment('<?php echo $usr; ?>','<?php echo $id; ?>')" class="btn btn-danger">Update</button></td>

        </tr>
    <?php }

    ?>
    </tbody>

</table>
<script>
    function Payment(str,id) {
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
                    alert(data.message);
                    document.getElementById(id).remove();
                }
            }
        }
        xmlhttp.open("GET", "mpesaValidate.php?username=" + str, true);
        xmlhttp.send();
    }
</script>
<link rel="stylesheet" type="text/css" href="../component/jquery.dataTables.min.css"/>
<script type="text/javascript" src="../component/js/export/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#btrc').DataTable({
            pageLength: 15,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            order: [[2, 'DESC']]

        });

    });
</script>
<?php include '../includes/footer.php'; ?>

