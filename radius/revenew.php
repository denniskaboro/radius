<?php
include 'includes/header.php';
include('config/data.php');
?>
<h3>Transaction History</h3>
<form method="POST" enctype="multipart/form-data">
    <div class="form-group col-lg-12">
        <div class="col-lg-2">
            <label for="inputStart" class="control-label">From create_date</label>
            <input type="date" class="form-control" id="inputStart" name="start">
        </div>
        <div class="col-lg-2">
            <label for="inputEnd" class="control-label">End create_date</label>
            <input type="date" class="form-control" id="inputEnd" name="end">
        </div>
        <div class="col-lg-2">
            <label for="ID" class="control-label">Supervisor ID</label>
            <input type="text" class="form-control" id="ID" name="id" placeholder="Supervisor ID">
        </div>
    </div>
    <div class="col-lg-2">
        <button type="submit" name="sort" class="btn btn-primary">Sort</button>
    </div>
</form>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>Name</th>
        <th>create_date</th>
        <th>Transaction</th>
        <th>Amount</th>
        <th>Remark</th>

    </tr>
    </thead>
    <tbody>
    <?php
    $showRecordPerPage = 15;
    if (isset($_GET['page']) && !empty($_GET['page'])) {
        $currentPage = $_GET['page'];
    } else {
        $currentPage = 1;
    }
    $start = '';
    $end = '';
    $id = '';
    $startFrom = ($currentPage * $showRecordPerPage) - $showRecordPerPage;
    $totalEmpSQL = "SELECT * FROM `transaction` WHERE credit !='0'";
    $amm = "SELECT SUM(credit) as credit
       FROM `transaction` WHERE credit !='0'";
    $sql = "SELECT * FROM `transaction` WHERE credit !='0'  ORDER BY id DESC LIMIT $startFrom, $showRecordPerPage";
    if (isset($_POST['sort'])) {
        $start = $_POST['start'];
        $end = $_POST['end'];
        $id = $_POST['id'];
        if ($start != null && $end != null && $id != null) {
            $totalEmpSQL = "SELECT * FROM `transaction` WHERE credit !='0' && supplier_id='$id' && create_date>='$start' && create_date<='$end'";
            $amm = "SELECT SUM(credit) as credit
       FROM transaction WHERE credit !='0' && supplier_id='$id' && create_date>='$start' && create_date<='$end'";
            $sql = "SELECT * FROM `transaction` WHERE credit !='0' && supplier_id='$id' && create_date>='$start' && create_date<='$end' 
ORDER BY id DESC LIMIT $startFrom, $showRecordPerPage";
        } else if ($start != null && $end != null) {
            $totalEmpSQL = "SELECT * FROM `transaction` WHERE credit !='0' && create_date>='$start' && create_date<='$end'";
            $amm = "SELECT SSUM(credit) as credit
       FROM `transaction` WHERE credit !='0' && create_date>='$start' && create_date<='$end'";
            $sql = "SELECT * FROM `transaction` WHERE credit !='0' && create_date>='$start' && create_date<='$end' 
ORDER BY id DESC LIMIT $startFrom, $showRecordPerPage";
        } else {
            $totalEmpSQL = "SELECT * FROM `transaction` WHERE credit !='0' && supplier_id='$id'";
            $amm = "SELECT SUM(credit) as credit
       FROM `transaction` WHERE credit !='0' && supplier_id='$id'";
            $sql = "SELECT * FROM `transaction` WHERE credit !='0' && supplier_id='$id' 
ORDER BY id DESC LIMIT $startFrom, $showRecordPerPage";
        }
    }
    if (!empty($_GET['id']) && !empty($_GET['start']) && !empty($_GET['end'])) {
        $start = $_GET['start'];
        $end = $_GET['end'];
        $id = $_GET['id'];
        $totalEmpSQL = "SELECT * FROM transaction WHERE credit !='0' && supplier_id='$id' && create_date>='$start' && create_date<='$end'";
        $amm = "SELECT SUM(supplier_ammount) as ammount,SUM(credit) as credit
       FROM transaction WHERE credit !='0' && supplier_id='$id' && create_date>='$start' && create_date<='$end'";
        $sql = "SELECT * FROM transaction WHERE credit !='0' && supplier_id='$id' && create_date>='$start' && create_date<='$end' 
ORDER BY id DESC LIMIT $startFrom, $showRecordPerPage";
    } else if (!empty($_GET['start']) && !empty($_GET['end'])) {
        $start = $_GET['start'];
        $end = $_GET['end'];
        $totalEmpSQL = "SELECT * FROM transaction WHERE credit !='0' && create_date>='$start' && create_date<='$end'";
        $amm = "SELECT SUM(supplier_ammount) as ammount,SUM(credit) as credit
       FROM transaction WHERE credit !='0' && create_date>='$start' && create_date<='$end'";
        $sql = "SELECT * FROM transaction WHERE credit !='0' && create_date>='$start' && create_date<='$end' 
ORDER BY id DESC LIMIT $startFrom, $showRecordPerPage";
    } else if (!empty($_GET['id'])) {
        $id = $_GET['id'];
        $totalEmpSQL = "SELECT * FROM transaction WHERE credit !='0' && supplier_id='$id'";
        $amm = "SELECT SUM(supplier_ammount) as ammount,SUM(credit) as credit
       FROM transaction WHERE supplier_id='$id'";
        $sql = "SELECT * FROM transaction WHERE credit !='0' && supplier_id='$id' 
ORDER BY id DESC LIMIT $startFrom, $showRecordPerPage";
    }

    $allEmpResult = mysqli_query($con, $totalEmpSQL);
    $totalEmployee = mysqli_num_rows($allEmpResult);
    $lastPage = ceil($totalEmployee / $showRecordPerPage);
    $firstPage = 1;
    $nextPage = $currentPage + 1;
    $previousPage = $currentPage - 1;
    $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
    while ($j = mysqli_fetch_array($r)) {
        $name = $j['supplier_name'];
        $create_date = $j['create_date'];
        $transaction = $j['transaction'];
        $credit = $j['credit'];
        $total = $credit;
        $reference = $j['reference'];

        ?>

        <tr>
            <td><?php echo $reference; ?></td>
            <td><?php echo $create_date; ?></td>
            <td><?php echo $transaction; ?></td>
            <td><?php echo $total; ?></td>
            <td><?php echo $name; ?></td>
        </tr>
    <?php }

    $amm = "SELECT SUM(credit) as credit
       FROM `transaction` WHERE credit !='0'";
    $total = mysqli_query($con, $amm);
    $d = mysqli_fetch_array($total);
    $cr = $d['credit'];
    echo "<h4>Total Revenue: " . $cr . " Kes</h4>";
    ?>
    </tbody>

