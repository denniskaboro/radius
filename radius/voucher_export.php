<?php
include 'includes/header.php';
if (isset($_GET['name'])) {
    $voucher = $_GET['name'];
}
$sq = "SELECT * FROM voucher WHERE voucher_name='$voucher'";
$rr = mysqli_query($con, $sq);
$f = mysqli_fetch_array($rr);
$limit = $f['total_limit'];
$duration = $f['expiration'];
$amount = $f['amount'];
$exp = $f['deadline'];
?>
<button class="btn btn-success" onclick="CreatePDFfromHTML()" style="width:70px;height:30px;padding: 5px;">Print
</button>
<div class="col-lg-12 col-md-12 col-sm-12" id="pr">
    <?php
    $sql = "SELECT * FROM radusergroup WHERE groupname='$voucher' ORDER BY id ASC ";
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    while ($j = mysqli_fetch_array($r)) {
        $user = $j['username'];
        ?>
        <div class="col-lg-2 col-md-3 col-sm-3"
             style="border:1px solid #0d8d5c !important;color: black;margin-top: 10px!important; margin-left: 8px !important; background: rgb(248,241,255);">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <img src="site/<?php echo $site_logo; ?>" width="100%" height="60px">
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12"
                 style="border-bottom:2px solid black;border-top:2px solid black;margin: 0px!important;padding: 0px !important;">
                <h5>SUBSCRIPTION</h5>
                <h5 style="font-weight: bold;">DURATION: <?php if ($duration == null) {
                        echo "UNLIMITED";
                    } else {
                        echo $duration;
                    } ?></h5>
                <h6 style="font-weight: bold;">DOWNLOAD: <?php if ($limit == null) {
                        echo "UNLIMITED";
                    } else {
                        echo $limit;
                    } ?></h6>

            </div>
            <div class="col-lg-12 col-md-12 col-sm-12" style="margin: 0px!important;padding: 0px !important;">
                <div class="col-lg-6 col-md-6 col-sm-6" style="margin: 0px!important;padding: 0px !important;">
                    <h5>PASSWORD</h5>
                    <h5 style="font-weight: bold;"><?php echo $user; ?></h5>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <h5>PRICE</h5>
                    <h5 style="font-weight: bold;"><?php echo $amount . ".00"; ?></h5>
                </div>
            </div>
        </div>
    <?php } ?>
    <script>
        function CreatePDFfromHTML() {
            var HTML_Width = $("#pr").width();
            var HTML_Height = $("#pr").height();
            var top_left_margin = 15;
            var PDF_Width = HTML_Width + (top_left_margin * 2);
            var PDF_Height = (PDF_Width * 1.5) + (top_left_margin * 2);
            var canvas_image_width = HTML_Width;
            var canvas_image_height = HTML_Height;

            var totalPDFPages = Math.ceil(HTML_Height / PDF_Height) - 1;

            html2canvas($("#pr")[0]).then(function (canvas) {
                var imgData = canvas.toDataURL("image/jpeg", 1.0);
                var pdf = new jsPDF('p', 'pt', [PDF_Width, PDF_Height]);
                pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin, canvas_image_width, canvas_image_height);
                for (var i = 1; i <= totalPDFPages; i++) {
                    pdf.addPage(PDF_Width, PDF_Height);
                    pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height * i) + (top_left_margin * 4), canvas_image_width, canvas_image_height);
                }
                pdf.save("Voucher.pdf");
                $("#pr").hide();
            });
        }
    </script>
    <script type="text/javascript" src="component/js/jspdf.min.js"></script>
    <script type="text/javascript" src="component/js/html2canvas.js"></script>
</div>


