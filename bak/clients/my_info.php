<?php
/**
 * Created by PhpStorm.
 * User: JEET
 * Date: 29-May-19
 * Time: 1:07 PM
 */
include('clients.php');

?>
<div id="last"></div>
<h3>My Information:</h3>
<div class="sonar-wrapper col-md-4">
    <?php
    $name = $_SESSION['username'];
    $perpage = "10";
    $tup = 0;
    $tdown = 0;
    $sql = "SELECT * FROM radacct WHERE username='$name' ";
    $p = mysqli_query($con,$sql);
    while ($k = mysqli_fetch_array($p)) {
        $up = $k['acctinputoctets'];
        $down = $k['acctoutputoctets'];
        $tup = $tup + $up;
        $tdown = $tdown + $down;

    }
    $totalup = $tup / (1024 * 1024 * 1024);
    $totalup = sprintf("%.2f", $totalup);
    $totaldown = $tdown / (1024 * 1024 * 1024);
    $totaldown = sprintf("%.2f", $totaldown);
    ?>
    <div class="sonar-emitter" style="background-color: #0088be;">
        <div class="sonar-wave" style="background-color: #162cc4;">
        </div>
        <br>
        <br>
        <br>
        <center style="color:#ffffff;"><h3>Total Upload</h3>
            <?php echo $totalup; ?> GB
        </center>
    </div>
</div>
<div class="sonar-wrapper col-md-4">
    <div class="sonar-emitter" style="background-color: #bc6669;">
        <div class="sonar-wave" style="background-color: #2afcff;">
        </div>
        <br>
        <br>
        <br>
        <center style="color:white;"><h3>Total Download</h3>
            <?php echo $totaldown; ?> GB
        </center>
    </div>
</div>
<table class="table table-bordered" id="btrc">

    <thead>

    <tr class="btn-success" style="background-color: rgba(21,53,74,0.75); color: #ceefde; font-size:16px;">
        <th>User Name</th>
        <th>Password</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Mobile</th>
        <th>Package</th>
        <th>Expiration</th>
        <th>Bandwidth</th>

    </tr>


    </thead>
    <tbody>
    <?php
    $name = $_SESSION['username'];
    ?>
    <tr style="background-color: rgba(89,177,159,0.21); color: #ffffff; font-size:16px;">

        <td><a href="client_his.php?name=<?php echo $name; ?>"
               role="button" style="color:#87b5dc;font-size:16px;"><?php echo $name; ?></a></td>
        <td><?php
            $sql = "SELECT * FROM radcheck WHERE attribute='Cleartext-Password' && username='$name'";
            $pass = mysqli_query($con,$sql) or $msg = mysqli_error($con);
            $passw = mysqli_fetch_array($pass);
            echo $passw['value']; ?></td>

        <?php
        $sql = "SELECT * FROM clients WHERE  username='$name'";
        $query = mysqli_query($con,$sql) or $msg = mysqli_error($con);
        $n = mysqli_fetch_array($query);
        $full = $n['full_name'];
        $email = $n['email'];
        $mobile = $n['mobile'];
        $sup = $n['supplier_id'];
        ?>
        <td><?php echo $full; ?></td>
        <td><?php echo $email; ?></td>
        <td><?php echo $mobile; ?></td>
        <?php

        $sql = "SELECT groups.groupname,groups.speed FROM groups INNER JOIN radusergroup ON radusergroup.groupname=groups.id WHERE username='$name'";
        $rs = mysqli_query($con,$sql) or $msg = mysqli_error($con);
        $p = mysqli_fetch_array($rs);
        if($rs){
            $pro = $p['groupname'];
            $speed = $p['speed'];
        }else{
            $pro = "";
        $speed = "";
        }
        
        ?>
        <td><?php echo $pro; ?></td>
        <?php
        $sql = "SELECT * FROM radcheck WHERE attribute='Expiration' && username='$name'";
        $rs = mysqli_query($con,$sql) or $msg = mysqli_error($con);
        $re = mysqli_num_rows($rs);
        if ($re) {
            $p = mysqli_fetch_array($rs);
            $expi = $p['value'];
            $d = strtotime($expi);
            $exp = date("Y m d H:i", $d);
            $dateTime = new DateTime();
            $date = $dateTime->format('Y m d H:i');
            if ($exp != null && $exp < $date) {
                $expi = "<button class=\"btn btn-warning\">Expired</button>
                            <a class=\"btn btn-primary\"  href=\"bkash.php?user=" . $name . "&id=" . $sup . "\" >Renew</a>";
            }
        } else {
            $sql = "SELECT * FROM radgroupcheck WHERE attribute='Expiration' && groupname='$pro'";
            $rs = mysqli_query($con,$sql) or $msg = mysqli_error($con);
            $p = mysqli_fetch_array($rs);
            $expi = $p['value'];
            $d = strtotime($expi);
            $exp = date("Y m d H:i", $d);
            $dateTime = new DateTime();
            $date = $dateTime->format('d M Y H:i');
            if ($exp != null && $exp < $date) {
                $expi = "<button class=\"btn btn-warning\">Expired</button>
                            <a class=\"btn btn-primary\"  href=\"bkash.php?user=" . $name . "&id=" . $sup . "\" >Renew</a>";
            }
        }

        ?>
        <td><?php echo $expi; ?></td>

        <td><?php echo $speed; ?></td>
    </tr>
    </tbody>

</table>

<?php include '../includes/footer.php'; ?>
