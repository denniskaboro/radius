<!--
////////////////////////////////////////////////////////////////////
// ESTE EJEMPLO SE DESCARGO DE www.tech-nico.com ///////////////////
// Creado por: Nicolas Daitsch. Guatrache. La Pampa ////////////////
// Contacto: administracion@tech-nico.com //////////////////////////
// RouterOS API: Grafica en tiempo real usando highcharts //////////
////////////////////////////////////////////////////////////////////
-->

<?php
include('reseller.php');
if (isset($_GET['ip'])) {
    $ip = $_GET['ip'];
    $sql = "SELECT * FROM nas WHERE nasname='$ip'";
    $res = mysqli_query($con, $sql);
    $f = mysqli_fetch_array($res);
    $user = $f['login_user'];
    $pass = $f['login_password'];
    $port = $f['login_port'];

}

if (isset($_GET['ppp'])) {
    $ppp = $_GET['ppp'];
    $u = "<pppoe-" . $ppp . ">";
    $dt = 'Download';
    $ut = 'Upload';
}
if (isset($_GET['phy'])) {
    $u = $_GET['phy'];
    $dt = 'Upload';
    $ut = 'Download';
}

?>
<!DOCTYPE HTML>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <title>Live Traffic</title>

    <script>
        var chart;

        function requestDatta() {
            $.ajax({
                url: '../graph/data.php?ip=<?php echo $ip; ?>&user=<?php echo $user; ?>&pass=<?php echo $pass; ?>&interface=<?php echo $u; ?>&rport=<?php echo $port; ?>',
                datatype: "json",
                success: function (data) {
                    var midata = JSON.parse(data);
                    if (midata.length > 0) {
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
                        document.getElementById("trafico").innerHTML = "<?php echo $dt; ?>: " + TX + " " + txs + " / <?php echo $ut; ?>: " + RX + " " + rxs;

                    } else {
                        document.getElementById("trafico").innerHTML = "- / -";
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    console.error("Status: " + textStatus + " request: " + XMLHttpRequest);
                    console.error("Error: " + errorThrown);
                }
            });
        }

        $(document).ready(function () {
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

</head>
<body>
<div id="interface">
    <h2>Total Traffic:</h2>
</div>
<div id="trafico"></div>
<script type="text/javascript" src="../graph/highchart/js/highcharts.js"></script>
<script type="text/javascript" src="../graph/highchart/js/themes/gray.js"></script>

<div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>

</body>
</html>


