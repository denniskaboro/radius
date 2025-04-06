<?php
include "includes/header.php";
$user = [];
$active_user = [];
$income = [];
for ($i = 1; $i <= 12; $i++) {
    $y = date("Y");
    $date = $y . "-0" . $i;
    $active = 0;
    $client = "SELECT radcheck.value FROM clients INNER JOIN radcheck ON clients.username=radcheck.username WHERE create_date LIKE '%$date%' and radcheck.attribute='Expiration'";
    $q = mysqli_query($con, $client);
    $total_user = mysqli_num_rows($q);
    while ($f = mysqli_fetch_array($q)) {
        $expi = $f['value'];
        $ex = strtotime($expi);
        $time = time();
        if ($ex > $time) {
            $active = $active + 1;
        }
    }
    $tra = "SELECT SUM(`debit`) as de FROM `transaction` WHERE `create_date` LIKE '%$date%'";
    $t = mysqli_query($con, $tra);
    $g = mysqli_fetch_array($t);
    $net_income = $g['de'];
    if ($net_income == null) {
        $net_income = 0;
    }
    $user[] = $total_user;
    $active_user[] = $active;
    $income[] = intval($net_income);
}

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<div class="container">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <h2>Monthly Report</h2>
        <div  style="background: #ffffff;padding: 20px;">
            <canvas id="monthly"></canvas>
        </div>
    </div>

</div>

<script>
    new Chart(document.getElementById("monthly"), {
        type: 'bar',
        data: {
            labels: [
                "January",
                "February",
                "March",
                "April",
                "May",
                "June",
                "July",
                "August",
                "September",
                "October",
                "November",
                "December"
            ],
            datasets: [
                {
                    label: "Total Users",
                    backgroundColor: ["#41cd12", "#41cd12", "#41cd12", "#41cd12", "#41cd12","#41cd12","#41cd12", "#41cd12", "#41cd12", "#41cd12", "#41cd12","#41cd12"],
                    data: <?php echo json_encode($user) ?>
                },
                {
                    label: "Active Users",
                    backgroundColor: ["#7b43cd", "#7b43cd", "#7b43cd", "#7b43cd", "#7b43cd","#7b43cd", "#7b43cd", "#7b43cd", "#7b43cd", "#7b43cd","#7b43cd"],
                    data: <?php echo json_encode($active_user) ?>
                },
                {
                    label: "Total Income",
                    backgroundColor: ["#ef6954", "#ef6954", "#ef6954", "#ef6954", "#ef6954", "#ef6954","#ef6954", "#ef6954", "#ef6954", "#ef6954", "#ef6954", "#ef6954"],
                    data: <?php echo json_encode($income) ?>
                }
            ]
        },
        options: {
            legend: {display: true},
            title: {
                display: true,
                text: 'Total Monthly Report'
            }

        }
    });

</script>

<?php
include("includes/footer.php");
?>

