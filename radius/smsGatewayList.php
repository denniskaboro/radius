<?php
include 'includes/header.php';
if (isset($_GET['val'])) {
    $val = $_GET['val'];
    $del = "DELETE FROM `smsgateway` WHERE `id`='$val'";
    $d = mysqli_query($con, $del) or $err = mysqli_error($con);
    if ($d) {
        $msg = "SMS Gateway deleted";
        $API->log($supplier_id, $wh, $msg);
    } else {
        $msg = $err;
        $API->log($supplier_id, $wh, $err);
    }
}
?>
<div class="row">
    <!-- Pie Chart -->
    <div class="col-xl-12 col-lg-12">
        <!-- Basic Card Example -->
        <div class="card shadow mb-4">
            <h4 style="color:darkred;"><?php if (isset($msg)) {
                    echo $msg;
                } ?></h4>
            <div class="card-header py-3">
                <a href="smsgateway.php" class="btn btn-danger"
                   style="float:right!important;">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Add New
                </a>
                <h4>SMS Gateway List</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="btrc">
                        <thead>
                        <tr id="trcolor">
                            <th>API URL</th>
                            <th>SerderId</th>
                            <th>ClientId</th>
                            <th>ApiKey</th>
                            <th>AccessKey</th>
                            <th>Activate Date</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sql = "SELECT * FROM `smsgateway` WHERE supplier_id='Admin'";
                        $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);

                        while ($f = mysqli_fetch_array($r)) {
                            $id = $f['id'];
                            ?>
                            <tr>
                                <td><?php echo $f['url']; ?></td>
                                <td><?php echo $f['SenderId']; ?></td>
                                <td><?php echo $f['ClientId']; ?></td>
                                <td><?php echo $f['ApiKey']; ?></td>
                                <td><?php echo $f['AccessKey']; ?></td>
                                <td><?php echo $f['create_date']; ?></td>
                                <td>
                                    <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete"
                                            onClick="Delete('smsGatewayList.php','<?php echo $id; ?>')">
                                        <i class="fa fa-trash" aria-hidden="true"></i></button>
                                </td>
                            </tr>

                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
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
