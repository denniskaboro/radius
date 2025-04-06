<?php include 'includes/header.php'; ?>
<?php

$sql = "SELECT * FROM nas ";

$r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
$nas = mysqli_num_rows($r);

?>
<div class="sonar-wrapper col-md-4">
    <div class="sonar-emitter">
        <a href="nas_list.php" style="color:white;">

            <div class="sonar-wave">
            </div>
            <br>
            <br>
            <br>
            <center style="color:white;"><h3>Total NAS</h3>
                <?php echo $nas; ?></center>
        </a></div>
</div>
<div class="sonar-wrapper col-md-4">
    <?php
    $sql = "SELECT * FROM `radacct`  WHERE `acctstoptime` IS NULL";
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    $a = mysqli_num_rows($r);
    ?>

    <div class="sonar-emitter" style="background-color: #26c488;">
        <a href="active_users.php" style="color:white;">
            <div class="sonar-wave" style="background-color: #4cae4c;">
            </div>
            <br>
            <br>
            <br>
            <center style="color:white;"><h3>Connected User</h3>
                <?php echo $a; ?></center>
        </a>
    </div>
</div>
<div class="sonar-wrapper col-md-4">
    <?php
    $total = 0;
    $i = 0;
    $sql = "SELECT * FROM clients ";
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    $to = mysqli_num_rows($r);

    $sql = "SELECT * FROM radcheck WHERE  attribute='Expiration'";
    $res = mysqli_query($con, $sql);
    while ($f = mysqli_fetch_array($res)) {
        $value = $f['value'];
        $d = strtotime($value);
        $now = strtotime("now");
        if ($d > $now) {
            $total = $total + 1;
        }
    }
    $sql = "SELECT * FROM radcheck WHERE attribute='Auth-Type' && value='reject'";
    $res = mysqli_query($con, $sql);
    $f = mysqli_num_rows($res);
    if ($f > 0) {
        $i = $i + $f;
    }

    ?>

    <div class="sonar-emitter" style="background-color: #c4a57a;">
        <a href="user_list.php" style="color:white;">
            <div class="sonar-wave" style="background-color: #c4a57a;">
            </div>
            <br>
            <br>
            <br>
            <center style="color:white;"><h3>Total User</h3>
                <?php echo $total; ?></center>
        </a>
    </div>
</div>
<div class="sonar-wrapper col-md-4">
    <div class="sonar-emitter" style="background-color: #c42e51;">
        <a href="deactive.php" style="color:white;">
            <div class="sonar-wave" style="background-color: #ff090e;">
            </div>
            <br>
            <br>
            <br>
            <center style="color:white;"><h3>Deactivated User</h3>
                <?php echo $i; ?></center>
        </a>
    </div>
</div>

<div class="sonar-wrapper col-md-4">
    <?php
    $sql = "SELECT * FROM complain";
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    $re = "SELECT * FROM resolved WHERE DATE_FORMAT(end_time,'%Y-%m-%d')=curdate()";
    $rr = mysqli_query($con, $re) or $msg = mysqli_error($con);
    $com = mysqli_num_rows($rr);
    $complain = mysqli_num_rows($r);
    #$complain=$com + $complain;

    ?>

    <div class="sonar-emitter" style="background-color:#bf0700;">
        <a href="com_pending.php">
            <div class="sonar-wave" style="background-color:#ff3850;">
            </div>
            <br>
            <br>
            <br>
            <center style="color:white;"><h3>Pending Complain</h3>
                <?php echo $complain; ?></center>

    </div>
    </a>
</div>
<div class="sonar-wrapper col-md-4">
    <div class="sonar-emitter" style="background-color:#7276f3;">
        <a href="com_resolve.php?sort=today" target="_blank" style="color:white;">
            <div class="sonar-wave" style="background-color:#728aff;">
            </div>
            <br>
            <br>
            <br>
            <center style="color:white;"><h3>Today's Resolved</h3>
                <?php echo $com; ?></center>
        </a>
    </div>

</div>

<?php if ($per == 'Full' || $per == 'Admin') { ?>
    <div class="sonar-wrapper col-md-4">
        <?php
        $balance = 0;
        $month=date('m');
        $sql = "SELECT SUM(`debit`) as `debit` FROM `transaction` WHERE `debit`!='0' and  MONTH(`create_date`)='$month'";
        $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
        $client = mysqli_fetch_array($r);
        $debit = $client['debit'];

        $sql2 = "select SUM(`debit`) as `debit` from `user_balance` where DATE_FORMAT(`create_date`,'%Y-%m-%d')=curdate()";
        $r = mysqli_query($con, $sql2) or $msg = mysqli_error($con);
        $client = mysqli_fetch_array($r);
        $daily = $client['debit'];

        ?>

        <div class="sonar-emitter" style="background-color:#3b555e;">
            <a href="mpesaResponse.php" target="_blank" style="color:white;">
                <div class="sonar-wave" style="background-color:#a3ff78;">
                </div>
                <br>
                <br>
                <br>
                <center style="color:white;"><h3>Monthly Collection</h3>
                    <?php echo number_format($debit); ?></center>
            </a>
        </div>
    </div>
    <div class="sonar-wrapper col-md-4">
        <div class="sonar-emitter" style="background-color:#1814be;">
            <a href="mpesaResponse.php?show=today" target="_blank" style="color:white;">
                <div class="sonar-wave" style="background-color: #2025ff;">
                </div>
                <br>
                <br>
                <br>
                <center style="color:white;"><h3>Today's Collection</h3>
                    <?php echo number_format($daily); ?></center>
            </a>
        </div>

    </div>
<?php } ?>
<!-- end of col-lg-10 -->
<?php include 'includes/footer.php'; ?>
