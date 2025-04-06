<?php
include 'reseller.php';
include('../config/data.php');
$per = $_SESSION['per'];
$id = $_SESSION['id'];
if (isset($_POST['create'])) {
    $usrn = $_SESSION['username'];
    $sup_id = $_POST['id'];
    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $pop = $_POST['pop'];
    $mobile = $_POST['mobile'];
    $home_user = $_POST['home_user_name'];
    $problem = $_POST['problem'];
    $file = $_FILES['prob']['name'];
    $client = "INSERT INTO complain (supplier_id,full_name,address,pop,mobile,home_user,problem,attachment,status,working_per)
VALUES('$sup_id','$fullname','$address','$pop','$mobile','$home_user','$problem','$file','Pending','Pending')";
    $create = mysqli_query($con, $client) or $msg = mysqli_error($con);
    if ($create) {
        $msg = "Complain Created show complain status";
        move_uploaded_file($_FILES['prob']['tmp_name'], "../complain/" . $_FILES['prob']['name']);
        $API->log($wh, $usrn . " Create a Complain");
    }

}

?>
<div class="col-sm-6">
    <?php
    $sql = "SELECT * FROM supplier WHERE supplier_id='$id'";
    $qu = mysqli_query($con, $sql);
    $f = mysqli_fetch_array($qu);
    if (isset($msg)) {
        echo "<h4 style='color: #de4e97'>" . $msg . "</h4>";
    } ?>
    <h4>Client Problem Details:</h4>
    <form data-toggle="validator" role="form" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="inputName" class="control-label">Supervisor ID</label>
            <input type="text" class="form-control" id="inputName" value="<?php echo $id; ?>" name="id" readonly>
        </div>
        <div class="form-group">
            <label for="inputFullname" class="control-label">Full Name</label>
            <input type="text" class="form-control" id="inputFullname" name="fullname"
                   value="<?php echo $f['full_name'] ?>" readonly>
        </div>
        <div class="form-group">
            <label for="inputDivision" class="control-label">Address</label>
            <input type="text" class="form-control" id="inputFullname" name="address"
                   value="<?php echo $f['address'] ?>" readonly>
        </div>
        <div class="form-group">
            <label for="inputArea" class="control-label">POP</label>
            <input type="text" class="form-control" id="inputFullname" name="pop" value="<?php echo $f['pop'] ?>"
                   readonly>
        </div>
        <div class="form-group">
            <label for="inputRoad" class="control-label">Contact No.</label>
            <input type="text" class="form-control" id="inputFullname" name="mobile" value="<?php echo $f['mobile'] ?>"
                   required>
        </div>
        <div class="form-group">
            <label for="inputEmail" class="control-label">Home User Name</label>
            <select class="form-control" id="Com">
                <option>All Users</option>
                <?php
                $sql1 = "SELECT * FROM clients WHERE supplier_id='$id'";
                $qq = mysqli_query($con, $sql1);
                while ($g = mysqli_fetch_array($qq)) {
                    ?>
                    <option><?php echo $g['username'] ?></option>
                <?php } ?>
            </select>
            <a onClick='ComPlain()' class="btn btn-primary">Add</a>
        </div>
        <div class="form-group">
            You can add multiple User
            <textarea rows="4" cols="50" class="form-control" id="inputHome" name="home_user_name" required></textarea>
        </div>
        <div class="form-group">
            <label for="inputRoad" class="control-label">Problem Type</label>
            <select class="form-control" id="Prob">
                <option>User Double Renew</option>
                <option>User MAC delete</option>
                <option>User Renew</option>
                <option>User not found</option>
                <option>Youtube Download Speed Slow</option>
                <option>Facebook Browsing Speed Slow</option>
                <option>Link Down</option>
                <option>User Bandwidth Slow</option>
                <option>The user is unable to connect</option>
            </select>
            <a onClick='ProbAdd()' class="btn btn-primary">Add</a>
        </div>
        <div class="form-group">
            Please add a problem or type problem bellow
            <textarea rows="4" cols="50" class="form-control" id="problem" name="problem"
                      placeholder="Type Your Problem Here..." required></textarea>
        </div>
        <div class="form-group">
            <label for="inputRoad" class="control-label">Attach Screenshot(if require)</label>
            <input class="form-control" type="file" name="prob">
        </div>
        <div class="form-group">
            <button type="submit" name="" onClick='pay(this.id)' id="create" class="btn btn-primary">Create</button>
        </div>
    </form>
    <br><br>
</div>
<script>
    function ProbAdd() {
        var str = document.getElementById("Prob").value;
        if (str == "") {
            document.getElementById("problem").innerHTML = "";
        } else {
            document.getElementById("problem").innerHTML += str + ", ";
        }

    }
</script>
<script>
    function ComPlain() {
        var str = document.getElementById("Com").value;
        if (str == "") {
            document.getElementById("inputHome").innerHTML = "";
        } else {
            document.getElementById("inputHome").innerHTML += str + ", ";
        }

    }
</script>
<script type="text/javascript">
    function pay(str) {
        var r = confirm("Confirm to Create ?");
        if (r == true) {
            document.getElementById(str).name = str;
        } else {
            document.getElementById(str).name = "";
        }
    }
</script>
<script type="text/javascript" src="../component/validator.js"></script>
<script type="text/javascript" src="../component/validator.min.js"></script>

<?php include '../includes/footer.php'; ?>
