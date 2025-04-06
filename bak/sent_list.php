<?php
include 'includes/header.php';
$supplier_id="Admin";
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
                    <h4 class="m-0 font-weight-bold text-secondary">Sent SMS History</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="btrc">
                            <thead>
                            <tr id="trcolor">
                                <th>Client Name</th>
                                <th>Number</th>
                                <th>Message</th>
                                <th>Sent Date</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $sql = "SELECT * FROM `sms` WHERE supplier_id='$supplier_id'";
                            $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);

                            while ($f = mysqli_fetch_array($r)) {
                                $mobile = $f['mobile'];
				$p_exp="/^254/i";
				$mobile=preg_replace($p_exp,"0",$mobile);
                                ?>
                                <tr>
                                    <td><?php
                                        $s = "SELECT * FROM clients WHERE mobile='$mobile'";
                                        $qm = mysqli_query($con, $s);
                                        $m = mysqli_fetch_array($qm);
                                        echo $m['full_name']; ?></td>
                                    <td><?php echo $f['mobile']; ?></td>
                                    <td><?php echo $f['message']; ?></td>
                                    <td><?php echo $f['create_date']; ?></td>

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
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
		order: [[3,"desc"]]

            });

        });
    </script>
<?php include 'includes/footer.php'; ?>
