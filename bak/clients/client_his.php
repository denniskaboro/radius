<?php
/**
 * Created by PhpStorm.
 * User: arman
 * Date: 5/24/2019
 * Time: 12:32 AM
 */
include('clients.php');
include('../config/data.php');
?>    <h3>Usage History</h3>
    <div class="col-md-12">
        <?php
        if(isset($_SESSION['username'])) {
            $name = $_SESSION['username'];
            $q="SELECT nasipaddress FROM radacct WHERE username='$name' && acctstoptime IS NULL ";
            $r=mysqli_query($con,$q);
            $rr=mysqli_fetch_array($r);
            if(mysqli_num_rows($r)>0){
                $ip=$rr['nasipaddress'];
                $u = "<pppoe-" . $name . ">";
                $user = 'api';
                $pass = 'PcL138536naS';

                ?>
                <script>
                    var chart;
                    function requestDatta() {
                        $.ajax({
                            url: '../graph/data.php?ip=<?php echo $ip; ?>&user=<?php echo $user; ?>&pass=<?php echo $pass; ?>&interface=<?php echo $u; ?>',
                            datatype: "json",
                            success: function(data) {
                                var midata = JSON.parse(data);
                                if( midata.length > 0 ) {
                                    var TX=midata[0].data;
                                    var RX=midata[1].data;
                                    var up=parseInt(TX);
                                    var dw=parseInt(RX);
                                    var x = (new Date()).getTime();
                                    shift=chart.series[0].data.length > 19;
                                    chart.series[0].addPoint([x, up], true, shift);
                                    chart.series[1].addPoint([x, dw], true, shift);
                                    document.getElementById("trafico").innerHTML="Download: " + TX + "Kbps / Upload: " + RX +"Kbps" ;
                                }else{
                                    document.getElementById("trafico").innerHTML="- / -";
                                }
                            },
                            error: function(XMLHttpRequest, textStatus, errorThrown) {
                                console.error("Status: " + textStatus + " request: " + XMLHttpRequest); console.error("Error: " + errorThrown);
                            }
                        });
                    }

                    $(document).ready(function() {
                        Highcharts.setOptions({
                            global: {
                                useUTC: false
                            }
                        });


                        chart = new Highcharts.Chart({
                            chart: {
                                renderTo: 'container',
                                animation: Highcharts.svg,
                                type: 'spline',
                                events: {
                                    load: function () {
                                        setInterval(function () {
                                            requestDatta(document.getElementById("interface").value);
                                        }, 1000);
                                    }
                                }
                            },
                            title: {
                                text: 'Monitoring'
                            },
                            xAxis: {
                                type: 'datetime',
                                tickPixelInterval: 150,
                                maxZoom: 20 * 1000
                            },
                            yAxis: {
                                minPadding: 0.2,
                                maxPadding: 0.2,
                                title: {
                                    text: 'Current Traffic',
                                    margin: 80
                                }
                            },
                            series: [{
                                name: 'Upload',
                                data: []
                            }, {
                                name: 'Download',
                                data: []
                            }]
                        });
                    });
                </script>
                <div id="interface">
                    <h2>Current Bandwidth:</h2>
                </div>
                <div id="trafico"></div>
                <script type="text/javascript" src="../graph/highchart/js/highcharts.js"></script>
                <script type="text/javascript" src="../graph/highchart/js/themes/gray.js"></script>

                <div id="container" style="min-width: 300px; height: 300px; margin: 0 auto"></div>

            <?php } }?>
    </div>
    <table class="table table-bordered" id="btrc">
        <thead>
        <tr>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Online Time</th>
            <th>Upload</th>
            <th>Download</th>

        </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT * FROM radacct WHERE username='$name' ORDER BY radacctid DESC ";
        $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
        while ($j = mysqli_fetch_array($r)) {
            $start = $j['acctstarttime'];
            $stop = $j['acctstoptime'];
            $online = $j['acctsessiontime'];
            $online = gmdate("H:i:s", $online);
            $up = $j['acctinputoctets'];
            $down = $j['acctoutputoctets'];
            $umb = $up / (1024 * 1024);
            $umb = sprintf("%.2f", $umb);
            $dmb = $down / (1024 * 1024);
            $dmb = sprintf("%.2f", $dmb);

            ?>

            <tr>
                <td><?php echo $start; ?></td>
                <td><?php echo $stop; ?></td>
                <td><?php echo $online; ?></td>
                <td><?php echo $umb; ?> MB</td>
                <td><?php echo $dmb; ?> MB</td>
            </tr>
        <?php }
        ?>
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