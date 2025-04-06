<?php
include 'reseller.php';
include('../config/data.php');
?>
<table class="table table-bordered" id="btrc">
    <h4>Network Access Server List:</h4>
    <thead>
    <tr>
        <th>Supervisor ID</th>
	<th>Client Name</th>
	<th>Router IP</th>
	<th>Interface Bandwidth</th>
	<th>Users Bandwidth</th>
        <th>Address</th>
	<th>POP</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $sql = "SELECT * FROM nas WHERE shortname='$supplier'";
    $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
    while ($f = mysqli_fetch_array($r)) {

        ?>
        <tr>
	 <td><?php echo $f['shortname']; ?></td>
            <?php
            $id=$f['shortname'];
                $qu="SELECT * FROM supplier WHERE supplier_id='$id'";
                $ff=mysqli_query($con,$qu);
                $show=mysqli_fetch_array($ff);

            ?>
	    <td><?php echo $show['full_name']; ?></td>
	    <td><?php echo $f['nasname']; ?></td>
            <td><a class="btn btn-primary" href="phy.php?nasname=<?php echo $f['nasname']; ?>">
                    Show</a></td>
	     <td><a class="btn btn-primary" href="pppoe.php?nasname=<?php echo $f['nasname']; ?>">
                    Show</a></td>

            <td><?php echo $show['address']; ?></td>
            <td><?php echo $show['pop']; ?></td>
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
