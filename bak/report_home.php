<?php
include('config/data.php');
include("includes/header.php");
function reportHome($tt,$con){
    $sql = "SELECT * FROM `clients` WHERE `create_date` LIKE '%$tt%'";
    $cl = mysqli_query($con,$sql);
    $total_user = mysqli_num_rows($cl);
    $total_bw = 0;
    $t_price = 0;
    $ac_total = 0;

    while ($f = mysqli_fetch_array($cl)) {
        $uname = $f['username'];

        $ac_qu = "SELECT * FROM radcheck WHERE attribute='Expiration' && username='$uname'";
        $acqu = mysqli_query($con,$ac_qu);
        $client = mysqli_fetch_array($acqu);
        $da = $client['value'];
        $ex_time=strtotime($da);
        $today=time();
        if($ex_time>=$today){
            $ac_total=$ac_total+1;
            $sqll = "SELECT groups.price, groups.speed FROM `groups` INNER  JOIN radusergroup ON groups.id=radusergroup.groupname WHERE `username`='$uname'";
            $quu = mysqli_query($con,$sqll);
            $g = mysqli_fetch_array($quu);
            $price = $g['price'];
            $speed = $g['speed'];
            $t_price = $t_price + $price;
            $cx = strpos($speed, 'M');
            if ($cx) {
                $local = substr($speed, 0, $cx);
                $total_bw = $total_bw + $local;
            }

        }

    }
   $res=array("total_user"=>$total_user,"active_user"=>$ac_total,"bw"=>$total_bw,"total_price"=>$t_price);
    return json_encode($res);
}

?>

<div class="col-lg-12">
    <h2>Home User Report (Supervisor)</h2>
    <table class="table table-bordered " id="btrc">
        <thead>
        <tr style="background-color: rgba(43,66,84,0.92); color: #ffffff; font-size:16px;">
            <th>Month</th>
            <th>Supervisor</th>
            <th>Total User</th>
            <th>Active User</th>
            <th>BW (Active User)</th>
            <th>New MRC (Active User)</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $g_ac_total=0;
        $g_bw_total=0;
        $g_mrc_total=0;
        $g_total_all=0;
        $total_sup=0;
        $time = strtotime(date("Y-m",strtotime('-1 month')));
        $cur=strtotime(date("Y-m"));
        while ($time < $cur) {
            $tt = date("Y-m", strtotime("+1 month", $time));
            $time = strtotime($tt);
            ?>
            <tr>
                <td><?php echo $tt; ?></td>
                <td><?php
                    $sql = "SELECT * FROM supplier WHERE create_date LIKE '%$tt%'";
                    $qu = mysqli_query($con,$sql);
                    $sup = mysqli_num_rows($qu);
                    echo $sup;
                    $total_sup =$total_sup+ $sup;
                    ?></td>
                <td><?php
                    $js=reportHome($tt,$con);
                    $result=json_decode($js);
                    echo $result->total_user;
                    $total_user=$result->total_user;
                    $active_user=$result->active_user;
                    $bw=$result->bw;
                    $t_price=$result->total_price;
                    $g_total_all=$total_user+$g_total_all;
                    $g_ac_total=$active_user+$g_ac_total;
                    $g_bw_total=$bw+$g_bw_total;
                    $g_mrc_total=$t_price+$g_mrc_total;
                    ?></td>
                <td><?php
                    echo $active_user;

                    ?></td>
                <td><?php echo $bw; ?></td>
                <td><?php echo $t_price; ?></td>
            </tr>

        <?php } ?>
	</tbody>
        <tfoot>
        <tr>
            <td style="background-color: rgba(24,43,84,0.92); color: #ffffff; font-size:16px;">TOTAL</td>
            <td style="background-color: rgba(24,43,84,0.92); color: #ffffff; font-size:16px;"><?php echo $total_sup; ?></td>
            <td style="background-color: rgba(24,43,84,0.92); color: #ffffff; font-size:16px;"><?php echo $g_total_all; ?></td>
            <td style="background-color: rgba(24,43,84,0.92); color: #ffffff; font-size:16px;"><?php echo $g_ac_total; ?></td>
            <td style="background-color: rgba(24,43,84,0.92); color: #ffffff; font-size:16px;"><?php echo $g_bw_total; ?> Mbps</td>
            <td style="background-color: rgba(24,43,84,0.92); color: #ffffff; font-size:16px;"><?php echo $g_mrc_total; ?> Tk</td>

        </tr>
        </tfoot>

    </table>

    <link rel="stylesheet" href="component/js/export/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="component/js/export/buttons.dataTables.min.css"/>
    <script type="text/javascript" src="component/js/export/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="component/js/export/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="component/js/export/jszip.min.js"></script>
    <script type="text/javascript" src="component/js/export/pdfmake.min.js"></script>
    <script type="text/javascript" src="component/js/export/vfs_fonts.js"></script>
    <script type="text/javascript" src="component/js/export/buttons.html5.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#btrc').DataTable({
                dom: 'Bfrtip',
                pageLength: 25,
                buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    className: 'exportExcel',
                    filename: 'report_home',
                    exportOptions: {modifier: {page: 'all'}}
                },
                    {
                        extend: 'csv',
                        text: 'CSV',
                        className: 'exportExcel',
                        filename: 'report_home',
                        exportOptions: {modifier: {page: 'all'}}
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        className: 'exportExcel',
                        filename: 'report_home',
                        exportOptions: {modifier: {page: 'all'}}
                    }]

            });

        });
    </script>
</div>  <!-- end of col-lg-10 -->

<?php include 'includes/footer.php'; ?>
