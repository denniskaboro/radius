<?php
include 'reseller.php';
include('../config/data.php');
$per = $_SESSION['per'];
$id = $_SESSION['id'];
?>
<style>
    #myForm {
        display: none;
        border: 3px solid salmon;
        padding: 2em;
        width: 1024px;
        text-align: center;
        background: #60728f;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        -webkit-transform: translate(-50%, -50%)

    }
</style>
<h3>Complain Resolved List</h3>
<table class="table table-bordered" id="btrc">
    <thead>
    <tr>
        <th>Coustomer Name</th>
        <th>Coustomer Problem</th>
        <th>Create Time</th>
        <th>Cause of Problem</th>
        <th>Attachment</th>
        <th>Status</th>
        <th>Completed Time</th>
        <th>Working Person</th>

    </tr>
    </thead>
    <tbody>
    <?php
    $id = $_SESSION['id'];
    $sql = "SELECT * FROM resolved WHERE supplier_id='$id'  ORDER BY id DESC ";
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    while ($j = mysqli_fetch_array($r)) {
        ?>
        <tr>

            <td><?php echo $j['home_user']; ?></td>
            <td><?php echo $j['problem']; ?></td>
            <td><?php echo $j['create_time']; ?></td>
            <td><?php echo $j['cause']; ?></td>
            <td>
                <button class="btn btn-primary" onClick='myValue(this.id)' id="<?php echo $j['attachment']; ?>">Prob.
                </button>
                <button class="btn btn-primary" onClick='myValue(this.id)'
                        id="<?php echo $j['feedback_attachment']; ?>">Res.
                </button>
            </td>

            <td><?php echo $j['status']; ?></td>
            <td><?php echo $j['end_time']; ?></td>
            <td><?php echo $j['working_per']; ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<div class="form-popup" id="myForm">

</div>
<link rel="stylesheet" type="text/css" href="../component/jquery.dataTables.min.css"/>
<script type="text/javascript" src="../component/js/export/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#btrc').DataTable({
            pageLength: 15,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            order: [[9, "desc"]]
        });

    });
</script>
<script>
    function myValue(str) {
        $('#myForm').fadeToggle();
        document.getElementById("myForm").innerHTML = "<img src='../complain/" + str + "' width='970px' height='700px'></img>";
        $(document).mouseup(function (e) {
            var container = $("#myForm");

            if (!container.is(e.target) // if the target of the click isn't the container...
                && container.has(e.target).length === 0) // ... nor a descendant of the container
            {
                container.fadeOut();
            }
        });

    }
</script>
<?php include '../includes/footer.php'; ?>

