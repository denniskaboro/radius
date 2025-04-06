<?php
include 'includes/header.php';
include('config/data.php');
?>
<table class="table table-bordered" id="btrc">
    <h4>Network Access Client List:</h4>
    <thead>
    <tr>
        <th>Client Name</th>
        <th>Router IP</th>
        <th>Session_ID</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $current_time = time();
    $time_out = $current_time - (60);
    $total = "SELECT * FROM visitor WHERE time>=$time_out";
    $found = mysqli_query($con, $total);
    while ($online = mysqli_fetch_array($found)) {

        ?>
        <tr>
            <td><?php echo $online['user']; ?></td>
            <td><?php echo $online['ip']; ?></td>
            <td><?php echo $online['session_id']; ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<link rel="stylesheet" type="text/css" href="component/jquery.dataTables.min.css"/>
<script type="text/javascript" src="component/js/export/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#btrc').DataTable({
            pageLength: 15,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]

        });

    });
</script>

<?php include 'includes/footer.php'; ?>