</table>
<?php echo "Showing " . $showRecordPerPage * ($currentPage - 1) . " to " . $showRecordPerPage * $currentPage . " of " . $totalEmployee ?>
<nav aria-label="Page navigation">
    <ul class="pagination">
        <?php $lastPart = $lastPage - 4;
        $compare = $currentPage;
        if ($currentPage != $firstPage) { ?>
            <li class="page-item">
                <a class="page-link"
                   href="?page=<?php echo $firstPage . "&id=" . $id . "&start=" . $start . "&end=" . $end; ?>"
                   tabindex="-1" aria-label="Previous"
                   style="width:50px;height:40px;padding:10px;background:#ddd;color:black;">
                    <span aria-hidden="true">First</span>
                </a>
            </li>
            <li class="page-item"><a class="page-link"
                                     href="?page=<?php echo ($currentPage - 1) . "&id=" . $id . "&start=" . $start . "&end=" . $end; ?>"
                                     style="width:50px;border: 1px dotted;color:black;height:40px;padding:10px;background:#ddd;"><<</a>
            </li>
        <?php } ?>
        <?php if ($lastPage < 5) {
            for ($currentPage = 1; $currentPage <= $lastPage; $currentPage++) {
                echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?page=$currentPage&id=$id&end=$end&start=$start\" style=\"width:50px;color:white;color:black;height:40px;border: 1px dotted;padding:10px;background:#ddd;\">$currentPage</a></li>";
            }
            ?>
        <?php } else if ($currentPage > $lastPart) {
            for ($currentPage = $lastPart; $currentPage <= $lastPage; $currentPage++) {
                echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?page=$currentPage&id=$id&end=$end&start=$start\"  style=\"width:50px;height:40px;color:black;padding:10px;border: 1px dotted;background:#ddd;\">$currentPage</a></li>";
            }
        } else {
            $ext = $currentPage + 4;
            for ($currentPage = $currentPage; $currentPage <= $ext; $currentPage++) {
                echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?page=$currentPage&id=$id&end=$end&start=$start\"  style=\"width:50px;color:black;height:40px;padding:10px;border: 1px dotted;background:#ddd;\">$currentPage</a></li>";
            } ?>
        <?php }
        if ($compare != $lastPage) { ?>
            <li class="page-item"><a class="page-link"
                                     href="?page=<?php echo ($compare + 1) . "&id=" . $id . "&start=" . $start . "&end=" . $end; ?>"
                                     style="width:50px;color:black;height:40px;border: 1px dotted;padding:10px;background:#ddd;">
                    >> </a></li>
            <li class="page-item">
                <a class="page-link"
                   href="?page=<?php echo $lastPage . "&id=" . $id . "&start=" . $start . "&end=" . $end; ?>"
                   aria-label="Next"
                   style="width:50px;height:40px;padding:10px;border: 1px dotted;color:black;background:#ddd;">
                    <span aria-hidden="true">Last</span>
                </a>
            </li>
        <?php } ?>
    </ul>
</nav>
<?php include 'includes/footer.php'; ?>
