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
            <center style="color:white;"><h4>Total Router</h4>
                <?php echo $nas; ?></center>
        </a></div>
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
        $now = time();
        if ($d > $now) {
            $total = $total + 1;
        }
    }

    ?>

    <div class="sonar-emitter" style="background-color: #0e9cc3;">
        <a href="user_list.php" style="color:white;">
            <div class="sonar-wave" style="background-color: #144293;">
            </div>
            <br>
            <br>
            <br>
            <center style="color:white;"><h4>Total User</h4>
                <?php echo $total; ?></center>
        </a>
    </div>
</div>
<div class="sonar-wrapper col-md-4">
    <?php
    $sql = "SELECT * FROM `radacct`  WHERE `acctstoptime` IS NULL and nasporttype='Ethernet'";
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    $a = mysqli_num_rows($r);
    ?>

    <div class="sonar-emitter" style="background-color: #07aa6c;">
        <a href="active_users.php" style="color:white;">
            <div class="sonar-wave" style="background-color: #4cae4c;">
            </div>
            <br>
            <br>
            <br>
            <center style="color:white;"><h4>PPPoE Online</h4>
                <?php echo $a; ?></center>
        </a>
    </div>
</div>
<div class="sonar-wrapper col-md-4">
    <div class="sonar-emitter" style="background-color: #c42e51;">
        <a href="expired.php" style="color:white;">
            <div class="sonar-wave" style="background-color: #ff090e;">
            </div>
            <br>
            <br>
            <br>
            <center style="color:white;"><h4>Expired PPPoE User</h4>
                <?php echo $to - $total; ?></center>
        </a>
    </div>
</div>
<?php
$active = $API->Select("*", "hotspot_user")->num_rows;
?>
<div class="sonar-wrapper col-md-4">
    <div class="sonar-emitter" style="background-color: #098703;">
        <a href="hotspot_user.php" style="color:white;">
            <div class="sonar-wave" style="background-color: #1e5b03;">
            </div>
            <br>
            <br>
            <br>
            <center style="color:white;"><h4>Hotspot Active</h4>
                <?php echo $active; ?></center>
        </a>
    </div>
</div>
<?php
$hot_on = $API->Select("*", "radacct","acctstoptime IS NULL and nasporttype !='Ethernet'")->num_rows;
?>
<div class="sonar-wrapper col-md-4">
    <div class="sonar-emitter" style="background-color: #1fdc32;">
        <a href="hotspot_active.php" style="color:white;">
            <div class="sonar-wave" style="background-color: #1fdc32;">
            </div>
            <br>
            <br>
            <br>
            <center style="color:white;"><h4>Hotspot Online</h4>
                <?php echo $hot_on; ?></center>
        </a>
    </div>
</div>
<?php if ($per == 'Full' || $per == 'Admin') { ?>
    <div class="sonar-wrapper col-md-4">
        <?php
        $balance = 0;
        $month = date('m');
        $year = date('Y');
        $sql = "SELECT SUM(`debit`) as `debit` FROM `transaction` WHERE `debit`!='0' and  MONTH(`create_date`)='$month' and YEAR(create_date)='$year'";
        $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
        $client = mysqli_fetch_array($r);
        $debit = $client['debit'];

        ?>

        <div class="sonar-emitter" style="background-color:#71921d;">
            <a href="mpesaResponse.php" target="_blank" style="color:white;">
                <div class="sonar-wave" style="background-color:#71921d;">
                </div>
                <br>
                <br>
                <br>
                <center style="color:white;"><h4>Monthly PPPoE Collection</h4>
                    <?php echo number_format($debit); ?></center>
            </a>
        </div>
    </div>
    <div class="sonar-wrapper col-md-4">
        <?php
        $sql2 = "SELECT SUM(`amount`) as `amount` from `hotspot_payment` WHERE MONTH(create_date)='$month' and YEAR(create_date)='$year'";
        $r = mysqli_query($con, $sql2) or $msg = mysqli_error($con);
        $client = mysqli_fetch_array($r);
        $daily = $client['amount'];

        ?>

        <div class="sonar-emitter" style="background-color:rgba(81,2,178,0.51);">
            <a href="hotspot_sell.php" target="_blank" style="color:white;">
                <div class="sonar-wave" style="background-color:rgba(45,2,95,0.51);">
                </div>
                <br>
                <br>
                <br>
                <center style="color:white;"><h4>Monthly Hotspot Collection</h4>
                    <?php echo number_format($daily); ?></center>
            </a>
        </div>
    </div>
<?php } ?>
<!-- end of col-lg-10 -->
<?php include 'includes/footer.php'; ?>
