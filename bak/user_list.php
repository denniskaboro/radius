<?php

include 'includes/header.php';
include('config/data.php');

?>

    <div id="last"></div>

    <h3>All Users List</h3>
<div style="float:right; color:red;">
    <form method="POST" enctype="multipart/form-data">
        <div class="col-lg-12">
            <input type="text" class="form-control" id="inputStart" placeholder="Search here" name="value" style="height:35px !important;background: #141035 !important;color:#fff6fe !important;">
            <input type="submit" value="Search" name="search" class="btn"style="float: right;background: rgba(59,143,17,0.8) !important;height:30px !important;color:#fff6fe !important;padding: 5px;margin: 5px;"/>
        </div>
    </form>
</div>
<?php
$totalEmpSQL = "SELECT * FROM clients";
$sql = "SELECT * FROM clients ORDER BY id DESC LIMIT 0,15";
if (isset($_GET['page'])) {
    $currentPage = $_GET['page'];
    $showRecordPerPage = $_GET['entries'];
    if($showRecordPerPage=="All"){
        $showRecordPerPage=10000;
    }
    $val =$_GET['sear'];
    $startFrom = ($currentPage * $showRecordPerPage) - $showRecordPerPage;
    $sql = "SELECT * FROM clients WHERE username LIKE '%$val%' or full_name LIKE '%$val%' or mobile LIKE '%$val%'
 or address LIKE '%$val%' or create_date LIKE '%$val%' or supplier_id LIKE '%$val%' ORDER BY id DESC LIMIT $startFrom, $showRecordPerPage";
    $totalEmpSQL = "SELECT * FROM clients WHERE username LIKE '%$val%' or full_name LIKE '%$val%' or mobile LIKE '%$val%'
 or address LIKE '%$val%' or create_date LIKE '%$val%' or supplier_id LIKE '%$val%' ";
} else {
    $currentPage = 1;
    $showRecordPerPage = 15;
    $val ='';
}
?>
<form enctype="multipart/form-data">
    <select onchange="location = this.options[this.selectedIndex].value;">
        <option selected="" value="user_list.php?page=1&amp;entries=15&amp;sear=<?php echo $val; ?>">Select</option>
        <option value="user_list.php?page=1&amp;entries=25&amp;sear=<?php echo $val; ?>">25</option>
        <option value="user_list.php?page=1&amp;entries=50&amp;sear=<?php echo $val; ?>">50</option>
        <option value="user_list.php?page=1&amp;entries=100&amp;sear=<?php echo $val; ?>">100</option>
        <option value="user_list.php?page=1&amp;entries=250&amp;sear=<?php echo $val; ?>">250</option>
        <option value="user_list.php?page=1&amp;entries=500&amp;sear=<?php echo $val; ?>">500</option>
        <option value="user_list.php?page=1&amp;entries=All&amp;sear=<?php echo $val; ?>">ALL</option>
    </select>
