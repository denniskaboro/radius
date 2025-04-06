<?php
include 'includes/header.php';
include('config/data.php');
$per = $_SESSION['per'];
if (isset($_POST['create'])) {
        $usrn = $_SESSION['username'];
        $sup_id = $_POST['id'];
        $fullname = $_POST['fullname'];
        $address = $_POST['address'];
        $pop = $_POST['pop'];
        $mobile = $_POST['mobile'];
        $home_user = $_POST['home_user_name'];
        $problem = $_POST['problem'];
            $client = "INSERT INTO complain (supplier_id,full_name,address,pop,mobile,home_user,problem,status,working_per)
VALUES('$sup_id','$fullname','$address','$pop','$mobile','$home_user','$problem','Pending','Pending')";
                $create= mysqli_query($con,$client) or $msg = mysqli_error($con);
                if ($create) {
                    $msg = "Complain Created show complain status";
		            $API->log($wh,$usrn." Create a Complain");
                }
        
}

?>
<div class="col-sm-6">
    <?php
    if (isset($msg)) {
        echo "<h4 style='color: #de4e97'>" . $msg . "</h4>";
    } ?>
    <h4>Create Client Problem Details:</h4>
    <form data-toggle="validator" role="form" method="POST">
        <div class="form-group">
            <label  class="control-label">Search By Any Charecter of Supervisor Name:: </label>
            <input class="form-control" name="char">
        </div>
        <div class="form-group">
            <button type="submit" name="search"  class="btn btn-primary">Search</button>
        </div>
    </form>
    <?php if(isset($_POST['search'])){ ?>
    <form data-toggle="validator" role="form" method="POST">
        <div class="form-group">
            <label  class="control-label">Select Supervisor</label>
            <select class="form-control" onchange="Select(this.value)" >
                <option>Select</option>
		<?php
                $search_char = $_POST['char'];
                $sql1="SELECT * FROM supplier WHERE (supplier_id LIKE '%$search_char%') OR (full_name LIKE '%$search_char%') OR (username LIKE '%$search_char%')";
                $qq=mysqli_query($con,$sql1);
                while($g=mysqli_fetch_array($qq)){
                    ?>
                    <option value="<?php echo $g['supplier_id']?>"><?php echo $g['full_name']?>-<?php echo $g['supplier_id']?></option>
                <?php } ?>
            </select>
        </div>
        <div id="sup">

        </div>
        <div class="form-group">
            <button type="submit" name="" onClick='pay(this.id)' id="create" class="btn btn-primary">Create</button>
        </div>
    </form>
<?php } ?>
    <br><br>
</div>
<script>
    function Select(str) {
        if (str == "") {
            document.getElementById("sup").innerHTML = "";
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
                document.getElementById("sup").innerHTML = this.responseText;
            }
        }
        xmlhttp.open("GET", "disable.php?com=" + str, true);
        xmlhttp.send();
    }
</script>
<script>
    function ProbAdd() {
        var str = document.getElementById("Prob").value;
        if (str == "") {
            document.getElementById("problem").innerHTML = "";
        }else{
            document.getElementById("problem").innerHTML += str +", ";
        }

    }
</script>
<script>
    function ComPlain() {
        var str = document.getElementById("Com").value;
        if (str == "") {
            document.getElementById("inputHome").innerHTML = "";
        }else{
            document.getElementById("inputHome").innerHTML += str +", ";
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
<script type="text/javascript" src="component/validator.js"></script>
<script type="text/javascript" src="component/validator.min.js"></script>

<?php include 'includes/footer.php'; ?>
