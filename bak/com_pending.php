<?php
include 'includes/header.php';
include 'config/data.php';
$user = $_SESSION['username'];
$sql="SELECT * FROM admin WHERE username='$user'";
$que=mysqli_query($con,$sql);
$f=mysqli_fetch_array($que);
$name=$f['full_name'];
if(isset($_GET['id'])){
    $com_id=$_GET['id'];
    $sql="DELETE FROM complain WHERE id='$com_id'";
    $q=mysqli_query($con,$sql);
    if($q){
        echo "Complain Delete successfully...";
    }
}
if(isset($_POST['start'])){
    $com_id=$_POST['id'];
    $time=$_POST['time'];
    $day=$_POST['day'];
    $time=date("Y-m-d H:i:s",strtotime("+".$time." ".$day));
    $cause=$_POST['problem'];
    $qq="UPDATE complain SET est_time='$time',cause='$cause',status='Working',working_per='$name' WHERE id='$com_id'";
    mysqli_query($con,$qq);
}
if(isset($_POST['end'])){
    $com_id=$_POST['id'];
    $cause=$_POST['problem'];
    $file = $_FILES['prob']['name'];
    $qq="UPDATE complain SET cause='$cause',status='Completed',feedback_attachment='$file' WHERE id='$com_id'";
    mysqli_query($con,$qq);
    move_uploaded_file($_FILES['prob']['tmp_name'],"complain/".$_FILES['prob']['name']);

    $sql = "INSERT INTO resolved (supplier_id,full_name,address,pop,mobile,home_user,problem,create_time,est_time,cause,attachment,feedback_attachment,status,working_per)
SELECT supplier_id,full_name,address,pop,mobile,home_user,problem,create_time,est_time,cause,attachment,feedback_attachment,status,working_per FROM complain WHERE id='$com_id'";
    mysqli_query($con,$sql);

    $ss="DELETE FROM complain WHERE id='$com_id'";
    $r=mysqli_query($con,$ss);
    if($r){
        echo "Problem Resolved...";
    }
}
?>
<style>

    #myForm {
        display: none;
        border: 3px solid salmon;
        padding: 2em;
        width: 400px;
        text-align: center;
        background: #656ea3;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        -webkit-transform: translate(-50%, -50%)

    }
 #prob {
        display: none;
        border: 3px solid salmon;
        padding: 2em;
        width: 970px;
        text-align: center;
        background: #656ea3;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        -webkit-transform: translate(-50%, -50%)

    }

#endForm {
        display: none;
        border: 3px solid salmon;
        padding: 2em;
        width: 400px;
        text-align: center;
        background: #656ea3;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        -webkit-transform: translate(-50%, -50%)

    }

    .formBtn {
        width: 140px;
        display: inline-block;

        background: teal;
        color: #fff;
        font-weight: 100;
        font-size: 1.2em;
        border: none;
        height: 30px;
    }


</style>
<h3>Complain Pending List</h3>
<table class="table table-bordered" id="btrc">
    <thead>
    <tr>
        <?php if ($dept !="Visitor") { ?> <th>Supervisor ID</th>
        <th>Supervisor Name</th><?php }?>
        <th>Coustomer Name</th>
	<th>Mobile</th>
        <th>Coustomer Problem</th>
        <th>Create Time</th>
        <th>Cause of Problem</th>
	<th>Attachment</th>
        <th>Status</th>
        <th>Estimate Time</th>
        <th>Working Person</th>
	<?php if($per != "Read"){?>
        <th>Troubleshoot</th>
        <th>Delete</th>
	<?php } ?>
    </tr>
    </thead>
    <tbody>
    <?php
    $sql = "SELECT * FROM complain ORDER BY id DESC ";
    $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
    while ($j = mysqli_fetch_array($r)) {
        ?>
        <tr><?php if ($dept !="Visitor") { ?>
	 <td><a style=" color: #e2b9db;" href="supplier_list.php?sort=<?php echo $j['supplier_id']; ?>"
                   role="button" style="color: #ffffff;font-size:16px;"><?php echo $j['supplier_id']; ?></a>
            <td><?php echo $j['full_name']; ?></td><?php }?>
            <td><?php echo $j['home_user']; ?></td>
	    <td><?php echo $j['mobile']; ?></td>
            <td><?php echo $j['problem']; ?></td>
            <td><?php echo $j['create_time']; ?></td>
            <td id="problem<?php echo $j['id'];?>"><?php echo $j['cause']; ?></td>
	    <td><button class="btn btn-primary" onClick='myValue(this.id)' id="<?php  echo $j['attachment']; ?>">Show</button></td>
            <td><?php echo $j['status']; ?></td>
            <td><?php echo $j['est_time']; ?></td>
            <td><?php echo $j['working_per']; ?></td>
	<?php if($per != "Read"){?>
            <?php if($j['status']=="Working"){?>
                <td><button class="btn btn-danger" id="<?php echo $j['id']; ?>" onclick="End(this.id)" style="color: #ffffff;">End</button></td>
            <?php }else{?>
            <td><button class="btn btn-success" id="<?php echo $j['id']; ?>" onclick="Start(this.id)" style="color: #ffffff;">Start</button></td>
            <?php }?>
            <td><a class="btn btn-danger" href="com_pending.php?id=<?php echo $j['id']; ?>" style="color: #ffffff;">
                    <i class="icon-edit"><span class="glyphicon glyphicon-trash"></span></i></a></td>
		<?php } ?>
        </tr>
    <?php } ?>
    </tbody>
