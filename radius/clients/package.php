<?php
include('clients.php');
?>
<h3>ALL Package:</h3>
<div class="col-lg-12 col-md-12 col-sm-12">
    <?php
    $username = $_SESSION['username'];
    $sql = "SELECT groups.* FROM groups INNER JOIN clients 
ON clients.supplier_id=groups.supplier_id WHERE clients.username='$username' and groups.status='1' and client_portal=1";
    $gr = mysqli_query($con, $sql);
    while ($f = mysqli_fetch_array($gr)) {
        $name = $f['groupname'];
        ?>
        <div class="col-lg-3 col-md-3 col-sm-3" style="color:white; margin-top:10px;">
            <form method="POST" enctype="multipart/form-data" action="mpesabilling.php">
                <div style="background: rgba(57,28,50,0.8) !important;">
                    <div style="background-color: rgba(127,74,123,0.59);padding: 1px !important;">
                        <h3 style="margin-left: 5px !important;"><?php echo $f['groupname']; ?></h3>
                    </div>
                    <center>
                        <h4>Download Limit: <?php echo $f['data']; ?></h4>
                        <h4>Duration: <?php echo $f['duration']; ?></h4>
                        <h4>SPEED: <?php echo $f['speed']; ?></h4>
                        <h4 style="color:#93ffd0;" class="font-weight-bold">PRICE: <?php echo $f['price']; ?></h4>
                        <input type="hidden" value="<?php echo $f['price']; ?>" name="price">
                        <input type="hidden" value="<?php echo $f['id']; ?>" name="package">
                        <button class="btn form-control" style="background: #c00642 !important;" name="pack">Buy
                        </button>
                    </center>
                </div>
            </form>
            <!-- Card Body -->
        </div>
    <?php } ?>
</div>
<link rel="stylesheet" type="text/css" href="../component/jquery.dataTables.min.css"/>
<script type="text/javascript" src="../component/js/export/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#btrc').DataTable({
            pageLength: 15,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "order": [[2, "ASC"]]

        });

    });
</script>
<?php include '../includes/footer.php'; ?>
