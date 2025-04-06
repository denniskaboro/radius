<?php
include('reseller.php');
include('../config/data.php');

?>
<h3>ALL Package:</h3>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>Package Name</th>
        <th>Duration</th>
        <th>Data</th>
        <th>Speed</th>
        <th>Price</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $sql = "SELECT * FROM `groups` WHERE status='1' and (`supplier_id`='$sup_id' or `supplier_id`='COMMON')";
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    while ($j = mysqli_fetch_array($r)) {
        ?>
        <tr>

            <td><?php echo $j['groupname']; ?></td>
            <td><?php echo $j['duration']; ?></td>
            <td><?php echo $j['data']; ?></td>
            <td><?php echo $j['speed']; ?></td>
            <td><?php echo $j['price']; ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<?php include '../includes/footer.php'; ?>
