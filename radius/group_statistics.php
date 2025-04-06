<?php
include 'includes/header.php';
include('config/data.php');
?>
<div class="col-lg-6 ">
    <h3>Assign Profile</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Package Name</th>
            <th>Service Name</th>
            <th>Value</th>

        </tr>
        </thead>
        <tbody>
        <?php

        if (isset($_GET['name'])) {
            $name = $_GET['name'];
            $table = ["radgroupcheck", "radgroupreply"];
            foreach ($table as $tab) {
                $sq = "SELECT * FROM $tab WHERE groupname='$name' ";
                $res = mysqli_query($con, $sq);
                while ($j = mysqli_fetch_array($res)) {
                    $attri = $j['attribute'];
                    $val = $j['value'];
                    ?>

                    <tr>
                        <td><?php echo $name; ?></td>
                        <td><?php echo $attri; ?></td>
                        <td><?php echo $val; ?></td>
                    </tr>

                <?php }
            }


            ?>

        <?php }

        ?>
        </tbody>
    </table>
</div>
<div class="col-lg-6">
    <h3>User List Under This Group:</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Package Name</th>
            <th>Username</th>

        </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT * FROM radusergroup WHERE groupname='$name'";
        $r = mysqli_query($con, $sql) or $msg = mysqli_error($con);
        while ($j = mysqli_fetch_array($r)) {
            $user = $j['username'];

            ?>

            <tr>
                <td><?php echo $name; ?></td>
                <td><?php echo $user; ?></td>

            </tr>
        <?php }
        ?>
        </tbody>
    </table>
    <div
</div>  <!-- end of col-lg-10 -->
<?php include 'includes/footer.php'; ?>

