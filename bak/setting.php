<?php
include 'includes/header.php';

if (isset($_POST['create'])) {
    if($per=='Full' || $per=='Admin'){
        $sql="SELECT * FROM `site_setting` WHERE `status`='1'";
        $q=mysqli_query($con,$sql);
        $f=mysqli_fetch_array($q);
        $por=$f['site_logo'];
        $site_name=$_POST['site_name'];

        $filename = uniqid() . "-" . time(); // 5dab1961e93a7-1571494241
        if ($_FILES["portal"]["name"]) {
            $fileinfo = @getimagesize($_FILES["portal"]["tmp_name"]);
            $width = $fileinfo[0];
            $height = $fileinfo[1];
            if ($width == '180' and $height == '80') {
                $status = 'match';
            } else {
                $status = "Portal logo size Doesn't match";
            }
            if ($status == 'match') {
                $main_ext = pathinfo($_FILES["portal"]["name"], PATHINFO_EXTENSION); // jpg
                $main = "P-" . $filename . "." . $main_ext; // 5dab1961e93a7_1571494241.jpg
                $source = $_FILES["portal"]["tmp_name"];
                $destination = "site/{$main}";
                $sql = "UPDATE `site_setting` SET site_name='$site_name',`site_logo`='$main' WHERE `status`='1'";
                $r = mysqli_query($con,$sql);
                if ($r) {
                    move_uploaded_file($source, $destination);
                    $API->log($wh, "Updated Portal Logo");
                    echo "Updated Portal Logo in Hotspot Settings<br>";
                    if($por){
                        unlink('site/'.$por);
                    }
                }
            } else {
                echo $status;
            }

        }else{
	 $sql = "UPDATE `site_setting` SET site_name='$site_name' WHERE `status`='1'";
          $r = mysqli_query($con,$sql);

	}
//        if ($_FILES["ads"]["name"]) {
//            $fileinfo = @getimagesize($_FILES["ads"]["tmp_name"]);
//            $width = $fileinfo[0];
//            $height = $fileinfo[1];
//            if ($width == '500' and $height == '200') {
//                $status = 'match';
//            } else {
//                $status = "Payment logo size Doesn't match";
//            }
//            if ($status == 'match') {
//                $pay_ext = pathinfo($_FILES["ads"]["name"], PATHINFO_EXTENSION); // jpg
//                $adsent = "A-" . $filename . "." . $pay_ext; // 5dab1961e93a7_1571494241.jpg
//                $source1 = $_FILES["ads"]["tmp_name"];
//                $destination1 = "site/{$adsent}";
//                $sql = "UPDATE `site_setting` SET site_name='$site_name',`ads`='$adsent' WHERE `status`='1'";
//                $r = mysqli_query($con,$sql);
//                if ($r) {
//                    move_uploaded_file($source1, $destination1);
//                    $API->log($wh, "Updated Payment Logo in Hotspot Settings");
//                    echo "Updated Payment Logo in Hotspot Settings<br>";
//                    if($ads){
//                        unlink('site/'.$ads);
//                    }
//                }
//
//            } else {
//                echo $status;
//            }
//        }
//        if ($_FILES["hotspot"]["name"]) {
//            $fileinfo = @getimagesize($_FILES["hotspot"]["tmp_name"]);
//            $width = $fileinfo[0];
//            $height = $fileinfo[1];
//            if ($width == '350' and $height == '200') {
//                $status = 'match';
//            } else {
//                $status = "Hotspot logo size Doesn't match";
//            }
//            if ($status == 'match') {
//                $ads_ext = pathinfo($_FILES["hotspot"]["name"], PATHINFO_EXTENSION); // jpg
//                $ads = "H-" . $filename . "." . $ads_ext; // 5dab1961e93a7_1571494241.jpg
//                $source3 = $_FILES["hotspot"]["tmp_name"];
//                $destination3 = "image/{$ads}";
//                $sql = "UPDATE `admin` SET `hotspot`='$ads' WHERE `admin_id`='$admin_id'";
//                $r = mysqli_query($con,$sql);
//                if ($r) {
//                    move_uploaded_file($source3, $destination3);
//                    $API->log($admin_id,$wh, "Updated Hotspot Logo in Hotspot Settings");
//                    echo "Updated Hotspot image in Hotspot Settings<br>";
//                    if($por){
//                        unlink('image/'.$hot);
//                    }
//                }
//            } else {
//                echo $status;
//            }
//        }
    }

}
?>
<div class="row">
    <!-- Pie Chart -->

    <div class="col-xl-8 col-lg-8" id="design" data-aos="fade-down" >
        <!-- Basic Card Example -->
        <div class="card shadow mb-4 text-gray-900" style="margin-left: 2%; padding: 30px;">
            <?php if (isset($msg)) {
                echo $msg;
            } ?>
            <div class="card-header py-3">
                <h4 class="m-0 font-weight-bold text-secondary">Manage Your Website and Hotspot Logo</h4>
                <hr>
            </div>
            <div class="card-body">
    <form data-toggle="validator" role="form" method="POST" enctype="multipart/form-data">
        <center><h2>MAIN LOGO</h2></center>
        <hr>
        <?php
        $sql = "SELECT * FROM `site_setting` WHERE `status`='1'";
        $q = mysqli_query($con,$sql);
        $f = mysqli_fetch_array($q);
        ?>
        <div class="form-group col-lg-8 col-md-8 col-sm-8">
            <label class="control-label">Company Name </label>
            <input type="text" class="form-control" name="site_name" value="<?php echo $f['site_name']; ?>">
        </div>
            <div class="form-group col-lg-8 col-md-8 col-sm-8">
                <label for="inputName" class="control-label">Portal Logo </label>
                <input type="file" class="form-control" name="portal"><h5 style="color:#ff585a;float:right;">Image size must
                    be 180x80</h5>
                <?php if (isset($f['site_logo'])) { ?>
                    <img src="site/<?php echo $f['site_logo']; ?>" width="100%" height="100%">
                <?php } ?>
            </div>
        <br>
        <br>

        <div class="form-group col-lg-8 col-md-8 col-sm-8">
            <button type="submit" style="padding: 10px;width: 70px; height: 40px;" name="create" class="btn btn-danger">
            SAVE
            </button>
        </div>
    </form>
            </div>
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>
