<?php
include "../config/data.php";
include '../log.php';
include 'session.php';

use PEAR2\Net\RouterOS;

include '../PEAR2/Autoload.php';

if (isset($_GET['filter'])) {
    $val = $_GET['filter'];
    $ip = $_GET['ip'];
    $user = $_GET['user'];
    $pass = $_GET['password'];
    try {
        $util = new RouterOS\Util($client = new RouterOS\Client($ip, $user, $pass));
        $setRequest = new RouterOS\Request(
            '/interface bridge filter remove'
        );
        $setRequest
            ->setArgument('.id', $val);
        $client->sendSync($setRequest);
        if ($client) {
            echo "MAC Filter Remove Successfully.";
        }
    } catch (Exception $e) {
        echo "<div>Unable to connect to RouterOS.</div>";
    }
}

$dept = $_SESSION['dept'];
$API = new log_api();
$per = $_SESSION['per'];
$wh = $_SESSION['username'];

if (isset($_GET['expiry'])) {
    if ($per == 'Full' || $per == 'Write' || $per == 'Admin') {
        $supplier = $_GET['sup_id'];
        $user = $_GET['name'];
        $newDate = $_GET['expiry'];
        $pack = $_GET['pack'];
        $package = "SELECT * FROM groups WHERE id='$pack'";
        $pack_query = mysqli_query($con,$package);
        $pack_res = mysqli_fetch_array($pack_query);
        $new_pack = $pack_res['groupname'];
        $price = $pack_res['price'];
        $supplier_ammount = $pack_res['supplier'];
        $check = "SELECT SUM(`debit`) as de, SUM(`credit`) as cr,`supplier_name` FROM `transaction` WHERE `supplier_id`='$supplier'";
        $usr = mysqli_query($con,$check);
        $u = mysqli_fetch_array($usr);
        $full_name = $u['supplier_name'];
        $de = $u['de'];
        $cr = $u['cr'];
        $bal = $de - $cr;
        $balance = $bal - $price;
        $user_update = "User " . $user . " Renew";

        if ($bal < $price) {
            echo "Your Balance Low...";
        } else {
            $update = "UPDATE radcheck set `value`='$newDate' WHERE `attribute`='Expiration' && `username`='$user'";
            $up = mysqli_query($con,$update);
            if ($up) {
                $trans = "INSERT INTO `transaction` (`supplier_id`,`supplier_name`,`transaction`,`credit`,`reference`)
	 VALUES('$supplier','$full_name','$new_pack','$price','$user_update')";
                mysqli_query($con,$trans);
                $API->log($wh, $price . " Kes has been taken for " . $user);
            }
            $pro = "UPDATE radusergroup set groupname='$new_pack' WHERE username='$user'";
            $r = mysqli_query($con,$pro);
            if ($r) {
                echo "Date Extended...";
                $API->log($wh, $user . " user date extended. new date is " . $newDate);
            }
        }
    } else {
        echo "You have no permission";
    }
}

if (isset($_GET['value'])) {
    $username = $_GET['value'];
    $st = $_GET['st'];
    $sql = "SELECT * FROM radcheck WHERE username='$username' && attribute='Auth-Type'";
    $r = mysqli_query($con,$sql);
    $result = mysqli_fetch_array($r);
    if ($result > 0) {
        $id = $result['id'];
        $up = "UPDATE radcheck SET value='$st' WHERE username='$username' && id='$id' && attribute='Auth-Type'";
        mysqli_query($con,$up);
        $API->log($wh, "User " . $username . " " . $st);

    } else {
        $in = "INSERT INTO radcheck (username,attribute,op,value) VALUES('$username','Auth-Type',':=','$st')";
        mysqli_query($con,$in);
        $API->log($wh, "User " . $username . " " . $st);

    }
    $update = "UPDATE clients SET new_user=' ' WHERE username='$username'";
    mysqli_query($con,$update);
}
if (isset($_GET['group'])) {
    $username = $_GET['group'];
    $st = $_GET['st'];
    $sql = "SELECT * FROM radgroupcheck WHERE groupname='$username' && attribute='Auth-Type'";
    $r = mysqli_query($con,$sql);
    $f = mysqli_num_rows($r);
    $result = mysqli_fetch_array($r);
    if ($f > 0) {
        $id = $result['id'];
        $up = "UPDATE radgroupcheck SET groupname='$username',value='$st' WHERE id='$id'";
        $u = mysqli_query($con,$up);
        $API->log($wh, "Group " . $username . " " . $st);

    } else {
        $in = "INSERT INTO radgroupcheck (groupname,attribute,op,value) VALUES('$username','Auth-Type',':=','$st')";
        $r = mysqli_query($con,$in);
        $API->log($wh, "Group " . $username . " " . $st);

    }
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM radcheck WHERE id='$id'";
    mysqli_query($con,$sql);
    $API->log($wh, "Delete MAC Address ");
}

