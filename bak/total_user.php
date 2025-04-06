<?php
include 'includes/header.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}
?>
<table class="table table-bordered" id="btrc">
    <thead>
    <tr>
        <th>Name</th>
        <th>Username</th>
        <th>Mobile</th>
        <th>Address</th>
	<th>NID</th>
        <th>Package</th>
	<th>Expiration</th>
        <th>Status</th>
        <th>Activation Date</th>
	<?php if ($per == "Full" || $per == "Write" || $per == "Admin") { ?>
                <th>Set Attribute</th>
            <?php } ?>

    </tr>
    </thead>
    <tbody>
    <?php
    $mi=0;
    $sql = "SELECT * FROM clients WHERE supplier_id='$id' ORDER BY id ASC ";
    $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
    $total = mysqli_num_rows($r);
    while ($j = mysqli_fetch_array($r)) {
        $user = $j['username'];
        ?>
        <tr>

            <td><?php echo $j['full_name']; ?></td>
            
	     <td><a style=" color: #e2b9db;" href="user_statistics.php?name=<?php echo  $user; ?>"
                       role="button" style="color: #ffffff;font-size:16px;"><?php echo  $user; ?></a></td>
            <td><?php echo $j['mobile']; ?></td>
            <td><?php echo $j['address']; ?></td>
	     <td><?php echo $j['nid']; ?></td>
            <td><?php
                $s = "SELECT * FROM radusergroup WHERE username='$user' ORDER BY id ASC ";
                $rr = mysqli_query($con,$s) or $msg = mysqli_error($con);
                $q = mysqli_fetch_array($rr);
                echo $q['groupname'];
                $pro = $q['groupname'];
                $cx = strpos($pro, ' M');
                if ($cx) {
                    $local = substr($pro, 0, $cx);
                    $band = $local + $band;
                }
		$price="SELECT * FROM groups WHERE groupname='$pro'";
                $ff=mysqli_query($con,$price);
                $bill=mysqli_fetch_array($ff);
                $total=$bill['price'];
                $pcl=$bill['pcl'];
                $rev=$rev+$total;
                $net=$net+$pcl;
                ?></td>
	<?php
            $sql = "SELECT * FROM radcheck WHERE attribute='Expiration' && username='$user'";
            $rs = mysqli_query($con,$sql) or $msg = mysqli_error($con);
            $re = mysqli_num_rows($rs);
            if ($re > 0) {
                $p = mysqli_fetch_array($rs);
                $expi = $p['value'];
                $d = strtotime($expi);
                $date = time();
                if ($d < $date) {
                    $expi = $expi."<a class=\"btn btn-primary\"  href=\"extend.php?user=" . $user . "&id=" . $id . "\" >Renew</a>";
                    $mi=$local+$mi;
                }
            } else {
                $expi = "No Expiratioin Set";
            }

            ?>
            <td><?php echo $expi; ?></td>
	    <td style="color:lightgreen !important;"><?php 
			$acct = "SELECT * FROM radacct  WHERE username='$user' && acctstoptime IS NULL && acctterminatecause='' ORDER BY acctstarttime DESC";
			$q=mysqli_query($con,$acct);
			$row=mysqli_num_rows($q);
			if($row>0 && $row<2){
				echo "Connected";
			}else if($row>1){
				echo "Multi Session";
			}else{
				echo "Not Connected";
			}
			?></td>
            <td><?php echo $j['create_date']; ?></td>
	    <?php if ($per == "Full" || $per == "Write" || $per == "Admin") { ?>
                    <td><a class="btn" href="user_attribute.php?val=<?php echo $user; ?>"
                           style="color: #ffffff;">
                            <i class="icon-edit"><span class="glyphicon glyphicon-cog"></span></i></a></td>
	<?php } ?>
        </tr>
    <?php }
	$ac=$band-$mi;
	echo "<h3> Total Bandwidth: " . $band . " Mbps</h3> ";
	echo "<h3> Total Active Bandwidth: " . $ac . " Mbps</h3> ";
	echo "<h3>YTC_50%=".(($ac*50)/100)." Mbps, FB_60%=".(($ac*60)/100)." Mbps, IP_40%=".(($ac*40)/100)." Mbps</h3>";
    echo "<h3> Total Revenew: " . $rev . " Kes</h3> ";
    echo "<h3> Net Revenew: " . $net . " Kes</h3> "; ?>
    </tbody>
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
