<?php
include('header.php');

$per = $_SESSION['per'];
?>

    <div class="sonar-wrapper col-md-4">
        <?php
        $amm = "SELECT SUM(credit) as credit, SUM(debit) as de, SUM(otc) as otc FROM `transaction` ";
        $r = mysqli_query($con, $amm) or $msg = mysqli_error($con);
        $total = mysqli_query($con, $amm);
        $d = mysqli_fetch_array($total);
        $debit = $d['de'];
        $otc = $d['otc'];
        $cr = $d['credit'];
        $sql2 = "select sum(debit) as debit from `transaction` where DATE_FORMAT(create_date,'%Y-%m-%d')=curdate()";
        $r = mysqli_query($con, $sql2) or $msg = mysqli_error($con);
        $client = mysqli_fetch_array($r);
        $daily = $client['debit'];
        ?>
        <div class="sonar-emitter" style="background-color: #7a2c36;">
            <div class="sonar-wave" style="background-color: #c45781;">
            </div>
            <br>
            <br>
            <br>
            <center style="color:white;"><h3>Total Revenew</h3>
                <?php echo $cr; ?></center>
        </div>
    </div>

    <div class="sonar-wrapper col-md-4">
        <div class="sonar-emitter" style="background-color: #118200;">
            <div class="sonar-wave" style="background-color: rgb(59,143,17);">
            </div>
            <br>
            <br>
            <br>
            <center style="color:white;"><h3>Total Payment</h3>
                <?php echo $debit; ?></center>
        </div>
    </div>
    <div class="sonar-wrapper col-md-4">
        <div class="sonar-emitter" style="background-color: #047578;">
            <a href="advance.php?show=today" target="_blank" style="color:#1d5045;">
                <div class="sonar-wave" style="background-color: #ffc461;">
                </div>
                <br>
                <br>
                <br>
                <center style="color:white;"><h3>Today's Collection</h3>
                    <?php echo number_format($daily); ?></center>
            </a>
        </div>

    </div>
    <div class="sonar-wrapper col-md-4">
        <div class="sonar-emitter" style="background-color: #1a48cc;">
            <div class="sonar-wave" style="background-color: #122465;">
            </div>
            <br>
            <br>
            <br>
            <center style="color:white;"><h3>Total OTC</h3>
                <?php echo $otc; ?></center>
        </div>
    </div>
<?php include '../includes/footer.php'; ?>