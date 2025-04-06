<?php
include 'includes/header.php';
include('config/data.php');
$per = $_SESSION['per'];
//For Delete Supplier
if (isset($_GET['id'])) {
    if ($per == "Admin") {
        $id = $_GET['id'];
        $name = "SELECT * FROM clients WHERE supplier_id='$id'";
        $na = mysqli_query($con,$name);
        while ($n = mysqli_fetch_array($na)) {
            $username[] = $n['username'];
        }
        foreach ($username as $usr) {
            $table = ["radcheck", "radreply", "radacct", "radusergroup"];
            foreach ($table as $tab) {
                $sq = "DELETE FROM $tab WHERE username='$usr'";
                $res = mysqli_query($con,$sq);
            }
        }
            $sq = "DELETE FROM supplier WHERE supplier_id='$id'  ";
            $res = mysqli_query($con,$sq);
	$API->log($wh,"Supplier ".$id." Deleted" );
        
    } else {
        echo "<h4 style='color: coral ;'>You have no permission...</h4>";
    }
}

?>
<div id="last"></div>
<table class="table table-bordered" id="btrc">
    <h3>Supervisor List</h3>
    <thead>
    <tr>
        <th>Supervisor Name</th>
        <th>Supervisor ID</th>
        <th>Username</th>
        <th>Total Users</th>
        <th>Session Clear</th>
        <th>Mobile</th>
        <th>Address</th>
        <th>POP Location</th>
        <th>Create Date</th>
        <th>Balance(Availabe)</th>
        <th>Edit</th>
        <th>Delete</th>

    </tr>
    </thead>
    <tbody>
    <?php
	if(isset($_GET['sort'])){
		$sup_id=$_GET['sort'];
		$sql = "SELECT * FROM supplier WHERE supplier_id='$sup_id'";
	}else{
		$sql = "SELECT * FROM supplier ";
	}
    
    $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);

    while ($f = mysqli_fetch_array($r)) {
        $sup_id = $f['supplier_id'];
	$uid=$f['id'];
        ?>
        <tr>

            <td><a style=" color: #e2b9db;" href="supplier_statis.php?id=<?php echo $sup_id; ?>"
                   role="button"><?php echo $f['full_name']; ?></a></td>
            <td><a style=" color: #e2b9db;" href="nas_list.php?sort=<?php echo $sup_id; ?>"
                   role="button" style="color: #ffffff;font-size:16px;"><?php echo $sup_id; ?></a>
            <td><?php echo $f['username']; ?></td>
            <?php $cou = "SELECT * FROM clients WHERE supplier_id='$sup_id'";
            $rc = mysqli_query($con,$cou);
            $tu = mysqli_num_rows($rc);
            ?>
            <td><a style=" color: #e2b9db;" href="total_user.php?id=<?php echo $sup_id; ?>"
                   role="button"><?php echo $tu; ?></a></td>
            <td>
                <button class="btn-warning" id="<?php echo $sup_id; ?>" style="color: #ffffff;" onclick="ClearSession(this.id)">
                    All
                </button>

            </td>
            <td><?php echo $f['mobile']; ?></td>
            <td><?php echo $f['address']; ?></td>
            <td><?php echo $f['pop']; ?></td>
            <td><?php echo $f['create_date']; ?></td>
            <?php
            $sql = "SELECT SUM(debit) as de, SUM(credit) as cr FROM transaction WHERE  supplier_id='$sup_id'";
            $j = mysqli_query($con,$sql) or $msg = mysqli_error($con);
            $ac = mysqli_fetch_array($j);
            $deposit = $ac['de'];
            $credit = $ac['cr'];
            $total = $deposit - $credit;

            ?>
            <td><?php echo $total; ?></td>
            <td><a class="btn" href="sup_edit.php?id=<?php echo $sup_id; ?>" style="color: #ffffff;">
                    <i class="icon-edit"><span class="glyphicon glyphicon-pencil"></span></i></a></td>
            <td><a class="btn btn-danger" href="supplier_list.php" onClick="showMessage('<?php echo $sup_id;?>','<?php echo $uid;?>')"
                   id="<?php echo $uid; ?>">
                    <i class="icon-edit"><span class="glyphicon glyphicon-trash"></span></i></a></td>

        </tr>

    <?php } ?>
    </tbody>

</table>
<script>
    function ClearSession(str) {
	var r = confirm("Confirm to Clear for this supplier "+str);
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
                document.getElementById("last").innerHTML = this.responseText;
            }
        }
        xmlhttp.open("GET", "clear.php?sup_id=" + str, true);
        xmlhttp.send();
	}
        else {
            document.getElementById("last").href = "supplier_list.php";
        }
    }

</script>

<script type="text/javascript">
    function showMessage(sup,str) {
        var r = confirm("Confirm to Delete " + sup + "?");
        if (r == true) {
            document.getElementById(str).href = "supplier_list.php?id=" + sup;
        } else {
            document.getElementById(str).href = "supplier_list.php";
        }
    }
</script>
<script>
    function payMessage(str) {
        var r = confirm("Confirm to Payment ?");
        if (r == true) {
            document.getElementById(str).href = "supplier_list.php?pay=" + str;
        } else {
            document.getElementById(str).href = "supplier_list.php";
        }
    }
</script>
<script>
    function disableBtn(str) {
        document.getElementById("disable" + str).disabled = true;
        document.getElementById("enable" + str).disabled = false;
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
                document.getElementById("last").innerHTML = this.responseText;
            }
        }
        xmlhttp.open("GET", "disable.php?st=reject&value=" + str, true);
        xmlhttp.send();
    }
</script>
<script>
    function enableBtn(str) {
        document.getElementById("disable" + str).disabled = false;
        document.getElementById("enable" + str).disabled = true;
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
                document.getElementById("last").innerHTML = this.responseText;
            }
        }
        xmlhttp.open("GET", "disable.php?st=PAP&value=" + str, true);
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
<?php include 'includes/footer.php'; ?>