</form>
<div class="table-responsive">
    <table class="table table-bordered">

        <thead>
        <tr>
            <th>Full Name</th>
            <th>User Name</th>
            <th>Mobile</th>
            <th>Package</th>
            <th>Create Date</th>
            <th>Expiration</th>
            <th>MAC</th>
            <th>Supervisor ID
            <th>Address</th> 
            <th>Email Notify</th> 
            <th>SMS Notify</th> 
            <th>Enable/Disable</th>
            <th>Edit</th>
            <?php if ($per == "Full" || $per == "Write" || $per == "Admin") { ?>
                <th>Set Attribute</th>
                <th>Delete</th>
            <?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php

        if (isset($_POST['search'])) {
            $currentPage = 1;
            $showRecordPerPage = 15;
            $startFrom = ($currentPage * $showRecordPerPage) - $showRecordPerPage;
            $val = $_POST['value'];
            $sql = "SELECT * FROM clients WHERE username LIKE '%$val%' or full_name LIKE '%$val%' or mobile LIKE '%$val%'
 or address LIKE '%$val%' or create_date LIKE '%$val%' or supplier_id LIKE '%$val%' ORDER BY id DESC LIMIT $startFrom, $showRecordPerPage";
            $totalEmpSQL = "SELECT * FROM clients WHERE username LIKE '%$val%' or full_name LIKE '%$val%' or mobile LIKE '%$val%'
 or address LIKE '%$val%' or create_date LIKE '%$val%' or supplier_id LIKE '%$val%' ";
        }
        $row = mysqli_query($con,$totalEmpSQL) or $msg = mysqli_error($con);
        $totalEmployee = mysqli_num_rows($row);
        $lastPage = ceil($totalEmployee / $showRecordPerPage);
        $firstPage = 1;
        $nextPage = $currentPage + 1;
        $previousPage = $currentPage - 1;
        $r = mysqli_query($con,$sql) or $msg = mysqli_error($con);
        $band = 0;
        while ($f = mysqli_fetch_array($r)) {
            $name = $f['username'];
            $sms = $f['sms_send'];
            $email = $f['email_send'];
            $sup = $f['supplier_id'];
            ?>
            <tr>
                <td><?php echo $f['full_name']; ?></td>
                <td><a style=" color: #e2b9db;" href="user_statistics.php?name=<?php echo $f['username']; ?>"
                       role="button" style="color: #ffffff;font-size:16px;"><?php echo $f['username']; ?></a></td>
                <?php

                $sql = "SELECT groups.groupname FROM groups INNER JOIN radusergroup ON radusergroup.groupname=groups.id WHERE username='$name'";
                $rs = mysqli_query($con,$sql) or $msg = mysqli_error($con);
                $p = mysqli_fetch_array($rs);
                $pro = $p['groupname'];
                $cx = strpos($pro, ' M');
                if ($cx) {
                    $local = substr($pro, 0, $cx);
                    $band = $local + $band;
                }
                ?>
				<td><?php echo $f['mobile']; ?></td>
                <td><?php echo $pro; ?></td>
                <td><?php echo $f['create_date'];; ?></td>
                <?php
                $sql = "SELECT * FROM radcheck WHERE attribute='Expiration' && username='$name'";
                $rs = mysqli_query($con,$sql) or $msg = mysqli_error($con);
                $re = mysqli_num_rows($rs);
                if ($re > 0) {
                    $p = mysqli_fetch_array($rs);
                    $expi = $p['value'];
                    $expira = $p['value'];
                    $d = strtotime($expi);
                    $exp = date("Y m d H:i", $d);
                    $dateTime = new DateTime();
                    $date = $dateTime->format('Y m d H:i');
                    if ($exp < $date) {
                        $expi = $expira."<a class=\"btn btn-primary\"  href=\"extend.php?user=" . $name . "&id=" . $sup . "\" >Renew</a>";
                    }
                } else {
                    $expi = "No Expiratioin";
                }

                ?>
                <td><?php echo $expi; ?></td>
                <td><?php $mac = "SELECT * FROM radcheck WHERE attribute='Calling-Station-Id' && username='$name'";
                    $ma = mysqli_query($con,$mac) or $msg = mysqli_error($con);
                    $m = mysqli_num_rows($ma);
                    if($m>0){
                    $mm = mysqli_fetch_array($ma);
                    $id=$mm['id'];
                    ?>
                    <button class="btn btn-danger" id="<?php echo $id; ?>"
                            value="<?php echo $id; ?>" onclick="Delete(this.value)">
                        <span class="glyphicon glyphicon-trash"></span></button></td> <?php } ?>
                <?php if ($dept !="Visitor") { ?><td><a style=" color: #e2b9db;" href="supplier_list.php?sort=<?php echo $sup; ?>"
                       role="button" style="color: #ffffff;font-size:16px;"><?php echo $sup; ?></a>
				</td><?php }?>
                <td><?php echo $f['address']; ?></td>
		 <td>
                    <button class="btn btn-success" id="emailenable<?php echo $f['username']; ?>"
                        <?php if ($email == "1") {
                            echo "disabled=\"true\"";
                        } ?> onclick="EmailEnable('<?php echo $name; ?>')">
                        <span class="glyphicon glyphicon-ok"></span></button>
                <button class="btn btn-danger" id="emaildisable<?php echo $f['username']; ?>"
                        <?php if ($email == "0") {
                            echo "disabled=\"true\"";
                        } ?> onclick="EmailDisable('<?php echo $name; ?>')">
                        <span class="glyphicon glyphicon-off"></span></button>
		</td>
		 <td>
                    <button class="btn btn-success" id="smsenable<?php echo $f['username']; ?>"
                        <?php if ($sms == "1") {
                            echo "disabled=\"true\"";
                        } ?> onclick="SmsEnable('<?php echo $name; ?>')">
                        <span class="glyphicon glyphicon-ok"></span></button>
                <button class="btn btn-danger" id="smsdisable<?php echo $f['username']; ?>"
                        <?php if ($sms == "0") {
                            echo "disabled=\"true\"";
                        } ?> onclick="SmsDisable('<?php echo $name; ?>')">
                        <span class="glyphicon glyphicon-off"></span></button>
		</td>
                <?php $sql = "SELECT * FROM radcheck WHERE attribute='Auth-Type' && username='$name'";
                $rj = mysqli_query($con,$sql) or $msg = mysqli_error($con);
                $ac = mysqli_fetch_array($rj);
                $type = $ac['value'];
                $idd = $ac['id'];
                ?>
                <td>
                    <button class="btn btn-success" id="enable<?php echo $f['username']; ?>"
                            value="<?php echo $f['username']; ?>"
                        <?php if ($type == "PAP" || empty($type)) {
                            echo "disabled=\"true\"";
                        } ?> onclick="enableBtn(this.value)">
                        <span class="glyphicon glyphicon-ok"></span></button>
                <button class="btn btn-danger" id="disable<?php echo $f['username']; ?>"
                            value="<?php echo $f['username']; ?>"
                        <?php if ($type == "reject") {
                            echo "disabled=\"true\"";
                        } ?> onclick="disableBtn(this.value)">
                        <span class="glyphicon glyphicon-off"></span></button></td>
                <td><a class="btn" href="user_edit.php?id=<?php echo $f['id']; ?>" style="color: #ffffff;">
                        <i class="icon-edit"><span class="glyphicon glyphicon-pencil"></span></i></a></td>
                <?php if ($per == "Full" || $per == "Write" || $per == "Admin") { ?>
                    <td><a class="btn" href="user_attribute.php?val=<?php echo $f['username']; ?>"
                           style="color: #ffffff;">
                            <i class="icon-edit"><span class="glyphicon glyphicon-cog"></span></i></a></td>

                    <td><a class="btn btn-danger" onclick="myFunction(this.id)" id="<?php echo $name; ?>"
                           href="user_list.php">
                            <i class="icon-edit"><span class="glyphicon glyphicon-trash"></span></i></a></td>
                <?php } ?>
            </tr>

        <?php }
        $total=0;
        $sql2 = "SELECT * FROM radusergroup";
        $rp = mysqli_query($con,$sql2) or $msg = mysqli_error($con);
        while($q = mysqli_fetch_array($rp)) {
            $pro = $q['groupname'];
            $cx = strpos($pro, ' M');
            if ($cx) {
                $local = substr($pro, 0, $cx);
                $total = $total + $local;
            }
        }
        ?>
        </tbody>
        <?php
        echo "<center><h4> Total Bandwidth: " . $total . " Mbps</h4></center>";
        echo "<center><h4> Current Bandwidth: " . $band . " Mbps</h4></center>"; ?>
    </table>
	</div>
<?php echo "Showing " . $showRecordPerPage * ($currentPage - 1) . " to " . $showRecordPerPage * $currentPage . " of " . $totalEmployee ?>
    <nav aria-label="Page navigation">
        <ul class="pagination"  style="height:40px;">
            <?php $lastPart = $lastPage - 4;
            $compare = $currentPage;
            if ($currentPage != $firstPage) { ?>
                <li class="page-item">
                    <a class="page-link"
                       href="?page=<?php echo $firstPage . "&entries=" . $showRecordPerPage ?>"
                       tabindex="-1" aria-label="Previous" style="width:50px;height:40px;padding:10px;border:1px solid;background:#ddd;color:black;">
                        <span aria-hidden="true">First</span>
                    </a>
                </li>
                <li class="page-item"><a class="page-link"
                                         href="?page=<?php echo ($currentPage - 1) . "&entries=" . $showRecordPerPage."&sear=".$val; ?>" style="width:50px;border:1px solid;height:40px;padding:10px;background:#ddd;color:black;"><<
                        </a></li>
            <?php } ?>
            <?php if ($lastPage < 5) {
                for ($currentPage = 1; $currentPage <= $lastPage; $currentPage++) {
                    echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?page=$currentPage&entries=$showRecordPerPage&sear=$val\" style=\"width:50px;border:1px solid;height:40px;padding:10px;background:#ddd;color:black;\">$currentPage</a></li>";
                }
                ?>
            <?php } else if ($currentPage > $lastPart) {
                for ($currentPage = $lastPart; $currentPage <= $lastPage; $currentPage++) {
                    echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?page=$currentPage&entries=$showRecordPerPage&sear=$val\" style=\"width:50px;border:1px solid;height:40px;padding:10px;background:#ddd;color:black;\">$currentPage</a></li>";
                }
            } else {
                $ext = $currentPage + 4;
                for ($currentPage = $currentPage; $currentPage <= $ext; $currentPage++) {
                    echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?page=$currentPage&entries=$showRecordPerPage&sear=$val\" style=\"width:50px;border:1px solid;height:40px;padding:10px;background:#ddd;color:black;\">$currentPage</a></li>";
                } ?>
            <?php }
            if ($compare != $lastPage) { ?>
                <li class="page-item"><a class="page-link"
                                         href="?page=<?php echo ($compare + 1) . "&entries=" . $showRecordPerPage."&sear=".$val; ?>" style="width:50px;border:1px solid;height:40px;padding:10px;background:#ddd;color:black;">
                        >> </a></li>
                <li class="page-item">
                    <a class="page-link"
                       href="?page=<?php echo $lastPage . "&entries=" . $showRecordPerPage."&sear=".$val;?>"
                       aria-label="Next" style="width:50px;height:40px;padding:10px;border:1px solid;background:#ddd;color:black;">
                        <span aria-hidden="true">Last</span>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </nav>

    <script type="text/javascript">
        function myFunction(str) {
                var r = confirm("Confirm to Delete " + str + "?");
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
			document.getElementById(str).remove();
                            alert(this.responseText);
                        }
                    }
                    xmlhttp.open("GET", "disable.php?usrdel=" + str, true);
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
                        document.getElementById("str").innerHTML = this.responseText;
                    }
                }
                xmlhttp.open("GET", "disable.php?id=" + str, true);
                xmlhttp.send();
            }
        }
    </script>
    <script>
	  function SmsDisable(str) {
            document.getElementById("smsdisable" + str).disabled = true;
            document.getElementById("smsenable" + str).disabled = false;
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
            xmlhttp.open("GET", "disable.php?sms=0&user=" + str, true);
            xmlhttp.send();
        }   
        function EmailDisable(str) {
            document.getElementById("emaildisable" + str).disabled = true;
            document.getElementById("emailenable" + str).disabled = false;
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
            xmlhttp.open("GET", "disable.php?email=0&user=" + str, true);
            xmlhttp.send();
        }

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
                alert(this.responseText);
		}
            }
            xmlhttp.open("GET", "disable.php?st=reject&value=" + str, true);
            xmlhttp.send();
        }
    </script>
    <script>
	function SmsEnable(str) {
            document.getElementById("smsdisable" + str).disabled = false;
            document.getElementById("smsenable" + str).disabled = true;
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
            xmlhttp.open("GET", "disable.php?sms=1&user=" + str, true);
            xmlhttp.send();
        }
	function EmailEnable(str) {
            document.getElementById("emaildisable" + str).disabled = false;
            document.getElementById("emailenable" + str).disabled = true;
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
            xmlhttp.open("GET", "disable.php?email=1&user=" + str, true);
            xmlhttp.send();
        }   

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
                alert(this.responseText);
		}
            }
            xmlhttp.open("GET", "disable.php?st=PAP&value=" + str, true);
            xmlhttp.send();
        }
    </script>
    <!-- end of col-lg-10 -->
