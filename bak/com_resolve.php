<?php
include 'includes/header.php';
include 'config/data.php';
$user = $_SESSION['username'];
$sql="SELECT * FROM admin WHERE username='$user'";
$que=mysqli_query($con,$sql);
$f=mysqli_fetch_array($que);
$name=$f['full_name'];
if(isset($_GET['id'])){
    $id=$_GET['id'];
    $sql="DELETE FROM complain WHERE id='$id'";
    $q=mysqli_query($con,$sql);
    if($q){
        echo "Complain Delete successfully...";
    }
}
if(isset($_POST['start'])){
    $id=$_POST['id'];
    $date=$_POST['date'];
    $time=$_POST['time'];
    $est=$date." ".$time;
    $cause=$_POST['problem'];
    $qq="UPDATE complain SET est_time='$est',cause='$cause',status='Working',working_per='$name' WHERE id='$id'";
    mysqli_query($con,$qq);
}
if(isset($_POST['end'])){
    $id=$_POST['id'];
    $cause=$_POST['problem'];
    $qq="UPDATE complain SET cause='$cause',status='Completed' WHERE id='$id'";
    mysqli_query($con,$qq);
    $sql = "INSERT INTO resolved (complain_id,supplier_id,full_name,address,pop,mobile,home_user,problem,create_time,est_time,cause,status,working_per)
SELECT complain_id,supplier_id,full_name,address,pop,mobile,home_user,problem,create_time,est_time,cause,status,working_per FROM complain WHERE id='$id'";
    mysqli_query($con,$sql);
    $ss="DELETE FROM complain WHERE id='$id'";
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
        width: 1024px;
        text-align: center;
        background: #6e8c8f;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        -webkit-transform: translate(-50%, -50%)

    }
</style>
<h3>Complain Resolved List</h3>
<table class="table table-bordered" id="btrc">
    <thead>
    <tr><?php if ($dept !="Visitor") { ?>
        <th>Supervisor ID</th>
        <th>Supervisor Name</th><?php }?>
        <th>Coustomer Name</th>
        <th>Coustomer Problem</th>
        <th>Create Time</th>
        <th>Comment</th>
	 <th>Attachment</th>
        <th>Status</th>
        <th>Completed Time</th>
        <th>Working Person</th>

    </tr>
    </thead>
    <tbody>
    <?php
    if(isset($_GET['sort'])){
	$sql = "SELECT * FROM resolved WHERE DATE_FORMAT(end_time,'%Y-%m-%d')=curdate()";
	}else{
    $sql = "SELECT * FROM resolved ORDER BY id DESC ";
}
    $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
    while ($j = mysqli_fetch_array($r)) {
        ?>
        <tr>

            <?php if ($dept !="Visitor") { ?><td><?php echo $j['supplier_id']; ?></td>
            <td><?php echo $j['full_name']; ?></td><?php }?>
            <td><?php echo $j['home_user']; ?></td>
            <td><?php echo $j['problem']; ?></td>
            <td><?php echo $j['create_time']; ?></td>
            <td><?php echo $j['cause']; ?></td>
	    <td><button class="btn btn-primary" onClick='myValue(this.id)' id="<?php  echo $j['attachment']; ?>">Prob.</button>
		<button class="btn btn-primary" onClick='reValue(this.id)' id="<?php  echo $j['feedback_attachment']; ?>">Res.</button></td>
            <td><?php echo $j['status']; ?></td>
            <td><?php echo $j['end_time']; ?></td>
            <td><?php echo $j['working_per']; ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<div class="form-popup" id="myForm">

</div>
<script>
    function myValue(str) {
        $('#myForm').fadeToggle();
        document.getElementById("myForm").innerHTML = "<img src='complain/"+str+"' width='970px' height='700px'></img>";
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
    function reValue(str) {
        $('#myForm').fadeToggle();
        document.getElementById("myForm").innerHTML = "<img src='complain/"+str+"' width='970px' height='700px'></img>";
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

<link rel="stylesheet" type="text/css" href="component/jquery.dataTables.min.css"/>
<script type="text/javascript" src="component/js/export/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#btrc').DataTable({
            pageLength: 15,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
	    order:[[8,"desc"]]
        });

    });
</script>
<?php include 'includes/footer.php'; ?>

