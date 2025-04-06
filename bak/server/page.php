<?php
include "header.php";
?>
<div class="container-fluid">
    <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12"  style="float: left;">
                <div class="col-lg-8 col-md-7">
                    <div class="row">
                        <?php $sql = "SELECT * FROM `groups` WHERE supplier_id='Admin'";
                        $q = mysqli_query($con,$sql);
                        while ($f = mysqli_fetch_array($q)) {

                            ?>
                            <div class="col-lg-4 col-md-4 col-sm-4 " data-aos="zoom-out" style="color:white; margin-top:10px;">
                                <div class="card-header" style="background-color: #3d3d54;">
                                    <h6 class="text-uppercase m-0 font-weight-bold"><?php echo $f['groupname']; ?></h6>
                                </div>
                                <form method="POST" enctype="multipart/form-data" action="account.php">
                                    <div class="card-body" style="background: rgba(39,33,79,0.38);">
                                        <center>
                                            <h5 style="color:#ff7385;" class="font-weight-bold"></h5>
                                            <h5>Download Limit: <?php echo $f['data']; ?></h5>
                                            <h5>Duration: <?php echo $f['duration']; ?></h5>
                                            <h5>SPEED: <?php echo $f['speed']; ?></h5>
                                            <h5 style="color:#e7595f;" class="font-weight-bold">
                                                PRICE: <?php echo $f['price']; ?></h5>
                                            <input type="hidden" value="<?php echo $f['id']; ?>" name="pack_id">
                                            <button class="btn btn-danger" name="pack">Buy</button>
                                        </center>
                                    </div>
                                </form>
                                <!-- Card Body -->
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

    </div>

</div>
<?php
include "footer.php";
?>