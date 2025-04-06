<?php
include('clients.php');
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $sql = "SELECT * FROM clients WHERE username='$username'";
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    $f = mysqli_fetch_array($r);
    $full = $f['full_name'];
    $id = $f['supplier_id'];
    $sql = "SELECT * FROM radcheck WHERE attribute='Expiration' && username='$username'";
    $rs = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    $p = mysqli_fetch_array($rs);
    $exp = $p['value'];
    $pre = date(strtotime($exp));
    $dis = date(strtotime("+5 days"));
}

?>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <h3>Payment:</h3>

    <form method="POST" data-toggle="validator" action="payment.php" role="form">
        <div class="form-group col-sm-8 ">

            <label for="Username">Username:</label>
            <input type="text" class="form-control" name="username" value="<?php echo $_SESSION['username']; ?>"
                   readonly>
            <label for="Username">Full Name:</label>
            <input type="text" class="form-control" name="full" value="<?php echo $full; ?>" readonly>
            <label for="Username">Supervisor ID:</label>
            <input type="text" class="form-control" name="id" value="<?php echo $id; ?>" readonly>
            <div class="form-group">
                <label for="inputPack" class="control-label">Existing Package</label>
                <?php $sql = "SELECT groups.id, groups.groupname FROM groups INNER JOIN radusergroup ON groups.id=radusergroup.groupname WHERE radusergroup.username='$username'";
                $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
                $f = mysqli_fetch_array($r);
                $groupId = $f['id'];
                $groupName = $f['groupname']; ?>
                <input type="hidden" class="form-control" id="inputPack" name="package" value="<?php echo $groupId; ?>"
                       readonly>
                <input type="text" class="form-control" value="<?php echo $groupName; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="Package">Set New Package:</label>
                <select class="form-control" onchange="showAttr(this.value)">
                    <option>None</option>
                    <?php
                    $sql = "SELECT groups.groupname, groups.id as gid FROM groups INNER JOIN clients 
ON clients.supplier_id=groups.supplier_id WHERE clients.username='$username'";

                    $gr = mysqli_query($con, $sql);
                    while ($res = mysqli_fetch_array($gr)) {
                        $name = $res['groupname'];
                        $gid = $res['gid'];
                        echo "<option value='$gid'>" . $name . "</option>";
                    }

                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="inputSet">Enter Number e.g 7**** or 2547****: </label>
                <input type="text" id="num" name="number" class="form-control" required>
            </div>
            <button type="button" name="pay" id="payment" <?php if ($pre > $dis) {
                echo "disabled";
            } ?> onclick="Payment()" style="padding: 10px !important;" class="btn btn-danger">
                Payment with Mpesa
            </button>
    </form>
</div>
<script type="text/javascript">
    function showAttr(str) {
        if (str == 'None') {
            document.getElementById("inputPack").value = '<?php echo $exist_group; ?>';
        } else {
            document.getElementById("inputPack").value = str;
        }

    }
</script>
<script>
    function Payment() {
        document.getElementById('payment').innerText = "Loading...";
        var number = document.getElementById('num').value;
        var pack = document.getElementById('inputPack').value;
        if (number == '') {
            alert("You must have provide the mobile number.");
        } else {

            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
            }
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);
                    var data = JSON.parse(this.responseText);
                    if (data.status == "error") {
                        document.getElementById('payment').innerText = "Payment with Mpasa";
                        alert(data.message);
                    } else {
                        alert(data.message);
                        window.location.href = "my_info.php?OrderID=" + data.message;
                    }
                }
            }
            xmlhttp.open("GET", "payment.php?number=" + number + "&pack=" + pack, true);
            xmlhttp.send();
        }
    }
</script>
<script type="text/javascript" src="../component/validator.js"></script>
<script type="text/javascript" src="../component/validator.min.js"></script>

<?php include '../includes/footer.php'; ?>
