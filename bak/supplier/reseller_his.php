<?php
/**
 * Created by PhpStorm.
 * User: jeet
 * Date: 5/24/2019
 * Time: 12:32 AM
 */
include('reseller.php');
include('../config/data.php');
?>
    <div class="col-md-12">
        <?php
        if(isset($_GET['name'])) {
            $name = $_GET['name'];
            $q="SELECT nasipaddress FROM radacct WHERE username='$name' && acctstoptime IS NULL ";
            $r=mysqli_query($con,$q);
            $rr=mysqli_fetch_array($r);
            if(mysqli_num_rows($r)>0){
                $ip=$rr['nasipaddress'];
                $u = "<pppoe-" . $name . ">";
		$sql = "SELECT * FROM nas WHERE nasname='$ip'";
$res = mysqli_query($con,$sql);
$f = mysqli_fetch_array($res);
$user = $f['login_user'];
$pass = $f['login_password'];
$port=$f['login_port'];

?>
<script>
        var chart;
        function requestDatta() {
                $.ajax({
                        url: '../graph/data.php?ip=<?php echo $ip; ?>&user=<?php echo $user; ?>&pass=<?php echo $pass; ?>&interface=<?php echo $u; ?>&rport=<?php echo $port; ?>',
                        datatype: "json",
                        success: function(data) {
                                var midata = JSON.parse(data);
                                if( midata.length > 0 ) {
                                        var TX = midata[0].data;
                                        var txs = midata[2].txs;
                                        var RX = midata[1].data;
                                        var rxs = midata[2].rxs;
                                        var up = parseInt(TX);
                                        var dw = parseInt(RX);
                                        var x = (new Date()).getTime();
                                        shift = chart.series[0].data.length > 19;
                                        chart.series[0].addPoint([x, up], true, shift);
                                        chart.series[1].addPoint([x, dw], true, shift);
                                        document.getElementById("trafico").innerHTML = "Download: " + TX + " " + txs + " / Upload: " + RX + " " + rxs;
					
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
                                    text: 'Traffic',
                                    margin: 80
                                }
                            },
                            series: [{
                                name: 'TX',
                                data: []
                            }, {
                                name: 'RX',
                                data: []
                            }]
                        });
                    });
                </script>
                <div id="interface">
                    <h2>Total Traffic:</h2>
                </div>
                <div id="trafico"></div>
                <script type="text/javascript" src="../graph/highchart/js/highcharts.js"></script>
                <script type="text/javascript" src="../graph/highchart/js/themes/gray.js"></script>

                <div id="container" style="min-width: 300px; height: 300px; margin: 0 auto"></div>

            <?php } }?>
    </div>

    <h3>Usage History</h3>
    <div class="col-lg-12 col-md-10">
        <form enctype="multipart/form-data">
            <select onchange="location = this.options[this.selectedIndex].value;">
                <option selected="" value="reseller_his.php?page=1&amp;entries=15&amp;name=<?php echo $name; ?>">Select</option>
                <option value="reseller_his.php?page=1&amp;entries=25&amp;name=<?php echo $name; ?>">25</option>
                <option value="reseller_his.php?page=1&amp;entries=50&amp;name=<?php echo $name; ?>">50</option>
                <option value="reseller_his.php?page=1&amp;entries=100&amp;name=<?php echo $name; ?>">100</option>
                <option value="reseller_his.php?page=1&amp;entries=250&amp;name=<?php echo $name; ?>">250</option>
                <option value="reseller_his.php?page=1&amp;entries=500&amp;name=<?php echo $name; ?>">500</option>
                <option value="reseller_his.php?page=1&amp;entries=All&amp;name=<?php echo $name; ?>">ALL</option>
            </select>
        </form>
        <h3>Usage History</h3>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Start Time</th>
                <th>Stop Time</th>
                <th>MAC</th>
                <th>Terminate Cause</th>
                <th>Online Time</th>
                <th>Upload</th>
                <th>Download</th>

            </tr>
            </thead>
            <tbody>
            <?php

            $name = $_GET['name'];
            $tup = 0;
            $tdown = 0;
            if (isset($_GET['page']) && !empty($_GET['page'])) {
                $showRecordPerPage = $_GET['entries'];
                if($showRecordPerPage=="All"){
                    $showRecordPerPage=1000;
                }
                $currentPage = $_GET['page'];
                $startFrom = ($currentPage * $showRecordPerPage) - $showRecordPerPage;
                $totalEmpSQL = "SELECT * FROM radacct WHERE username='$name'";
                $sql = "SELECT * FROM radacct WHERE username='$name' ORDER BY radacctid DESC  LIMIT $startFrom, $showRecordPerPage";
            } else {
                $currentPage = 1;
                $showRecordPerPage = 15;
                $startFrom = ($currentPage * $showRecordPerPage) - $showRecordPerPage;
                $totalEmpSQL = "SELECT * FROM radacct WHERE username='$name'";
                $sql = "SELECT * FROM radacct WHERE username='$name' ORDER BY radacctid DESC  LIMIT $startFrom, $showRecordPerPage";
            }
            $row = mysqli_query($con,$totalEmpSQL) or $msg = mysqli_error($con);
            $totalEmployee = mysqli_num_rows($row);
            $lastPage = ceil($totalEmployee / $showRecordPerPage);
            $firstPage = 1;
            $nextPage = $currentPage + 1;
            $previousPage = $currentPage - 1;
            $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
            while ($j = mysqli_fetch_array($r)){
                $start = $j['acctstarttime'];
                $stop = $j['acctstoptime'];
                $terminate = $j['acctterminatecause'];
                $online = $j['acctsessiontime'];
                $online = gmdate("H:i:s", $online);
                $up = $j['acctinputoctets'];
                $down = $j['acctoutputoctets'];
                $tup = $tup + $up;
                $tdown = $tdown + $down;
                $umb = $up / (1024 * 1024);
                $umb = sprintf("%.2f", $umb);
                $dmb = $down / (1024 * 1024);
                $dmb = sprintf("%.2f", $dmb);

                ?>

                <tr>
                    <td><?php echo $start; ?></td>
                    <td><?php echo $stop; ?></td>
                    <td><?php echo $j['callingstationid']; ?></td>
                    <td><?php echo $terminate; ?></td>
                    <td><?php echo $online; ?></td>
                    <td><?php echo $umb; ?> MB</td>
                    <td><?php echo $dmb; ?> MB</td>
                </tr>
            <?php }
            $totalup = $tup / (1024 * 1024 * 1024);
            $totalup = sprintf("%.2f", $totalup);
            $totaldown = $tdown / (1024 * 1024 * 1024);
            $totaldown = sprintf("%.2f", $totaldown);

            echo "<h4>Total Upload: " . $totalup . "GB</h4>";
            echo "<h4>Total Download: " . $totaldown . "GB</h4>";
            ?>
            </tbody>
        </table>
        <?php echo "Showing " . $showRecordPerPage * ($currentPage - 1) . " to " . $showRecordPerPage * $currentPage . " of " . $totalEmployee ?>
        <nav aria-label="Page navigation">
            <ul class="pagination"  style="height:40px;">
                <?php $lastPart = $lastPage - 4;
                $compare = $currentPage;
                if ($currentPage != $firstPage) { ?>
                    <li class="page-item">
                        <a class="page-link"
                           href="?name=<?php echo $name;?>&page=<?php echo $firstPage . "&entries=" . $showRecordPerPage ?>"
                           tabindex="-1" aria-label="Previous" style="width:50px;height:40px;padding:10px;border:1px solid;background:#ddd;color:black;">
                            <span aria-hidden="true">First</span>
                        </a>
                    </li>
                    <li class="page-item"><a class="page-link"
                                             href="?page=<?php echo ($currentPage - 1) . "&entries=" . $showRecordPerPage."&name=".$name; ?>" style="width:50px;border:1px solid;height:40px;padding:10px;background:#ddd;color:black;"><<
                        </a></li>
                <?php } ?>
                <?php if ($lastPage < 5) {
                    for ($currentPage = 1; $currentPage <= $lastPage; $currentPage++) {
                        echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?page=$currentPage&entries=$showRecordPerPage&name=$name\" style=\"width:50px;border:1px solid;height:40px;padding:10px;background:#ddd;color:black;\">$currentPage</a></li>";
                    }
                    ?>
                <?php } else if ($currentPage > $lastPart) {
                    for ($currentPage = $lastPart; $currentPage <= $lastPage; $currentPage++) {
                        echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?page=$currentPage&entries=$showRecordPerPage&name=$name\" style=\"width:50px;border:1px solid;height:40px;padding:10px;background:#ddd;color:black;\">$currentPage</a></li>";
                    }
                } else {
                    $ext = $currentPage + 4;
                    for ($currentPage = $currentPage; $currentPage <= $ext; $currentPage++) {
                        echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?page=$currentPage&entries=$showRecordPerPage&name=$name\" style=\"width:50px;border:1px solid;height:40px;padding:10px;background:#ddd;color:black;\">$currentPage</a></li>";
                    } ?>
                <?php }
                if ($compare != $lastPage) { ?>
                    <li class="page-item"><a class="page-link"
                                             href="?page=<?php echo ($compare + 1) . "&entries=" . $showRecordPerPage."&name=".$name; ?>" style="width:50px;border:1px solid;height:40px;padding:10px;background:#ddd;color:black;">
                            >> </a></li>
                    <li class="page-item">
                        <a class="page-link"
                           href="?page=<?php echo $lastPage . "&entries=" . $showRecordPerPage."&name=".$name;?>"
                           aria-label="Next" style="width:50px;height:40px;padding:10px;border:1px solid;background:#ddd;color:black;">
                            <span aria-hidden="true">Last</span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
    </div>
<?php include '../includes/footer.php'; ?>
