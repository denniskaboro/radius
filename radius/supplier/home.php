<?php include 'reseller.php'; ?>
<div class="sonar-wrapper col-md-4">
    <?php
    $id = $_SESSION['id'];
    $usr = "SELECT * FROM clients WHERE supplier_id='$id'";
    $res = mysqli_query($con, $usr);
    $client = mysqli_num_rows($res);
    $count = 0;
    while ($f = mysqli_fetch_array($res)) {
        $name = $f['username'];
        $sql = "SELECT DISTINCT username FROM radacct WHERE acctstoptime IS null && username='$name' && acctterminatecause=''";
        $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
        $found = mysqli_num_rows($r);
        if ($found > 0) {
            $count = $count + 1;
        }

    }

    ?>

    <div class="sonar-emitter" style="background-color: #239f6d;">
        <a href="active_users.php" style="color:white;">
            <div class="sonar-wave" style="background-color: #4cae4c;">
            </div>
            <br>
            <br>
            <br>
            <center style="color:white;"><h3>Connected User</h3>
                <?php echo $count; ?></center>
        </a>
    </div>
</div>
<div class="sonar-wrapper col-md-4">

    <div class="sonar-emitter" style="background-color: #7f3f45;">
        <a href="my_user.php" style="color:white;">
            <div class="sonar-wave" style="background-color: #c43b51;">
            </div>
            <br>
            <br>
            <br>
            <center style="color:white;"><h3>Total User</h3>
                <?php echo $client; ?></center>
        </a>
    </div>
</div>
<div class="sonar-wrapper col-md-4">
    <?php

    $sql = "SELECT * FROM clients WHERE supplier_id='$id' && new_user='yes' ORDER BY id DESC ";
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    $f = mysqli_num_rows($r);

    ?>
    <div class="sonar-emitter" style="background-color: #7040c9;">
        <a href="new_user.php" style="color:#4630ff;">
            <div class="sonar-wave" style="background-color: #ff090e;">
            </div>
            <br>
            <br>
            <br>
            <center style="color:white;"><h3>New User</h3>
                <?php echo $f; ?></center>
        </a>
    </div>
</div>
<div class="sonar-wrapper col-md-4">
    <?php
    $i = 0;
    $user = [];
    $sql = "SELECT * FROM clients WHERE supplier_id='$id' && new_user!='yes' ORDER BY id DESC ";
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    while ($j = mysqli_fetch_array($r)) {
        $user[] = $j['username'];
    }
    foreach ($user as $u) {

        $sql = "SELECT * FROM radcheck WHERE username='$u' && value='reject'";
        $res = mysqli_query($con, $sql);
        $f = mysqli_num_rows($res);

    }
    ?>
    <div class="sonar-emitter" style="background-color: #c42e51;">
        <a href="deactive.php" style="color:white;">
            <div class="sonar-wave" style="background-color: #ff090e;">
            </div>
            <br>
            <br>
            <br>
            <center style="color:white;"><h3>Deactivate User</h3>
                <?php echo $f; ?></center>
        </a>
    </div>
</div>
<!-- end of col-lg-10 -->
<?php include '../includes/footer.php'; ?>
