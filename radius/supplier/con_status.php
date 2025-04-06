<?php include 'reseller.php';
if (isset($_GET['val'])) {
    $id = $_GET['val'];
    $sql = "DELETE FROM radpostauth WHERE id='$id'";
    mysqli_query($con, $sql);

}
?>
<h3>User Authentication Log</h3>
<h4>Last 5 Minutes Log here</h4>
<table class="table table-bordered" id="btrc">
    <thead>
    <tr>
        <th>Username</th>
        <th>User Password</th>
        <th>Reply</th>
        <th>Authentication Date</th>
        <th>Delete</th>
    </tr>
    </thead>
    <tbody>

    <?php
    $sql = "SELECT * FROM clients WHERE supplier_id='$supplier'";
    $data = mysqli_query($con, $sql);
    while ($g = mysqli_fetch_array($data)) {
        $name = $g['username'];
        $log = "SELECT * FROM radpostauth WHERE username='$name' and authdate >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) ORDER BY id DESC";
        $r = mysqli_query($con, $log) or $msg = mysqli_error($con);
        while ($f = mysqli_fetch_array($r)) {
            ?>

            <tr>
                <td><?php echo $f['username']; ?></td>
                <td><?php echo $f['pass']; ?></td>
                <td><?php echo $f['reply']; ?></td>
                <td><?php echo $f['authdate']; ?></td>
                <td><a class="btn btn-danger" href="con_status.php?val=<?php echo $f['id']; ?>">
                        <i class="icon-edit"><span class="glyphicon glyphicon-trash"></span></i></a></td>
            </tr>
        <?php }
    } ?>
    </tbody>
</table>
<link rel="stylesheet" type="text/css" href="../component/jquery.dataTables.min.css"/>
<script type="text/javascript" src="../component/js/export/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#btrc').DataTable({
            pageLength: 15,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
        });

    });
</script>

<?php include '../includes/footer.php'; ?>