if (isset($_GET['active'])) {
    $username = $_GET['active'];
    $check = "SELECT * FROM clients WHERE username='$username'";
    $chk = mysqli_query($con,$check);
    $c = mysqli_num_rows($chk);
    if ($c > 0) {
        echo "Username has been already taken. Please Use another name.";
    } else {
        $sup = "SELECT * FROM clients_pending WHERE username='$username' ";
        $query = mysqli_query($con,$sup);
        $s = mysqli_fetch_array($query);
        $supplier = $s['supplier_id'];
        $pass = $s['password'];
        $profile = $s['package'];
        $pack = "SELECT * FROM groups WHERE groupname='$profile'";
        $pack_query = mysqli_query($con,$pack);
        $pack_res = mysqli_fetch_array($pack_query);
        $price = $pack_res['price'];
        $supplier_ammount = $pack_res['supplier'];
        $user = "SELECT SUM(`debit`) as de, SUM(`credit`) as cr,supplier_name FROM `transaction` WHERE `supplier_id`='$supplier'";
        $usr = mysqli_query($con,$user);
        $u = mysqli_fetch_array($usr);
        $full_name = $u['supplier_name'];
        $de = $u['de'];
        $cr = $u['cr'];
        $bal = $de - $cr;
        $balance = $bal - $price;

        if ($bal >= $price) {
            $radcheck = "INSERT INTO radcheck (username, attribute, op, value) VALUES('$username','Cleartext-Password',':=','$pass')";
            $rj = "INSERT INTO radcheck (username, attribute, op, value) VALUES('$username','Auth-Type',':=','PAP')";
            mysqli_query($con,$rj) or $msg = mysqli_error($con);
            mysqli_query($con,$radcheck) or $msg = mysqli_error($con);
            $grp = "INSERT INTO radusergroup (username, groupname) VALUES('$username','$profile')";
            $create = mysqli_query($con,$grp) or $msg = mysqli_error($con);
            $sql = "INSERT INTO clients (username, full_name, email, mobile, address,house,road,area,thana,district, nid, password, client_type,connectivity,connection,supplier_id,reference,approval,status)
SELECT username, full_name, email, mobile, address,house,road,area,thana,district, nid, password, client_type,connectivity,connection,supplier_id,reference,approval,status FROM clients_pending WHERE username='$username'";
            $qu = mysqli_query($con,$sql);
            $del = "DELETE  FROM clients_pending WHERE username='$username'";
            mysqli_query($con,$del);
            $date = date("d M Y 23:59:00", strtotime("+31 days"));
            $day = "INSERT INTO radcheck (username, attribute, op, value) VALUES('$username','Expiration',':=','$date')";
            mysqli_query($con,$day) or $msg = mysqli_error($con);
            $trans = "INSERT INTO `transaction` (`supplier_id`,`supplier_name`,`transaction`,`credit`,`reference`)VALUES('$supplier','$full_name','$profile','$price','$username Active')";
            $create = mysqli_query($con,$trans);
            if ($create) {
                echo "Username Activated";
                $API->log($wh, "User " . $username . "  Activated");
            }
        } else {
            echo "Supervisor Balance Low";
        }
    }

}

