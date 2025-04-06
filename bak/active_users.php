<?php
include 'includes/header.php';
$per = $_SESSION['per'];
include "config/data.php";

?>
<div id="last"></div>
<div class="col-lg-12">
<button class="btn-warning" id="dup" style="color: #ffffff; padding: 5px;font-size:14px;" onclick='Dup(this.id)'>Clear</button>
<h3>Active User's</h3>
<div class="table-responsive">
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
        <th>IP Address</th>
        <th>Disconnect</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $sql = "SELECT * FROM radacct  WHERE acctstoptime IS NULL ";
    $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
    while ($f = mysqli_fetch_array($r)) {
        $name = $f['username'];

        ?>
        <tr>
	    <td><a style=" color: #fae2f4;" href="user_statistics.php?name=<?php echo $f['username']; ?>"
                   role="button" style="color: #ffffff;font-size:16px;"><?php echo $f['username']; ?></a></td>
            <td><?php echo $f['nasipaddress']; ?></td>
            <td><?php echo $f['nasportid']; ?></td>
            <td><?php echo $f['acctstarttime']; ?></td>
            <td><?php echo $f['calledstationid']; ?></td>
            <td><?php echo $f['callingstationid']; ?></td>
            <?php
            $test = "SELECT * FROM radcheck WHERE username='$name' && attribute='Calling-Station-Id'";
            $check = mysqli_query($con,$test);
            $row = mysqli_num_rows($check);
            if ($row > 0) {
                $fi = mysqli_fetch_array($check);
                $id=$fi['id'];
                ?>
                <td><button class="btn btn-danger" id="<?php echo $id; ?>"
            value="<?php echo $id; ?>" onclick="Delete(this.value)">
        <span class="glyphicon glyphicon-trash"></span></button></td><?php
            } else { ?>
                <td><button class="btn btn-primary" id="<?php echo $f['callingstationid'] ?>" value="<?php echo $f['username']; ?>" onclick="Bind(this.id)">
                        <i class="icon-edit"><span class="glyphicon glyphicon-add"></span></i>Bind</button></td>
            <?php }

            ?>

            <td><?php echo $f['framedipaddress']; ?></td>
            <td><button class="btn btn-danger" id=<?php echo $f['radacctid'] ?>" onclick="DisConnect(this.id)">
                    <i class="icon-edit"><span class="glyphicon glyphicon-remove"></span></i></button></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
</div>
</div>
<script>
    function DisConnect(str) {
        var r = confirm("Confirm to Disconnect this User?");
        if (r == true) {
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
                 alert(this.responseText);
		}
            }
            xmlhttp.open("GET", "clear.php?disconnect=" + str, true);
            xmlhttp.send();
        }
    }
</script>

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
                 alert(this.responseText);
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
                 alert(this.responseText);
		}
            }
            xmlhttp.open("GET", "disable.php?uname=" + nam + "&bind=" + str, true);
            xmlhttp.send();
        }
    }
</script>

<script>
    function Dup(str) {
        var r = confirm("Confirm to Clear ?");
        if (r == true) {

            if (str == "") {
                document.getElementById("last").innerHTML = "";
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
                    alert(this.responseText);
                }
            }
            xmlhttp.open("GET", "duplicate.php?all=" + str, true);
            xmlhttp.send();
        }
        else {
            document.getElementById("last").href = "active_users.php";
        }

    }
</script>
<link rel="stylesheet" type="text/css" href="component/jquery.dataTables.min.css"/>
<script type="text/javascript" src="component/js/export/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#btrc').DataTable({
            pageLength: 15,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
	    order: [[ 6, "desc" ]]
        });

    });
</script>

<?php include 'includes/footer.php'; ?>
