<?php
include 'clients.php';
include('../config/data.php');
include '../log.php';
$API = new log_api();
$user = $_SESSION['username'];

if (isset($_POST['create'])) {
    $usrn = $_SESSION['username'];
    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $mobile = $_POST['mobile'];
    $sup_id = $_POST['sup_id'];
    $problem = $_POST['problem'];
    $file = $_FILES['prob']['name'];
    $client = "INSERT INTO complain (supplier_id,full_name,address,mobile,home_user,problem,attachment,status,working_per)
VALUES('$sup_id','$fullname','$address','$mobile','$user','$problem','$file','Pending','Pending')";
    $create = mysqli_query($con, $client) or $msg = mysqli_error($con);
    if ($create) {
        $msg = "Complain Created show complain status";
        move_uploaded_file($_FILES['prob']['tmp_name'], "../complain/" . $_FILES['prob']['name']);
        $API->log($user, $usrn . " Create a Complain Under this Supplier " . $sup_id);
    }

}

?>
<div class="col-sm-6">
    <?php

    $sql = "SELECT * FROM clients WHERE username='$user'";
    $qu = mysqli_query($con, $sql);
    $f = mysqli_fetch_array($qu);
    if (isset($msg)) {
        echo "<h4 style='color: #de4e97'>" . $msg . "</h4>";
    } ?>
    <h4>Client Problem Details:</h4>
    <form data-toggle="validator" role="form" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="inputFullname" class="control-label">Full Name</label>
            <input type="text" class="form-control" id="inputFullname" name="fullname"
                   value="<?php echo $f['full_name'] ?>" readonly>
            <input type="hidden" class="form-control" id="inputFullname" name="sup_id"
                   value="<?php echo $f['supplier_id'] ?>" readonly>
        </div>
        <div class="form-group">
            <label for="inputDivision" class="control-label">Address</label>
            <input type="text" class="form-control" id="inputFullname" name="address"
                   value="<?php echo $f['address'] ?>" readonly>
        </div>
        <div class="form-group">
            <label for="inputRoad" class="control-label">Contact No.</label>
            <input type="text" class="form-control" id="inputFullname" name="mobile" value="<?php echo $f['mobile'] ?>"
                   required>
        </div>
        <div class="form-group">
            <label for="inputEmail" class="control-label">Home User Name</label>
            <input type="text" class="form-control" id="inputFullname" value="<?php echo $user; ?>" readonly>

        </div>

        <div class="form-group">
            <label for="inputRoad" class="control-label">Problem Type</label>
            <select class="form-control" id="Prob" required>
                <option>Select</option>
                <option>User Payment Problem</option>
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
                      placeholder="Type Your Problem Here..."></textarea>
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
