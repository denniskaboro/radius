<?php
include 'reseller.php';
$supid = $_SESSION['id'];
if (isset($_GET['val'])) {
    $id = $_GET['val'];
    $sql = "SELECT * FROM radacct  WHERE acctstoptime IS NULL && radacctid='$id' ORDER BY radacctid DESC";
    $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
    $f = mysqli_fetch_array($r);
    $session = $f['acctsessionid'];
    $name = $f['username'];
    $nas = $f['nasipaddress'];
    $myfile = fopen($_SERVER['DOCUMENT_ROOT'] . "/supplier/disconnect.txt", "wb");
    $txt = "Acct-Session-Id=" . $session . "\n";
    fwrite($myfile, $txt);
    $txt = "User-Name=" . $name . "\n";
    fwrite($myfile, $txt);
    $txt = "NAS-IP-Address=" . $nas . "\n";
    fwrite($myfile, $txt);
    fclose($myfile);
    shell_exec("cat disconnect.txt | radclient -x " . $nas . ":1700 disconnect ''PcL138536naS''");
    $sql = "UPDATE radacct SET  acctstoptime=NOW(), acctterminatecause='Stale-Session' WHERE
 AcctStopTime IS NULL && radacctid='$id'";
    $r = mysqli_query($con,$sql);
    if ($r) {
        echo "User has been disconnected...";
        $API->log($wh, $name . "  has been disconnected");
    }

}

?>
<h3>My Active User's</h3>
<table class="table table-bordered" id="btrc">
    <thead>
    <tr>
        <th>Username</th>
        <th>NAS IP Address</th>
        <th>NAS Port</th>
        <th>Start Time</th>
        <th>Service Type</th>
        <th>MAC Address</th>
        <th>Bind/Clear</th>
        <th>IP Address</th> <?php if($per=='Full' || $per=='Admin' || $per=='Write') {?>
        <th>Disconnect</th><?php }?>
    </tr>
    </thead>
    <tbody>
    <?php
    $usr = "SELECT * FROM clients WHERE supplier_id='$supid'";
    $res = mysqli_query($con,$usr);
    $client = mysqli_num_rows($res);
    while ($g = mysqli_fetch_array($res)) {
        $username = $g['username'];

        $sql = "SELECT * FROM radacct  WHERE acctstoptime IS NULL && username='$username' ORDER BY radacctid DESC";
        $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
        $f = mysqli_fetch_array($r);
        if ($f) {
            ?>
            <tr>

                <td><a href="reseller_his.php?name=<?php echo $f['username']; ?>"
                       role="button" style="color:#87b5dc;font-size:16px;"><?php echo $f['username']; ?></a></td>
                <td><?php echo $f['nasipaddress']; ?></td>
                <td><?php echo $f['nasportid']; ?></td>
                <td><?php echo $f['acctstarttime']; ?></td>
                <td><?php echo $f['calledstationid']; ?></td>
                <td><?php echo $f['callingstationid']; ?></td>
                <?php
                $test = "SELECT * FROM radcheck WHERE username='$username' && attribute='Calling-Station-Id'";
                $check = mysqli_query($con,$test);
                $row = mysqli_num_rows($check);
                if ($row > 0) {
                    $fi = mysqli_fetch_array($check);
                    $id = $fi['id'];
                    ?>
                    <td>
                    <button class="btn btn-danger" id="<?php echo $id; ?>"
                            value="<?php echo $id; ?>" onclick="Delete(this.value)">
                        <span class="glyphicon glyphicon-trash"></span></button></td><?php
                } else { ?>
                    <td>
                        <button class="btn btn-primary" id="<?php echo $f['callingstationid'] ?>"
                                value="<?php echo $f['username']; ?>" onclick="Bind(this.id)">
                            <i class="icon-edit"><span class="glyphicon glyphicon-add"></span></i>Bind
                        </button>
                    </td>
                <?php }

                ?>
                <td><?php echo $f['framedipaddress']; ?></td>
                <?php if($per=='Full' || $per=='Admin' || $per=='Write') {?>
                <td><a class="btn btn-danger" href="active_users.php?val=<?php echo $f['radacctid'] ?>">
                        <i class="icon-edit"><span class="glyphicon glyphicon-remove"></span></i></a></td>
                <?php }?>
            </tr>
        <?php }
    } ?>
    </tbody>
</table>
<script>
    function Delete(str) {
        var r = confirm("Confirm to Delete " + str + "?");
        if (r == true) {
            document.getElementById(str).innerHTML = "";
            if (str == "") {
                document.getElementById(str).innerHTML = "";
                return;
            }
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else { // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("str").innerHTML = this.responseText;
                }
            }
            xmlhttp.open("GET", "disable.php?id=" + str, true);
            xmlhttp.send();
        }
    }
</script>
<script>
    function Bind(str) {
        var nam = document.getElementById(str).value;
        var r = confirm("Confirm to Bind This Mac " + str + "?");
        if (r == true) {
            document.getElementById(str).innerHTML = "";
            if (str == "") {
                document.getElementById(str).innerHTML = "";
                return;
            }
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else { // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("str").innerHTML = this.responseText;
                }
            }
            xmlhttp.open("GET", "disable.php?uname=" + nam + "&bind=" + str, true);
            xmlhttp.send();
        }
    }
</script>
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
