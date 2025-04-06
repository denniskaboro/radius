<?php
include 'includes/header.php';

if (isset($_POST['create'])) {
    if($per=='Full' || $per=='Admin'){
        $title=$_POST['title'];
        $description=$_POST['description'];
        $title=$_POST['title'];
        $position=$_POST['position'];
        $filename = uniqid() . "_" . time(); // 5dab1961e93a7-1571494241
        if ($_FILES["ads"]["name"]) {
//            $fileinfo = @getimagesize($_FILES["portal"]["tmp_name"]);
//            $width = $fileinfo[0];
//            $height = $fileinfo[1];
//            if ($width == '180' and $height == '80') {
//                $status = 'match';
//            } else {
//                $status = "Portal logo size Doesn't match";
//            }
//            if ($status == 'match') {
                $main_ext = pathinfo($_FILES["ads"]["name"], PATHINFO_EXTENSION); // jpg
                $main = "A-" . $filename . "." . $main_ext; // 5dab1961e93a7_1571494241.jpg
                $source = $_FILES["ads"]["tmp_name"];
                $destination = "site/{$main}";
                $in="INSERT INTO ads_setting (`title`,`description`,`ads`,`position`) VALUES ('$title','$description','$main','$position')";
                $r = mysqli_query($con,$in);
                if ($r) {
                    move_uploaded_file($source, $destination);
                    $API->log($wh, "New Ads added");
                    echo "<script>alert('Ads added in the database')</script>";
                }

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
                <h4 class="m-0 font-weight-bold text-secondary">Manage Your Website Ads</h4>
                <hr>
            </div>
            <div class="card-body">
    <form data-toggle="validator" role="form" method="POST" enctype="multipart/form-data">
        <center><h2>Ads Settings</h2></center>
        <hr>
        <div class="form-group col-lg-8 col-md-8 col-sm-8">
            <label class="control-label">Title</label>
            <input type="text" class="form-control" name="title">
        </div>
        <div class="form-group col-lg-8 col-md-8 col-sm-8">
            <label class="control-label">Description</label>
            <textarea cols="4" rows="4" class="form-control" name="description"></textarea>
        </div>
            <div class="form-group col-lg-8 col-md-8 col-sm-8">
                <label  class="control-label">Ads Image </label>
                <input type="file" class="form-control" name="ads">
            </div>
        <div class="form-group col-lg-8 col-md-8 col-sm-8">
            <label class="control-label">Slider Position </label>
            <input type="number" class="form-control" name="position">
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