</table>
<div class="form-popup" id="myForm">
    <form method="post" enctype="multipart/form-data" class="form-container">
        <h1>Update Info.</h1>
        <label>Estimate Time:</label>
	<div class="form-group">
          <input class="form-control" type="text" name="time" placeholder="1,2,3..." required>
        </div>
        <div class="form-group">
            <select name="day">
                <option value="minutes">Minutes</option>
                <option value="hours">Hours</option>
                <option value="day">Day</option>
            </select>
        </div>
        <div class="form-group">
            <label for="inputIP" class="control-label">Cause of Problem</label>
            <input type="text" class="form-control" id="inputIP" name="problem" placeholder="Found Problem Details">
            <input type="hidden" class="form-control" id="userid" value='' name="id">
        </div>

        <input class="formBtn" name='start' type="submit"/>
        <input class="formBtn" type="reset"/>
    </form>
</div>

<div class="form-popup" id="endForm">
    <form method="post" enctype="multipart/form-data" class="form-container">
        <h1>Update Info.</h1>
        <div class="form-group">
            <label for="inputIP" class="control-label">Cause of Problem</label>
            <input type="text" class="form-control" id="inputProb" value='' name="problem" placeholder="Found Problem Details">
            <input type="hidden" class="form-control" id="com_id" value='' name="id">
        </div>
	<div class="form-group">
            <label for="inputRoad" class="control-label">Attach Screenshot(if require)</label>
            <input class="form-control" type="file" name="prob">
        </div>
        <input class="formBtn" name='end' type="submit"/>
        <input class="formBtn" type="reset"/>
    </form>
</div>
<div class="form-popup" id="prob">

</div>
<script>
    function Start(str) {
        $('#myForm').fadeToggle();
        document.getElementById("userid").value = str;
        $(document).mouseup(function (e) {
            var container = $("#myForm");

            if (!container.is(e.target) // if the target of the click isn't the container...
                && container.has(e.target).length === 0) // ... nor a descendant of the container
            {
                container.fadeOut();
            }
        });

    }
</script>
<script>
    function End(str) {
        $('#endForm').fadeToggle();
        document.getElementById("com_id").value = str;
        var cc = document.getElementById('problem' + str).innerText;
        document.getElementById("inputProb").value = cc;
        $(document).mouseup(function (e) {
            var container = $("#endForm");

            if (!container.is(e.target) // if the target of the click isn't the container...
                && container.has(e.target).length === 0) // ... nor a descendant of the container
            {
                container.fadeOut();
            }
        });

    }
</script>
<script>
    function myValue(str) {
        $('#prob').fadeToggle();
        document.getElementById("prob").innerHTML = "<img src='complain/"+str+"' width='930px' height='700px'></img>";
        $(document).mouseup(function (e) {
            var container = $("#prob");

            if (!container.is(e.target) // if the target of the click isn't the container...
                && container.has(e.target).length === 0) // ... nor a descendant of the container
            {
                container.fadeOut();
            }
        });

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

