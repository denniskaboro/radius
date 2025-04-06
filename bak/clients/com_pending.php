<?php
include 'clients.php';
include('../config/data.php');
if(isset($_GET['id'])){
    $id=$_GET['id'];
    $sql="DELETE FROM complain WHERE id='$id'";
    $q=mysqli_query($con,$sql);
    if($q){
        echo "Complain Delete successfully...";
    }
}
?>
<h3>Complain Pending List</h3>
<table class="table table-bordered" id="btrc">
    <thead>
    <tr>
        <th>Coustomer Name</th>
        <th>Coustomer Problem</th>
        <th>Create Time</th>
        <th>Cause of Problem</th>
        <th>Status</th>
        <th>Estimate Time</th>
        <th>Working Person</th>
        <th>Delete</th>

    </tr>
    </thead>
    <tbody>
    <?php
    $sql = "SELECT * FROM complain WHERE home_user='$username'  ORDER BY id DESC ";
    $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
    while ($j = mysqli_fetch_array($r)) {
        ?>
        <tr>

            <td><?php echo $j['home_user']; ?></td>
            <td><?php echo $j['problem']; ?></td>
            <td><?php echo $j['create_time']; ?></td>
            <td><?php echo $j['cause']; ?></td>
            <td><?php echo $j['status']; ?></td>
            <td><?php echo $j['est_time']; ?></td>
            <td><?php echo $j['working_per']; ?></td>
            <td><a class="btn btn-danger" href="com_pending.php?id=<?php echo $j['id']; ?>"
                   onClick='myFunction()' id="click">
                    <i class="icon-edit"><span class="glyphicon glyphicon-trash"></span></i></a></td>
        </tr>
    <?php } ?>
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