if (isset($_GET['pending'])) {
    $id = $_GET['pending'];
    $name = $_GET['user'];
    $sql = "UPDATE clients SET approval='Approve by $name'  WHERE id='$id'";
    $succ = mysqli_query($con,$sql);
    if ($succ) {
        echo "Approved";
        $API->log($wh, "User " . $id . " Approved");
    }
}
if (isset($_GET['company'])) {
    $com = $_GET['company'];
    $sql = "SELECT * FROM supplier WHERE type='$com'";
    $query = mysqli_query($con,$sql);
    if ($dept != "Visitor") {
        echo "<div class=\"form-group\">
            <label for=\"inputCom\">Supervisor:</label>
            <select class=\"form-control\"  name=\"sup_id\" required>
                <option></option>";
        while ($f = mysqli_fetch_array($query)) {
            $id = $f['supplier_id'];
            $name = $f['full_name'];
            echo "<option value='" . $id . "'>" . $name . "-" . $id . "</option>";
        }
    }
    echo "</select>
        </div>";
    $sql = "SELECT * FROM groups WHERE type='$com'";
    $query = mysqli_query($con,$sql);
    echo "<div class=\"form-group\">
            <label for=\"inputCom\">Package:</label>
            <select class=\"form-control\" name=\"package\" required>
                <option></option>";
    while ($f = mysqli_fetch_array($query)) {
        $pack = $f['groupname'];
        echo "<option>" . $pack . "</option>";
    }
    echo "</select>
        </div>";
}
if (isset($_GET['bind'])) {
    $mac = $_GET['bind'];
    $name = $_GET['uname'];
    $sql = "INSERT INTO radcheck (username,attribute,op,value) VALUES('$name','Calling-Station-Id','==','$mac')";
    $r = mysqli_query($con,$sql);
    echo "This MAC Address has been Fixed...";

}
//For Delete User
if (isset($_GET['usrdel'])) {
    if ($per == "Admin") {
        $name = $_GET['usrdel'];
        $sql = "INSERT INTO delete_user SELECT * FROM clients WHERE username='$name'";
        mysqli_query($con,$sql);
        $table = ["radcheck", "radreply", "radacct", "clients", "radusergroup", "radacct_old"];
        foreach ($table as $tab) {
            $sq = "DELETE FROM $tab WHERE username='$name'  ";
            $res = mysqli_query($con,$sq);
        }
        echo "User " . $name . " Deleted";
        $API->log($wh, "User " . $name . "  has been deleted");
    } else {
        echo "<h4 style='color: coral ;'>You have no permission...</h4>";
    }
}
//Complain Create
if (isset($_GET['com'])) {
    $id = $_GET['com'];
    $sql = "SELECT * FROM supplier WHERE supplier_id='$id'";
    $query = mysqli_query($con,$sql);
    $f = mysqli_fetch_array($query);
    ?>
    <div class="form-group">
        <label for="inputName" class="control-label">Supervisor ID</label>
        <input type="text" class="form-control" id="inputName" value="<?php echo $id; ?>" name="id" readonly>
    </div>
    <div class="form-group">
        <label for="inputFullname" class="control-label">Full Name</label>
        <input type="text" class="form-control" id="inputFullname" name="fullname" value="<?php echo $f['full_name'] ?>"
               readonly>
    </div>
    <div class="form-group">
        <label for="inputDivision" class="control-label">Address</label>
        <input type="text" class="form-control" id="inputFullname" name="address" value="<?php echo $f['address'] ?>"
               readonly>
    </div>
    <div class="form-group">
        <label for="inputArea" class="control-label">POP</label>
        <input type="text" class="form-control" id="inputFullname" name="pop" value="<?php echo $f['pop'] ?>" readonly>
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
            $qq = mysqli_query($con,$sql1);
            while ($g = mysqli_fetch_array($qq)) {
                ?>
                <option><?php echo $g['username'] ?></option>
            <?php } ?>
        </select>
        <a onClick='ComPlain()' class="btn btn-primary">Add</a>
    </div>
    <div class="form-group">
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
            <option>Package Change</option>
            <option>The user is unable to connect</option>
        </select>
        <a onClick='ProbAdd()' class="btn btn-primary">Add</a>
    </div>
    <div class="form-group">
        <textarea rows="4" cols="50" class="form-control" id="problem" name="problem"
                  placeholder="Type Your Problem Here..." required></textarea>
    </div>
<?php } ?>
