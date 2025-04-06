<?php
include 'header.php';

$per = $_SESSION['per'];


?>
<div class="col-lg-12 ">
    <h3>Package List</h3>
    <?php if (isset($msg)) {
        echo $msg;
    } ?>
    <table class="table table-bordered" id="btrc">
        <thead>
        <tr>
            <th>Package Name</th>
            <th>Customer Price</th>
            <th>Duration</th>
            <th>Bandwidth</th>
            <th>Speed</th>

        </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT * FROM groups ORDER BY price DESC";
        $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
        while ($f = mysqli_fetch_array($r)) {
            $name = $f['groupname'];
            ?>
            <tr>

                <td><?php echo $f['groupname']; ?></td>
                <td><?php echo $f['price']; ?></td>
                <td><?php echo $f['duration']; ?></td>
                <td><?php echo $f['data']; ?></td>
                <td><?php echo $f['speed']; ?></td>

            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
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
