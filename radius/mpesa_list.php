<?php
include 'includes/header.php';
include('config/data.php');
$per = $_SESSION['per'];
//For Delete Attribute
if (isset($_GET['del'])) {
    if ($per == "Full" || $per == "Admin") {
        $id = $_GET['del'];
        $sq = "DELETE FROM mpesa WHERE id='$id'  ";
        $res = mysqli_query($con, $sq);
        echo "Payment Gateway Deleted Successfully...";

    } else {
        echo "<h4 style='color: coral ;'>You have no permission...</h4>";
    }
}
?>
<div style="float: right;"><a href="mpesa.php" class="btn btn-info btn-lg">
        <span class="glyphicon glyphicon-plus"></span> Add
    </a></div>
<div class="table-responsive">
    <table class="table table-bordered" id="btrc">
        <h4>Ads List:</h4>
        <thead>
        <tr>
            <th>Customer Key</th>
            <th>Customer Secret</th>
            <th>Shortcode</th>
            <th>Success URL</th>
            <th>Reference</th>
            <th>Payment URL</th>
            <th>Token URL</th>
            <th>Mode</th>
            <th>Enable/Disable</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody>
        <?php

        $sql = "SELECT * FROM mpesa ";
        $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
        while ($f = mysqli_fetch_array($r)) {
            $idd = $f['id'];
            $type = $f['status'];
            ?>
            <tr>
                <td><?php echo $f['customer_key']; ?></td>
                <td><?php echo $f['customer_key']; ?></td>
                <td><?php echo $f['shortcode']; ?></td>
                <td><?php echo $f['success']; ?></td>
                <td><?php echo $f['refer']; ?></td>
                <td><?php echo $f['payment_url']; ?></td>
                <td><?php echo $f['token_url']; ?></td>
                <td><?php echo $f['mode']; ?></td>
                <td>
                    <button class="btn btn-success" id="enable<?php echo $f['id']; ?>"
                        <?php if ($type == "1") {
                            echo "disabled=\"true\"";
                        } ?> onclick="enableBtn('<?php echo $idd; ?>','1')">
                        <span class="glyphicon glyphicon-ok"></span></button>
                    <button class="btn btn-danger" id="disable<?php echo $f['id']; ?>"
                        <?php if ($type == "0") {
                            echo "disabled=\"true\"";
                        } ?> onclick="disableBtn('<?php echo $idd; ?>','0')">
                        <span class="glyphicon glyphicon-off"></span></button>
                </td>
                <td><a class="btn btn-danger" id="<?php echo $idd; ?>" onclick="myFunction('<?php echo $idd; ?>')"
                       href="">
                        <i class="icon-edit"><span class="glyphicon glyphicon-trash"></span></i></a></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<script type="text/javascript">
    function myFunction(id) {
        var r = confirm("Confirm to do this?");
        if (r == true) {
            document.getElementById(id).href = "mpesa_list.php?del=" + id;
        } else {
            document.getElementById(id).href = "mpesa_list.php";
        }
    }

</script>

<script>
    function enableBtn(id, val) {
        document.getElementById("disable" + id).disabled = false;
        document.getElementById("enable" + id).disabled = true;
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
        xmlhttp.open("GET", "disable.php?mpesa=" + id + "&status=" + val, true);
        xmlhttp.send();
    }

    function disableBtn(id, val) {
        document.getElementById("disable" + id).disabled = true;
        document.getElementById("enable" + id).disabled = false;
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
        xmlhttp.open("GET", "disable.php?mpesa=" + id + "&status=" + val, true);
        xmlhttp.send();
    }
</script>
<link rel="stylesheet" type="text/css" href="component/jquery.dataTables.min.css"/>
<script type="text/javascript" src="component/js/export/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#btrc').DataTable({
            pageLength: 15,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]

        });

    });
</script>
<?php
include 'includes/footer.php'; ?>
